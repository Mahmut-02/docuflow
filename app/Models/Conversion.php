<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversion extends Model
{
    // Veritabanına tek seferde kaydedilmesine izin verdiğimiz alanlar
    protected $fillable = [
        'original_filename',
        'original_path',
        'converted_path',
        'type',
        'status',
        'error_message',
    ];
}
