<?php

use Illuminate\Support\Facades\Route;

Route::get('/api-docs', function () {
    return view('swagger.index');
})->name('swagger.index');

Route::get('/api-docs.json', function () {
    $swagger = \OpenApi\scan(app_path('Http/Controllers/Api'));
    return response()->json($swagger);
})->name('swagger.json');
