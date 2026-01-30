<x-filament-panels::page>
    {{-- GLOBAL LOADER --}}
    <div wire:loading
        wire:target="lembagaId,kelasId,tanggalAwal,tanggalAkhir,loadAbsensi"
        class="fixed inset-0 z-50 bg-white/60 backdrop-blur-sm flex items-center justify-center">
        <div class="flex items-center gap-3 bg-white px-6 py-4 rounded-xl shadow">
            <x-filament::loading-indicator class="h-6 w-6" />
            <span class="text-sm font-medium text-gray-700">
                Memuat data...
            </span>
        </div>
    </div>
    <div class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- LEMBAGA --}}
            <div>
                <label class="text-sm font-medium">Lembaga</label>
                <select wire:model.live="lembagaId"
                    class="w-full mt-1 rounded-lg border-gray-300 fi-input fi-select">
                    <option value="">-- Pilih Lembaga --</option>
                    @foreach ($lembagas as $lembaga)
                    <option value="{{ $lembaga->id }}">
                        {{ $lembaga->nama_lembaga }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- KELAS --}}
            <div>
                <label class="text-sm font-medium">Kelas</label>
                <select wire:model.live="kelasId"
                    class="w-full mt-1 rounded-lg border-gray-300"
                    @disabled(!$lembagaId)>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach ($kelas as $kls)
                    <option value="{{ $kls->id }}">
                        {{ $kls->nama_kelas }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Mata Pelajaran</label>
                <select wire:model.live="mataPelajaranId"
                    class="w-full mt-1 rounded-lg border-gray-300"
                    @disabled(!$kelasId)>
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach ($mata_pelajaran as $mp)
                    <option value="{{ $mp->id }}">
                        {{ $mp->nama_mapel }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Tanggal Awal</label>
                <input type="date"
                    wire:model.live="tanggalAwal"
                    @disabled(!$kelasId)
                    class="w-full mt-1 rounded-lg border-gray-300">
            </div>

            <div>
                <label class="text-sm font-medium">Tanggal Akhir</label>
                <input type="date"
                    wire:model.live="tanggalAkhir"
                    @disabled(!$tanggalAwal)
                    min="{{ $tanggalAwal }}"
                    class="w-full mt-1 rounded-lg border-gray-300">
            </div>
        </div>

        @if ($absensi->isEmpty())
        <div class="border rounded-lg p-6 text-center text-gray-500">
            Pilih lembaga & kelas untuk melihat data
        </div>
        @else
        <div class="flex justify-end mb-2">
            <button
                onclick="window.print()"
                title="Print laporan"
                class="fi-btn fi-btn-color-gray fi-btn-size-xs">
                🖨️
            </button>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th rowspan="2" class="border px-3 py-2 text-center align-middle">
                        No
                    </th>
                    <th rowspan="2" class="border px-3 py-2 text-center align-middle">
                        Nama
                    </th>

                    <th colspan="4" class="border px-3 py-2 text-center">
                        Tingkat Kehadiran
                    </th>

                    <th colspan="2" class="border px-3 py-2 text-center">
                        Penilaian
                    </th>
                </tr>

                <tr>
                    <th class="border px-3 py-2 text-center">Hadir</th>
                    <th class="border px-3 py-2 text-center">Izin</th>
                    <th class="border px-3 py-2 text-center">Sakit</th>
                    <th class="border px-3 py-2 text-center">Alpa</th>

                    <th class="border px-3 py-2 text-center">%</th>
                    <th class="border px-3 py-2 text-center">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($absensi as $row)
                <tr class="border-t">
                    <td class="border px-3 py-2">{{ $loop->iteration }}</td>
                    <td class="border px-3 py-2">{{ $row->siswa->nama_siswa }}</td>
                    <td class="border px-3 py-2">{{ $row->hadir }}</td>
                    <td class="border px-3 py-2">{{ $row->izin }}</td>
                    <td class="border px-3 py-2">{{ $row->sakit }}</td>
                    <td class="border px-3 py-2">{{ $row->alpa }}</td>
                    <td class="border px-3 py-2">{{ $row->persentase }}%</td>
                    <td class="border px-3 py-2">{{ $row->keterangan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    </div>
</x-filament-panels::page>