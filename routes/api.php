<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Idea;

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

Route::get('/ideen', function (Request $request) {
    $query = Idea::query();

    if ($request->has('category')) {
        $query->where('category', $request->category);
    }

    return $query->latest()->get([
        'title',
        'description',
        'category',
        'created_at as date',
    ]);
});