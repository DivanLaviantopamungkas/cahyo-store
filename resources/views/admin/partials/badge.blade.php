@props(['color' => 'gray', 'size' => 'md'])

@php
    $colors = [
        'gray' => 'bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-300',
        'red' => 'bg-rose-100 dark:bg-rose-900/30 text-rose-800 dark:text-rose-300',
        'green' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300',
        'blue' => 'bg-sky-100 dark:bg-sky-900/30 text-sky-800 dark:text-sky-300',
        'yellow' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300',
        'purple' => 'bg-violet-100 dark:bg-violet-900/30 text-violet-800 dark:text-violet-300',
        'pink' => 'bg-pink-100 dark:bg-pink-900/30 text-pink-800 dark:text-pink-300',
    ];
    
    $sizes = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-3 py-1 text-sm',
        'lg' => 'px-4 py-1.5 text-base',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full font-medium ' . $colors[$color] . ' ' . $sizes[$size]]) }}>
    {{ $slot }}
</span>