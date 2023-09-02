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
        return $youtube->getVideoByID($youtube->getVideoId($query));
    }

    $results = $youtube->YoutubeSearch($query);
    return response()->json($results);
});
