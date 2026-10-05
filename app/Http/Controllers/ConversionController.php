<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Jobs\ConvertWordToPdfJob;
use App\Models\Conversion;
use Illuminate\Http\Request;

class ConversionController extends Controller
{
    // Ana sayfayı ve yükleme formunu gösterir
    public function index()
    {
        return view('welcome');
    }

    // Dosyayı karşılar ve kaydeder
    public function convertWordToPdf(Request $request)
    {
        // 1. Doğrulama: Gerçekten bir dosya geldi mi ve uzantısı docx mi? (En fazla 10MB)
        $request->validate([
            'document' => 'required|file|mimes:doc,docx|max:10240',
        ]);

        $file = $request->file('document');
        $originalName = $file->getClientOriginalName();

        // 2. Dosyayı fiziksel olarak storage/app/documents klasörüne kaydet
        $path = $file->store('documents');

        // 3. Veritabanına kayıt at (Model aracılığıyla)
        $conversion = Conversion::create([
            'original_filename' => $originalName,
            'original_path' => $path,
            'type' => 'word_to_pdf',
            'status' => 'pending',
        ]);
        ConvertWordToPdfJob::dispatch($conversion);
        // 4. Kullanıcıya başarı mesajı dön
        return redirect()->back()
            ->with('success', 'Dosyanız kuyruğa alındı! İşlem bitince yandaki butondan indirebilirsiniz.')
            ->with('conversion_id', $conversion->id);    }

    // Dönüştürülen dosyayı indirme metodu
    public function download($id)
    {
        $conversion = Conversion::findOrFail($id);

        // Dosya henüz hazır değilse veya hata aldıysa
        if ($conversion->status !== 'completed' || !$conversion->converted_path) {
            return redirect()->back()->withErrors(['Dosyanız henüz dönüştürülüyor veya bir hata oluştu. Lütfen 3-5 saniye bekleyip tekrar deneyin.']);
        }

        // Hazırsa indir (İndirilen dosyanın adını orjinal dosya adı yapıyoruz)
        $downloadName = pathinfo($conversion->original_filename, PATHINFO_FILENAME) . '.pdf';
        return Storage::download($conversion->converted_path, $downloadName);
    }
}
