<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// Temizlik komutumuzu her saat başı otomatik çalışacak şekilde zamanlıyoruz
Schedule::command('docuflow:clean')->hourly();
