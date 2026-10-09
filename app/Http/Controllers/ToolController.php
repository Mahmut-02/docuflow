<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use iio\libmergepdf\Merger;

class ToolController extends Controller
{
    // 1. Birleştirme sayfasının arayüzünü gösterir
    public function mergePdfView()
    {
        return view('tools.merge');
    }

    // 2. Yüklenen PDF'leri birleştirip indirmeyi başlatır
    public function mergePdfProcess(Request $request)
    {
        // En az 2 PDF dosyası yüklenmesini zorunlu tutuyoruz
        $request->validate([
            'documents'   => 'required|array|min:2',
            'documents.*' => 'required|file|mimes:pdf',
        ], [
            'documents.min'     => 'Birleştirme yapabilmek için en az 2 adet PDF seçmelisiniz.',
            'documents.*.mimes' => 'Sadece PDF uzantılı dosyalar yükleyebilirsiniz.',
        ]);

        $merger = new Merger;

        // Yüklenen her PDF'i sırayla birleştiriciye ekle
        foreach ($request->file('documents') as $file) {
            $merger->addFile($file->getPathname());
        }

        // Birleştirme işlemini çalıştır
        $createdPdf = $merger->merge();

        // Yeni dosya adını oluştur (Örn: DocuFlow_Merged_169...pdf)
        $fileName = 'DocuFlow_Merged_' . time() . '.pdf';

        // İşlem bitince dosyayı doğrudan kullanıcının tarayıcısına indir
        return response($createdPdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
