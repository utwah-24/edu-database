<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\EventContentController;
use App\Http\Controllers\Api\TopicController;

Route::get('/documentation', fn () => view('swagger-ui'));
Route::get('/openapi.json', function () {
    $path = storage_path('api-docs/api-docs.json');
    abort_unless(is_readable($path), 404, 'OpenAPI spec not found. Ensure storage/api-docs/api-docs.json exists.');

    return response()->file($path, ['Content-Type' => 'application/json']);
});

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

Route::get('/gallery', [EventContentController::class, 'indexGallery']);
Route::post('/gallery', [EventContentController::class, 'storeGallery']);
Route::get('/gallery/{id}', [EventContentController::class, 'showGallery']);
Route::put('/gallery/{id}', [EventContentController::class, 'updateGallery']);
Route::delete('/gallery/{id}', [EventContentController::class, 'destroyGallery']);
Route::get('/faqs', [EventContentController::class, 'indexFaqs']);
Route::post('/faqs', [EventContentController::class, 'storeFaq']);
Route::get('/faqs/{id}', [EventContentController::class, 'showFaq']);
Route::put('/faqs/{id}', [EventContentController::class, 'updateFaq']);
Route::get('/resources', [EventContentController::class, 'indexResources']);
Route::post('/resources', [EventContentController::class, 'storeResource']);
Route::get('/resources/{id}', [EventContentController::class, 'showResource']);
Route::put('/resources/{id}', [EventContentController::class, 'updateResource']);
Route::delete('/resources/{id}', [EventContentController::class, 'destroyResource']);
Route::get('/themes', [EventContentController::class, 'indexThemes']);
Route::get('/themes/{id}', [EventContentController::class, 'showTheme']);
Route::get('/summaries', [EventContentController::class, 'indexSummaries']);
Route::post('/summaries', [EventContentController::class, 'storeSummary']);
Route::get('/summaries/{id}', [EventContentController::class, 'showSummary']);
Route::get('/programmes', [EventContentController::class, 'indexProgrammes']);
Route::post('/programmes', [EventContentController::class, 'storeProgramme']);
Route::get('/programmes/{id}', [EventContentController::class, 'showProgramme']);
Route::get('/speakers', [EventContentController::class, 'indexSpeakers']);
Route::post('/speakers', [EventContentController::class, 'storeSpeaker']);
Route::get('/speakers/{id}', [EventContentController::class, 'showSpeaker']);
Route::get('/sponsors', [EventContentController::class, 'indexSponsors']);
Route::post('/sponsors', [EventContentController::class, 'storeSponsor']);
Route::get('/sponsors/{id}', [EventContentController::class, 'showSponsor']);
Route::get('/attendances', [AttendanceController::class, 'index']);
Route::post('/attendances', [AttendanceController::class, 'store']);
Route::get('/attendances/{id}', [AttendanceController::class, 'show']);
Route::put('/attendances/{id}', [AttendanceController::class, 'update']);
Route::delete('/attendances/{id}', [AttendanceController::class, 'destroy']);

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
    Route::post('/{eventId}/themes', [EventContentController::class, 'storeTheme']);
});

// Content management routes
Route::put('/summaries/{id}', [EventContentController::class, 'updateSummary']);
Route::delete('/summaries/{id}', [EventContentController::class, 'destroySummary']);
Route::put('/themes/{id}', [EventContentController::class, 'updateTheme']);
Route::delete('/themes/{id}', [EventContentController::class, 'destroyTheme']);

Route::put('/programmes/{id}', [EventContentController::class, 'updateProgramme']);
Route::delete('/programmes/{id}', [EventContentController::class, 'destroyProgramme']);

Route::put('/speakers/{id}', [EventContentController::class, 'updateSpeaker']);
Route::delete('/speakers/{id}', [EventContentController::class, 'destroySpeaker']);

Route::put('/sponsors/{id}', [EventContentController::class, 'updateSponsor']);
Route::delete('/sponsors/{id}', [EventContentController::class, 'destroySponsor']);
Route::delete('/faqs/{id}', [EventContentController::class, 'destroyFaq']);

Route::get('/media', [EventContentController::class, 'indexMedia']);
Route::post('/media', [EventContentController::class, 'storeMedia']);
Route::put('/media/{id}', [EventContentController::class, 'updateMedia']);

// Topic Routes
Route::prefix('topics')->group(function () {
    Route::get('/', [TopicController::class, 'index']);
    Route::get('/{id}', [TopicController::class, 'show']);
    Route::post('/', [TopicController::class, 'store']);
    Route::put('/{id}', [TopicController::class, 'update']);
    Route::delete('/{id}', [TopicController::class, 'destroy']);
});
