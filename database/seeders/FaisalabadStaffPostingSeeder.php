<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;
use App\Models\Circle;
use App\Models\DumpingPoint;
use App\Models\MedicalCenter;
use App\Models\StaffPosting;

class FaisalabadStaffPostingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Fixed IDs as per requirement
        $provinceId = 1;
        $cityId = 2;

        // Fetch DB data
        $circles = Circle::all();
        $dumpingPoints = DumpingPoint::all();
        $medicalCenters = MedicalCenter::all();

        // Fetch all staff — Spatie roles
        $staffMembers = Staff::with('user')->get();

        foreach ($staffMembers as $staff) {

            $roleName = $staff->user->getRoleNames()->first(); // Spatie

            // Default posting structure
            $posting = [
                'staff_id' => $staff->id,
                'province_id' => null,
                'city_id' => null,
                'circle_id' => null,
                'dumping_point_id' => null,
                'medical_center_id' => null,
                'start_date' => now(),
                'end_date' => null,
                'status' => 'active',
            ];

            switch ($roleName) {

                // Dumping point based roles
                case 'incharge':
                case 'duty_officer':
                case 'deo':
                case 'challan_officer':
                    $posting['city_id'] = $cityId;
                    $posting['dumping_point_id'] = $dumpingPoints->random()->id;
                    break;

                // Circle level
                case 'circle_officer':
                    $posting['city_id'] = $cityId;
                    $posting['circle_id'] = $circles->random()->id;
                    break;

                // City level
                case 'reader':
                case 'accountant':
                case 'cto':
                    $posting['city_id'] = $cityId;
                    break;

                // Doctor → city + medical center
                case 'doctor':
                    $posting['city_id'] = $cityId;
                    $selectedMedicalCenter = $medicalCenters->random();
                    $posting['circle_id'] = $selectedMedicalCenter->circle_id;
                    $posting['medical_center_id'] = $selectedMedicalCenter->id;
                    break;

                // DIG → multiple cities (city 1 & 2)
                case 'dig':
                    StaffPosting::create([
                        'staff_id' => $staff->id,
                        'province_id' => null,
                        'city_id' => 1,
                        'circle_id' => null,
                        'dumping_point_id' => null,
                        'medical_center_id' => null,
                        'start_date' => now(),
                        'end_date' => null,
                        'status' => 'active',
                    ]);

                    StaffPosting::create([
                        'staff_id' => $staff->id,
                        'province_id' => null,
                        'city_id' => 2,
                        'circle_id' => null,
                        'dumping_point_id' => null,
                        'medical_center_id' => null,
                        'start_date' => now(),
                        'end_date' => null,
                        'status' => 'active',
                    ]);

                    continue 2; // skip normal posting creation

                // Province level
                case 'addl_ig':
                case 'ig':
                    $posting['province_id'] = $provinceId;
                    break;

                default:
                    $posting['city_id'] = $cityId;
            }

            StaffPosting::create($posting);
        }
    }
}
