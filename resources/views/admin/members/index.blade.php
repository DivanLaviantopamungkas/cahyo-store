@extends('admin.layouts.app')

@section('title', 'Manajemen Member')
@section('subtitle', 'Pantau dan kelola hak akses pelanggan anda')
@section('breadcrumb', 'Semua Member')

@section('content')
    <div class="space-y-8">
        
        @php
            $totalMembers = App\Models\User::count();
            $activeMembers = App\Models\User::where('is_active', true)->count();
            $inactiveMembers = App\Models\User::where('is_active', false)->count();
            $totalTrx = App\Models\Trancsaction::count();
        @endphp

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            @php
                $memberStats = [
                    ['label' => 'Total Member', 'value' => $totalMembers, 'color' => 'blue', 'icon' => 'icon-users'],
                    ['label' => 'Member Aktif', 'value' => $activeMembers, 'color' => 'emerald', 'icon' => 'icon-users'],
                    ['label' => 'Nonaktif', 'value' => $inactiveMembers, 'color' => 'rose', 'icon' => 'icon-users'],
                    ['label' => 'Total Order', 'value' => $totalTrx, 'color' => 'violet', 'icon' => 'icon-credit-card'],
                ];
            @endphp

            @foreach($memberStats as $stat)
            <div class="relative group">
                <div class="absolute inset-0 bg-{{ $stat['color'] }}-500/20 blur-xl opacity-0 group-hover:opacity-100 transition-opacity rounded-[2rem]"></div>
                <div class="relative bg-white dark:bg-slate-800 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-700 shadow-sm transition-all group-hover:-translate-y-1">
                    <div class="flex flex-col gap-4">
                        <div class="w-10 h-10 rounded-xl bg-{{ $stat['color'] }}-50 dark:bg-{{ $stat['color'] }}-500/10 flex items-center justify-center text-{{ $stat['color'] }}-500">
                            <svg class="w-6 h-6"><use href="#{{ $stat['icon'] }}"></use></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 mb-1">{{ $stat['label'] }}</p>
                            <p class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($stat['value']) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="bg-white/40 dark:bg-slate-800/40 backdrop-blur-xl p-3 lg:p-4 rounded-[2.5rem] border border-white/20 dark:border-slate-700/50 shadow-2xl shadow-slate-200/50 dark:shadow-none">
            <form action="{{ route('admin.members.index') }}" method="GET" class="flex flex-col lg:flex-row gap-3">
                <div class="relative flex-1 group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                        <svg class="h-5 w-5"><use href="#icon-magnifying-glass"></use></svg>
                    </div>
                    <input type="search" name="q" value="{{ $search }}" placeholder="Cari nama, email, atau WhatsApp member..."
                        class="block w-full pl-12 pr-4 py-4 rounded-[1.8rem] border-none bg-white dark:bg-slate-900 text-sm font-medium focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-sm">
                </div>

                <div class="flex gap-2 w-full lg:w-auto">
                    <button type="submit" class="flex-1 lg:flex-none px-8 py-4 rounded-[1.8rem] bg-slate-900 dark:bg-emerald-500 text-white font-black uppercase tracking-widest text-[10px] hover:shadow-xl hover:shadow-emerald-500/20 transition-all active:scale-95">
                        Cari Member
                    </button>
                    
                    @if($search)
                        <a href="{{ route('admin.members.index') }}" 
                           class="p-4 rounded-[1.8rem] bg-rose-100 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 hover:bg-rose-500 hover:text-white transition-all shadow-lg border border-rose-200 dark:border-rose-500/30 flex items-center justify-center"
                           title="Clear Search">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-[3rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden hidden lg:block">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-50 dark:border-slate-700">
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Identitas Member</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Kontak Informasi</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-center">Status</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Join Date</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                    @forelse($members as $member)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-900/80 transition-all group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center text-white font-black text-lg">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 dark:text-white text-sm">#{{ $member->id }} • {{ $member->name }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">MEMBER</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $member->email }}</p>
                            <p class="text-[10px] font-medium text-slate-400">{{ $member->whatsapp ?? '-' }}</p>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest {{ $member->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-500' : 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-500' }}">
                                {{ $member->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <p class="text-sm font-black text-slate-800 dark:text-white">{{ $member->created_at->format('d M Y') }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $member->created_at->diffForHumans() }}</p>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <button type="button" 
                                onclick="showToggleModal('{{ $member->id }}', '{{ $member->name }}', {{ $member->is_active ? 'true' : 'false' }})"
                                class="inline-flex items-center gap-2 px-4 py-2 {{ $member->is_active ? 'bg-rose-50 dark:bg-rose-500/10 text-rose-500' : 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500' }} rounded-xl text-xs font-black uppercase tracking-widest transition-all">
                                {{ $member->is_active ? 'Suspend' : 'Activate' }}
                            </button>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="5" class="py-24 text-center text-slate-300 font-black uppercase tracking-[0.3em]">No Members Found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="lg:hidden space-y-4 pb-20">
            @foreach($members as $member)
            <div class="bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-[1.5rem] bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center text-white font-black text-xl shadow-lg">
                        {{ strtoupper(substr($member->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex justify-between items-start">
                            <h3 class="font-black text-slate-800 dark:text-white text-base truncate leading-none">{{ $member->name }}</h3>
                            <span class="px-2.5 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest {{ $member->is_active ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }}">
                                {{ $member->is_active ? 'Active' : 'Off' }}
                            </span>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">ID: #{{ $member->id }}</p>
                    </div>
                </div>

                <div class="bg-slate-50/50 dark:bg-slate-900/50 p-4 rounded-[1.5rem] border border-slate-100 dark:border-slate-700 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Email</span>
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-200 truncate ml-4">{{ $member->email }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">WhatsApp</span>
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-200">{{ $member->phone ?? '-' }}</span>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="button" 
                        onclick="showToggleModal('{{ $member->id }}', '{{ $member->name }}', {{ $member->is_active ? 'true' : 'false' }})"
                        class="flex-1 py-4 rounded-2xl {{ $member->is_active ? 'bg-rose-500' : 'bg-emerald-500' }} text-white font-black text-[10px] uppercase tracking-[0.2em] shadow-lg shadow-{{ $member->is_active ? 'rose' : 'emerald' }}-500/20 active:scale-95 transition-all">
                        {{ $member->is_active ? 'Suspend Member' : 'Activate Member' }}
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-[2rem] p-6 border border-slate-100 dark:border-slate-700 shadow-sm">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <p class="text-[11px] font-black uppercase text-slate-400 tracking-[0.2em]">
                    Showing {{ $members->firstItem() }}-{{ $members->lastItem() }} of {{ $members->total() }}
                </p>
                <div class="flex items-center">
                    <x-admin.pagination :paginator="$members" />
                </div>
            </div>
        </div>
    </div>

    <div id="toggleModal" class="hidden fixed inset-0 z-[60] overflow-y-auto" x-data="{}" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeToggleModal()"></div>
            <div class="relative bg-white dark:bg-slate-800 rounded-[3rem] w-full max-w-sm p-8 shadow-2xl border border-slate-100 dark:border-slate-700">
                <div id="toggleModalIcon" class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 transition-all duration-500">
                    </div>
                <h3 id="toggleModalTitle" class="text-xl font-black text-center text-slate-800 dark:text-white mb-2 uppercase tracking-tight"></h3>
                <p id="toggleModalMessage" class="text-center text-slate-500 text-sm mb-8 font-medium leading-relaxed"></p>
                
                <div class="grid grid-cols-2 gap-3">
                    <button onclick="closeToggleModal()" class="py-4 rounded-2xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-white font-bold text-xs uppercase tracking-widest active:scale-95 transition-all">Batal</button>
                    <form id="toggleForm" method="POST" class="inline">
                        @csrf
                        <button id="toggleSubmitButton" type="submit" class="w-full py-4 rounded-2xl text-white font-black text-xs uppercase tracking-widest shadow-lg active:scale-95 transition-all">
                            Konfirmasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function showToggleModal(memberId, memberName, isActive) {
                const modal = document.getElementById('toggleModal');
                const iconDiv = document.getElementById('toggleModalIcon');
                const title = document.getElementById('toggleModalTitle');
                const message = document.getElementById('toggleModalMessage');
                const form = document.getElementById('toggleForm');
                const submitBtn = document.getElementById('toggleSubmitButton');

                form.action = "{{ route('admin.members.toggle', ':id') }}".replace(':id', memberId);

                if (isActive) {
                    iconDiv.className = "w-20 h-20 bg-rose-50 dark:bg-rose-900/30 rounded-full flex items-center justify-center mx-auto mb-6";
                    iconDiv.innerHTML = '<svg class="w-10 h-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/></svg>';
                    title.textContent = "Suspend Member";
                    message.textContent = `Menonaktifkan "${memberName}" akan mencabut akses login member tersebut dari sistem.`;
                    submitBtn.className = "w-full py-4 rounded-2xl bg-rose-500 text-white font-black text-xs uppercase tracking-widest shadow-lg shadow-rose-200 dark:shadow-none transition-all";
                    submitBtn.textContent = "Nonaktifkan";
                } else {
                    iconDiv.className = "w-20 h-20 bg-emerald-50 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mx-auto mb-6";
                    iconDiv.innerHTML = '<svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                    title.textContent = "Aktifkan Member";
                    message.textContent = `Aktifkan kembali "${memberName}" untuk memberikan izin akses masuk ke dalam sistem.`;
                    submitBtn.className =  "w-full py-4 rounded-2xl bg-emerald-500 text-white font-black text-xs uppercase tracking-widest shadow-lg shadow-emerald-200 dark:shadow-none transition-all";
                    submitBtn.textContent = "Aktifkan";
                }

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeToggleModal() {
                document.getElementById('toggleModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        </script>
    @endpush
@endsection