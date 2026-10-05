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
            '/Applications/LibreOffice.app/Contents/MacOS/soffice',
            '-env:UserInstallation=file:///tmp/LibreOffice_Conversion_' . $this->conversion->id, // <-- Mac'te kilitlenmeyi önleyen sihirli satır
            '--headless',
            '--convert-to',
            'pdf',
            '--outdir',
            $outputDirectory,
            $inputPath,
        ]);

        try {
            // İşlemi çalıştır
            $process->run();

            // Eğer motor hata verirse süreci durdur ve hatayı fırlat
            if (!$process->isSuccessful()) {
                $errorMsg = $process->getErrorOutput();
                \Illuminate\Support\Facades\Log::error('LibreOffice Hatası: ' . $errorMsg);
                throw new \Exception('LibreOffice Hatası: ' . $errorMsg);
            }

            // Başarılıysa dosya adını bul
            $filenameWithoutExt = pathinfo($this->conversion->original_path, PATHINFO_FILENAME);
            $convertedFilename = $filenameWithoutExt . '.pdf';
            $relativeConvertedPath = 'documents/converted/' . $convertedFilename;

            // Veritabanını completed olarak güncelle
            $this->conversion->update([
                'status' => 'completed',
                'converted_path' => $relativeConvertedPath,
            ]);

        } catch (\Throwable $exception) {
            // Herhangi bir hata durumunda veritabanını failed olarak güncelle
            $this->conversion->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
            ]);
        }
    }
}
