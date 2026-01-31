@extends('admin.layouts.app')

@section('title', 'Landing Page')
@section('breadcrumb', 'Kelola Landing Page')

@section('actions')
    <button type="submit" form="landing-form" 
            class="group inline-flex items-center px-6 py-3 rounded-[1.8rem] bg-emerald-500 hover:bg-emerald-600 text-white font-black uppercase tracking-widest text-[10px] shadow-lg shadow-emerald-500/20 transition-all active:scale-95">
        <svg class="w-4 h-4 mr-2 transition-transform group-hover:scale-125"><use href="#icon-check"></use></svg>
        <span>Simpan Perubahan</span>
    </button>
@endsection

@section('content')
    <div x-data="landingApp()" x-init="init()" class="max-w-5xl mx-auto pb-24 px-2 lg:px-0">
        <form action="{{ route('admin.settings.update.landing') }}" method="POST" id="landing-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-12">
                <section>
                    <div class="flex items-center justify-between gap-4 mb-8 border-l-4 border-emerald-500 pl-4">
                        <div>
                            <h4 class="text-base lg:text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight">Hero Section Slider</h4>
                            <p class="text-[10px] lg:text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5">Banner visual utama di halaman depan</p>
                        </div>
                        <button type="button" @click="addHeroSlide()"
                                class="inline-flex items-center justify-center p-3 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-emerald-500 shadow-sm hover:shadow-md transition-all active:scale-90">
                            <svg class="w-6 h-6"><use href="#icon-plus"></use></svg>
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-8">
                        <template x-for="(slide, index) in heroSlides" :key="slide.id">
                            <div class="group relative bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm transition-all hover:shadow-xl hover:shadow-slate-200/50 dark:hover:shadow-none overflow-hidden">
                                <div class="p-6 border-b border-slate-50 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-black text-xs" x-text="index + 1"></div>
                                        <span class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest">Konfigurasi Slide</span>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        {{-- Custom Toggle Switch --}}
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" x-model="slide.is_active" class="sr-only peer">
                                            <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                            <span class="ml-3 text-[10px] font-black text-slate-400 uppercase tracking-widest" x-text="slide.is_active ? 'Aktif' : 'Off'"></span>
                                        </label>
                                        
                                        <button type="button" @click="removeHeroSlide(index)"
                                                :disabled="heroSlides.length <= 1"
                                                class="p-2 text-slate-400 hover:text-rose-500 transition-colors disabled:opacity-30">
                                            <svg class="w-5 h-5"><use href="#icon-trash"></use></svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <input type="hidden" :name="'hero_slides[' + index + '][id]'" :value="slide.id">
                                <input type="hidden" :name="'hero_slides[' + index + '][order]'" :value="index">
                                <input type="hidden" :name="'hero_slides[' + index + '][is_active]'" :value="slide.is_active ? 1 : 0">
                                <input type="hidden" :name="'hero_slides[' + index + '][existing_image]'" :value="slide.existing_image">

                                <div class="p-6 lg:p-8 grid grid-cols-1 lg:grid-cols-12 gap-8">
                                    <div class="lg:col-span-4">
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Visual Slide</label>
                                        <div class="relative aspect-video lg:aspect-square rounded-[2rem] bg-slate-50 dark:bg-slate-900 border-2 border-dashed border-slate-200 dark:border-slate-700 flex items-center justify-center overflow-hidden group/img">
                                            {{-- Image Display --}}
                                            <img :src="slide.new_image_preview ? slide.new_image_preview : getStorageUrl(slide.existing_image)" 
                                                x-show="slide.new_image_preview || slide.existing_image"
                                                class="w-full h-full object-cover">
                                            
                                            <div x-show="!slide.new_image_preview && !slide.existing_image" class="text-center p-4">
                                                <svg class="w-10 h-10 text-slate-300 mx-auto mb-2"><use href="#icon-photo"></use></svg>
                                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">No Image</p>
                                            </div>

                                            {{-- Overlay Actions --}}
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                                <input type="file" :id="'slide_image_' + slide.id" @change="handleSlideImageUpload($event, index)" :name="'hero_slides[' + index + '][image]'" accept="image/*" class="hidden">
                                                <label :for="'slide_image_' + slide.id" class="p-3 bg-white text-slate-800 rounded-xl cursor-pointer hover:scale-110 transition-transform">
                                                    <svg class="w-5 h-5"><use href="#icon-arrow-up-tray"></use></svg>
                                                </label>
                                                <button x-show="slide.new_image_preview" type="button" @click="removeNewImage(index)" class="p-3 bg-rose-500 text-white rounded-xl hover:scale-110 transition-transform">
                                                    <svg class="w-5 h-5"><use href="#icon-x-mark"></use></svg>
                                                </button>
                                            </div>
                                        </div>
                                        <p class="text-[9px] text-slate-400 mt-4 text-center italic font-medium">Recom: 1200x600px (Max 2MB)</p>
                                    </div>

                                    <div class="lg:col-span-8 space-y-6">
                                        <div class="grid grid-cols-1 gap-6">
                                            <div class="group/field">
                                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1 group-focus-within/field:text-emerald-500 transition-colors">Judul Slide</label>
                                                <input type="text" x-model="slide.title" :name="'hero_slides[' + index + '][title]'" 
                                                    class="w-full px-5 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-inner">
                                            </div>

                                            <div class="group/field">
                                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1 group-focus-within/field:text-emerald-500 transition-colors">Deskripsi Slide</label>
                                                <textarea x-model="slide.description" :name="'hero_slides[' + index + '][description]'" rows="2"
                                                        class="w-full px-5 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-inner"></textarea>
                                            </div>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                                <div class="group/field">
                                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1 group-focus-within/field:text-emerald-500 transition-colors">Teks Tombol</label>
                                                    <input type="text" x-model="slide.button_text" :name="'hero_slides[' + index + '][button_text]'" 
                                                        class="w-full px-5 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-inner">
                                                </div>
                                                <div class="group/field">
                                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1 group-focus-within/field:text-emerald-500 transition-colors">Link Tombol</label>
                                                    <input type="text" x-model="slide.button_link" :name="'hero_slides[' + index + '][button_link]'" placeholder="#products"
                                                        class="w-full px-5 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-inner">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </section>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm p-6 lg:p-8">
                        <div class="flex items-center gap-3 mb-8 border-l-4 border-violet-500 pl-4">
                            <h4 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tight">Featured Products</h4>
                        </div>
                        <div class="space-y-6">
                            <div class="group/field">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1 group-focus-within/field:text-violet-500 transition-colors">Judul Seksi</label>
                                <input type="text" name="featured_title" value="{{ old('featured_title', $settings['featured_title'] ?? '') }}"
                                    class="w-full px-5 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-violet-500/50 transition-all shadow-inner">
                            </div>
                            <div class="group/field">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1 group-focus-within/field:text-violet-500 transition-colors">Deskripsi</label>
                                <textarea name="featured_description" rows="2"
                                        class="w-full px-5 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-violet-500/50 transition-all shadow-inner">{{ old('featured_description', $settings['featured_description'] ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm p-6 lg:p-8">
                        <div class="flex items-center gap-3 mb-8 border-l-4 border-amber-500 pl-4">
                            <h4 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tight">Call to Action (CTA)</h4>
                        </div>
                        <div class="space-y-6">
                            <div class="group/field">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1 group-focus-within/field:text-amber-500 transition-colors">Headline CTA</label>
                                <input type="text" name="cta_title" value="{{ old('cta_title', $settings['cta_title'] ?? '') }}"
                                    class="w-full px-5 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-amber-500/50 transition-all shadow-inner">
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="group/field">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1 group-focus-within/field:text-amber-500 transition-colors">Teks Tombol</label>
                                    <input type="text" name="cta_button_text" value="{{ old('cta_button_text', $settings['cta_button_text'] ?? '') }}"
                                        class="w-full px-5 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-amber-500/50 transition-all shadow-inner">
                                </div>
                                <div class="group/field">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1 group-focus-within/field:text-amber-500 transition-colors">Deskripsi Singkat</label>
                                    <textarea name="cta_description" rows="1"
                                            class="w-full px-5 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-amber-500/50 transition-all shadow-inner">{{ old('cta_description', $settings['cta_description'] ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit Mobile (Fixed Bottom Style) --}}
                <div class="lg:hidden mt-12">
                    <button type="submit" 
                            class="w-full py-5 rounded-[2rem] bg-emerald-500 text-white font-black uppercase tracking-[0.2em] text-[11px] shadow-xl shadow-emerald-500/20 active:scale-95 transition-all">
                        Update Landing Page
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function landingApp() {
                return {
                    heroSlides: @json($heroSlides ?? []),
                    
                    init() {
                        if (this.heroSlides.length === 0) {
                            this.addHeroSlide();
                        } else {
                            this.heroSlides.forEach(slide => {
                                slide.existing_image = slide.image; 
                                slide.new_image_preview = null;
                            });
                        }
                    },

                    getStorageUrl(path) {
                        if (!path) return '';
                        return path.startsWith('http') || path.startsWith('/storage') ? path : '/storage/' + path;
                    },
                    
                    addHeroSlide() {
                        const newId = Date.now();
                        this.heroSlides.push({
                            id: newId,
                            image: null,
                            existing_image: null,
                            new_image_preview: null,
                            title: '',
                            description: '',
                            button_text: 'Jelajahi',
                            button_link: '#',
                            is_active: true,
                            order: this.heroSlides.length
                        });
                    },
                    
                    removeHeroSlide(index) {
                        if (this.heroSlides.length > 1) {
                            this.heroSlides.splice(index, 1);
                            this.heroSlides.forEach((slide, idx) => slide.order = idx);
                        }
                    },
                    
                    handleSlideImageUpload(event, index) {
                        const file = event.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = (e) => this.heroSlides[index].new_image_preview = e.target.result;
                            reader.readAsDataURL(file);
                        }
                    },
                    
                    removeNewImage(index) {
                        this.heroSlides[index].new_image_preview = null;
                        const fileInput = document.getElementById('slide_image_' + this.heroSlides[index].id);
                        if (fileInput) fileInput.value = '';
                    }
                };
            }
        </script>
    @endpush
@endsection