# 📄 DocuFlow - Akıllı Belge Dönüştürücü

DocuFlow, kullanıcıların Word (`.doc`, `.docx`) belgelerini asenkron olarak PDF formatına dönüştürmesini sağlayan, yüksek performanslı ve güvenilir bir web uygulamasıdır.

Büyük boyutlu dosyaların dönüştürülme sürecinde sunucuyu kilitlemesini önlemek amacıyla **Kuyruk (Queue) Mimarisi** kullanılarak geliştirilmiştir.

## ✨ Öne Çıkan Özellikler

* **Asenkron İşlem (Background Jobs):** Dönüştürme işlemleri arayüzü dondurmadan arka planda Laravel Queues ile işlenir.
* **Canlı Durum Takibi (Polling):** JavaScript Fetch API kullanılarak sayfa yenilenmeden gerçek zamanlı durum takibi yapılır.
* **Güvenlik Duvarı:** Yüklenen dosyalar sisteme girmeden önce MIME type ve maksimum boyut (10MB) kontrollerinden geçirilir.
* **Zaman Aşımı Koruması (Timeout):** Bozuk dosyaların sistemi sonsuz döngüye sokmasını engellemek için işçilere (workers) 60 saniyelik zaman aşımı zırhı eklenmiştir.
* **Otonom Temizlik (Scheduler):** Sunucu disk alanını korumak için 2 saatten eski dosyalar ve veritabanı kayıtları saatlik olarak otomatik temizlenir.
* **Dinamik Motor Yolu:** `.env` konfigürasyonu sayesinde uygulama, Mac ve Linux sunucular arasında kod değişikliği gerektirmeden çalışabilir.

## 🛠️ Teknolojiler

* **Backend:** PHP, Laravel 10+
* **Frontend:** Blade, TailwindCSS, Vanilla JS
* **Dönüştürme Motoru:** LibreOffice (Headless Mode)
* **Veritabanı:** SQLite / MySQL

## 🚀 Kurulum ve Çalıştırma

Projeyi kendi ortamında test etmek isteyen geliştiriciler için kurulum adımları:

**1. Projeyi Klonlayın:**
```bash
git clone [https://github.com/Mahmut-02/docuflow.git](https://github.com/Mahmut-02/docuflow.git)
cd docuflow
```

**2. Bağımlılıkları Yükleyin:**
```bash
composer install
```

**3. Çevresel Değişkenleri Ayarlayın:**
`.env.example` dosyasını `.env` olarak kopyalayın ve veritabanı ile LibreOffice yolunu kendi sisteminize göre ayarlayın:
```env
LIBREOFFICE_PATH="/Applications/LibreOffice.app/Contents/MacOS/soffice" # Mac için
# LIBREOFFICE_PATH="/usr/bin/libreoffice" # Linux/Ubuntu için
```

**4. Veritabanını Hazırlayın:**
```bash
php artisan migrate
```

**5. Uygulamayı ve Kuyruk İşçisini Başlatın:**
Birinci terminalde projeyi ayağa kaldırın:
```bash
php artisan serve
```
İkinci terminalde arka plan işçisini (worker) başlatın:
```bash
php artisan queue:work --timeout=65
```
