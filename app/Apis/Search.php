<?php

namespace App\Apis;

use DOMDocument;

function getstr($string, $start, $end, $i): string
{
    $i++;
    $str = explode($start, $string);
    $str = explode($end, $str[$i]);
    return $str[0];
}


class Search
{
    public function YoutubeSearch(string $query): array
    {
        $url = "https://www.youtube.com/results?search_query=" . urlencode($query);
        $html = file_get_contents($url);
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $getter = getstr($html, 'var ytInitialData = ', ';</script>', 0);
        $json = json_decode($getter, true);
        $results = [];
        foreach ($json['contents']['twoColumnSearchResultsRenderer']['primaryContents']['sectionListRenderer']['contents'][0]['itemSectionRenderer']['contents'] as $item) {
            if (isset($item['videoRenderer'])) {
                $video = $item['videoRenderer'];
                $results[] = [
                    'id' => $video['videoId'],
                    'title' => $video['title']['runs'][0]['text'],
                    'thumbnail' => $video['thumbnail']['thumbnails'][0]['url'],
                    'duration' => $video['lengthText']['simpleText'] ?? $video['lengthText']['runs'][0]['text'] ?? null,
                    'channel' => [
                        'id' => $video['ownerText']['runs'][0]['navigationEndpoint']['browseEndpoint']['browseId'],
                        'name' => $video['ownerText']['runs'][0]['text'],
                        'thumbnail' => $video['channelThumbnailSupportedRenderers']['channelThumbnailWithLinkRenderer']['thumbnail']['thumbnails'][0]['url'],
                    ],
                    'views' => $video['viewCountText']['simpleText'] ?? $video['viewCountText']['runs'][0]['text'] ?? null,
                    'uploaded' => $video['publishedTimeText']['simpleText'] ?? $video['publishedTimeText']['runs'][0]['text'] ?? null,
                ];
            }
        }
        return $results;
    }

    public function isURL(string $url): bool
    {
        return preg_match('/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/', $url);
    }

    public function getVideoId(string $url): string|null
    {
        if (!$this->isURL($url)) {
            return null;
        }
        preg_match('/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/', $url, $matches);
        return $matches[5];
    }

    public function getVideoInfo(string $id): array
    {
        $url = "https://www.youtube.com/watch?v=" . urlencode($id);
        $html = file_get_contents($url);
        $dom = new DOMDocument();
        @$dom->loadHTML($html);

        $getter = getstr($html, 'var ytInitialPlayerResponse = ', ';</script>', 0);
        $json = json_decode($getter, true);

        $video = $json['videoDetails'];
        return [
            'id' => $video['videoId'],
            'title' => $video['title'],
            'thumbnail' => $video['thumbnail']['thumbnails'][0]['url'],
            'duration' => $video['lengthSeconds'],
            'channel' => [
                'id' => $video['channelId'],
                'name' => $video['author'],
            ],
            'views' => $video['viewCount'],
            'uploaded' => $video['publishDate'] ?? null,
            'extra' => $json
        ];
    }

    public function getDownload(string $id): array
    {
        $data = array(
            "context" => array(
                "client" => array(
                    "clientName" => "ANDROID_EMBEDDED_PLAYER",
                    "clientVersion" => "16.02"
                )
            ),
            "videoId" => $id
        );
        $options = array(
            'http' => array(
                'header' => "Content-type: application/json\r\n",
                'method' => 'POST',
                'content' => json_encode($data)
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents("https://www.youtube.com/youtubei/v1/player?key=AIzaSyAO_FJ2SlqU8Q4STEHLGCilw_Y9_11qcW8", false, $context);
        if ($result === FALSE) {
            return [];
        }

        $json = json_decode($result, true);
        $formats = $json['streamingData']['formats'];
        $adaptiveFormats = $json['streamingData']['adaptiveFormats'];
        $adaptiveFormatsFiltered = array_filter($adaptiveFormats, function ($item) {
            return isset($item['audioQuality']) && in_array($item['audioQuality'], ['AUDIO_QUALITY_LOW', 'AUDIO_QUALITY_MEDIUM', 'AUDIO_QUALITY_HIGH']);
        });
        $formats = array_merge($formats, $adaptiveFormatsFiltered);

        $results = [];
        foreach ($formats as $item) {
            $results[] = [
                'itag' => $item['itag'],
                'url' => $item['url'],
                'quality' => $item['qualityLabel'] ?? $item['audioQuality'] ?? $item['quality'] ?? null,
                'type' => $item['mimeType'],
                'extra' => $item
            ];
        }

        $videos = array_filter($results, function ($item) {
            return str_contains($item['type'], 'video');
        });

        $audios = array_filter($results, function ($item) {
            return str_contains($item['type'], 'audio');
        });
        return [
            'videos' => $videos,
            'audios' => $audios,
        ];
    }
}
