<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\EventContentController;
use App\Http\Controllers\Api\TopicController;

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

// Event Routes
Route::prefix('events')->group(function () {
    // Public routes
    Route::get('/', [EventController::class, 'index']);
    Route::get('/current', [EventController::class, 'current']);
    Route::get('/year/{year}', [EventController::class, 'getByYear']);
    Route::get('/{id}', [EventController::class, 'show']);
    
    // Admin routes (add authentication middleware as needed)
    Route::post('/', [EventController::class, 'store']);
    Route::put('/{id}', [EventController::class, 'update']);
    Route::delete('/{id}', [EventController::class, 'destroy']);
    
    // Event content routes
    Route::post('/{eventId}/summaries', [EventContentController::class, 'storeSummary']);
    Route::post('/{eventId}/themes', [EventContentController::class, 'storeTheme']);
    Route::post('/{eventId}/programmes', [EventContentController::class, 'storeProgramme']);
    Route::post('/{eventId}/speakers', [EventContentController::class, 'storeSpeaker']);
    Route::post('/{eventId}/sponsors', [EventContentController::class, 'storeSponsor']);
    Route::post('/{eventId}/faqs', [EventContentController::class, 'storeFaq']);
});

// Content management routes
Route::delete('/summaries/{id}', [EventContentController::class, 'destroySummary']);
Route::delete('/themes/{id}', [EventContentController::class, 'destroyTheme']);

Route::put('/programmes/{id}', [EventContentController::class, 'updateProgramme']);
Route::delete('/programmes/{id}', [EventContentController::class, 'destroyProgramme']);

Route::put('/speakers/{id}', [EventContentController::class, 'updateSpeaker']);
Route::delete('/speakers/{id}', [EventContentController::class, 'destroySpeaker']);

Route::delete('/sponsors/{id}', [EventContentController::class, 'destroySponsor']);
Route::delete('/faqs/{id}', [EventContentController::class, 'destroyFaq']);

// Topic Routes
Route::prefix('topics')->group(function () {
    Route::get('/', [TopicController::class, 'index']);
    Route::get('/{id}', [TopicController::class, 'show']);
    Route::post('/', [TopicController::class, 'store']);
    Route::put('/{id}', [TopicController::class, 'update']);
    Route::delete('/{id}', [TopicController::class, 'destroy']);
});
