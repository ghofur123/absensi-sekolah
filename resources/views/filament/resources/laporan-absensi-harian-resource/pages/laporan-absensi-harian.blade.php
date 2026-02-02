<x-filament-panels::page>

    {{-- LOADING --}}
    <div wire:loading
        wire:target="lembagaId,kelasId,tanggalAwal,tanggalAkhir,mataPelajaranId,status"
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
                <select wire:model.live="lembagaId" class="w-full mt-1 rounded-lg border border-gray-300 bg-white">
                    <option value="">-- Pilih Lembaga --</option>
                    @foreach ($lembagas as $l)
                    <option value="{{ $l['id'] }}">{{ $l['nama'] }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Kelas</label>
                <select wire:model.live="kelasId"
                    class="w-full mt-1 rounded-lg border border-gray-300 bg-white"
                    @disabled(!$lembagaId)>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach ($kelas as $k)
                    <option value="{{ $k['id'] }}">{{ $k['nama'] }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Mata Pelajaran</label>
                <select wire:model.live="mataPelajaranId"
                    class="w-full mt-1 rounded-lg border border-gray-300 bg-white"
                    @disabled(!$kelasId)>
                    <option value="">-- Pilih Mapel --</option>
                    @foreach ($mata_pelajaran as $m)
                    <option value="{{ $m['id'] }}">{{ $m['nama'] }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Tanggal Awal</label>
                <input type="date" wire:model.live="tanggalAwal"
                    class="w-full mt-1 rounded-lg border border-gray-300 bg-white"
                    @disabled(!$kelasId)>
            </div>

            <div>
                <label class="text-sm font-medium">Tanggal Akhir</label>
                <input type="date" wire:model.live="tanggalAkhir"
                    class="w-full mt-1 rounded-lg border border-gray-300 bg-white"
                    min="{{ $tanggalAwal }}"
                    @disabled(!$tanggalAwal)>
            </div>

            <div>
                <label class="text-sm font-medium">Status (Filter)</label>
                <select wire:model.live="status"
                    class="w-full mt-1 rounded-lg border border-gray-300 bg-white"
                    @disabled(!$tanggalAkhir)>
                    <option value="">-- Semua Status --</option>
                    <option value="hadir">Hadir</option>
                    <option value="izin">Izin</option>
                    <option value="sakit">Sakit</option>
                    <option value="alpa">Alpa</option>
                </select>
            </div>
        </div>

        {{-- INFO FILTER --}}
        @if($status)
        <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-2 text-sm text-blue-800">
            <strong>Filter Aktif:</strong> Hanya menampilkan status <strong class="uppercase">{{ $status }}</strong>.
            Sel lain ditandai dengan "-".
        </div>
        @endif

        {{-- TABEL --}}
        @if (empty($absensi))
        <div class="border rounded-lg p-6 text-center text-gray-500">
            Pilih filter untuk melihat data
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th rowspan="2" class="border px-2 py-2 align-middle">No</th>
                        <th rowspan="2" class="border px-2 py-2 align-middle">Nama Siswa</th>

                        {{-- Header Tanggal --}}
                        @foreach ($absensi['tanggal'] as $tgl)
                        <th class="border px-2 py-1 text-center text-xs">
                            {{ \Carbon\Carbon::parse($tgl)->format('d/m') }}
                        </th>
                        @endforeach

                        <th colspan="4" class="border px-2 py-1 bg-blue-50">Rekap</th>
                        <th colspan="2" class="border px-2 py-1 bg-green-50">Nilai</th>
                    </tr>
                    <tr>
                        {{-- Hari --}}
                        @foreach ($absensi['tanggal'] as $tgl)
                        <th class="border px-2 py-1 text-center text-xs">
                            {{ \Carbon\Carbon::parse($tgl)->locale('id')->isoFormat('ddd') }}
                        </th>
                        @endforeach

                        <th class="border px-1 py-1 text-xs bg-blue-50">H</th>
                        <th class="border px-1 py-1 text-xs bg-blue-50">I</th>
                        <th class="border px-1 py-1 text-xs bg-blue-50">S</th>
                        <th class="border px-1 py-1 text-xs bg-blue-50">A</th>
                        <th class="border px-1 py-1 text-xs bg-green-50">%</th>
                        <th class="border px-1 py-1 text-xs bg-green-50">Ket</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($absensi['data'] as $index => $row)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-2 py-2 text-center">{{ $index + 1 }}</td>
                        <td class="border px-2 py-2">{{ $row['nama'] }}</td>

                        {{-- Data Harian --}}
                        @foreach ($row['harian'] as $item)
                        <td class="border px-2 py-1 text-center text-xs">
                            @if($item['kode'] !== '-')
                            {{-- Status normal: H, I, S, A --}}
                            <div class="font-bold
                                    @if($item['kode']=='H') text-green-600
                                    @elseif($item['kode']=='I') text-blue-600
                                    @elseif($item['kode']=='S') text-yellow-600
                                    @elseif($item['kode']=='A') text-red-600
                                    @endif
                                ">
                                {{ $item['kode'] }}
                            </div>
                            @if($item['text'])
                            <div class="text-[10px] text-gray-500">
                                {{ $item['text'] }}
                            </div>
                            @endif

                            @elseif($item['text'])
                            {{-- Kode "-" tapi ada text (misal "Di Luar Jadwal") --}}
                            <div class="text-[10px] text-orange-600 font-medium leading-tight">
                                {{ $item['text'] }}
                            </div>

                            @else
                            {{-- Kosong: disembunyikan oleh filter status --}}
                            <div class="text-gray-300 font-light">-</div>
                            @endif
                        </td>
                        @endforeach

                        {{-- Rekapitulasi --}}
                        <td class="border px-2 py-1 text-center bg-green-50 font-medium text-green-700">{{ $row['hadir'] }}</td>
                        <td class="border px-2 py-1 text-center bg-yellow-50 font-medium text-yellow-700">{{ $row['izin'] }}</td>
                        <td class="border px-2 py-1 text-center bg-blue-50 font-medium text-blue-700">{{ $row['sakit'] }}</td>
                        <td class="border px-2 py-1 text-center bg-red-50 font-medium text-red-700">{{ $row['alpa'] }}</td>

                        {{-- Penilaian --}}
                        <td class="border px-2 py-1 text-center font-semibold
                            @if($row['persentase'] >= 95) text-green-700
                            @elseif($row['persentase'] >= 75) text-yellow-700
                            @else text-red-700
                            @endif
                        ">{{ $row['persentase'] }}%</td>
                        <td class="border px-2 py-1 text-center text-xs font-medium">{{ $row['keterangan'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Legend --}}
        <div class="flex gap-4 text-xs mt-4 justify-center flex-wrap">
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 bg-green-100 border border-green-300 rounded flex items-center justify-center font-bold text-green-800">H</span>
                <span>Hadir</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 bg-yellow-100 border border-yellow-300 rounded flex items-center justify-center font-bold text-yellow-800">I</span>
                <span>Izin</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 bg-blue-100 border border-blue-300 rounded flex items-center justify-center font-bold text-blue-800">S</span>
                <span>Sakit</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 bg-red-100 border border-red-300 rounded flex items-center justify-center font-bold text-red-800">A</span>
                <span>Alpa</span>
            </div>
        </div>
        @endif

    </div>

    {{-- Print Styles --}}
    <style>
        @media print {

            .fi-sidebar,
            .fi-topbar,
            button {
                display: none !important;
            }

            table {
                font-size: 9px;
            }

            th,
            td {
                padding: 2px 4px !important;
            }
        }
    </style>
</x-filament-panels::page>