@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

        <!-- Karşılama Alanı (Hero) -->
        <div class="text-center mb-16">
            <h1 class="text-4xl font-extrabold text-slate-900 sm:text-5xl tracking-tight mb-4">
                Belgelerinizi <span class="text-indigo-600">Akıllıca</span> Yönetin
            </h1>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto">
                DocuFlow ile Word dosyalarınızı PDF'e çevirin, PDF'leri birleştirin, resimleri belgeye dönüştürün ve dosyalarınızı şifreleyerek güvence altına alın. Üstelik tamamen ücretsiz.
            </p>
        </div>

        <!-- Araçlar Izgarası (Grid) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Word'den PDF'e Kartı -->
            <a href="/tools/word-to-pdf" class="group bg-white rounded-3xl p-8 border border-slate-200 shadow-sm hover:shadow-xl hover:border-blue-300 transition-all duration-300 flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 group-hover:-translate-y-2 transition-transform duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800 group-hover:text-blue-600 transition-colors">Word'den PDF'e</h3>
                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">DOCX formatındaki belgelerinizi sayfa yapısı bozulmadan saniyeler içinde PDF formatına dönüştürün.</p>
                </div>
            </a>

            <!-- PDF Birleştir Kartı -->
            <a href="{{ route('tools.merge.view') }}" class="group bg-white rounded-3xl p-8 border border-slate-200 shadow-sm hover:shadow-xl hover:border-indigo-300 transition-all duration-300 flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 group-hover:-translate-y-2 transition-transform duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">PDF Birleştirici</h3>
                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">Birden fazla PDF dosyasını seçin, sıraya koyun ve tek tıklamayla birleştirerek tek belge haline getirin.</p>
                </div>
            </a>

            <!-- Görselden PDF'e Kartı -->
            <a href="{{ route('tools.image.view') }}" class="group bg-white rounded-3xl p-8 border border-slate-200 shadow-sm hover:shadow-xl hover:border-purple-300 transition-all duration-300 flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                <div class="w-16 h-16 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 group-hover:-translate-y-2 transition-transform duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800 group-hover:text-purple-600 transition-colors">Görselden PDF'e</h3>
                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">JPG ve PNG formatındaki resimlerinizi, taranmış evrakları veya makbuzları tek bir PDF dosyasına çevirin.</p>
                </div>
            </a>

            <!-- PDF Şifrele Kartı -->
            <a href="{{ route('tools.protect.view') }}" class="group bg-white rounded-3xl p-8 border border-slate-200 shadow-sm hover:shadow-xl hover:border-slate-800 transition-all duration-300 flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-800 flex items-center justify-center shrink-0 group-hover:-translate-y-2 transition-transform duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800 group-hover:text-slate-900 transition-colors">PDF Şifreleyici</h3>
                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">Belgelerinize açılış parolası ekleyerek yetkisiz kişilerin erişimine karşı AES-256 standardında kilit vurun.</p>
                </div>
            </a>

        </div>
    </div>
@endsection
