@props(['hover' => true, 'striped' => false])

<div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
        <thead class="{{ $striped ? 'bg-slate-50 dark:bg-slate-700/50' : '' }}">
            <tr>
                {{ $header }}
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 dark:divide-slate-700 {{ $striped ? 'bg-white dark:bg-slate-800' : '' }}">
            {{ $body }}
        </tbody>
        @if(isset($footer))
        <tfoot class="bg-slate-50 dark:bg-slate-700/50">
            <tr>
                {{ $footer }}
            </tr>
        </tfoot>
        @endif
    </table>
    
    @if(isset($empty))
    <div class="text-center py-12">
        {{ $empty }}
    </div>
    @endif
</div>