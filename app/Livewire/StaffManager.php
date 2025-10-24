<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Staff;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StaffManager extends Component
{
    use WithPagination;

    public $name, $cnic, $email, $department, $designation, $current_posting, $role;
    public $staffId = null; // for edit
    public $tempPassword = null;

    public $perPage = 10;
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'name' => 'required|string|max:255',
        'cnic' => 'required|string|max:30',
        'email' => 'required|email|max:255',
        'department' => 'nullable|string|max:100',
        'designation' => 'nullable|string|max:100',
        'current_posting' => 'nullable|string|max:255',
        'role' => 'required|string|in:admin,challan_officer,accountant',
    ];

    protected $listeners = [
        'confirmDelete' => 'deleteConfirmed'
    ];

    public function mount()
    {
        // set default role if needed
        $this->role = 'challan_officer';
    }

    public function render()
{
    $staff = collect([
        (object)['id'=>1,'name'=>'Test','cnic'=>'11111','email'=>'t@example.com','department'=>'X','designation'=>'Y','current_posting'=>'Z','role'=>'admin']
    ])->paginate(10); // simple test pagination won't be exact but ok for debug

    return view('livewire.staff-manager');
}


    public function rules()
    {
        // dynamic unique checks for create/update
        $rules = $this->rules;

        if ($this->staffId) {
            // editing: ignore current staff record for uniques
            $rules['cnic'] = 'required|string|max:30|unique:staff,cnic,'.$this->staffId;
            $rules['email'] = 'required|email|max:255|unique:staff,email,'.$this->staffId;
        } else {
            $rules['cnic'] = 'required|string|max:30|unique:staff,cnic';
            $rules['email'] = 'required|email|max:255|unique:staff,email';
        }

        return $rules;
    }

    public function updated($field)
    {
        $this->validateOnly($field, $this->rules());
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->staffId = null;
        $this->dispatchBrowserEvent('show-staff-modal');
    }

    public function openEditModal($id)
    {
        $this->resetValidation();
        $this->resetForm(false); // keep role default maybe
        $staff = Staff::findOrFail($id);

        // authorization: admin can only edit challan_officer
        if (auth()->user()->hasRole('admin') && ! auth()->user()->isSuperAdmin()) {
            if ($staff->roleName() !== 'challan_officer') {
                session()->flash('error','You are not allowed to edit this staff record.');
                return;
            }
        }

        $this->staffId = $staff->id;
        $this->name = $staff->name;
        $this->cnic = $staff->cnic;
        $this->email = $staff->email;
        $this->department = $staff->department;
        $this->designation = $staff->designation;
        $this->current_posting = $staff->current_posting;

        // set role from user if available
        $this->role = $staff->roleName() ?? $this->role;

        $this->dispatchBrowserEvent('show-staff-modal');
    }

    public function save()
    {
        $this->validate($this->rules());

        // enforce role creation rules server-side
        if (in_array($this->role, ['admin','accountant']) && ! auth()->user()->isSuperAdmin()) {
            $this->addError('role', 'Only Super Admin can create or assign Admin/Accountant roles.');
            return;
        }

        if ($this->role === 'challan_officer' && auth()->user()->hasRole('admin') && ! auth()->user()->isSuperAdmin()) {
            // check admin's staff record permission
            $creatorStaff = Staff::where('cnic', auth()->user()->cnic)->orWhere('email', auth()->user()->email)->first();
            if ($creatorStaff && ! $creatorStaff->can_create_officer) {
                $this->addError('role','You are not allowed to create challan officer accounts. Contact Super Admin.');
                return;
            }
        }

        DB::beginTransaction();

        try {
            if ($this->staffId) {
                // Update existing staff
                $staff = Staff::findOrFail($this->staffId);

                // authorization: admin limited to challan_officer
                if (auth()->user()->hasRole('admin') && ! auth()->user()->isSuperAdmin()) {
                    if ($staff->roleName() !== 'challan_officer') {
                        $this->addError('general', 'You are not allowed to update this staff record.');
                        DB::rollBack();
                        return;
                    }
                }

                $staff->update([
                    'name' => $this->name,
                    'cnic' => $this->cnic,
                    'email' => $this->email,
                    'department' => $this->department,
                    'designation' => $this->designation,
                    'current_posting' => $this->current_posting,
                ]);

                // update user if exists
                $user = User::where('cnic', $staff->cnic)->orWhere('email', $staff->email)->first();
                if ($user) {
                    $user->update([
                        'name' => $this->name,
                        'cnic' => $this->cnic,
                        'username' => $this->cnic,
                        'email' => $this->email,
                    ]);

                    // sync role if changed
                    $roleModel = Role::firstWhere('name', $this->role);
                    if ($roleModel && ! $user->roles()->where('role_id', $roleModel->id)->exists()) {
                        $user->roles()->sync([$roleModel->id]);
                    }
                }

                DB::commit();
                session()->flash('success','Staff updated successfully.');
            } else {
                // Create new staff + user + role
                $staff = Staff::create([
                    'name' => $this->name,
                    'cnic' => $this->cnic,
                    'email' => $this->email,
                    'department' => $this->department,
                    'designation' => $this->designation,
                    'current_posting' => $this->current_posting,
                    'created_by' => auth()->id(),
                ]);

                $this->tempPassword = Str::random(10);

                $user = User::create([
                    'name' => $this->name,
                    'cnic' => $this->cnic,
                    'username' => $this->cnic,
                    'email' => $this->email,
                    'password' => Hash::make($this->tempPassword),
                    'is_department_user' => true,
                    'email_verified_at' => now(),
                ]);

                $roleModel = Role::firstWhere('name', $this->role);
                if (! $roleModel) {
                    DB::rollBack();
                    $this->addError('role','Selected role is not available.');
                    return;
                }

                $user->roles()->attach($roleModel->id);

                DB::commit();

                session()->flash('success','Staff created successfully. Temporary password: '.$this->tempPassword);
            }

            $this->dispatchBrowserEvent('hide-staff-modal');
            $this->resetForm();

        } catch (\Throwable $e) {
            DB::rollBack();
            $this->addError('general', 'Failed: '.$e->getMessage());
        }
    }

    public $deleteId = null;

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatchBrowserEvent('show-delete-confirm');
    }

    public function deleteConfirmed()
    {
        $id = $this->deleteId;
        $staff = Staff::findOrFail($id);

        // only allow admin to delete challan_officer
        if (auth()->user()->hasRole('admin') && ! auth()->user()->isSuperAdmin()) {
            if ($staff->roleName() !== 'challan_officer') {
                session()->flash('error','You are not allowed to delete this staff record.');
                return;
            }
        }

        DB::beginTransaction();
        try {
            $user = User::where('cnic', $staff->cnic)->orWhere('email', $staff->email)->first();
            if ($user) {
                $user->roles()->detach();
                $user->delete();
            }

            $staff->delete();
            DB::commit();
            session()->flash('success','Staff deleted.');
        } catch (\Throwable $e) {
            DB::rollBack();
            session()->flash('error','Delete failed: '.$e->getMessage());
        }

        $this->deleteId = null;
        $this->dispatchBrowserEvent('hide-delete-confirm');
    }

    public function resetForm($clearAll = true)
    {
        if ($clearAll) {
            $this->resetValidation();
            $this->resetErrorBag();
        }
        $this->name = $this->cnic = $this->email = $this->department = $this->designation = $this->current_posting = $this->role = null;
        $this->staffId = null;
        $this->tempPassword = null;
    }
}
