<section class="mt-3">
    <!-- Slider Alpine - Mobile Responsive -->
    <div class="overflow-hidden bg-white">
        <div x-data="{
            currentSlide: 0,
            slides: [
                '/images/slider/topup1.png',
                '/images/slider/topup2.jpg',
                '/images/slider/topup3.jpg'
            ],
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
                            <!-- Mobile: Full width tanpa spacer -->
                            <div class="block md:hidden">
                                <div class="relative h-[180px] sm:h-[220px]">
                                    <img :src="slide" :alt="'Promo ' + (index + 1)"
                                        class="w-full h-full object-cover">
                                </div>
                            </div>

                            <!-- Desktop: Dengan spacer -->
                            <div class="hidden md:flex items-center py-6">
                                <!-- Left Spacer -->
                                <div class="w-1/6 flex-shrink-0"></div>

                                <!-- Main Image -->
                                <div class="flex-1 relative h-[350px] lg:h-[400px]">
                                    <img :src="slide" :alt="'Promo ' + (index + 1)"
                                        class="w-full h-full object-cover rounded-lg shadow-xl">
                                </div>

                                <!-- Right Spacer -->
                                <div class="w-1/6 flex-shrink-0"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Dots Indicator -->
            <div class="absolute bottom-3 left-0 right-0 flex justify-center space-x-2 z-30">
                <template x-for="(slide, index) in slides" :key="index">
                    <button @click="currentSlide = index"
                        class="w-8 h-1.5 transition-all duration-300 bg-gray-400 hover:bg-gray-600"
                        :class="currentSlide === index ? 'bg-black w-12' : ''">
                    </button>
                </template>
            </div>

            <!-- Navigation Arrows - Desktop Only -->
            <div class="hidden md:block">
                <button @click="currentSlide = (currentSlide - 1 + slides.length) % slides.length"
                    class="absolute left-6 top-1/2 transform -translate-y-1/2 bg-black/70 text-white w-10 h-10 hover:bg-black transition-all duration-300 z-30 flex items-center justify-center rounded-full shadow-lg">
                    <i class='bx bx-chevron-left text-2xl'></i>
                </button>
                <button @click="currentSlide = (currentSlide + 1) % slides.length"
                    class="absolute right-6 top-1/2 transform -translate-y-1/2 bg-black/70 text-white w-10 h-10 hover:bg-black transition-all duration-300 z-30 flex items-center justify-center rounded-full shadow-lg">
                    <i class='bx bx-chevron-right text-2xl'></i>
                </button>
            </div>
        </div>
    </div>
</section>
