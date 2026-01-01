<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Ifsnop\Mysqldump\Mysqldump;
use Illuminate\Support\Facades\DB;

class BackupController extends Controller
{
    protected $backupPath = 'backups';

    public function __construct()
    {
        // Ensure directory exists
        if (!Storage::exists($this->backupPath)) {
            Storage::makeDirectory($this->backupPath);
        }
    }

    /**
     * Display a listing of backups.
     */
    public function index()
    {
        // Only Admin/Super Admin
        if (!Auth::user()->hasRole(['super_admin', 'admin'])) {
            abort(403);
        }

        $files = Storage::files($this->backupPath);
        $backups = [];

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) == 'sql') {
                $backups[] = [
                    'filename' => basename($file),
                    'size' => $this->humanFileSize(Storage::size($file)),
                    'date' => Carbon::createFromTimestamp(Storage::lastModified($file))->toDateTimeString(),
                    'path' => $file
                ];
            }
        }

        // Sort by date desc
        usort($backups, function ($a, $b) {
            return $b['date'] <=> $a['date'];
        });

        return view('admin.backups.index', compact('backups'));
    }

    /**
     * Create a new backup (PHP Native).
     */
    public function create()
    {
        if (!Auth::user()->hasRole(['super_admin', 'admin'])) {
            abort(403);
        }

        try {
            $filename = 'backup-' . Carbon::now()->format('Y-m-d-H-i-s') . '.sql';
            
            // Use Storage facade to get the absolute path. 
            // This ensures it matches where Storage::files() looks (e.g. storage/app/private/backups)
            $path = Storage::path($this->backupPath . '/' . $filename);
            
            // Database credentials
            $dbName = env('DB_DATABASE');
            $dbUser = env('DB_USERNAME');
            $dbPass = env('DB_PASSWORD');
            $dbHost = env('DB_HOST', '127.0.0.1');

            $dsn = "mysql:host={$dbHost};dbname={$dbName}";
            
            // mysqldump-php settings
            $dumpSettings = [
                'compress' => 'None',
                'no-data' => false,
                'add-drop-table' => true,
                'single-transaction' => true,
                'lock-tables' => false,
                'add-locks' => true,
                'extended-insert' => true,
            ];

            $dump = new Mysqldump($dsn, $dbUser, $dbPass, $dumpSettings);
            $dump->start($path);

            if (file_exists($path) && filesize($path) > 0) {
                return redirect()->route('backups.index')->with('success', 'Backup created successfully.');
            } else {
                return redirect()->route('backups.index')->with('error', 'Backup created but empty.');
            }

        } catch (\Exception $e) {
            return redirect()->route('backups.index')->with('error', 'Exception: ' . $e->getMessage());
        }
    }

    /**
     * Download backup.
     */
    public function download($filename)
    {
        if (!Auth::user()->hasRole(['super_admin', 'admin'])) {
            abort(403);
        }

        $path = $this->backupPath . '/' . $filename;
        if (Storage::exists($path)) {
            return Storage::download($path);
        }
        return back()->with('error', 'File not found.');
    }

    /**
     * Delete backup.
     */
    public function delete($filename)
    {
        if (!Auth::user()->hasRole(['super_admin', 'admin'])) {
            abort(403);
        }

        $path = $this->backupPath . '/' . $filename;
        if (Storage::exists($path)) {
            Storage::delete($path);
            return back()->with('success', 'Backup deleted.');
        }
        return back()->with('error', 'File not found.');
    }

    /**
     * Restore backup (Super Admin Only).
     */
    public function restore($filename)
    {
        // STRICTLY Super Admin
        if (!Auth::user()->hasRole('super_admin')) {
            abort(403, 'Only Super Admin can restore databases.');
        }

        try {
            // Use Storage facade to get the correct absolute path
            $path = Storage::path($this->backupPath . '/' . $filename);
            
            if (!file_exists($path)) {
                return back()->with('error', 'Backup file not found on server.');
            }

            // Restore using DB::unprepared
            // Note: DB::unprepared loads the whole file string into memory. 
            // For very large files, users should use CLI or phpMyAdmin.
            
            $sql = file_get_contents($path);
            
            if (!$sql) {
                return back()->with('error', 'Could not read backup file.');
            }

            // Disable foreign key checks to avoid ordering issues during restore
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::unprepared($sql);
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return redirect()->route('backups.index')->with('success', 'Database restored successfully.');

        } catch (\Exception $e) {
            return redirect()->route('backups.index')->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }
    
    private function humanFileSize($bytes, $decimals = 2)
    {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }
}
