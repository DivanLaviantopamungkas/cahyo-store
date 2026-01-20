@php
    $slides = hero_slides();
@endphp

@if(count($slides) > 0)
<section class="mt-3 sm:mt-4">
    <!-- Slider Alpine - Mobile Responsive -->
    <div class="overflow-hidden bg-white rounded-xl shadow-lg">
        <div x-data="{
            currentSlide: 0,
            slides: {{ json_encode($slides) }},
            init() {
                setInterval(() => {
                    this.currentSlide = (this.currentSlide + 1) % this.slides.length;
                }, 4000);
            }
        }" class="relative overflow-hidden w-full">
            
            <!-- Slides Container -->
            <div class="relative w-full">
                <!-- Slides Track -->
                <div class="flex items-center transition-transform duration-500 ease-in-out"
                    :style="`transform: translateX(-${currentSlide * 100}%)`">
                    
                    <template x-for="(slide, index) in slides" :key="index">
                        <!-- Each Slide Full Width -->
                        <div class="w-full flex-shrink-0">
                            <!-- Mobile Version -->
                            <div class="block lg:hidden">
                                <div class="relative h-[180px] sm:h-[220px] md:h-[280px] bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700">
                                    <!-- Background Image -->
                                    <template x-if="slide.image">
                                        <img :src="slide.image" :alt="slide.title || 'Slide ' + (index + 1)"
                                            class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-30">
                                    </template>
                                    
                                    <!-- Content -->
                                    <div class="relative h-full flex items-center px-6 text-white z-10">
                                        <div class="max-w-md">
                                            <h2 x-text="slide.title" class="text-xl sm:text-2xl md:text-3xl font-bold mb-2 leading-tight"></h2>
                                            <p x-text="slide.description" class="text-sm sm:text-base mb-4 text-gray-100 line-clamp-2"></p>
                                            
                                            <template x-if="slide.button_text">
                                                <a :href="slide.button_link || '#'" 
                                                   class="inline-flex items-center bg-white text-blue-600 px-5 py-2.5 rounded-full font-bold text-sm hover:bg-gray-50 transition-all duration-300 shadow-lg hover:shadow-xl">
                                                    <span x-text="slide.button_text"></span>
                                                    <i class='bx bx-chevron-right text-xl ml-1'></i>
                                                </a>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Desktop Version -->
                            <div class="hidden lg:block">
                                <div class="relative min-h-[380px] xl:min-h-[450px] bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 overflow-hidden">
                                    <!-- Background Image -->
                                    <template x-if="slide.image">
                                        <img :src="slide.image" :alt="slide.title || 'Slide ' + (index + 1)"
                                            class="absolute inset-0 w-full h-full object-cover">
                                    </template>
                                    
                                    <!-- Gradient Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/90 via-blue-600/70 to-transparent"></div>
                                    
                                    <!-- Content Container -->
                                    <div class="relative container mx-auto px-8 h-full">
                                        <div class="flex items-center h-full min-h-[380px] xl:min-h-[450px]">
                                            <!-- Left Content -->
                                            <div class="w-full lg:w-1/2 xl:w-2/5 py-12 pr-8">
                                                <!-- Badge -->
                                                <div class="inline-flex items-center bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-semibold mb-6 border border-white/30">
                                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                                                    TERPERCAYA & AMAN
                                                </div>
                                                
                                                <h2 x-text="slide.title" class="text-3xl xl:text-4xl 2xl:text-5xl font-bold text-white mb-4 leading-tight"></h2>
                                                <p x-text="slide.description" class="text-lg xl:text-xl text-gray-100 mb-8 leading-relaxed"></p>
                                                
                                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-8">
                                                    <template x-if="slide.button_text">
                                                        <a :href="slide.button_link || '#'" 
                                                           class="inline-flex items-center bg-white text-blue-600 px-8 py-3.5 rounded-full font-bold text-base hover:bg-gray-50 transition-all duration-300 shadow-xl hover:shadow-2xl hover:-translate-y-0.5">
                                                            <span x-text="slide.button_text"></span>
                                                            <i class='bx bx-chevron-right text-2xl ml-2'></i>
                                                        </a>
                                                    </template>
                                                    
                                                    <!-- Stats -->
                                                    <div class="flex items-center space-x-6">
                                                        <div class="text-white">
                                                            <div class="text-2xl font-bold">UP TO <span class="text-green-300">20%</span></div>
                                                            <div class="text-sm opacity-80">DISKON</div>
                                                        </div>
                                                        <div class="text-white">
                                                            <div class="text-2xl font-bold"><span x-text="'24/' + (index + 1)"></span></div>
                                                            <div class="text-sm opacity-80">JAM</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Info -->
                                                <div class="flex items-center text-white/90">
                                                    <i class='bx bx-check-circle text-xl mr-3 text-green-300'></i>
                                                    <span class="text-sm sm:text-base">Toko Murah, Terpercaya dan Cepat</span>
                                                </div>
                                            </div>
                                            
                                            <!-- Right Image/Graphics -->
                                            <div class="hidden xl:block absolute right-0 top-0 bottom-0 w-1/2">
                                                <template x-if="slide.image">
                                                    <img :src="slide.image" :alt="slide.title || 'Slide ' + (index + 1)"
                                                        class="h-full w-full object-contain object-right scale-110 transform translate-y-8">
                                                </template>
                                                
                                                <!-- Floating Elements -->
                                                <div class="absolute top-1/4 -left-12 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                                                <div class="absolute bottom-1/4 -right-8 w-32 h-32 bg-blue-300/20 rounded-full blur-xl"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Dots Indicator -->
            <div class="absolute bottom-4 sm:bottom-6 left-0 right-0 flex justify-center space-x-2 z-30">
                <template x-for="(slide, index) in slides" :key="index">
                    <button @click="currentSlide = index"
                        class="h-2 transition-all duration-300 rounded-full"
                        :class="currentSlide === index ? 'bg-white w-8 lg:bg-white/90 lg:w-10' : 'bg-white/40 w-3 lg:bg-white/30 lg:w-4 hover:bg-white/60'">
                    </button>
                </template>
            </div>

            <!-- Navigation Arrows - Desktop Only -->
            <div class="hidden lg:block">
                <button @click="currentSlide = (currentSlide - 1 + slides.length) % slides.length"
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white w-12 h-12 transition-all duration-300 z-30 flex items-center justify-center rounded-full shadow-lg border border-white/20 hover:border-white/40 hover:scale-110">
                    <i class='bx bx-chevron-left text-3xl'></i>
                </button>
                <button @click="currentSlide = (currentSlide + 1) % slides.length"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white w-12 h-12 transition-all duration-300 z-30 flex items-center justify-center rounded-full shadow-lg border border-white/20 hover:border-white/40 hover:scale-110">
                    <i class='bx bx-chevron-right text-3xl'></i>
                </button>
            </div>
        </div>
    </div>
</section>
@else
{{-- Fallback jika tidak ada slides --}}
<section class="mt-3 sm:mt-4">
    <div class="overflow-hidden bg-white rounded-xl shadow-lg">
        <div class="relative overflow-hidden w-full">
            <!-- Mobile Version -->
            <div class="block lg:hidden">
                <div class="relative h-[180px] sm:h-[220px] md:h-[280px] bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700">
                    <div class="relative h-full flex items-center px-6 text-white z-10">
                        <div class="max-w-md">
                            <div class="inline-flex items-center bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-semibold mb-3 border border-white/30">
                                <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                                TERPERCAYA & AMAN
                            </div>
                            <h2 class="text-xl sm:text-2xl md:text-3xl font-bold mb-2 leading-tight">Selamat Datang di {{ setting('site_name', 'CahyoStore') }}</h2>
                            <p class="text-sm sm:text-base mb-4 text-gray-100">{{ setting('site_description', 'Top Up Game Termurah & Terpercaya') }}</p>
                            <a href="#products" 
                               class="inline-flex items-center bg-white text-blue-600 px-5 py-2.5 rounded-full font-bold text-sm hover:bg-gray-50 transition-all duration-300 shadow-lg hover:shadow-xl">
                                Mulai Belanja
                                <i class='bx bx-chevron-right text-xl ml-1'></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Version -->
            <div class="hidden lg:block">
                <div class="relative min-h-[380px] xl:min-h-[450px] bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 overflow-hidden">
                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/90 via-blue-600/70 to-transparent"></div>
                    
                    <!-- Content Container -->
                    <div class="relative container mx-auto px-8 h-full">
                        <div class="flex items-center h-full min-h-[380px] xl:min-h-[450px]">
                            <!-- Left Content -->
                            <div class="w-full lg:w-1/2 xl:w-2/5 py-12 pr-8">
                                <!-- Badge -->
                                <div class="inline-flex items-center bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-semibold mb-6 border border-white/30">
                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                                    TERPERCAYA & AMAN
                                </div>
                                
                                <h2 class="text-3xl xl:text-4xl 2xl:text-5xl font-bold text-white mb-4 leading-tight">Tempat Beli Voucher Digital Terbaik MURAH DAN AMAN</h2>
                                <p class="text-lg xl:text-xl text-gray-100 mb-8 leading-relaxed">Beli voucher game, pulsa, e-money dengan harga terbaik. Proses cepat dan aman.</p>
                                
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-8">
                                    <a href="#products" 
                                       class="inline-flex items-center bg-white text-blue-600 px-8 py-3.5 rounded-full font-bold text-base hover:bg-gray-50 transition-all duration-300 shadow-xl hover:shadow-2xl hover:-translate-y-0.5">
                                        Mulai Sekarang
                                        <i class='bx bx-chevron-right text-2xl ml-2'></i>
                                    </a>
                                    
                                    <!-- Stats -->
                                    <div class="flex items-center space-x-6">
                                        <div class="text-white">
                                            <div class="text-2xl font-bold">UP TO <span class="text-green-300">20%</span></div>
                                            <div class="text-sm opacity-80">DISKON</div>
                                        </div>
                                        <div class="text-white">
                                            <div class="text-2xl font-bold">24/7</div>
                                            <div class="text-sm opacity-80">JAM</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Info -->
                                <div class="flex items-center text-white/90">
                                    <i class='bx bx-check-circle text-xl mr-3 text-green-300'></i>
                                    <span class="text-sm sm:text-base">Toko Murah, Terpercaya dan Cepat</span>
                                </div>
                            </div>
                            
                            <!-- Right Graphics -->
                            <div class="hidden xl:block absolute right-0 top-0 bottom-0 w-1/2">
                                <!-- Floating Elements -->
                                <div class="absolute top-1/4 -left-12 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                                <div class="absolute bottom-1/4 -right-8 w-32 h-32 bg-blue-300/20 rounded-full blur-xl"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif