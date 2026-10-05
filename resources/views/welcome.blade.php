@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto py-10">

        <!-- Başlık & Açıklama -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 mb-4 shadow-sm">
                <i class="ph ph-file-doc text-3xl"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Word'den PDF'e Dönüştür</h1>
            <p class="text-sm text-slate-500 mt-2">DOCX veya DOC formatındaki belgenizi seçin, saniyeler içinde PDF olarak hazırlayalım.</p>
        </div>

        <!-- Yükleme Kartı / Form -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm">
            <form action="{{ route('convert.word_to_pdf') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Dosya Seçim Alanı -->
                <div>
                    <label for="document" class="block text-sm font-medium text-slate-700 mb-2">Belge Seçin</label>
                    <div class="border-2 border-dashed border-slate-300 hover:border-indigo-400 rounded-xl p-6 text-center transition cursor-pointer bg-slate-50/50">
                        <input type="file" name="document" id="document" accept=".doc,.docx" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" />
                        <p class="text-xs text-slate-400 mt-2">Maksimum dosya boyutu: 10 MB (.docx, .doc)</p>
                    </div>
                </div>

                <!-- Gönder Butonu -->
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    <i class="ph ph-arrows-clockwise text-lg"></i>
                    <span>PDF'e Dönüştür</span>
                </button>
            </form>
        </div>

    </div>
@endsection
