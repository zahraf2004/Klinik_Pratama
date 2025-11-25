<section class="doctors" id="doctors">
  <div class="container">
    <h2 class="section-title1">Dokter Kami</h2>
    <p class="section-subtitle2">Tim tenaga profesional yang siap memberikan pelayanan kesehatan terbaik untuk Anda</p>

    <div class="doctor-grid">
      @foreach($dokters as $d)
        <div class="doctor-card">
          @if($d->foto_path)
            <img src="{{ asset('storage/'.$d->foto_path) }}" alt="{{ $d->nama }}">
          @else
            <img src="{{ asset('assets/img/avatar/avatar-1.png') }}" alt="{{ $d->nama }}">
          @endif

          <h3>{{ $d->nama }}</h3>
          <span class="role">
            @if($d->role === 'dokter_umum')
              Dokter Umum
            @else
              Dokter
            @endif
          </span>
        </div>
      @endforeach
    </div>
  </div>
</section>
