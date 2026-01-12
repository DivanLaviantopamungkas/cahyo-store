@extends('admin.layouts.app')

@section('title', 'Pengaturan Landing Page')
@section('breadcrumb', 'Kelola Landing Page')

@section('actions')
    <button type="submit" form="landing-form" 
            class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 rounded-xl sm:rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-medium sm:font-semibold text-sm shadow-lg hover:shadow-xl transition-all duration-300">
        <svg class="w-4 h-4 mr-1 sm:mr-2"><use href="#icon-check"></use></svg>
        <span class="hidden xs:inline">Simpan Perubahan</span>
        <span class="xs:hidden">Simpan</span>
    </button>
@endsection

@section('content')
<div x-data="landingApp()" x-init="init()" class="pb-6">
    <x-admin.card class="p-4 sm:p-6">
        <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4 sm:mb-6 pb-3 sm:pb-4 border-b border-slate-200 dark:border-slate-700">
            Landing Page
        </h3>
        
        <form action="{{ route('admin.settings.update.landing') }}" method="POST" id="landing-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-6 sm:space-y-8">
                <!-- Hero Section Slider -->
                <div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 sm:mb-6">
                        <h4 class="font-semibold text-slate-800 dark:text-white text-base sm:text-lg">
                            Hero Section Slider
                        </h4>
                        <button type="button" @click="addHeroSlide()"
                                class="inline-flex items-center justify-center px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium w-full sm:w-auto">
                            <svg class="w-4 h-4 mr-1.5"><use href="#icon-plus"></use></svg>
                            Tambah Slide
                        </button>
                    </div>
                    
                    <!-- Hero Slides Management -->
                    <template x-for="(slide, index) in heroSlides" :key="slide.id">
                        <div class="mb-6 p-4 sm:p-6 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl bg-slate-50/50 dark:bg-slate-800/50">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-4 border-b border-slate-200 dark:border-slate-700">
                                <h5 class="font-medium text-slate-800 dark:text-white">
                                    Slide <span x-text="index + 1"></span>
                                </h5>
                                <div class="flex items-center justify-between sm:justify-end space-x-4">
                                    <button type="button" @click="removeHeroSlide(index)"
                                            :disabled="heroSlides.length <= 1"
                                            :class="heroSlides.length <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:text-rose-500'"
                                            class="text-slate-500 dark:text-slate-400 transition-colors p-1">
                                        <svg class="w-5 h-5"><use href="#icon-trash"></use></svg>
                                    </button>
                                    <div class="flex items-center space-x-2">
                                        <div class="relative inline-block w-10 h-5 sm:w-12 sm:h-6">
                                            <input type="checkbox" :id="'slide_active_' + slide.id"
                                                   x-model="slide.is_active"
                                                   class="sr-only peer">
                                            <div class="w-10 h-5 sm:w-12 sm:h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-emerald-500 transition-colors"></div>
                                            <div class="absolute left-1 top-1 w-3 h-3 sm:w-4 sm:h-4 rounded-full bg-white transition-transform peer-checked:translate-x-5 sm:peer-checked:translate-x-6"></div>
                                        </div>
                                        <label :for="'slide_active_' + slide.id" class="text-sm text-slate-600 dark:text-slate-400 whitespace-nowrap">
                                            <span x-show="slide.is_active">Aktif</span>
                                            <span x-show="!slide.is_active">Nonaktif</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Hidden inputs for slide data -->
                            <input type="hidden" :name="'hero_slides[' + index + '][id]'" :value="slide.id">
                            <input type="hidden" :name="'hero_slides[' + index + '][order]'" :value="index">
                            <input type="hidden" :name="'hero_slides[' + index + '][is_active]'" :value="slide.is_active ? 1 : 0">
                            
                            <div class="space-y-4 sm:space-y-6">
                                <!-- Slide Title -->
                                <div>
                                    <label :for="'slide_title_' + slide.id" 
                                           class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Judul Slide
                                    </label>
                                    <input type="text" :id="'slide_title_' + slide.id"
                                           x-model="slide.title"
                                           :name="'hero_slides[' + index + '][title]'"
                                           class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                                </div>
                                
                                <!-- Slide Description -->
                                <div>
                                    <label :for="'slide_description_' + slide.id" 
                                           class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Deskripsi Slide
                                    </label>
                                    <textarea :id="'slide_description_' + slide.id"
                                              x-model="slide.description"
                                              :name="'hero_slides[' + index + '][description]'"
                                              rows="2"
                                              class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all"></textarea>
                                </div>
                                
                                <!-- Slide Image -->
                                <div>
                                    <label :for="'slide_image_' + slide.id" 
                                           class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Gambar Slide
                                    </label>
                                    
                                    <!-- Existing Image Preview -->
                                    <template x-if="slide.existing_image">
                                        <div class="mb-3 relative group">
                                            <img :src="slide.existing_image" :alt="'Slide ' + (index + 1)" 
                                                 class="h-40 sm:h-48 w-full object-cover rounded-lg">
                                            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-lg">
                                                <span class="text-white text-sm">Gambar tersimpan</span>
                                            </div>
                                        </div>
                                    </template>
                                    
                                    <!-- File Input -->
                                    <div class="space-y-3">
                                        <input type="file" :id="'slide_image_' + slide.id"
                                               @change="handleSlideImageUpload($event, index)"
                                               :name="'hero_slides[' + index + '][image]'"
                                               accept="image/*"
                                               class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs sm:file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-emerald-900/20 dark:file:text-emerald-300">
                                        
                                        <!-- New Image Preview -->
                                        <template x-if="slide.new_image_preview">
                                            <div class="relative">
                                                <img :src="slide.new_image_preview" :alt="'Preview slide ' + (index + 1)" 
                                                     class="h-40 sm:h-48 w-full object-cover rounded-lg">
                                                <button type="button" @click="removeNewImage(index)"
                                                        class="absolute top-2 right-2 p-1.5 bg-rose-500 text-white rounded-full hover:bg-rose-600 transition-colors">
                                                    <svg class="w-4 h-4"><use href="#icon-x-mark"></use></svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                    
                                    <!-- Hidden input for existing image -->
                                    <template x-if="slide.existing_image">
                                        <input type="hidden" :name="'hero_slides[' + index + '][existing_image]'" :value="slide.existing_image">
                                    </template>
                                    
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                                        Ukuran maksimal 2MB. Format: JPG, PNG, GIF, WebP
                                    </p>
                                </div>
                                
                                <!-- Slide Button -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                    <div>
                                        <label :for="'slide_button_text_' + slide.id" 
                                               class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            Teks Tombol
                                        </label>
                                        <input type="text" :id="'slide_button_text_' + slide.id"
                                               x-model="slide.button_text"
                                               :name="'hero_slides[' + index + '][button_text]'"
                                               class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                                    </div>
                                    
                                    <div>
                                        <label :for="'slide_button_link_' + slide.id" 
                                               class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            Link Tombol
                                        </label>
                                        <input type="text" :id="'slide_button_link_' + slide.id"
                                               x-model="slide.button_link"
                                               :name="'hero_slides[' + index + '][button_link]'"
                                               placeholder="#products atau /products"
                                               class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                    <!-- Empty State -->
                    <template x-if="heroSlides.length === 0">
                        <div class="text-center py-8 border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl sm:rounded-2xl">
                            <svg class="w-12 h-12 text-slate-400 dark:text-slate-500 mx-auto mb-3">
                                <use href="#icon-photo"></use>
                            </svg>
                            <p class="text-slate-500 dark:text-slate-400 mb-4">Belum ada slide hero</p>
                            <button type="button" @click="addHeroSlide()"
                                    class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white font-medium">
                                <svg class="w-4 h-4 mr-2"><use href="#icon-plus"></use></svg>
                                Tambah Slide Pertama
                            </button>
                        </div>
                    </template>
                </div>
                
                <!-- Featured Products Section -->
                <div class="pt-6 border-t border-slate-200 dark:border-slate-700">
                    <h4 class="font-semibold text-slate-800 dark:text-white mb-4">Featured Products</h4>
                    <div class="space-y-4">
                        <div>
                            <label for="featured_title" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Judul
                            </label>
                            <input type="text" id="featured_title" name="featured_title" 
                                   value="{{ old('featured_title', $settings['featured_title'] ?? '') }}"
                                   class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                        </div>
                        
                        <div>
                            <label for="featured_description" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Deskripsi
                            </label>
                            <textarea id="featured_description" name="featured_description" rows="2"
                                      class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">{{ old('featured_description', $settings['featured_description'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
                
                <!-- CTA Section -->
                <div class="pt-6 border-t border-slate-200 dark:border-slate-700">
                    <h4 class="font-semibold text-slate-800 dark:text-white mb-4">Call to Action</h4>
                    <div class="space-y-4">
                        <div>
                            <label for="cta_title" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Judul CTA
                            </label>
                            <input type="text" id="cta_title" name="cta_title" 
                                   value="{{ old('cta_title', $settings['cta_title'] ?? '') }}"
                                   class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                        </div>
                        
                        <div>
                            <label for="cta_description" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Deskripsi CTA
                            </label>
                            <textarea id="cta_description" name="cta_description" rows="2"
                                      class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">{{ old('cta_description', $settings['cta_description'] ?? '') }}</textarea>
                        </div>
                        
                        <div>
                            <label for="cta_button_text" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Teks Tombol
                            </label>
                            <input type="text" id="cta_button_text" name="cta_button_text" 
                                   value="{{ old('cta_button_text', $settings['cta_button_text'] ?? '') }}"
                                   class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-slate-200 dark:border-slate-700">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 rounded-xl sm:rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-medium text-sm sm:text-base shadow-lg hover:shadow-xl transition-all duration-300">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2"><use href="#icon-check"></use></svg>
                    Simpan Pengaturan Landing Page
                </button>
            </div>
        </form>
    </x-admin.card>
</div>

@push('scripts')
<script>
function landingApp() {
    return {
        heroSlides: @json($heroSlides ?? []),
        
        init() {
            if (this.heroSlides.length === 0) {
                this.addHeroSlide();
            }
        },
        
        addHeroSlide() {
            const newId = this.heroSlides.length > 0 
                ? Math.max(...this.heroSlides.map(s => s.id)) + 1 
                : 1;
            
            this.heroSlides.push({
                id: newId,
                image: null,
                existing_image: null,
                new_image_preview: null,
                title: 'Tempat Beli Voucher Digital Terbaik',
                description: 'Beli voucher game, pulsa, e-money dengan harga terbaik. Proses cepat dan aman.',
                button_text: 'Mulai Sekarang',
                button_link: '#products',
                is_active: true,
                order: this.heroSlides.length
            });
        },
        
        removeHeroSlide(index) {
            if (this.heroSlides.length > 1 && confirm('Hapus slide ini?')) {
                this.heroSlides.splice(index, 1);
                this.heroSlides.forEach((slide, idx) => {
                    slide.order = idx;
                });
            }
        },
        
        handleSlideImageUpload(event, index) {
            const file = event.target.files[0];
            if (file) {
                if (!file.type.match('image.*')) {
                    alert('Hanya file gambar yang diizinkan');
                    event.target.value = '';
                    return;
                }
                
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB');
                    event.target.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.heroSlides[index].new_image_preview = e.target.result;
                    this.heroSlides[index].existing_image = null;
                };
                reader.readAsDataURL(file);
            }
        },
        
        removeNewImage(index) {
            this.heroSlides[index].new_image_preview = null;
            const fileInput = document.querySelector(`[name="hero_slides[${index}][image]"]`);
            if (fileInput) fileInput.value = '';
        }
    };
}
</script>
@endpush

@if(session('toast'))
    @include('components.admin.toast')
@endif
@endsection