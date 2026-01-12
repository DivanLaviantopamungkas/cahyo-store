@extends('admin.layouts.app')

@section('title', 'Member')
@section('breadcrumb', 'Semua Member')

@section('content')
    <div class="space-y-6">
        <!-- Search -->
        <x-admin.card>
            <form method="GET" action="{{ route('admin.members.index') }}">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="relative flex-1 max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400">
                                <use href="#icon-magnifying-glass"></use>
                            </svg>
                        </div>
                        <input type="search" name="q" value="{{ old('q', $search) }}"
                            placeholder="Cari berdasarkan nama, email, atau WhatsApp..."
                            class="block w-full pl-10 pr-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                    </div>

                    <div class="flex items-center space-x-3">
                        <button type="submit"
                            class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl font-medium transition-colors">
                            Cari
                        </button>
                        @if ($search)
                            <a href="{{ route('admin.members.index') }}"
                                class="px-4 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 rounded-2xl font-medium transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </x-admin.card>

        <!-- Stats -->
        @php
            $totalMembers = App\Models\User::count();
            $activeMembers = App\Models\User::where('is_active', true)->count();
            $inactiveMembers = App\Models\User::where('is_active', false)->count();
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <x-admin.card
                class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-900/10 border-emerald-200 dark:border-emerald-800">
                <div class="text-center">
                    <p class="text-3xl font-bold text-emerald-800 dark:text-emerald-200">{{ $totalMembers }}</p>
                    <p class="text-sm text-emerald-700 dark:text-emerald-300 font-medium mt-1">Total Member</p>
                </div>
            </x-admin.card>

            <x-admin.card
                class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-900/10 border-green-200 dark:border-green-800">
                <div class="text-center">
                    <p class="text-3xl font-bold text-green-800 dark:text-green-200">{{ $activeMembers }}</p>
                    <p class="text-sm text-green-700 dark:text-green-300 font-medium mt-1">Aktif</p>
                </div>
            </x-admin.card>

            <x-admin.card
                class="bg-gradient-to-br from-rose-50 to-rose-100 dark:from-rose-900/20 dark:to-rose-900/10 border-rose-200 dark:border-rose-800">
                <div class="text-center">
                    <p class="text-3xl font-bold text-rose-800 dark:text-rose-200">{{ $inactiveMembers }}</p>
                    <p class="text-sm text-rose-700 dark:text-rose-300 font-medium mt-1">Nonaktif</p>
                </div>
            </x-admin.card>

            <x-admin.card
                class="bg-gradient-to-br from-violet-50 to-violet-100 dark:from-violet-900/20 dark:to-violet-900/10 border-violet-200 dark:border-violet-800">
                <div class="text-center">
                    <p class="text-3xl font-bold text-violet-800 dark:text-violet-200">0</p>
                    <p class="text-sm text-violet-700 dark:text-violet-300 font-medium mt-1">Total Transaksi</p>
                </div>
            </x-admin.card>
        </div>

        <!-- Members Table -->
        <x-admin.table>
            <x-slot name="header">
                <th
                    class="px-6 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                    Member</th>
                <th
                    class="px-6 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                    Kontak</th>
                <th
                    class="px-6 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                    Status</th>
                <th
                    class="px-6 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                    Bergabung</th>
                <th
                    class="px-6 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                    Aksi</th>
            </x-slot>

            <x-slot name="body">
                @forelse($members as $member)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-10 w-10 rounded-xl bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center">
                                    <span class="text-white font-bold">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $member->name }}
                                    </div>
                                    <div class="text-sm text-slate-500 dark:text-slate-400">ID: {{ $member->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-slate-900 dark:text-white">{{ $member->email }}</div>
                            <div class="text-sm text-slate-500 dark:text-slate-400">
                                {{ $member->whatsapp ?? 'Belum diisi' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-admin.badge :color="$member->is_active ? 'green' : 'red'" size="sm">
                                {{ $member->is_active ? 'Aktif' : 'Nonaktif' }}
                            </x-admin.badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">
                            {{ $member->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button type="button"
                                onclick="showToggleModal('{{ $member->id }}', '{{ $member->name }}', {{ $member->is_active ? 'true' : 'false' }})"
                                class="{{ $member->is_active ? 'text-rose-600 dark:text-rose-400 hover:text-rose-800 dark:hover:text-rose-300' : 'text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300' }} p-1 rounded-lg {{ $member->is_active ? 'hover:bg-rose-50 dark:hover:bg-rose-900/30' : 'hover:bg-emerald-50 dark:hover:bg-emerald-900/30' }}">
                                {{ $member->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center">
                            <div class="text-slate-400 dark:text-slate-500">
                                <svg class="h-12 w-12 mx-auto mb-3 text-slate-300 dark:text-slate-600">
                                    <use href="#icon-user-group"></use>
                                </svg>
                                <p class="text-lg font-medium text-slate-700 dark:text-slate-300">Tidak ada member</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                    @if ($search)
                                        Hasil pencarian "{{ $search }}" tidak ditemukan
                                    @else
                                        Belum ada member yang terdaftar
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-slot>
        </x-admin.table>

        <!-- Pagination -->
        @if ($members->hasPages())
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Menampilkan {{ $members->firstItem() }} hingga {{ $members->lastItem() }} dari
                    {{ $members->total() }} member
                </p>
                <div class="flex space-x-2">
                    {{ $members->withQueryString()->links('vendor.pagination.simple-tailwind') }}
                </div>
            </div>
        @endif
    </div>

    <!-- Toggle Status Confirmation Modal -->
    <div id="toggleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl max-w-md w-full p-6">
            <div class="text-center">
                <!-- Icon -->
                <div id="toggleModalIcon" class="mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4">
                    <!-- Icon akan diisi oleh JavaScript -->
                </div>

                <!-- Title -->
                <h3 id="toggleModalTitle" class="text-lg font-medium text-slate-900 dark:text-white mb-2">
                    <!-- Title akan diisi oleh JavaScript -->
                </h3>

                <!-- Message -->
                <p id="toggleModalMessage" class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                    <!-- Message akan diisi oleh JavaScript -->
                </p>

                <!-- Actions -->
                <div class="flex justify-center space-x-3">
                    <button type="button" onclick="closeToggleModal()"
                        class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                        Batal
                    </button>
                    <form id="toggleForm" method="POST" class="inline">
                        @csrf
                        <button id="toggleSubmitButton" type="submit"
                            class="px-4 py-2 rounded-xl text-white font-medium transition-colors">
                            <!-- Text button akan diisi oleh JavaScript -->
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let currentMemberId = null;
            let currentMemberName = null;
            let currentIsActive = null;

            function showToggleModal(memberId, memberName, isActive) {
                currentMemberId = memberId;
                currentMemberName = memberName;
                currentIsActive = isActive;

                const modal = document.getElementById('toggleModal');
                const iconDiv = document.getElementById('toggleModalIcon');
                const title = document.getElementById('toggleModalTitle');
                const message = document.getElementById('toggleModalMessage');
                const form = document.getElementById('toggleForm');
                const submitButton = document.getElementById('toggleSubmitButton');

                // Set form action
                form.action = "{{ route('admin.members.toggle', ':id') }}".replace(':id', memberId);

                if (isActive) {
                    // Nonaktifkan
                    iconDiv.className =
                        "mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-rose-100 dark:bg-rose-900/30 mb-4";
                    iconDiv.innerHTML = `
            <svg class="h-6 w-6 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        `;
                    title.textContent = "Nonaktifkan Member";
                    message.textContent =
                        `Apakah Anda yakin ingin menonaktifkan member "${memberName}"? Member tidak akan dapat mengakses sistem.`;
                    submitButton.className =
                        "px-4 py-2 rounded-xl bg-rose-500 hover:bg-rose-600 text-white font-medium transition-colors";
                    submitButton.textContent = "Nonaktifkan";
                } else {
                    // Aktifkan
                    iconDiv.className =
                        "mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 dark:bg-emerald-900/30 mb-4";
                    iconDiv.innerHTML = `
            <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        `;
                    title.textContent = "Aktifkan Member";
                    message.textContent =
                        `Apakah Anda yakin ingin mengaktifkan member "${memberName}"? Member akan dapat mengakses sistem kembali.`;
                    submitButton.className =
                        "px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-medium transition-colors";
                    submitButton.textContent = "Aktifkan";
                }

                // Show modal
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeToggleModal() {
                const modal = document.getElementById('toggleModal');
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';

                // Reset
                currentMemberId = null;
                currentMemberName = null;
                currentIsActive = null;
            }

            // Close modal when clicking outside
            document.getElementById('toggleModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeToggleModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeToggleModal();
                }
            });
        </script>
    @endpush
@endsection
