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

            <!-- Araçlar Açılır Menüsü (Dropdown) -->
            <div class="relative group">
                <button class="flex items-center gap-1 hover:text-indigo-600 transition font-medium focus:outline-none py-2">
                    Araçlar
                    <!-- Aşağı Ok İkonu -->
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <!-- Menü İçeriği (Fare üzerine gelince görünür) -->
                <div class="absolute right-0 top-full mt-0 w-48 bg-white border border-slate-100 rounded-xl shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <div class="p-2 flex flex-col gap-1">
                        <a href="/" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition font-medium">Word'den PDF'e</a>
                        <a href="{{ route('tools.merge.view') }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition font-medium">PDF Birleştir</a>
                        <a href="{{ route('tools.image.view') }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition font-medium">Görselden PDF'e</a>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>

<!-- Bildirim / Alert Alanı -->
<div class="max-w-2xl mx-auto px-4 w-full mt-6">
    @if(session('conversion_id'))
        <div id="status-card" class="p-4 mb-4 text-sm rounded-xl border flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm bg-indigo-50 text-indigo-800 border-indigo-200 transition-all duration-500">
            <div class="flex items-center gap-3">
                <i id="status-icon" class="ph ph-spinner animate-spin text-2xl"></i>
                <span id="status-text" class="font-medium text-base">Belgeniz PDF'e dönüştürülüyor, lütfen bekleyin...</span>
            </div>

            <!-- Buton başlangıçta gizli (hidden) olarak geliyor -->
            <a id="download-btn" href="{{ route('convert.download', session('conversion_id')) }}" class="hidden whitespace-nowrap flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg font-semibold shadow-md transition-transform transform hover:scale-105">
                <i class="ph ph-download-simple text-xl"></i>
                <span>PDF'i İndir</span>
            </a>
        </div>

        <!-- Canlı Durum Takibi (Polling) Scripti -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const conversionId = "{{ session('conversion_id') }}";
                const statusCard = document.getElementById('status-card');
                const statusIcon = document.getElementById('status-icon');
                const statusText = document.getElementById('status-text');
                const downloadBtn = document.getElementById('download-btn');

                // Her 2 saniyede bir veritabanına sor
                let pollInterval = setInterval(() => {
                    fetch(`/status/${conversionId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'completed') {
                                clearInterval(pollInterval); // Sormayı bırak

                                // Arayüzü Yeşil (Başarılı) Temaya Çevir
                                statusCard.className = "p-4 mb-4 text-sm rounded-xl border flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm bg-emerald-50 text-emerald-800 border-emerald-200 transition-all duration-500";
                                statusIcon.className = "ph ph-check-circle text-2xl";
                                statusText.innerText = "Dönüştürme başarıyla tamamlandı!";

                                // İndirme butonunu göster
                                downloadBtn.classList.remove('hidden');
                            } else if (data.status === 'failed') {
                                clearInterval(pollInterval); // Sormayı bırak

                                // Arayüzü Kırmızı (Hatalı) Temaya Çevir
                                statusCard.className = "p-4 mb-4 text-sm rounded-xl border flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm bg-rose-50 text-rose-800 border-rose-200 transition-all duration-500";
                                statusIcon.className = "ph ph-warning-circle text-2xl";
                                statusText.innerText = "Dönüştürme sırasında bir hata oluştu!";
                            }
                        })
                        .catch(error => console.error('Hata:', error));
                }, 2000); // 2000 milisaniye = 2 saniye
            });
        </script>
    @endif

        <!-- Standart Hata Mesajları -->
        @if($errors->any())
            <div class="p-4 mb-4 text-sm text-rose-800 rounded-xl bg-rose-50 border border-rose-200 shadow-sm">
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
