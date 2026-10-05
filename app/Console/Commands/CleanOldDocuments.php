<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Conversion;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanOldDocuments extends Command
{
    // Terminalde çalıştıracağımız komutun adı
    protected $signature = 'docuflow:clean';

    // Komutun açıklaması
    protected $description = '2 saatten eski dönüştürme dosyalarını ve veritabanı kayıtlarını mutlak yolla temizler';

    public function handle()
    {
        // 2 saatten eski kayıtları bul
        $oldConversions = Conversion::where('created_at', '<', Carbon::now()->subHours(2))->get();
        $count = 0;

        foreach ($oldConversions as $conversion) {
            // 1. Orijinal Word dosyasını mutlak yolla bul ve sil
            if ($conversion->original_path) {
                $absoluteOriginal = Storage::path($conversion->original_path);
                if (file_exists($absoluteOriginal)) {
                    unlink($absoluteOriginal);
                }
            }

            // 2. Dönüştürülen PDF dosyasını mutlak yolla bul ve sil
            if ($conversion->converted_path) {
                $absoluteConverted = Storage::path($conversion->converted_path);
                if (file_exists($absoluteConverted)) {
                    unlink($absoluteConverted);
                }
            }

            // 3. Veritabanından satırı sil
            $conversion->delete();
            $count++;
        }

        $this->info("Temizlik tamamlandı. {$count} eski belge ve veritabanı kaydı başarıyla silindi.");
    }
}
