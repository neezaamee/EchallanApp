<?php

namespace App\Livewire\Forms;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Registration extends Component
{
    #[Validate('required')]
    public $name = '';

    #[Validate('required')]
    public $cnic = '';

    public function save()
    {
        $this->validate();

        return $this->redirect('/posts');
    }
    public function render()
    {
        return view('livewire.forms.registration');
    }
}
