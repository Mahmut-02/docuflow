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

    // 3. Görselden PDF'e sayfasının arayüzünü gösterir
    public function imageToPdfView()
    {
        return view('tools.image-to-pdf');
    }

    // 4. Yüklenen görselleri tek bir PDF'te toplar
    public function imageToPdfProcess(Request $request)
    {
        $request->validate([
            'images'   => 'required|array|min:1',
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:5120', // Maksimum 5MB
        ], [
            'images.required'   => 'Lütfen en az bir görsel seçin.',
            'images.*.image'    => 'Yüklenen dosyalardan biri geçerli bir resim değil.',
            'images.*.mimes'    => 'Sadece JPG, JPEG ve PNG formatları desteklenmektedir.',
            'images.*.max'      => 'Görsellerin her biri en fazla 5MB boyutunda olabilir.',
        ]);

        // Tüm görselleri tutacağımız HTML şablonunu hazırlıyoruz
        // CSS ile her resmin yeni bir sayfada ve tam boyutta görünmesini sağlıyoruz
        $html = '<style>
                    body { margin: 0; padding: 0; }
                    .page { page-break-after: always; text-align: center; }
                    img { max-width: 100%; max-height: 100%; object-fit: contain; }
                 </style>';

        foreach ($request->file('images') as $file) {
            // Görseli Base64'e çeviriyoruz (PDF motorunun dosyayı sorunsuz okuması için en güvenli yöntem)
            $extension = $file->getClientOriginalExtension();
            $base64 = base64_encode(file_get_contents($file));
            $imageSrc = 'data:image/' . $extension . ';base64,' . $base64;

            $html .= '<div class="page"><img src="' . $imageSrc . '"></div>';
        }

        // DomPDF motorunu çalıştır ve PDF'i A4 dikey (portrait) formatında oluştur
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4', 'portrait');

        $fileName = 'DocuFlow_Images_' . time() . '.pdf';

        return $pdf->download($fileName);
    }


}
