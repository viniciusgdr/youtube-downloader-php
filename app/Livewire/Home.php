<?php

namespace App\Livewire;

use App\Apis\Search;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Home extends Component
{
    public string $search = '';
    public bool $loading = false;
    public array $results = [];

    public function searchSubmit()
    {
        $this->validate([
            'search' => 'required|min:3',
        ]);

        $this->loading = true;

        $youtube = new Search();
        $this->results = $youtube->YoutubeSearch($this->search);
        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.home');
    }
}
