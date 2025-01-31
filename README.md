# 🚗 Araç Takip Simülasyonu

Bu proje, fiziksel araçlardan API vasıtasıyla alınan gerçek zamanlı GPS koordinatlarını simüle etmek için geliştirilmiş bir web uygulamasıdır. Her koordinat 1 saniyelik zaman dilimini temsil eder ve araç bu koordinatlar arasında gerçek zamanlı olarak hareket eder.

## 👨‍💻 Demo https://proje.alperenirtik.com/proje/car-tracking/


## 🚀 Özellikler

- 🗺️ Harita üzerinden anlık animasyonlu araç takibi
- 📏 Toplam mesafe hesaplama
- ⚡ Ortalama hız hesaplama (km/saat)
- 🎯 Hedefe kalan mesafe takibi
- 📁 CSV dosyası ile koordinat yükleme desteği
- 📱 Tam responsive tasarım

## 🛠️ Kullanılan Teknolojiler

### Frontend
- HTML5
- TailwindCSS (v3.x)
- Alpine.js (v3.x)

### Harita
- Leaflet.js
- OpenStreetMap

### Animasyonlar
- CSS3 Transitions
- CSS3 Transforms

### Bildirimler
- SweetAlert2

## 📦 Kurulum

1. Projeyi klonlayın:
```bash
git clone https://github.com/alperenirtik/car-tracking.git
```

2. Proje dizinine gidin:
```bash
cd car-tracking
```

3. Gerekli bağımlılıkları yükleyin:
- TailwindCSS CDN
- Alpine.js CDN
- Leaflet.js CDN
- SweetAlert2 CDN

4. Projeyi başlatın:
- PHP ile yerel sunucu başlatın:
```bash
php -S localhost:8000
```

## 💻 Kullanım

1. Koordinatları manuel girin veya CSV dosyası yükleyin
   - Her satıra bir koordinat çifti (enlem,boylam)
   - Örnek: `39.925533,32.866287`

2. "Simülasyonu Başlat" butonuna tıklayın
   - Her koordinat 1 saniye aralıkla işlenir
   - Araç harita üzerinde animasyonlu şekilde hareket eder
   - İstatistikler gerçek zamanlı güncellenir

3. İstatistikleri takip edin
   - Toplam mesafe
   - Ortalama hız
   - Kalan mesafe

## 📄 CSV Dosya Formatı

```csv
39.925533,32.866287
39.925633,32.866387
39.925733,32.866487
```

## 📝 Lisans

Bu proje MIT lisansı altında lisanslanmıştır. Daha fazla bilgi için `LICENSE` dosyasına bakın.

## 👨‍💻 Geliştirici

- **Alperen İrtik**
  - Website: [alperenirtik.com](https://www.alperenirtik.com)
  - Firma: [ankasoftyazilim.com](https://www.ankasoftyazilim.com)
  - GitHub: [@alperenirtik](https://github.com/alperenirtik) 
