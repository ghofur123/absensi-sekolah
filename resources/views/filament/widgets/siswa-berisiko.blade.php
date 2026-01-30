<x-filament::card>
    <h2 class="font-bold text-danger mb-3">⚠️ Siswa Berisiko</h2>

    @forelse($siswa as $item)
    <div class="text-sm">
        {{ $item->nama }} ({{ $item->kelas->nama }})
    </div>
    @empty
    <div class="text-success">✅ Tidak ada siswa berisiko</div>
    @endforelse
</x-filament::card>