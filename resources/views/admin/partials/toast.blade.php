@if(session('toast'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-full" class="fixed top-4 right-4 z-50 max-w-sm w-full">
    @php
        $toast = session('toast');
        $type = $toast['type'] ?? 'info';
        $colors = [
            'success' => 'bg-emerald-50 dark:bg-emerald-900/30 border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200',
            'error' => 'bg-rose-50 dark:bg-rose-900/30 border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200',
            'warning' => 'bg-amber-50 dark:bg-amber-900/30 border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-200',
            'info' => 'bg-sky-50 dark:bg-sky-900/30 border-sky-200 dark:border-sky-800 text-sky-800 dark:text-sky-200',
        ];
        $icons = [
            'success' => 'check-circle',
            'error' => 'exclamation-circle',
            'warning' => 'exclamation-circle',
            'info' => 'information-circle',
        ];
    @endphp
    
    <div class="{{ $colors[$type] }} rounded-2xl border shadow-lg p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5">
                    <use href="#icon-{{ $icons[$type] }}"></use>
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium">{{ $toast['title'] ?? ucfirst($type) }}</p>
                @if(isset($toast['message']))
                <p class="mt-1 text-sm opacity-90">{{ $toast['message'] }}</p>
                @endif
            </div>
            <button @click="show = false" class="ml-4 flex-shrink-0">
                <svg class="w-4 h-4">
                    <use href="#icon-x-mark"></use>
                </svg>
            </button>
        </div>
    </div>
</div>
@endif