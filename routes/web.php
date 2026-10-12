<?php

use App\Http\Controllers\ConversionController;
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;



// Yeni Ana Sayfa (Vitrin / Dashboard)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Word aracını kendi alt sayfasına taşıdık
Route::get('/tools/word-to-pdf', [App\Http\Controllers\ConversionController::class, 'index'])->name('tools.word.view');
Route::post('/tools/word-to-pdf', [App\Http\Controllers\ConversionController::class, 'convertWordToPdf'])->name('tools.word.process');
// PDF İndirme rotası
Route::get('/download/{id}', [ConversionController::class, 'download'])->name('convert.download');

Route::get('/status/{id}', [App\Http\Controllers\ConversionController::class, 'status']);

// Dosya ve kayıt silme rotası
Route::delete('/conversion/{id}', [App\Http\Controllers\ConversionController::class, 'destroy'])->name('convert.destroy');


// Araçlar Menüsü ve PDF Birleştirme Rotaları
Route::get('/tools/merge-pdf', [App\Http\Controllers\ToolController::class, 'mergePdfView'])->name('tools.merge.view');
Route::post('/tools/merge-pdf', [App\Http\Controllers\ToolController::class, 'mergePdfProcess'])->name('tools.merge.process');


// Görselden PDF'e Aracı
Route::get('/tools/image-to-pdf', [App\Http\Controllers\ToolController::class, 'imageToPdfView'])->name('tools.image.view');
Route::post('/tools/image-to-pdf', [App\Http\Controllers\ToolController::class, 'imageToPdfProcess'])->name('tools.image.process');

// PDF Şifreleme Aracı
Route::get('/tools/protect-pdf', [App\Http\Controllers\ToolController::class, 'protectPdfView'])->name('tools.protect.view');
Route::post('/tools/protect-pdf', [App\Http\Controllers\ToolController::class, 'protectPdfProcess'])->name('tools.protect.process');
