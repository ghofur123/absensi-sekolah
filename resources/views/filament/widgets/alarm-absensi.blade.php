<x-filament::card>
    <h2 class="font-bold text-danger mb-3">🚨 Alarm Absensi</h2>

    @forelse($jadwalBelumAbsen as $jadwal)
    <div class="text-sm text-gray-700">
        ⚠️ {{ $jadwal->kelas->nama }} - {{ $jadwal->mapel->nama }} belum diabsen
    </div>
    @empty
    <div class="text-success">✅ Semua jadwal sudah diabsen</div>
    @endforelse
</x-filament::card>