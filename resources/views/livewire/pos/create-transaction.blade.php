<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <!-- Sticky Header -->
    <div class="sticky top-0 z-10 bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <!-- Search Bar -->
            <div class="mb-4">
                <input type="text" 
                    wire:model.live.debounce.300ms="search"
                    class="w-full rounded-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 shadow-sm focus:border-orange-300 dark:focus:border-orange-500 focus:ring focus:ring-orange-200 dark:focus:ring-orange-800 focus:ring-opacity-50"
                    placeholder="Cari produk...">
            </div>
            
            <!-- Category Filter -->
            <div class="flex space-x-2 overflow-x-auto pb-2 scrollbar-hide">
                <button wire:click="$set('category_filter', '')" 
                    class="flex-none px-4 py-2 rounded-full text-sm font-medium transition-colors {{ !$category_filter ? 'bg-orange-500 text-white dark:bg-orange-600' : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    Semua
                </button>
                @foreach($categories as $category)
                    <button wire:click="$set('category_filter', '{{ $category->id }}')"
                        class="flex-none px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $category_filter == $category->id ? 'bg-orange-500 text-white dark:bg-orange-600' : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-32">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($products as $product)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md dark:hover:shadow-lg transition-shadow">
                    @if($product->image)
                        <div class="aspect-square">
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                alt="{{ $product->name }}"
                                class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="aspect-square bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <span class="text-gray-400 dark:text-gray-500">No Image</span>
                        </div>
                    @endif
                    <div class="p-3">
                        <h3 class="font-medium text-sm text-gray-900 dark:text-gray-100 truncate">{{ $product->name }}</h3>
                        <p class="text-orange-600 dark:text-orange-400 font-medium text-sm mt-1">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Stok: {{ $product->stock }}</p>
                        @if($product->variants->isEmpty())
                            <button type="button"
                                wire:click="quickAddToCart({{ $product->id }})"
                                class="mt-2 w-full bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 py-1.5 px-3 rounded-md text-sm hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                + Tambah
                            </button>
                        @else
                            <button type="button"
                                wire:click="selectProduct({{ $product->id }})"
                                class="mt-2 w-full bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 py-1.5 px-3 rounded-md text-sm hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                Pilih Varian
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Floating Cart -->
    <div x-data="{ showDetails: @entangle('showCart') }"
        class="fixed bottom-0 inset-x-0 bg-white dark:bg-gray-800 shadow-lg border-t border-gray-200 dark:border-gray-700 z-20">
        <!-- Cart Summary Bar -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                @if(!empty($cart))
                    <button type="button" @click="showDetails = !showDetails" class="text-gray-900 dark:text-gray-100 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                x-show="!showDetails" d="M19 9l-7 7-7-7"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                x-show="showDetails" d="M5 15l7-7 7 7"></path>
                        </svg>
                    </button>
                @endif
                <div>
                    <span class="text-sm text-gray-900 dark:text-gray-100">{{ collect($cart)->sum('quantity') }} Item</span>
                    <span class="mx-2 text-gray-300 dark:text-gray-600">|</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
            <button type="button"
                wire:click="$set('showPaymentModal', true)"
                class="bg-amber-600 dark:bg-amber-500 text-black dark:text-white py-2 px-6 rounded-full text-sm font-medium hover:bg-amber-700 dark:hover:bg-amber-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                {{ empty($cart) ? 'disabled' : '' }}>
                Bayar
            </button>
        </div>

        <!-- Cart Details Panel - Only show when cart has items -->
        @if(!empty($cart))
            <div x-show="showDetails" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform translate-y-4"
                class="border-t border-gray-200 dark:border-gray-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        @foreach($cart as $key => $item)
                            <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                                <div class="flex-1">
                                    <h4 class="font-medium text-sm text-gray-900 dark:text-gray-100">{{ $item['product_name'] }}</h4>
                                    @if($item['variant_name'])
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item['variant_name'] }}</p>
                                    @endif
                                    <p class="text-sm text-orange-600 dark:text-orange-400">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <button type="button" 
                                        wire:click="decrementCartItem('{{ $key }}')" 
                                        class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                        </svg>
                                    </button>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item['quantity'] }}</span>
                                    <button type="button"
                                        wire:click="incrementCartItem('{{ $key }}')"
                                        class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </button>
                                    <button type="button"
                                        wire:click="removeFromCart('{{ $key }}')" 
                                        class="text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 ml-2 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    @include('livewire.pos.partials.modals')
</div>
