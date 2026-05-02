<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-bold text-[#004193] mb-1 uppercase">Mulai Tanggal</label>
                <input type="date" wire:model.live="startDate" class="rounded-lg border-gray-300 focus:ring-[#F39200] focus:border-[#F39200]">
            </div>
            <div>
                <label class="block text-sm font-bold text-[#004193] mb-1 uppercase">Sampai Tanggal</label>
                <input type="date" wire:model.live="endDate" class="rounded-lg border-gray-300 focus:ring-[#F39200] focus:border-[#F39200]">
            </div>
            <button wire:click="$refresh" class="bg-[#F39200] text-white px-6 py-2 rounded-lg font-bold hover:bg-[#e68a00] transition shadow-md">
                Tampilkan
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-[#004193]">
                <p class="text-sm font-bold text-gray-500 uppercase">Total Penjualan</p>
                <h2 class="text-2xl font-black text-[#004193]">Rp {{ number_format($this->ringkasan['total_penjualan'], 0, ',', '.') }}</h2>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-gray-300">
                <p class="text-sm font-bold text-gray-500 uppercase">Total Modal</p>
                <h2 class="text-2xl font-black text-gray-700">Rp {{ number_format($this->ringkasan['total_modal'], 0, ',', '.') }}</h2>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-[#F39200]">
                <p class="text-sm font-bold text-[#F39200] uppercase">Total Keuntungan (Cuan)</p>
                <h2 class="text-3xl font-black text-[#004193]">Rp {{ number_format($this->ringkasan['total_keuntungan'], 0, ',', '.') }}</h2>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-[#004193] text-white uppercase text-xs">
                    <tr>
                        <th class="p-4">Produk</th>
                        <th class="p-4 text-center">Terjual</th>
                        <th class="p-4 text-right">Total Modal</th>
                        <th class="p-4 text-right">Total Penjualan</th>
                        <th class="p-4 text-right bg-[#F39200]">Keuntungan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($this->laporan_data as $item)
                    <tr class="hover:bg-blue-50">
                        <td class="p-4 font-bold text-gray-800">{{ $item->product_name }}</td>
                        <td class="p-4 text-center font-bold">{{ $item->total_terjual }}</td>
                        <td class="p-4 text-right text-gray-500">Rp {{ number_format($item->total_modal, 0, ',', '.') }}</td>
                        <td class="p-4 text-right text-[#004193] font-bold">Rp {{ number_format($item->total_penjualan, 0, ',', '.') }}</td>
                        <td class="p-4 text-right font-black text-[#004193] bg-orange-50">
                            Rp {{ number_format($item->keuntungan, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center text-gray-400">Tidak ada data penjualan di tanggal ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
