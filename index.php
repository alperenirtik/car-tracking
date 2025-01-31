<!DOCTYPE html>
<html lang="tr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Araç Takip Simülasyonu</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="./favicon.ico">
    
    <!-- Özel CSS ve JS dosyaları -->
    <link rel="stylesheet" href="./app.css">
    <script defer src="./app.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body class="h-full" x-data="simulationData()">
    <div class="min-h-full">
        <!-- Navbar -->
        <nav class="bg-white/80 backdrop-blur-lg border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900">Araç Takip Simülasyonu</h1>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Ana İçerik -->
        <main class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-6 py-4 sm:py-6 lg:py-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
                <!-- Sol Panel - Kontrol Alanı -->
                <div class="bg-white rounded-xl sm:rounded-2xl content-shadow order-2 lg:order-1">
                    <div class="p-4 sm:p-6 lg:p-8">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">Koordinat Listesi</h2>
                        </div>
                        <div class="space-y-6">
                            <div class="relative">
                    <textarea 
                        x-model="coordinates" 
                                    class="w-full h-48 sm:h-64 p-3 sm:p-4 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none text-sm sm:text-base"
                        placeholder="Her satıra bir koordinat çifti girin (örn: 35.1234,33.4567)"
                    ></textarea>
                                <div class="absolute bottom-4 right-4 text-sm bg-white px-3 py-1.5 rounded-md shadow-sm">
                                    <div class="flex items-center gap-1 text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span x-text="coordinates.split('\n').filter(line => line.trim()).length + ' koordinat ('+ coordinates.split('\n').filter(line => line.trim()).length + ' saniye)'"></span>
                                    </div>
                                </div>
                </div>
                            <div class="flex gap-4 mb-4">
                    <button 
                        @click="startSimulation"
                                    class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0"
                        :disabled="isRunning"
                    >
                                    <span class="flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.933 12.8a1 1 0 000-1.6L6.6 7.2A1 1 0 005 8v8a1 1 0 001.6.8l5.333-4zM19.933 12.8a1 1 0 000-1.6l-5.333-4A1 1 0 0013 8v8a1 1 0 001.6.8l5.333-4z"/>
                                        </svg>
                        Simülasyonu Başlat
                                    </span>
                    </button>
                    <button 
                        @click="stopSimulation"
                        x-show="isRunning"
                                    class="flex-1 bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 transform hover:-translate-y-0.5"
                    >
                                    <span class="flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                        Durdur
                                    </span>
                                </button>
                            </div>
                            <button 
                                @click="coordinates = ''; totalDistance = 0; remainingDistance = 0; currentSpeed = 0;"
                                class="w-full bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 transform hover:-translate-y-0.5 mb-4 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0"
                                :disabled="isRunning"
                            >
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Koordinatları Temizle
                                </span>
                    </button>

                            <!-- CSV Yükleme -->
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-600">CSV Dosyası Yükle</span>
                                    <label class="cursor-pointer bg-white px-4 py-2 rounded-lg border border-gray-200 hover:border-blue-400 transition-colors">
                                        <input type="file" 
                                               accept=".csv" 
                                               class="hidden" 
                                               @change="loadCSV($event)"
                                               :disabled="isRunning">
                                        <span class="text-sm text-gray-600 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                            CSV Yükle
                                        </span>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">CSV formatı: Her satırda "enlem,boylam" şeklinde koordinat çiftleri olmalıdır.</p>
                            </div>

                            <!-- Proje Açıklaması -->
                            <div class="mt-6 p-6 bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-100">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 bg-blue-500/10 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-blue-400">
                                        Proje Hakkında
                                    </h3>
                                </div>

                                <div class="space-y-4 text-sm text-gray-600">
                                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                                        <p class="leading-relaxed">
                                            Bu proje, fiziksel araçlardan API vasıtasıyla alınan gerçek zamanlı GPS koordinatlarını simüle etmek için geliştirilmiş bir web uygulamasıdır. Her koordinat 1 saniyelik zaman dilimini temsil eder ve araç bu koordinatlar arasında gerçek zamanlı olarak hareket eder. Araç takip sistemlerinin çalışma mantığını ve veri akışını görselleştirmek amacıyla tasarlanmıştır.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sağ Panel - Harita -->
                <div class="bg-white rounded-2xl content-shadow order-1 lg:order-2">
                    <div class="p-8">
                        <div class="map-container">
                            <div id="map" class="h-[300px] sm:h-[400px] lg:h-[500px] rounded-xl"></div>
                            <div class="map-overlay" x-show="isRunning">
                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                        <span class="text-sm font-medium text-gray-700">Koordinat: <span x-text="currentIndex + 1"></span></span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                        <span class="text-sm font-medium text-gray-700">Anlık Yol: <span x-text="((totalDistance - remainingDistance) * 1000).toFixed(0) + ' metre'"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- İstatistik Barları -->
                        <div class="flex flex-col gap-3 mt-4">
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 w-full">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-600">Toplam Mesafe</span>
                                    </div>
                                    <span class="text-sm font-bold text-blue-600" x-text="totalDistance + ' km'">0 km</span>
                                </div>
                                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-500 rounded-full transition-all duration-300"
                                         :style="'width: ' + (totalDistance / maxDistance * 100) + '%'"></div>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 w-full">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-600">Ortalama Hız</span>
                                    </div>
                                    <span class="text-sm font-bold text-green-600" x-text="currentSpeed + ' km/saat'">0 km/saat</span>
                                </div>
                                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-green-500 rounded-full transition-all duration-300"
                                         :style="'width: ' + (currentSpeed / maxSpeed * 100) + '%'"></div>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 w-full">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.933 12.8a1 1 0 000-1.6L6.6 7.2A1 1 0 005 8v8a1 1 0 001.6.8l5.333-4zM19.933 12.8a1 1 0 000-1.6l-5.333-4A1 1 0 0013 8v8a1 1 0 001.6.8l5.333-4z"/>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-600">Kalan Mesafe</span>
                                    </div>
                                    <span class="text-sm font-bold text-purple-600" x-text="remainingDistance + ' km'">0 km</span>
                                </div>
                                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-purple-500 rounded-full transition-all duration-300"
                                         :style="'width: ' + (remainingDistance / maxDistance * 100) + '%'"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Teknolojiler ve Özellikler -->
            <div class="mt-4 sm:mt-6 lg:mt-8 grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <div class="bg-white p-6 rounded-2xl content-shadow border border-gray-100 hover:border-blue-200 transition-colors">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-blue-500/10 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-blue-400">
                            Kullanılan Teknolojiler
                        </h3>
                    </div>
                    <ul class="space-y-3">
                        <li class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-2 mb-1 sm:mb-0">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                                <span class="font-medium">Frontend:</span>
                            </div>
                            <span class="text-gray-600 pl-4 sm:pl-0">HTML5, TailwindCSS, Alpine.js</span>
                        </li>
                        <li class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-2 mb-1 sm:mb-0">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                <span class="font-medium">Harita:</span>
                            </div>
                            <span class="text-gray-600 pl-4 sm:pl-0">Leaflet.js (OpenStreetMap)</span>
                        </li>
                        <li class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-2 mb-1 sm:mb-0">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="font-medium">Animasyonlar:</span>
                            </div>
                            <span class="text-gray-600 pl-4 sm:pl-0">CSS3</span>
                        </li>
                        <li class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-2 mb-1 sm:mb-0">
                                <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                                <span class="font-medium">Bildirimler:</span>
                            </div>
                            <span class="text-gray-600 pl-4 sm:pl-0">SweetAlert2</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-white p-6 rounded-2xl content-shadow border border-gray-100 hover:border-blue-200 transition-colors">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-blue-500/10 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-blue-400">
                            Özellikler
                        </h3>
                    </div>
                    <ul class="space-y-3">
                        <li class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-2 mb-1 sm:mb-0">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                                <span class="font-medium">Harita Takibi:</span>
                            </div>
                            <span class="text-gray-600 pl-4 sm:pl-0">Harita üzerinden anlık animasyonlu araç takibi</span>
                        </li>
                        <li class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-2 mb-1 sm:mb-0">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                <span class="font-medium">Toplam Mesafe:</span>
                            </div>
                            <span class="text-gray-600 pl-4 sm:pl-0">Kat edilen toplam yol hesaplama</span>
                        </li>
                        <li class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-2 mb-1 sm:mb-0">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="font-medium">Ortalama Hız:</span>
                            </div>
                            <span class="text-gray-600 pl-4 sm:pl-0">Km/saat cinsinden hız hesaplama</span>
                        </li>
                        <li class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-2 mb-1 sm:mb-0">
                                <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                                <span class="font-medium">Kalan Mesafe:</span>
                            </div>
                            <span class="text-gray-600 pl-4 sm:pl-0">Hedefe kalan mesafe takibi</span>
                        </li>
                    </ul>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gradient-to-b from-white to-gray-50 border-t border-gray-100 mt-6 sm:mt-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
                <div class="flex flex-col items-center justify-center space-y-4">
                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="text-center">
                        <p class="text-xl font-bold text-gray-900 mb-1">Alperen İrtik</p>
                        <p class="text-sm text-gray-500 bg-white px-4 py-1 rounded-full shadow-sm border border-gray-100 inline-block">
                            Araç Takip Simülasyonu
                        </p>
                    </div>
                    <!-- Sosyal Medya Butonları -->
                    <div class="flex flex-wrap justify-center gap-2 sm:gap-3 mt-4 sm:mt-6 px-2 sm:px-0">
                        <a href="https://www.alperenirtik.com/" target="_blank" class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:shadow-lg transition-all duration-200 hover:-translate-y-0.5">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                            </svg>
                            <span class="text-sm sm:text-base font-medium">Kişisel Web Sitem</span>
                        </a>
                        <a href="https://www.ankasoftyazilim.com/" target="_blank" class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl hover:shadow-lg transition-all duration-200 hover:-translate-y-0.5">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="text-sm sm:text-base font-medium">Firma Sitem</span>
                        </a>
                        <a href="https://github.com/alperenirtik/barcode-scanner" target="_blank" class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-gradient-to-r from-gray-700 to-gray-800 text-white rounded-xl hover:shadow-lg transition-all duration-200 hover:-translate-y-0.5">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.87 8.17 6.84 9.5.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34-.46-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.87 1.52 2.34 1.07 2.91.83.09-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.92 0-1.11.38-2 1.03-2.71-.1-.25-.45-1.29.1-2.64 0 0 .84-.27 2.75 1.02.79-.22 1.65-.33 2.5-.33.85 0 1.71.11 2.5.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.35.2 2.39.1 2.64.65.71 1.03 1.6 1.03 2.71 0 3.82-2.34 4.66-4.57 4.91.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0012 2z"/>
                            </svg>
                            <span class="text-sm sm:text-base font-medium">GitHub'da İncele</span>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>


</body>
</html> 