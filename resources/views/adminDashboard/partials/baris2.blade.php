<div class="row">
    <div class="col-lg-8 col-md-12 col-12 col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4>Statistik Janji Berobat Selesai</h4>
        </div>
        <div class="card-body">
          <canvas id="janjiChart" height="120"></canvas>
        </div>
      </div>
    </div>


    <!-- Janji Berobat Terbaru -->
    <div class="col-lg-4 col-md-12 col-12 col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4>Janji Berobat Terbaru</h4>
        </div>
        <div class="card-body">
          <ul class="list-unstyled p-0 list-unstyled-border">
            @forelse($janjiTerbaru as $janji)
            <li class="media">
              <img class="mr-3 rounded-circle" width="50" src="assets/img/avatar/avatar-1.png" alt="avatar">
              <div class="media-body">
                <div class="float-right text-primary">{{ $janji->created_at->diffForHumans() }}</div>
                <div class="media-title">{{ $janji->nama }}</div>
                <span class="text-small text-muted">Keluhan: {{ $janji->keluhan }}</span>
              </div>
            </li>
            @empty
            <li class="media">
              <div class="media-body text-center">
                <div class="text-muted">Belum ada janji berobat</div>
              </div>
            </li>
            @endforelse
          </ul>

          <div class="text-center pt-1 pb-1">
            <a href="{{ route('appointment.admin') }}" class="btn btn-primary btn-lg btn-round">
              Lihat Semua
            </a>
          </div>
        </div>
      </div>
    </div>
</div>
