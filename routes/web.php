<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\FrontendController;

Route::get('/', [FrontendController::class, 'home'])->name('frontend.home');
Route::get('/events', [FrontendController::class, 'events'])->name('frontend.events');
Route::get('/events/{id}', [FrontendController::class, 'eventDetails'])->name('frontend.events.show');
Route::get('/events/{eventId}/topics/{topicId}', [FrontendController::class, 'topicDetail'])->name('frontend.events.topic');
Route::get('/speakers', [FrontendController::class, 'speakers'])->name('frontend.speakers');
Route::get('/topics', [FrontendController::class, 'topics'])->name('frontend.topics');
Route::get('/resources', [FrontendController::class, 'resources'])->name('frontend.resources');
Route::get('/gallery', [FrontendController::class, 'gallery'])->name('frontend.gallery');
