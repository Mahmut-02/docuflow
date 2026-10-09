@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto py-10">

        <!-- Başlık & Açıklama (Orijinal Tasarıma Uygun) -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">PDF Birleştirici</h1>
            <p class="text-sm text-slate-500 mt-2">Birden fazla PDF dosyasını seçin, sıraya koyun ve tek tıkla birleştirin.</p>
        </div>

        <!-- Yükleme Kartı / Form -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm">

            @if ($errors->any())
                <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm font-medium">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('tools.merge.process') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="dropzone" class="relative border-2 border-dashed border-gray-300 rounded-xl p-10 text-center hover:border-indigo-400 hover:bg-indigo-50 transition cursor-pointer flex flex-col items-center justify-center">
                    <input type="file" name="documents[]" id="fileInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".pdf" multiple required>

                    <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                    </svg>

                    <p class="text-gray-600 font-medium"><span class="text-indigo-600 font-semibold">PDF Dosyalarını Seç</span> veya sürükle</p>
                    <p class="text-sm text-gray-400 mt-1">En az 2 adet PDF belgesi yükleyin</p>

                    <div id="fileCountBadge" class="hidden mt-4 px-4 py-2 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-lg text-sm font-semibold flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span id="fileCountText">0 dosya seçildi</span>
                    </div>
                </div>

                <button type="submit" class="mt-6 w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-xl shadow-sm transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    PDF'leri Birleştir ve İndir
                </button>
            </form>
        </div>
    </div>

    <script>
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('fileInput');
        const fileCountBadge = document.getElementById('fileCountBadge');
        const fileCountText = document.getElementById('fileCountText');

        function preventDefaults (e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, () => {
                dropzone.classList.add('border-indigo-500', 'bg-indigo-50');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, () => {
                dropzone.classList.remove('border-indigo-500', 'bg-indigo-50');
            }, false);
        });

        dropzone.addEventListener('drop', (e) => {
            let dt = e.dataTransfer;
            let files = dt.files;
            fileInput.files = files;
            updateFileDisplay(files);
        });

        fileInput.addEventListener('change', function() {
            if(this.files && this.files.length > 0) {
                updateFileDisplay(this.files);
            }
        });

        function updateFileDisplay(files) {
            fileCountText.textContent = files.length + " adet PDF seçildi";
            fileCountBadge.classList.remove('hidden');
        }
    </script>
@endsection
