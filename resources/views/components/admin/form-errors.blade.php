@if($errors->any())
<div x-data="{ show: true }" x-show="show" class="mb-6 rounded-2xl bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 p-4">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="w-5 h-5 text-rose-500 dark:text-rose-400"><use href="#icon-exclamation-circle"></use></svg>
        </div>
        <div class="ml-3 flex-1">
            <h3 class="text-sm font-medium text-rose-800 dark:text-rose-200">
                Terdapat {{ $errors->count() }} kesalahan yang harus diperbaiki:
            </h3>
            <div class="mt-2 text-sm text-rose-700 dark:text-rose-300">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button @click="show = false" class="ml-4 flex-shrink-0">
            <svg class="w-4 h-4 text-rose-500 dark:text-rose-400">
                <use href="#icon-x-mark"></use>
            </svg>
        </button>
    </div>
</div>
@endif