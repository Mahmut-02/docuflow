<?php

use App\Http\Controllers\ConversionController;
use Illuminate\Support\Facades\Route;

// Ana sayfa
Route::get('/', [ConversionController::class, 'index'])->name('home');

// Word to PDF formunun gönderileceği rota
Route::post('/convert/word-to-pdf', [ConversionController::class, 'convertWordToPdf'])->name('convert.word_to_pdf');

// PDF İndirme rotası
Route::get('/download/{id}', [ConversionController::class, 'download'])->name('convert.download');
