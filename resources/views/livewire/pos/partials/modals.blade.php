<!-- Product Selection Modal -->
@if($selected_product)
    <div class="fixed inset-0 overflow-hidden z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Overlay with blur -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 backdrop-blur-sm transition-opacity cursor-pointer"
                wire:click="closeProductModal">
            </div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle w-full max-w-lg">
                <div class="bg-white px-6 pt-10 pb-4 sm:p-8">
                    <div class="flex items-start justify-between mb-5">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 leading-6">
                                {{ $selected_product->name }}
                            </h3>
                            <p class="text-lg text-orange-600 font-medium mt-2">
                                Rp {{ number_format($selected_product->base_price, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">Stok: {{ $product->stock }}</p>
                        </div>
                        <button type="button" wire:click="closeProductModal"
                            class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    @if($selected_product->variants->isNotEmpty())
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Varian</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($selected_product->variants as $variant)
                                    <button type="button"
                                        wire:click="$set('selected_variant', {{ $variant->id }})"
                                        class="relative w-full text-left px-4 py-3 rounded-lg transition-all duration-200
                                            {{ $selected_variant == $variant->id 
                                                ? 'border-2 border-orange-500 bg-orange-50 ring-2 ring-orange-200' 
                                                : 'border border-gray-200 hover:border-orange-200 hover:bg-orange-50/50' }}">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <span class="block font-medium text-gray-900">{{ $variant->name }}</span>
                                                <span class="block text-sm text-orange-600 mt-0.5">
                                                    +Rp {{ number_format($variant->price_adjustment, 0, ',', '.') }}
                                                </span>
                                            </div>
                                            @if($selected_variant == $variant->id)
                                                <span class="text-orange-500 ml-2">
                                                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            @endif
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Jumlah</label>
                        <div class="flex items-center justify-center space-x-3">
                            <button type="button" wire:click="decrementQuantity"
                                class="inline-flex items-center justify-center w-12 h-12 rounded-lg border border-gray-200 
                                       text-gray-600 hover:bg-gray-100 hover:text-gray-700 focus:outline-none 
                                       focus:ring-2 focus:ring-orange-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/>
                                </svg>
                            </button>
                            <input type="number" 
                                wire:model="quantity" 
                                class="w-24 text-center text-lg rounded-lg border-gray-200 focus:border-orange-300 
                                       focus:ring focus:ring-orange-200 focus:ring-opacity-50"
                                min="1">
                            <button type="button" wire:click="incrementQuantity"
                                class="inline-flex items-center justify-center w-12 h-12 rounded-lg border border-gray-200 
                                       text-gray-600 hover:bg-gray-100 hover:text-gray-700 focus:outline-none 
                                       focus:ring-2 focus:ring-orange-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 sm:px-6 sm:py-4 flex flex-col sm:flex-row-reverse gap-3">
                    <button type="button" wire:click="addToCart" 
                        class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 rounded-lg bg-orange-500 
                               text-black text-base font-semibold hover:bg-orange-600 focus:outline-none 
                               focus:ring-2 focus:ring-orange-300 transition-colors">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                        </svg>
                        Tambah ke Keranjang
                    </button>
                    <button type="button" wire:click="closeProductModal" 
                        class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 rounded-lg border border-gray-300 
                               bg-white text-base font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none 
                               focus:ring-2 focus:ring-orange-300 transition-colors">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Include Payment Modal -->
@include('livewire.pos.partials.payment-modal')

<!-- Include Success Modal -->
@include('livewire.pos.partials.success-modal')
