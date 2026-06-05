<x-filament-panels::page>

    {{-- LOADING --}}
    <div wire:loading
        wire:target="lembagaId,tanggalAwal,tanggalAkhir"
        class="fixed inset-0 z-50 bg-white/60 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white px-6 py-4 rounded-xl shadow flex gap-3">
            <x-filament::loading-indicator class="h-6 w-6" />
            <span class="text-sm font-medium">Memuat data...</span>
        </div>
    </div>

    <div class="space-y-6">

        {{-- FILTER --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div>
                <label class="text-sm font-medium">Lembaga</label>
                <select wire:model.live="lembagaId"
                    class="w-full mt-1 rounded-lg border border-gray-300 bg-white">
                    <option value="">-- Pilih Lembaga --</option>
                    @foreach ($lembagas as $l)
                    <option value="{{ $l->id }}">
                        {{ $l->nama_lembaga }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Tanggal Awal</label>
                <input type="date"
                    wire:model.live="tanggalAwal"
                    class="w-full mt-1 rounded-lg border border-gray-300 bg-white"
                    @disabled(!$lembagaId)>
            </div>

            <div>
                <label class="text-sm font-medium">Tanggal Akhir</label>
                <input type="date"
                    wire:model.live="tanggalAkhir"
                    class="w-full mt-1 rounded-lg border border-gray-300 bg-white"
                    min="{{ $tanggalAwal }}"
                    @disabled(!$tanggalAwal)>
            </div>

        </div>

        {{-- TABEL --}}
        @if ($rekap->isEmpty())
        <div class="border rounded-lg p-6 text-center text-gray-500">
            Pilih lembaga dan rentang tanggal untuk melihat data
        </div>
        @else
        <div class="overflow-x-auto border rounded-lg">
            <table class="w-full text-sm border border-collapse">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-2 text-center">No</th>
                        <th class="border px-2 py-2 text-left">Nama Guru</th>

                        @foreach ($mapel as $m)
                        <th class="border px-2 py-2 text-center">
                            {{ $m->nama_mapel }}
                        </th>
                        @endforeach

                        <th class="border px-2 py-2 text-center bg-green-50">
                            Total
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($rekap as $i => $row)
                    <tr class="hover:bg-gray-50">

                        <td class="border px-2 py-2 text-center">
                            {{ $i + 1 }}
                        </td>

                        <td class="border px-2 py-2 font-medium">
                            {{ $row->nama }}
                        </td>

                        @foreach ($mapel as $m)
                        <td class="border px-2 py-2 text-center">
                            {{ $row->{'mapel_' . $m->id} }}
                        </td>
                        @endforeach

                        <td class="border px-2 py-2 text-center font-bold bg-green-50 text-green-700">
                            {{ $row->total }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
        @endif

    </div>

    {{-- PRINT STYLE --}}
    <style>
        @media print {

            .fi-sidebar,
            .fi-topbar,
            button,
            select,
            input {
                display: none !important;
            }

            table {
                font-size: 10px;
            }

            th,
            td {
                padding: 3px 5px !important;
            }
        }
    </style>

</x-filament-panels::page>