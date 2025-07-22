<div x-data="{ show: false, transactionId: null }"
    x-on:transaction-completed.window="show = true; transactionId = $event.detail"
    x-show="show"
    class="fixed inset-0 z-50"
    x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Overlay with blur -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 backdrop-blur-sm transition-opacity"></div>

        <!-- Modal panel -->
        <div class="relative inline-block bg-white rounded-xl shadow-2xl max-w-sm w-full p-8 text-center
                    transform transition-all animate-[modal-pop_0.2s_ease-out]">
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
                <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h3 class="text-2xl font-semibold text-gray-900 mb-2">Transaksi Berhasil!</h3>
            <p class="text-gray-500 mb-6">Transaksi telah berhasil diproses</p>
            
            <div class="flex flex-col gap-3">
                <a :href="'/transactions/' + transactionId + '/print'" target="_blank"
                    class="inline-flex justify-center items-center px-6 py-3 rounded-lg bg-orange-500 
                           text-black text-base font-semibold hover:bg-orange-600 focus:outline-none 
                           focus:ring-2 focus:ring-orange-300 transition-colors">
                    <svg class="w-5 h-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" 
                            d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" 
                            clip-rule="evenodd" />
                    </svg>
                    Cetak Struk
                </a>
                <button type="button" x-on:click="show = false"
                    class="inline-flex justify-center items-center px-6 py-3 rounded-lg border border-gray-300 
                           bg-white text-base font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none 
                           focus:ring-2 focus:ring-orange-300 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
