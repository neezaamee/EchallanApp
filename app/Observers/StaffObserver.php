<?php

namespace App\Observers;

use App\Models\Staff;
use App\Models\User;

class StaffObserver
{
    /**
     * Handle the Staff "updated" event.
     */
    public function updated(Staff $staff): void
    {
        // Check if any syncable field was changed
        if ($staff->isDirty(['email', 'first_name', 'last_name', 'cnic'])) {
            // Check if staff has a linked user
            if ($staff->user_id) {
                $user = User::find($staff->user_id);
                if ($user) {
                    if ($staff->isDirty('email')) {
                        $user->email = $staff->email;
                    }
                    if ($staff->isDirty(['first_name', 'last_name'])) {
                        $user->name = trim($staff->first_name . ' ' . $staff->last_name);
                    }
                    if ($staff->isDirty('cnic')) {
                        $user->cnic = $staff->cnic;
                    }
                    $user->save();
                }
            }
        }
    }
}
