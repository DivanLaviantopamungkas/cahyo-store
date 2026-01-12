@props(['product' => null])

@php
    $product = $product ?? [
        'id' => 1,
        'name' => 'Mobile Legends',
        'slug' => 'mobile-legends',
        'category' => 'Game',
        'image' => 'https://cdn.worldvectorlogo.com/logos/mobile-legends.svg',
        'description' => 'Top up Diamond Mobile Legends',
        'price_range' => 'Rp 1.000 - Rp 500.000',
    ];
@endphp

<a href="/produk/{{ $product['slug'] }}" class="block">
    <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover">
        <div class="p-4">
            <div class="flex items-start space-x-4">
                <!-- Product Image -->
                <div
                    class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-blue-100 to-purple-100 rounded-lg flex items-center justify-center">
                    @if (isset($product['image']) && $product['image'])
                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-10 h-10 object-contain">
                    @else
                        <i class="fas fa-gamepad text-2xl text-purple-500"></i>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="flex-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-gray-900">{{ $product['name'] }}</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $product['description'] ?? 'Top up cepat dan aman' }}</p>
                        </div>
                        <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full">
                            {{ $product['category'] ?? 'Game' }}
                        </span>
                    </div>

                    <div class="mt-3">
                        <p class="text-sm font-medium text-gray-900">
                            {{ $product['price_range'] ?? 'Mulai dari Rp 1.000' }}</p>
                        <div class="flex items-center mt-1 text-xs text-gray-500">
                            <i class="fas fa-bolt text-yellow-500 mr-1"></i>
                            <span>Proses instan 1-5 menit</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hover effect indicator -->
        <div class="px-4 pb-3">
            <div class="text-right">
                <span class="inline-flex items-center text-sm font-medium text-purple-600">
                    Beli Sekarang
                    <i class="fas fa-chevron-right ml-1 text-xs"></i>
                </span>
            </div>
        </div>
    </div>
</a>
