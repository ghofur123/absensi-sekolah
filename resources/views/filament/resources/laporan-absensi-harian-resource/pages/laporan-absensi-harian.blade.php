<x-filament-panels::page>

    {{-- LOADING --}}
    <div wire:loading
        wire:target="lembagaId,kelasId,tanggalAwal,tanggalAkhir,loadAbsensi"
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
                <select wire:model.live="lembagaId" class="w-full mt-1 rounded-lg border-gray-300">
                    <option value="">-- Pilih Lembaga --</option>
                    @foreach ($lembagas as $l)
                    <option value="{{ $l->id }}">{{ $l->nama_lembaga }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Kelas</label>
                <select wire:model.live="kelasId"
                    class="w-full mt-1 rounded-lg border-gray-300"
                    @disabled(!$lembagaId)>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach ($kelas as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Mata Pelajaran</label>
                <select wire:model.live="mataPelajaranId"
                    class="w-full mt-1 rounded-lg border-gray-300"
                    @disabled(!$kelasId)>
                    <option value="">-- Pilih Mapel --</option>
                    @foreach ($mata_pelajaran as $m)
                    <option value="{{ $m->id }}">{{ $m->nama_mapel }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Tanggal Awal</label>
                <input type="date" wire:model.live="tanggalAwal"
                    class="w-full mt-1 rounded-lg border-gray-300"
                    @disabled(!$kelasId)>
            </div>

            <div>
                <label class="text-sm font-medium">Tanggal Akhir</label>
                <input type="date" wire:model.live="tanggalAkhir"
                    class="w-full mt-1 rounded-lg border-gray-300"
                    min="{{ $tanggalAwal }}"
                    @disabled(!$tanggalAwal)>
            </div>

            <div>
                <label class="text-sm font-medium">Status</label>
                <select wire:model.live="status"
                    class="w-full mt-1 rounded-lg border-gray-300"
                    @disabled(!$tanggalAkhir)>
                    <option value="">-- Pilih Status --</option>
                    <option value="hadir">Hadir</option>
                    <option value="izin">Izin</option>
                    <option value="sakit">Sakit</option>
                    <option value="alpa">Alpa</option>
                </select>
            </div>
        </div>

        {{-- TABEL --}}
        @if (!$absensi)
        <div class="border rounded-lg p-6 text-center text-gray-500">
            Pilih filter untuk melihat data
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1">No</th>
                        <th class="border px-2 py-1">Nama</th>
                        @foreach ($absensi['tanggal'] as $tgl)
                        <th class="border px-2 py-1 text-center">
                            {{ \Carbon\Carbon::parse($tgl)->format('d') }}
                        </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @foreach ($absensi['data'] as $row)
                    <tr>
                        <td class="border px-2 py-1 text-center">{{ $loop->iteration }}</td>
                        <td class="border px-2 py-1">{{ $row->siswa->nama_siswa }}</td>

                        @foreach ($row->harian as $item)
                        <td class="border px-2 py-1 text-center text-xs">
                            <div class="font-bold
                    @if($item['kode']=='H') text-green-600
                    @elseif($item['kode']=='I') text-blue-600
                    @elseif($item['kode']=='S') text-yellow-600
                    @else text-red-600 @endif
                ">
                                {{ $item['kode'] }}
                            </div>

                            <div class="text-[10px] text-gray-500">
                                {{ $item['text'] }}
                            </div>
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

    </div>
</x-filament-panels::page>