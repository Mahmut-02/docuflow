<?php

namespace App\Jobs;

use App\Models\Conversion;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ConvertWordToPdfJob implements ShouldQueue
{
    use Queueable;

    // Görevin işleyeceği kayıt
    public function __construct(public Conversion $conversion)
    {
    }

    // Sırası geldiğinde çalışacak asıl kod
    public function handle(): void
    {
        // 1. Durumu 'processing' yap
        $this->conversion->update([
            'status' => 'processing'
        ]);

        $inputPath = Storage::path($this->conversion->original_path);
        $outputDirectory = Storage::path('documents/converted');

        // Hedef klasör yoksa oluştur
        if (!file_exists($outputDirectory)) {
            mkdir($outputDirectory, 0755, true);
        }

        // 2. LibreOffice headless komutunu hazırla
        // Bu komut arayüz açmadan terminalden word'ü pdf'e çevirir
        $process = new Process([
            'soffice',
            '--headless',
            '--convert-to',
            'pdf',
            '--outdir',
            $outputDirectory,
            $inputPath,
        ]);

        try {
            $process->mustRun();

            // Dönüşen dosyanın yeni adını bul (ornek.docx -> ornek.pdf)
            $filenameWithoutExt = pathinfo($this->conversion->original_filename, PATHINFO_FILENAME);
            $convertedFilename = $filenameWithoutExt . '.pdf';
            $relativeConvertedPath = 'documents/converted/' . $convertedFilename;

            // 3. Başarılı olduysa veritabanını güncelle
            $this->conversion->update([
                'status' => 'completed',
                'converted_path' => $relativeConvertedPath,
            ]);

        } catch (ProcessFailedException $exception) {
            // 4. Hata aldıysa durumu failed yap ve hata mesajını yaz
            $this->conversion->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
            ]);
        }
    }
}
