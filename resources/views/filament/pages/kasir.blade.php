<x-filament-panels::page>
    <style>
        .toko-ibu-navy { background-color: #004193 !important; }
        .toko-ibu-orange { background-color: #F39200 !important; }
        .text-toko-ibu-navy { color: #004193 !important; }
        .text-toko-ibu-orange { color: #F39200 !important; }
        .teks-putih { color: #ffffff !important; }
        .teks-hitam { color: #000000 !important; }
        .border-toko-ibu-navy { border-color: #004193 !important; }
    </style>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 teks-hitam">
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl shadow-xl border-t-4 border-toko-ibu-navy overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h2 class="text-2xl font-extrabold text-toko-ibu-navy">🛒 Daftar Belanja</h2>
                    <span class="text-sm font-bold px-4 py-2 toko-ibu-navy teks-putih rounded-full">
                        {{ count($cart) }} Jenis Barang
                    </span>
                </div>

                <div class="p-6">
                    <div class="mb-6">
                        <select wire:change="addToCart($event.target.value)" class="w-full p-4 rounded-xl border-2 border-toko-ibu-navy teks-hitam font-semibold focus:border-[#F39200] focus:ring-0 shadow-sm">
                            <option value="">🔍 Klik untuk pilih barang...</option>
                            @foreach(\App\Models\Product::where('stock', '>', 0)->get() as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }} — Rp{{ number_format($product->selling_price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="toko-ibu-navy teks-putih text-sm uppercase tracking-wider">
                                    <th class="p-4 text-left rounded-l-xl teks-putih">Barang</th>
                                    <th class="p-4 text-right teks-putih">Harga</th>
                                    <th class="p-4 text-center teks-putih">Jumlah</th>
                                    <th class="p-4 text-right rounded-r-xl teks-putih">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($cart as $id => $item)
                                <tr class="hover:bg-orange-50 transition-colors text-gray-900 font-medium">
                                    <td class="p-4">
                                        <p class="font-bold teks-hitam">{{ $item['name'] }}</p>
                                    </td>
                                    <td class="p-4 text-right teks-hitam">
                                        Rp{{ number_format($item['price'], 0, ',', '.') }}
                                    </td>
                                    <td class="p-4">
                                        <div class="flex justify-center items-center gap-3">
                                            <button wire:click="decrementQty({{ $id }})" class="w-10 h-10 flex items-center justify-center bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-all shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" />
                                                </svg>
                                            </button>

                                            <span class="font-black text-toko-ibu-navy w-10 text-center text-2xl">{{ $item['qty'] }}</span>

                                            <button wire:click="incrementQty({{ $id }})" class="w-10 h-10 flex items-center justify-center toko-ibu-orange teks-putih rounded-lg hover:bg-[#e68a00] transition-all shadow-md">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-6 h-6 teks-putih">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="p-4 text-right font-black text-toko-ibu-navy text-xl">
                                        Rp{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-20 text-center text-gray-400 font-bold italic text-lg">
                                        🛒 Belum ada barang di keranjang
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border-2 border-toko-ibu-navy sticky top-6">
                <div class="toko-ibu-orange p-6 text-center text-white">
                    <p class="text-xs font-bold uppercase tracking-widest teks-putih">Total Tagihan</p>
                    <h1 class="text-3xl font-black mt-1 teks-putih">Rp {{ number_format($this->total, 0, ',', '.') }}</h1>
                </div>

                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-black text-toko-ibu-navy mb-2 uppercase">💵 Uang Tunai (Bayar)</label>
                        <input
                            type="text"
                            wire:model.live="amount_paid_formatted"
                            class="w-full p-4 rounded-xl border-2 border-toko-ibu-navy text-2xl font-black text-gray-900 text-right focus:ring-4 focus:ring-[#F39200] focus:border-[#F39200]"
                            placeholder="0"
                            onkeyup="this.value = this.value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                        >
                    </div>

                    <div class="p-4 bg-blue-50 rounded-xl border-l-8 border-toko-ibu-orange">
                        <p class="text-xs font-bold text-gray-500 uppercase">Kembalian</p>
                        <p class="text-3xl font-black text-toko-ibu-navy">
                            Rp {{ number_format($this->change, 0, ',', '.') }}
                        </p>
                    </div>

                    <button wire:click="saveTransaction" class="w-full toko-ibu-navy teks-putih py-5 rounded-2xl font-black text-xl hover:bg-[#002a5e] active:scale-95 transition-all shadow-lg flex items-center justify-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 teks-putih">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        SIMPAN TRANSAKSI
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
