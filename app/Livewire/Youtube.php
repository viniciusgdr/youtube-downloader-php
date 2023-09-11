<?php

namespace App\Livewire;

use App\Apis\Search;
use App\Defaults\YoutubeDefaults;
use Livewire\Component;
use function PHPUnit\Framework\stringContains;

class Youtube extends Component
{
    public string $search = '';
    public string $id = '';
    public array $infoVideo = [
        'id' => '',
        'title' => '',
        'thumbnail' => '',
        'channelName' => '',
        'audios' => [],
        'videos' => [],
    ];

    public bool $error = false;

    public array $resultsRecommended = [];

    protected array $queryString = [
        'id' => ['except' => ''],
    ];

    public function searchSubmit()
    {
        $this->validate([
            'search' => 'required|min:3',
        ]);

        $youtube = new Search();
        if ($youtube->isURL($this->search)) {
            $videoId = $youtube->getVideoId($this->search);
            if (!$videoId) {
                $this->error = true;
                return;
            }
            $infoVideo = $youtube->getVideoInfo($videoId);
            $this->infoVideo = [
                'id' => $infoVideo['id'],
                'title' => $infoVideo['title'],
                'thumbnail' => strpos($infoVideo['thumbnail'], '?') ? explode('?', $infoVideo['thumbnail'])[0] : $infoVideo['thumbnail'],
                'channelName' => $infoVideo['channel']['name'] ?? '',
            ];
            $downloads = $youtube->getDownload($infoVideo['id']);
            $this->infoVideo['audios'] = $downloads['audios'];
            $this->infoVideo['videos'] = $downloads['videos'];
            $this->resultsRecommended = $youtube->YoutubeSearch($infoVideo['title'] . ' ' . $infoVideo['channel']['name']);
        } else {
            $this->resultsRecommended = $youtube->YoutubeSearch($this->search);
        }
    }

    public function render()
    {
        return view('livewire.youtube');
    }

    public function mount(): void
    {
        $this->id = request()->route()->parameter('id');
        $this->search = YoutubeDefaults::YOUTUBE_VIDEO_URL . $this->id;
    }
}
