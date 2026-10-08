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

                <!-- Sürükle-Bırak Alanı -->
                <div id="dropzone" class="relative border-2 border-dashed border-gray-300 rounded-xl p-10 text-center hover:bg-gray-50 transition cursor-pointer flex flex-col items-center justify-center gap-3">
                    <!-- Gizli dosya inputu -->
                    <input type="file" name="document" id="fileInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".doc,.docx" required>

                    <!-- İkon -->
                    <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>

                    <!-- Metin -->
                    <p class="text-gray-600 font-medium"><span class="text-blue-600 font-semibold">Dosya Seç</span> veya buraya sürükleyin</p>
                    <p class="text-sm text-gray-400">Sadece .doc ve .docx (Maks 10MB)</p>

                    <!-- Seçilen dosya adının belireceği rozet -->
                    <div id="fileNameDisplay" class="hidden mt-4 px-4 py-2 bg-blue-50 text-blue-700 border border-blue-100 rounded-lg text-sm font-semibold flex items-center gap-2 shadow-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"></path></svg>
                        <span id="fileNameText"></span>
                    </div>
                </div>

                <!-- Gönder Butonu -->
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    <i class="ph ph-arrows-clockwise text-lg"></i>
                    <span>PDF'e Dönüştür</span>
                </button>
            </form>
        </div>

        <!-- GEÇMİŞ DÖNÜŞTÜRMELER TABLOSU -->
        @if($conversions->isNotEmpty())
            <div class="mt-12 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Son Dönüştürmeler</h3>
                <div class="overflow-x-auto max-h-72 overflow-y-auto pr-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                        <tr class="text-sm text-gray-400 border-b border-gray-100">
                            <th class="pb-3 font-medium">Dosya Adı</th>
                            <th class="pb-3 font-medium">Tarih</th>
                            <th class="pb-3 font-medium text-right">İşlem</th>
                        </tr>
                        </thead>
                        <tbody class="text-sm">
                        @foreach($conversions as $conversion)
                            <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50 transition">
                                <td class="py-4 text-gray-700 font-medium">
                                    {{ $conversion->original_filename }}
                                </td>
                                <td class="py-4 text-gray-500">
                                    {{ $conversion->created_at->diffForHumans() }}
                                </td>

                                <td class="py-4 text-right flex justify-end items-center gap-4">
                                    @if($conversion->status === 'completed')
                                        <a href="{{ route('convert.download', $conversion->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold transition">
                                            PDF İndir &darr;
                                        </a>
                                    @elseif($conversion->status === 'failed')
                                        <span class="text-red-500 font-medium">Başarısız</span>
                                    @else
                                        <span class="text-yellow-500 animate-pulse font-medium">İşleniyor...</span>
                                    @endif

                                    <!-- Silme Butonu (Çöp Kutusu) -->
                                    <form action="{{ route('convert.destroy', $conversion->id) }}" method="POST" onsubmit="return confirm('Bu dosyayı geçmişten silmek istediğinize emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition" title="Sil">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256">
                                                <path d="M216,48H176V40a24,24,0,0,0-24-24H104A24,24,0,0,0,80,40v8H40a8,8,0,0,0,0,16h8V208a16,16,0,0,0,16,16H192a16,16,0,0,0,16-16V64h8a8,8,0,0,0,0-16ZM96,40a8,8,0,0,1,8-8h48a8,8,0,0,1,8,8v8H96Zm96,168H64V64H192ZM112,104v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Zm48,0v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Z"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @endif

    </div>

    <script>
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('fileInput');
        const fileNameDisplay = document.getElementById('fileNameDisplay');
        const fileNameText = document.getElementById('fileNameText');

        // Tarayıcının varsayılan olarak dosyayı yeni sekmede açmasını engeller
        function preventDefaults (e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Sürükleme sırasında mavi çerçeve animasyonu
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
            dropzone.addEventListener(eventName, () => dropzone.classList.add('border-blue-500', 'bg-blue-50'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
            dropzone.addEventListener(eventName, () => dropzone.classList.remove('border-blue-500', 'bg-blue-50'), false);
        });

        // Dosya bırakıldığında input'a aktarma
        dropzone.addEventListener('drop', (e) => {
            let dt = e.dataTransfer;
            let files = dt.files;

            // Sürüklenen dosyayı gizli inputa ata
            fileInput.files = files;
            updateFileName(files[0].name);
        });

        // Butona basıp klasik yöntemle seçildiğinde
        fileInput.addEventListener('change', function() {
            if(this.files && this.files.length > 0) {
                updateFileName(this.files[0].name);
            }
        });

        // Ekranda dosya adını gösteren fonksiyon
        function updateFileName(name) {
            fileNameText.textContent = name;
            fileNameDisplay.classList.remove('hidden');
        }
    </script>
@endsection
