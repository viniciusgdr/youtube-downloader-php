<?php

namespace App\Livewire;

use Livewire\Component;

class Youtube extends Component
{
    public string $id = '';
    public string $title = '';
    public string $thumbnail = '';
    public bool $loading = false;
    public function render()
    {
        return view('livewire.youtube');
    }
}
