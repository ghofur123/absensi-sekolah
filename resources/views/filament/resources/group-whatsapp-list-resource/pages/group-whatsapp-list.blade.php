<x-filament::page>

    <x-filament::card>
        <h2 class="text-lg font-bold mb-4">
            Daftar Group WhatsApp
        </h2>

        <div class="space-y-3">
            @forelse ($this->groups as $group)
            <div class="flex items-center gap-4 p-4 border rounded-xl">

                {{-- ICON GROUP --}}
                <div class="w-12 h-12">
                    @if (!empty($group['picture']))
                    <img
                        src="{{ $group['picture'] }}"
                        alt="Group Icon"
                        class="w-12 h-12 rounded-full object-cover">
                    @else
                    <div class="w-12 h-12 flex items-center justify-center rounded-full bg-gray-200">
                        <x-heroicon-o-users class="w-6 h-6 text-gray-500" />
                    </div>
                    @endif
                </div>

                {{-- INFO GROUP --}}
                <div class="flex-1">
                    <div class="font-semibold text-base">
                        {{ $group['name'] ?? '-' }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $group['id'] ?? '-' }}
                    </div>
                </div>

            </div>
            @empty
            <div class="text-gray-500">
                Tidak ada group WhatsApp terdeteksi.
            </div>
            @endforelse
        </div>

    </x-filament::card>

</x-filament::page>