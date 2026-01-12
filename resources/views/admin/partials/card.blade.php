@props(['padding' => 'p-6', 'hover' => false])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm ' . ($hover ? 'hover:shadow-md transition-shadow duration-300' : '')]) }}>
    @if(isset($header))
    <div class="border-b border-slate-200 dark:border-slate-700 {{ $padding }}">
        {{ $header }}
    </div>
    @endif
    
    <div class="{{ $padding }}">
        {{ $slot }}
    </div>
    
    @if(isset($footer))
    <div class="border-t border-slate-200 dark:border-slate-700 {{ $padding }}">
        {{ $footer }}
    </div>
    @endif
</div>