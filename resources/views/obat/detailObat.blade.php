@extends('layouts.app4')

@section('content')
<div class="obat-detail-container">

  <!-- Judul & Breadcrumb -->
  <div class="obat-detail-header" >
    <h1 class="page-title" >Obat & Vitamin Kesehatan</h1>
    <nav class="breadcrumb">
      <a href="{{ route('obat.index') }}">Dashboard</a> >
      <a href="{{ route('obat.all') }}">Lihat Semua Obat</a> >
      <span>Detail Obat</span>
    </nav>
  </div>

  <!-- Kiri: Foto Produk -->
  <div class="obat-detail-left">
    <img src="{{ asset('storage/'.$obat->foto) }}" alt="{{ $obat->nama_obat }}">
  </div>

  <!-- Tengah: Informasi Produk -->
  <div class="obat-detail-middle">
    <h1 class="obat-title">{{ $obat->nama_obat }}</h1>

    <!-- Badge Kategori dengan logo -->
    @php
        $kategori = strtolower($obat->kategori);
        $img = 'default.png'; 

        switch ($kategori) {
            case 'obat bebas': $img = 'bebas.png'; break;
            case 'obat bebas terbatas': $img = 'bebas_terbatas.png'; break;
            case 'obat herbal': $img = 'herbal.png'; break;
            case 'jamu': $img = 'jamu.png'; break;
            case 'fitofarmaka': $img = 'fitofarmaka.png'; break;
            case 'obat keras': $img = 'keras.png'; break;
            case 'narkotika': $img = 'narkotika.png'; break;
        }
    @endphp

    <div class="kategori-badge">
      <img src="{{ asset('img/'.$img) }}" alt="{{ $obat->kategori }}">
      <span>{{ $obat->kategori }}</span>
    </div>

    <p class="bentuk">Bentuk: {{ $obat->bentuk }}</p>
    <p class="bentuk">Klasifikasi: {{ $obat->klasifikasi }}</p>

    <div class="deskripsi-box">
      <h3>Deskripsi & Manfaat</h3>
      <p>{{ $obat->deskripsi }}</p>
    </div>

    <div class="dosis-box">
      <h3>Dosis & Aturan Pakai</h3>
      <p>{{ $obat->dosis ?? 'Ikuti aturan pakai sesuai petunjuk dokter.' }}</p>
    </div>
  </div>

  <!-- Kanan: Rekomendasi Produk -->
  <div class="obat-detail-right">
    <h3 style="font-weight:bold;">Rekomendasi Produk Lain</h3>
    <div class="rekomendasi-list">
      @foreach($rekomendasi as $item)
        <div class="rekomendasi-card">
          <img src="{{ asset('storage/'.$item->foto) }}" alt="{{ $item->nama_obat }}">
          <p>{{ $item->nama_obat }}</p>
          <p class="unit">{{ $item->bentuk }}</p>
          <a href="{{ route('obat.show', $item->id) }}" class="btn-rekomendasi">Lihat Obat</a>
        </div>
      @endforeach
    </div>
  </div>

</div>
@endsection
