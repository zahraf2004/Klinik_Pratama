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
            <img src="{{ asset('img/default-doctor.jpg') }}" alt="{{ $d->nama }}">
          @endif

          <h3>{{ $d->nama }}</h3>
          <span class="role">{{ $d->profesi ?? 'Dokter' }}</span>

          <div class="social-icons">
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin"></i></a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
