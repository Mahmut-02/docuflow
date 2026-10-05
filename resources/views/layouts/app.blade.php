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
