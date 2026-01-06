<section class="product-section">
    <h2 style="margin-top:75px;">Obat & Suplemen Kesehatan</h2>

    <!-- Kategori --> 
     <div class="categories"> 
        <button class="active">Obat Bebas</button> 
        <button>Obat Bebas terbatas</button> <button>obat Keras</button> 
        <button>Jamu</button> 
        <a href="{{ route('obat.all') }}" class="see-all">Lihat Semua Kategori Obat</a> </div>

    <!-- Grid Produk -->
    <div class="product-grid">
        @foreach($obat as $item)
            <div class="product-card">
                <img src="{{ asset('storage/'.$item->foto) }}" alt="{{ $item->nama_obat }}">
                <h3>{{ $item->nama_obat }}</h3>
                <p class="unit">{{ $item->bentuk }}</p>
                <a href="{{ route('obat.show', $item->id) }}">
                    <button>Lihat Informasi Obat</button>
                </a>
            </div>
        @endforeach
    </div>
</section>
