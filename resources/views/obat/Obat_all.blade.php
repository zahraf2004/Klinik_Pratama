@extends('layouts.app3')

@section('content')
<div class="obat-container" style="margin-top: 100px;">
  <!-- Judul & Breadcrumb -->
  <div class="obat-detail-header1">
    <h1 class="page-title1">Obat & Vitamin Kesehatan</h1>
    <nav class="breadcrumb1">
      <a href="{{ route('obat.index') }}">Dashboard</a> >
      <span>Lihat Semua Obat</span>
    </nav>
  </div>

  <!-- Search + Filter -->
  <div class="obat-search-filter">
    <form action="{{ route('obat.all') }}" method="GET" class="obat-search-form">
      <input type="text" name="q" placeholder="Cari obat atau suplemen..." value="{{ request('q') }}">
      <button type="submit">Cari</button>
    </form>

    <div class="obat-category-filter">
      <a href="{{ route('obat.all') }}" class="{{ request('kategori')=='' ? 'active' : '' }}">Semua</a>
      <a href="{{ route('obat.all', ['kategori'=>'Obat Bebas']) }}" class="{{ request('kategori')=='Obat Bebas' ? 'active' : '' }}">Obat Bebas</a>
      <a href="{{ route('obat.all', ['kategori'=>'Obat Bebas Terbatas']) }}" class="{{ request('kategori')=='Obat Bebas Terbatas' ? 'active' : '' }}">Obat Bebas Terbatas</a>
      <a href="{{ route('obat.all', ['kategori'=>'Obat Keras']) }}" class="{{ request('kategori')=='Obat Keras' ? 'active' : '' }}">Obat Keras</a>
      <a href="{{ route('obat.all', ['kategori'=>'Jamu']) }}" class="{{ request('kategori')=='Jamu' ? 'active' : '' }}">Jamu</a>
    </div>
  </div>

  <!-- Grid Produk -->
  <div class="obat-grid">
    @forelse($obat as $item)
      <div class="obat-card">
        <img src="{{ asset('storage/'.$item->foto) }}" alt="{{ $item->nama_obat }}">
        <h3>{{ $item->nama_obat }}</h3>
        <p class="obat-unit">{{ $item->bentuk }}</p>
        <a href="{{ route('obat.show', $item->id) }}">
          <button class="obat-btn">Lihat Informasi Obat</button>
        </a>
      </div>
    @empty
      <p class="obat-empty">Tidak ada obat ditemukan</p>
    @endforelse
  </div>

  <!-- Pagination -->
  <div class="obat-pagination">
    {{ $obat->withQueryString()->links() }}
  </div>

</div>
@endsection
