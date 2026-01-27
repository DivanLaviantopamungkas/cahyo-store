@php
    $slides = hero_slides();
@endphp

@if(count($slides) > 0)
    <section class="relative">
        <div x-data="{
            currentSlide: 0,
            slides: {{ json_encode($slides) }},
            init() {
                setInterval(() => {
                    this.currentSlide = (this.currentSlide + 1) % this.slides.length;
                }, 5000);
            }
        }" class="relative overflow-hidden rounded-[30px] shadow-2xl shadow-blue-900/10 group">
            
            <div class="flex transition-transform duration-1000 ease-[cubic-bezier(0.23,1,0.32,1)]"
                :style="`transform: translateX(-${currentSlide * 100}%)`">
                
                <template x-for="(slide, index) in slides" :key="index">
                    <div class="w-full flex-shrink-0 relative overflow-hidden">
                        <div class="relative h-[180px] sm:h-[280px] md:h-[350px] lg:h-[420px] bg-indigo-950">
                            
                            <template x-if="slide.image">
                                <div class="absolute inset-0">
                                    <img :src="slide.image" class="w-full h-full object-cover transform scale-105 group-hover:scale-100 transition-transform duration-[2000ms]">
                                    <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/40 to-transparent"></div>
                                </div>
                            </template>

                            <div class="relative h-full flex items-center px-7 sm:px-14 md:px-20 text-white z-10">
                                <div class="max-w-[260px] sm:max-w-xl">
                                    <h2 x-text="slide.title" class="text-xl sm:text-4xl md:text-5xl font-black mb-2 sm:mb-4 leading-[1.1] tracking-tighter"></h2>
                                    <p x-text="slide.description" class="text-[10px] sm:text-base text-gray-300 font-medium line-clamp-2 opacity-90"></p>
                                    
                                    </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="absolute bottom-5 left-1/2 transform -translate-x-1/2 flex items-center gap-2 z-30 bg-black/20 backdrop-blur-md px-3 py-2 rounded-full border border-white/10">
                <template x-for="(slide, index) in slides" :key="index">
                    <button @click="currentSlide = index"
                            class="transition-all duration-500 rounded-full"
                            :class="currentSlide === index ? 'bg-blue-500 w-6 h-1.5' : 'bg-white/40 w-1.5 h-1.5 hover:bg-white'"></button>
                </template>
            </div>

            <div class="hidden md:block opacity-0 group-hover:opacity-100 transition-all duration-300">
                <button @click="currentSlide = (currentSlide - 1 + slides.length) % slides.length"
                        class="absolute left-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 backdrop-blur-xl text-white rounded-2xl flex items-center justify-center hover:bg-blue-600 transition-all border border-white/10 z-30 shadow-2xl">
                    <i class='bx bx-chevron-left text-3xl'></i>
                </button>
                <button @click="currentSlide = (currentSlide + 1) % slides.length"
                        class="absolute right-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 backdrop-blur-xl text-white rounded-2xl flex items-center justify-center hover:bg-blue-600 transition-all border border-white/10 z-30 shadow-2xl">
                    <i class='bx bx-chevron-right text-3xl'></i>
                </button>
            </div>
        </div>
    </section>
@endif