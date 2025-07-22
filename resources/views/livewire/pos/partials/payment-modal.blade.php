@if($showPaymentModal)
    <div class="fixed inset-0 overflow-hidden z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Overlay with blur -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 backdrop-blur-sm transition-opacity cursor-pointer"
                wire:click="$set('showPaymentModal', false)">
            </div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle w-full max-w-lg">
                <div class="bg-white px-6 pt-5 pb-4 sm:p-6">
                    <div class="flex items-start justify-between mb-5">
                        <h3 class="text-xl font-semibold text-gray-900 leading-6 mt-1">Proses Pembayaran</h3>
                        <button type="button" wire:click="$set('showPaymentModal', false)"
                            class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                            <select wire:model="payment_method_id" 
                                class="w-full rounded-lg border-gray-200 text-base focus:border-orange-300 focus:ring 
                                       focus:ring-orange-200 focus:ring-opacity-50">
                                <option value="">Pilih Metode Pembayaran</option>
                                @foreach($payment_methods as $method)
                                    <option value="{{ $method->id }}">{{ $method->name }}</option>
                                @endforeach
                            </select>
                            @error('payment_method_id') 
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Pembayaran</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                                    
                                </span>
                                <input type="number" 
                                    wire:model.live="payment_amount"
                                    class="w-full pl-10 rounded-lg border-gray-200 text-base focus:border-orange-300 focus:ring 
                                           focus:ring-orange-200 focus:ring-opacity-50"
                                    min="0">
                            </div>
                            @error('payment_amount') 
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        @if($payment_amount > 0)
                            <div class="p-4 bg-gray-50 rounded-lg space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-gray-700">Total Belanja</span>
                                    <span class="font-semibold text-gray-900 text-lg">
                                        Rp {{ number_format($this->subtotal, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-gray-700">Kembalian</span>
                                    <span class="font-semibold text-lg {{ $this->change_amount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        Rp {{ number_format($this->change_amount, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                            <textarea wire:model="notes" 
                                class="w-full rounded-lg border-gray-200 text-base focus:border-orange-300 focus:ring 
                                       focus:ring-orange-200 focus:ring-opacity-50"
                                rows="2"></textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 sm:px-6 sm:py-4 flex flex-col sm:flex-row-reverse gap-3">
                    <button type="button" wire:click="processTransaction" 
                        class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 rounded-lg bg-orange-500 
                               text-black text-base font-semibold hover:bg-orange-600 focus:outline-none 
                               focus:ring-2 focus:ring-orange-300 transition-colors">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        Proses Transaksi
                    </button>
                    <button type="button" wire:click="$set('showPaymentModal', false)" 
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
