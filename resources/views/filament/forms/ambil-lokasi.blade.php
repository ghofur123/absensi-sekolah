<button
    type="button"
    class="fi-btn fi-btn-primary"
    x-data
    x-on:click="
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    $wire.set('data.latitude', pos.coords.latitude);
                    $wire.set('data.longitude', pos.coords.longitude);
                },
                () => alert('Gagal mengambil lokasi')
            );
        } else {
            alert('Browser tidak mendukung GPS');
        }
    ">
    📍 Ambil Lokasi Sekarang
</button>