<?php

use App\Apis\Search;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/search', function (Request $request) {
    if (!$request->has('q')) {
        return response()->json([
            'message' => 'No query string provided'
        ], 422);
    }
    $query = $request->get('q');
    $youtube = new Search();

    if ($youtube->isURL($query)) {
        return $youtube->getVideoInfo($youtube->getVideoId($query));
    }

    $results = $youtube->YoutubeSearch($query);
    return response()->json($results);
});

Route::get('/download', function (Request $request) {
    if (!$request->has('id')) {
        return response()->json([
            'message' => 'No id provided'
        ], 422);
    }
    $id = $request->get('id');
    $youtube = new Search();
    if ($youtube->isURL($id)) {
        $id = $youtube->getVideoId($id);
    }
    $downloads = $youtube->getDownload($id);
    foreach ($downloads['videos'] as $key => $video) {
        unset($downloads['videos'][$key]['extra']);
    }
    foreach ($downloads['audios'] as $key => $audio) {
        unset($downloads['audios'][$key]['extra']);
    }
    return response()->json($downloads);
});
