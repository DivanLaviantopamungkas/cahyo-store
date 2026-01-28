@extends('admin.layouts.app')

@section('title', 'Voucher Codes')
@section('subtitle', 'Kelola stok kode voucher digital anda')

@section('actions')
    <div class="hidden lg:flex items-center space-x-3">
        <a href="{{ route('admin.voucher-codes.create') }}"
            class="inline-flex items-center px-4 py-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold transition-all shadow-lg shadow-emerald-200 dark:shadow-none">
            <svg class="w-5 h-5 mr-2"><use href="#icon-plus"></use></svg>
            Tambah Voucher
        </a>
        <a href="{{ route('admin.voucher-codes.import') }}"
            class="inline-flex items-center px-4 py-2 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold hover:bg-slate-50 transition-all">
            <svg class="w-5 h-5 mr-2"><use href="#icon-arrow-down-tray"></use></svg>
            Import
        </a>
    </div>

    <div class="lg:hidden fixed bottom-24 right-6 z-50 flex flex-col space-y-3" x-data="{ open: false }">
        <div x-show="open" x-transition class="flex flex-col space-y-3 items-end">
             <a href="{{ route('admin.voucher-codes.import') }}" class="flex items-center px-4 py-3 rounded-2xl bg-slate-800 text-white shadow-xl">
                <span class="mr-2 text-xs font-bold uppercase tracking-wider">Import TXT</span>
                <svg class="w-5 h-5"><use href="#icon-arrow-down-tray"></use></svg>
            </a>
            <a href="{{ route('admin.voucher-codes.create') }}" class="flex items-center px-4 py-3 rounded-2xl bg-emerald-600 text-white shadow-xl">
                <span class="mr-2 text-xs font-bold uppercase tracking-wider">Baru</span>
                <svg class="w-5 h-5"><use href="#icon-plus"></use></svg>
            </a>
        </div>
        <button @click="open = !open" class="w-14 h-14 rounded-full bg-slate-900 dark:bg-emerald-500 text-white flex items-center justify-center shadow-2xl transition-transform" :class="open ? 'rotate-45' : ''">
            <svg class="w-8 h-8"><use href="#icon-plus"></use></svg>
        </button>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $statConfig = [
                    'available' => ['color' => 'emerald', 'label' => 'Tersedia', 'icon' => 'icon-ticket'],
                    'reserved'  => ['color' => 'amber', 'label' => 'Dipesan', 'icon' => 'icon-clock'],
                    'sold'      => ['color' => 'violet', 'label' => 'Terjual', 'icon' => 'icon-shopping-bag'],
                    'expired'   => ['color' => 'rose', 'label' => 'Expired', 'icon' => 'icon-x-circle'],
                ];
            @endphp
            @foreach($statConfig as $key => $cfg)
            <div class="bg-white dark:bg-slate-800 p-5 rounded-[2rem] border border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-2 -top-2 w-16 h-16 bg-{{ $cfg['color'] }}-500/5 rounded-full transition-transform group-hover:scale-150"></div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1 relative z-10">{{ $cfg['label'] }}</p>
                <p class="text-3xl font-black text-{{ $cfg['color'] }}-600 dark:text-{{ $cfg['color'] }}-400 relative z-10">
                    {{ number_format($stats[$key] ?? 0) }}
                </p>
            </div>
            @endforeach
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm p-6">
            <form action="{{ route('admin.voucher-codes.index') }}" method="GET" id="filterForm" class="space-y-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400"><use href="#icon-magnifying-glass"></use></svg>
                    </div>
                    <input type="search" name="q" value="{{ $search }}" placeholder="Cari kode voucher atau secret..."
                        class="block w-full pl-12 pr-4 py-4 rounded-3xl border-none bg-slate-100 dark:bg-slate-900 text-slate-900 dark:text-white placeholder-slate-500 focus:ring-2 focus:ring-emerald-500 transition-all">
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
                    <select name="product_id" onchange="this.form.submit()" class="text-xs font-bold rounded-2xl border-none bg-slate-50 dark:bg-slate-900 py-3.5 focus:ring-2 focus:ring-emerald-500 cursor-pointer">
                        <option value="">Semua Produk</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" {{ $productId == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                        @endforeach
                    </select>

                    <select name="status" onchange="this.form.submit()" class="text-xs font-bold rounded-2xl border-none bg-slate-50 dark:bg-slate-900 py-3.5 focus:ring-2 focus:ring-emerald-500 cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="available" {{ $status == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="reserved" {{ $status == 'reserved' ? 'selected' : '' }}>Reserved</option>
                        <option value="sold" {{ $status == 'sold' ? 'selected' : '' }}>Sold</option>
                        <option value="expired" {{ $status == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>

                    <button type="submit" class="col-span-2 lg:col-span-1 py-3.5 rounded-2xl bg-slate-900 dark:bg-emerald-500 text-white font-black uppercase tracking-widest text-[10px] hover:opacity-90 active:scale-95 transition-all">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
            
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-50 dark:border-slate-700">
                            <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Produk & Nominal</th>
                            <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Data Kode</th>
                            <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Status</th>
                            <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                        @forelse($voucherCodes as $voucher)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-black text-slate-800 dark:text-white text-sm leading-tight">{{ $voucher->product->name ?? '-' }}</p>
                                <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-tighter">{{ $voucher->nominal->name ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <code class="text-xs font-mono bg-slate-100 dark:bg-slate-900 px-2 py-1 rounded-lg text-slate-700 dark:text-slate-200 font-bold border border-slate-200/50 dark:border-slate-700">
                                        {{ $voucher->code }}
                                    </code>
                                </div>
                                @if($voucher->secret)
                                    <p class="text-[9px] text-slate-400 mt-1 font-medium">Secret: {{ $voucher->secret }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusMap = [
                                        'available' => 'bg-emerald-100 text-emerald-600',
                                        'reserved'  => 'bg-amber-100 text-amber-600',
                                        'sold'      => 'bg-violet-100 text-violet-600',
                                        'expired'   => 'bg-rose-100 text-rose-600',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $statusMap[$voucher->status] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ $voucher->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.voucher-codes.edit', $voucher) }}" class="p-2.5 bg-slate-50 dark:bg-slate-900 rounded-2xl text-slate-400 hover:text-blue-500 transition-all active:scale-90 shadow-sm">
                                        <svg class="w-4 h-4"><use href="#icon-pencil"></use></svg>
                                    </a>
                                    <button onclick="confirmDelete('{{ $voucher->id }}')" class="p-2.5 bg-slate-50 dark:bg-slate-900 rounded-2xl text-slate-400 hover:text-rose-500 transition-all active:scale-90 shadow-sm">
                                        <svg class="w-4 h-4"><use href="#icon-trash"></use></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-20 text-center text-slate-400 font-bold uppercase tracking-widest text-xs">Belum ada data voucher</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="lg:hidden divide-y divide-slate-50 dark:divide-slate-700">
                @forelse($voucherCodes as $voucher)
                <div class="p-5 flex flex-col space-y-4">
                    <div class="flex justify-between items-start">
                        <div class="min-w-0 flex-1">
                            <p class="text-[9px] font-black text-emerald-600 uppercase tracking-tighter mb-0.5">{{ $voucher->nominal->name ?? 'Voucher' }}</p>
                            <h3 class="font-black text-slate-800 dark:text-white text-sm truncate">{{ $voucher->product->name ?? '-' }}</h3>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest {{ $statusMap[$voucher->status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $voucher->status }}
                        </span>
                    </div>
                    
                    <div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-[1.5rem] border border-slate-100 dark:border-slate-700 shadow-inner">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Voucher Code</span>
                            <button onclick="navigator.clipboard.writeText('{{ $voucher->code }}')" class="text-emerald-500 text-[10px] font-black uppercase">Copy</button>
                        </div>
                        <code class="text-sm font-black text-slate-700 dark:text-slate-200 block break-all">{{ $voucher->code }}</code>
                        
                        @if($voucher->secret)
                        <div class="mt-3 pt-3 border-t border-slate-200/50 dark:border-slate-700 flex justify-between items-center">
                            <span class="text-[9px] font-bold text-slate-400 uppercase">Secret Key</span>
                            <span class="text-[10px] font-mono text-slate-500 truncate ml-4">{{ $voucher->secret }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-[9px] font-bold text-slate-400 uppercase">Dibuat: {{ $voucher->created_at->format('d M y') }}</span>
                        <div class="flex space-x-2">
                            @if ($voucher->status == 'available')
                                <form action="{{ route('admin.voucher-codes.reserve', $voucher) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-2xl text-amber-600 active:scale-90 transition-transform">
                                        <svg class="w-5 h-5"><use href="#icon-clock"></use></svg>
                                    </button>
                                </form>
                            @elseif($voucher->status == 'reserved')
                                <form action="{{ route('admin.voucher-codes.unreserve', $voucher) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl text-emerald-600 active:scale-90 transition-transform">
                                        <svg class="w-5 h-5"><use href="#icon-x-circle"></use></svg>
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('admin.voucher-codes.edit', $voucher) }}" class="p-3 bg-slate-100 dark:bg-slate-900 rounded-2xl text-slate-400 active:scale-90 transition-transform">
                                <svg class="w-5 h-5"><use href="#icon-pencil"></use></svg>
                            </a>
                            <button onclick="confirmDelete('{{ $voucher->id }}')" class="p-3 bg-rose-50 dark:bg-rose-900/20 rounded-2xl text-rose-500 active:scale-90 transition-transform">
                                <svg class="w-5 h-5"><use href="#icon-trash"></use></svg>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="py-20 text-center text-slate-400 font-bold uppercase tracking-widest text-xs">Data tidak ditemukan</div>
                @endforelse
            </div>

            <div class="p-6 border-t border-slate-50 dark:border-slate-700 bg-slate-50/30 dark:bg-slate-900/30">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">
                        Total {{ number_format($voucherCodes->total()) }} Records
                    </p>
                    <div class="flex items-center space-x-2">
                        @if (View::exists('components.admin.pagination'))
                            {{ $voucherCodes->links('components.admin.pagination') }}
                        @else
                            {{ $voucherCodes->links('pagination::tailwind') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="hidden fixed inset-0 z-[60] overflow-y-auto" x-data="{}" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
            <div class="relative bg-white dark:bg-slate-800 rounded-[3rem] w-full max-w-sm p-8 shadow-2xl border border-slate-100 dark:border-slate-700">
                <div class="w-20 h-20 bg-rose-50 dark:bg-rose-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-rose-500"><use href="#icon-trash"></use></svg>
                </div>
                <h3 class="text-xl font-black text-center text-slate-800 dark:text-white mb-2">Hapus Voucher?</h3>
                <p class="text-center text-slate-500 text-sm mb-8 font-medium">Stok kode ini akan dihapus permanen dari sistem anda.</p>
                <div class="grid grid-cols-2 gap-3">
                    <button onclick="closeDeleteModal()" class="py-4 rounded-2xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-white font-bold text-xs uppercase tracking-widest transition-all active:scale-95">Batal</button>
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-4 rounded-2xl bg-rose-500 text-white font-black text-xs uppercase tracking-widest shadow-lg shadow-rose-200 dark:shadow-none active:scale-95 transition-all">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .scrollbar-hide::-webkit-scrollbar { 
            display: none; 
        }

        .scrollbar-hide { 
            -ms-overflow-style: none; 
            scrollbar-width: none; 
        }
    </style>
@endsection

@push('scripts')
    <script>
        function confirmDelete(id) {
            const form = document.getElementById('deleteForm');
            form.action = "{{ route('admin.voucher-codes.destroy', ':id') }}".replace(':id', id);
            document.getElementById('deleteModal').classList.remove('hidden');
        }
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
@endpush