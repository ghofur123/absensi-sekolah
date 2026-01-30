<x-filament-panels::page>
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
                <label class="text-sm font-medium">Tanggal Awal</label>
                <input type="date"
                    @disabled(!$kelasId)
                    wire:model.live="tanggalAwal"
                    class="w-full mt-1 rounded-lg border-gray-300">
            </div>

            <div>
                <label class="text-sm font-medium">Tanggal Akhir</label>
                <input type="date"
                    @disabled(!$tanggalAwal)
                    wire:model.live="tanggalAkhir"
                    class="w-full mt-1 rounded-lg border-gray-300">
            </div>

        </div>

        @if ($absensi->isEmpty())
        <div class="border rounded-lg p-6 text-center text-gray-500">
            Pilih lembaga & kelas untuk melihat data
        </div>
        @else
        <div class="overflow-x-auto border rounded-lg">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2">No</th>
                        <th class="px-3 py-2">Nama</th>
                        <th class="px-3 py-2">Hadir</th>
                        <th class="px-3 py-2">Sakit</th>
                        <th class="px-3 py-2">Izin</th>
                        <th class="px-3 py-2">Tidak Hadir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($absensi as $row)
                    <tr class="border-t">
                        <td class="px-3 py-2">{{ $loop->iteration }}</td>
                        <td class="px-3 py-2">{{ $row->siswa->nama_siswa }}</td>
                        <td class="px-3 py-2">{{ $row->hadir }}</td>
                        <td>{{ $row->sakit }}</td>
                        <td>{{ $row->izin }}</td>
                        <td>{{ $row->tidak_hadir }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

    </div>
</x-filament-panels::page>