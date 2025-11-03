<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EditProfile extends Component
{
    public $name;
    public $cnic;
    public $password;
    public $password_confirmation;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->cnic = $user->cnic;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'cnic' => 'required|string|max:20|unique:users,cnic,' . Auth::id(),
            'password' => 'nullable|confirmed|min:8',
        ]);

        $user = Auth::user();
        $user->name = $this->name;
        $user->cnic = $this->cnic;

        if ($this->password) {
            $user->password = Hash::make($this->password);
        }

        $user->save();

        session()->flash('success', 'Profile updated successfully!');
    }

    public function render()
    {
        return view('livewire.profile.edit-profile');
    }
}
