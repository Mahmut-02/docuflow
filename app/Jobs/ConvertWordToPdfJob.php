<?php

namespace App\Jobs;
use App\Models\Conversion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class ConvertWordToPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use Queueable;

    // İşlem 60 saniyeyi geçerse kes ve failed (başarısız) olarak işaretle
    public $timeout = 60;

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
            config('services.libreoffice.path'),
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

    /**
     * İşlem herhangi bir nedenden (dosya yok, zaman aşımı, çökme) başarısız olursa çalışır.
     */
    public function failed(\Throwable $exception)
    {
        // Veritabanındaki durumu 'failed' (Başarısız) olarak güncelle
        $this->conversion->update([
            'status' => 'failed'
        ]);
    }
}
