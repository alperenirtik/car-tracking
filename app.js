function simulationData() {
    return {
        coordinates: `39.92000,32.85400
39.92013,32.85400
39.92026,32.85400
39.92039,32.85400
39.92052,32.85400
39.92065,32.85400
39.92078,32.85400
39.92091,32.85400
39.92104,32.85400
39.92117,32.85400
39.92130,32.85400
39.92143,32.85400
39.92156,32.85400
39.92169,32.85400
39.92182,32.85400
39.92195,32.85400
39.92208,32.85400
39.92221,32.85400
39.92234,32.85400
39.92247,32.85400
39.92260,32.85410
39.92273,32.85410
39.92286,32.85410
39.92299,32.85410
39.92312,32.85410
39.92325,32.85410
39.92338,32.85410
39.92351,32.85410
39.92364,32.85410
39.92377,32.85410
39.92390,32.85420
39.92403,32.85420
39.92416,32.85420
39.92429,32.85420
39.92442,32.85420
39.92455,32.85420
39.92468,32.85420
39.92481,32.85420
39.92494,32.85420
39.92507,32.85420
39.92520,32.85430
39.92533,32.85430
39.92546,32.85430
39.92559,32.85430
39.92572,32.85430
39.92585,32.85430
39.92598,32.85430
39.92611,32.85430
39.92624,32.85430
39.92637,32.85430
39.92650,32.85440
39.92663,32.85440
39.92676,32.85440
39.92689,32.85440
39.92702,32.85440
39.92715,32.85440
39.92728,32.85440
39.92741,32.85440
39.92754,32.85440
39.92767,32.85440`,
        map: null,
        marker: null,
        isRunning: false,
        currentIndex: 0,
        simulationInterval: null,
        totalDistance: 0,
        remainingDistance: 0,
        currentSpeed: 0,
        maxDistance: 100,
        maxSpeed: 60,
        lastUpdateTime: null,

        init() {
            // Haritayı başlat - ilk koordinata göre
            this.map = L.map('map', {
                attributionControl: false
            }).setView([39.92000, 32.85400], 18);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: ''
            }).addTo(this.map);

            // Ölçek çubuğu ekle
            L.control.scale({
                metric: true,
                imperial: false,
                position: 'bottomleft'
            }).addTo(this.map);

            // Özel araba ikonu oluştur
            const carIcon = L.divIcon({
                html: `<div class="relative car-animation">
                    <div class="car-container">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 512 512">
                            <defs>
                                <linearGradient id="carGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#EF4444"/>
                                    <stop offset="100%" style="stop-color:#DC2626"/>
                                </linearGradient>
                                <filter id="carShadow" x="-20%" y="-20%" width="140%" height="140%">
                                    <feGaussianBlur in="SourceAlpha" stdDeviation="3"/>
                                    <feOffset dx="2" dy="2" result="offsetblur"/>
                                    <feComponentTransfer>
                                        <feFuncA type="linear" slope="0.3"/>
                                    </feComponentTransfer>
                                    <feMerge>
                                        <feMergeNode/>
                                        <feMergeNode in="SourceGraphic"/>
                                    </feMerge>
                                </filter>
                            </defs>
                            <g fill="url(#carGradient)" filter="url(#carShadow)">
                                <path d="M499.99 176h-59.87l-16.64-41.6C406.38 91.63 365.57 64 319.5 64h-127c-46.06 0-86.88 27.63-103.99 70.4L71.87 176H12.01C4.2 176-1.53 183.34.37 190.91l6 24C7.7 220.25 12.5 224 18.01 224h20.07C24.65 235.73 16 252.78 16 272v48c0 16.12 6.16 30.67 16 41.93V416c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32v-32h256v32c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32v-54.07c9.84-11.25 16-25.8 16-41.93v-48c0-19.22-8.65-36.27-22.07-48H494c5.51 0 10.31-3.75 11.64-9.09l6-24c1.89-7.57-3.84-14.91-11.65-14.91zm-352.06-17.83c7.29-18.22 24.94-30.17 44.57-30.17h127c19.63 0 37.28 11.95 44.57 30.17L384 208H128l19.93-49.83zM96 319.8c-19.2 0-32-12.76-32-31.9S76.8 256 96 256s48 28.71 48 47.85-28.8 15.95-48 15.95zm320 0c-19.2 0-48 3.19-48-15.95S396.8 256 416 256s32 12.76 32 31.9-12.8 31.9-32 31.9z"/>
                            </g>
                        </svg>
                        <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2">
                            <div class="w-8 h-8 bg-red-500/20 rounded-full animate-ping"></div>
                        </div>
                    </div>
                    <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-10 h-1 bg-black/10 rounded-full blur-sm"></div>
                </div>`,
                className: 'custom-car-marker',
                iconSize: [40, 40],
                iconAnchor: [20, 20],
            });

            // Marker'ı araba ikonu ile oluştur - ilk koordinata göre
            this.marker = L.marker([39.92000, 32.85400], {
                icon: carIcon
            }).addTo(this.map);

            // İlk koordinatlar için istatistikleri güncelle
            const coordArray = this.coordinates.split('\n')
                .map(line => line.trim())
                .filter(line => line)
                .map(line => {
                    const [lat, lng] = line.split(',').map(Number);
                    return [lat, lng];
                });

            if (coordArray.length >= 2) {
                this.updateStats(coordArray, coordArray[0], coordArray[1]);
            }
        },

        calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // Dünya yarıçapı (km)
            const dLat = this.deg2rad(lat2 - lat1);
            const dLon = this.deg2rad(lon2 - lon1);
            const a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(this.deg2rad(lat1)) * Math.cos(this.deg2rad(lat2)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        },

        deg2rad(deg) {
            return deg * (Math.PI / 180);
        },

        loadCSV(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const newCoordinates = e.target.result;
                    // Önce mevcut koordinatları temizle
                    if (this.coordinates.trim() !== '') {
                        Swal.fire({
                            title: 'Dikkat!',
                            text: 'Mevcut koordinatlar temizlenip yeni koordinatlar yüklenecek.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Devam Et',
                            cancelButtonText: 'İptal',
                            confirmButtonColor: '#10B981',
                            cancelButtonColor: '#EF4444'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.coordinates = newCoordinates;
                                Swal.fire({
                                    title: 'Başarılı!',
                                    text: 'CSV dosyası başarıyla yüklendi.',
                                    icon: 'success',
                                    confirmButtonText: 'Tamam',
                                    confirmButtonColor: '#10B981'
                                });
                            }
                        });
                    } else {
                        this.coordinates = newCoordinates;
                        Swal.fire({
                            title: 'Başarılı!',
                            text: 'CSV dosyası başarıyla yüklendi.',
                            icon: 'success',
                            confirmButtonText: 'Tamam',
                            confirmButtonColor: '#10B981'
                        });
                    }
                };
                reader.readAsText(file);
            }
            // Input'u sıfırla ki aynı dosya tekrar seçilebilsin
            event.target.value = '';
        },

        updateStats(coordArray, currentPos, nextPos) {
            // Toplam mesafe hesaplama (tüm noktalar arası toplam mesafe)
            let totalDistance = 0;
            for (let i = 0; i < coordArray.length - 1; i++) {
                totalDistance += this.calculateDistance(
                    coordArray[i][0], coordArray[i][1],
                    coordArray[i + 1][0], coordArray[i + 1][1]
                );
            }
            this.totalDistance = totalDistance.toFixed(2);

            // Kalan mesafe hesaplama (mevcut noktadan son noktaya kadar olan mesafe)
            let remainingDistance = 0;
            for (let i = this.currentIndex; i < coordArray.length - 1; i++) {
                remainingDistance += this.calculateDistance(
                    coordArray[i][0], coordArray[i][1],
                    coordArray[i + 1][0], coordArray[i + 1][1]
                );
            }
            this.remainingDistance = remainingDistance.toFixed(2);

            // Ortalama hız hesaplama (km/saat)
            if (currentPos && nextPos) {
                // Geçen süre (saat cinsinden)
                const elapsedTimeHours = (this.currentIndex + 1) / 3600;
                // Ortalama hız = Toplam mesafe / Geçen süre
                this.currentSpeed = ((totalDistance - remainingDistance) / elapsedTimeHours).toFixed(2);

                // Gerçekçi bir maksimum hız sınırı
                if (this.currentSpeed > 120) {
                    this.currentSpeed = 120;
                }
            } else {
                this.currentSpeed = 0;
            }

            // Maksimum değerleri güncelle
            this.maxDistance = Math.max(this.maxDistance, totalDistance);
            this.maxSpeed = Math.max(this.maxSpeed, this.currentSpeed);
        },

        startSimulation() {
            if (this.isRunning) return;

            const coordArray = this.coordinates.split('\n')
                .map(line => line.trim())
                .filter(line => line)
                .map(line => {
                    const [lat, lng] = line.split(',').map(Number);
                    return [lat, lng];
                });

            if (coordArray.length < 2) {
                Swal.fire({
                    title: 'Hata!',
                    text: 'En az iki koordinat girmelisiniz!',
                    icon: 'error',
                    confirmButtonText: 'Tamam',
                    confirmButtonColor: '#EF4444'
                });
                return;
            }

            this.isRunning = true;
            this.currentIndex = 0;
            this.lastUpdateTime = Date.now();
            this.maxDistance = 0;
            this.maxSpeed = 0;

            // İlk konuma git
            this.marker.setLatLng(coordArray[0]);
            this.map.setView(coordArray[0], 18);
            this.updateStats(coordArray, coordArray[0], coordArray[1]);

            this.simulationInterval = setInterval(() => {
                this.currentIndex++;
                if (this.currentIndex >= coordArray.length) {
                    this.stopSimulation();
                    Swal.fire({
                        title: 'Geldik Geldik!',
                        html: `<div class="space-y-2">
                            <p class="text-lg">Bütün koordinatlar başarıyla işlendi.</p>
                            <p class="text-sm text-gray-500">Toplam ${coordArray.length} koordinat gezildi.</p>
                            <p class="text-sm text-gray-500">Toplam mesafe: ${this.totalDistance} km</p>
                            <p class="text-sm text-gray-500">Ortalama hız: ${this.currentSpeed} km/saat</p>
                        </div>`,
                        icon: 'success',
                        confirmButtonText: 'Tamam',
                        confirmButtonColor: '#10B981'
                    });
                    return;
                }

                const currentPos = coordArray[this.currentIndex - 1];
                const newPos = coordArray[this.currentIndex];
                const nextPos = coordArray[this.currentIndex + 1];

                this.updateStats(coordArray, currentPos, newPos);
                this.marker.setLatLng(newPos);
                this.map.panTo(newPos, {
                    animate: true,
                    duration: 1.0,
                    easeLinearity: 0.5
                });
            }, 1000);
        },

        stopSimulation() {
            this.isRunning = false;
            clearInterval(this.simulationInterval);
        }
    }
}