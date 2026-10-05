<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'DocuFlow - Akıllı Belge Dönüştürücü' }}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col font-sans">

<!-- Navbar -->
<header class="bg-white border-b border-slate-200 sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
        <a href="/" class="flex items-center gap-2 font-bold text-xl text-indigo-600">
            <i class="ph ph-files text-2xl"></i>
            <span>DocuFlow</span>
        </a>
        <nav class="flex items-center gap-6 text-sm font-medium text-slate-600">
            <a href="/" class="hover:text-indigo-600 transition">Ana Sayfa</a>
            <a href="#tools" class="hover:text-indigo-600 transition">Araçlar</a>
        </nav>
    </div>
</header>

<!-- Bildirim / Alert Alanı -->
<div class="max-w-2xl mx-auto px-4 w-full mt-6">
    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
            <div class="flex items-center gap-2">
                <i class="ph ph-check-circle text-xl"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>

            @if(session('conversion_id'))
                <a href="{{ route('convert.download', session('conversion_id')) }}" class="whitespace-nowrap flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg font-semibold transition shadow-sm">
                    <i class="ph ph-download-simple text-lg"></i>
                    <span>PDF'i İndir</span>
                </a>
            @endif
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 mb-4 text-sm text-rose-800 rounded-xl bg-rose-50 border border-rose-200">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<!-- Dinamik İçerik (welcome.blade.php buraya oturacak) -->
<main class="flex-1 max-w-6xl mx-auto px-4 w-full py-4">
    @yield('content')
</main>

<!-- Footer -->
<footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
    <div class="max-w-6xl mx-auto px-4 flex flex-col sm:flex-row justify-between items-center gap-2">
        <p>© {{ date('Y') }} DocuFlow. Tüm hakları saklıdır.</p>
        <p class="text-slate-400">Dosyalarınız işlemden sonra otomatik olarak temizlenir.</p>
    </div>
</footer>

</body>
</html>
