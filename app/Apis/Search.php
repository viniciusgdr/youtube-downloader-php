<?php

namespace App\Apis;

use DOMDocument;

function getstr($string, $start, $end, $i)
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
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    public function getVideoId(string $url): string|null
    {
        $url = parse_url($url);
        if (isset($url['query'])) {
            parse_str($url['query'], $query);
            if (isset($query['v'])) {
                return $query['v'];
            }
        }
        return null;
    }

    public function getVideoByID(string $id): array
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
                'thumbnail' => $video['authorThumbnails'][0]['url'] ?? null,
            ],
            'views' => $video['viewCount'],
            'uploaded' => $video['publishDate'] ?? null,
            'extra' => $json
        ];
    }
}
