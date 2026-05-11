<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tools/render', [ToolController::class, 'render'])->name('tools.render');
Route::post('/tools/pdf-service', [ToolController::class, 'unavailable'])->name('tools.pdf-service');
Route::post('/tools/ai-image', [ToolController::class, 'unavailable'])->name('tools.ai-image');
Route::get('/{slug}', [HomeController::class, 'tool'])->name('tools.show');
