<div class="row">
    <div class="col-lg-8 col-md-12 col-12 col-sm-12">
        <div class="card">
            <div class="card-header">
            <h4>Tenaga Kesehatan Terbaru</h4>
            <div class="card-header-action">
                <a href="{{ url('/admin/data-nakes') }}" class="btn btn-primary">Lihat Semua</a>
            </div>
            </div>
            <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                <thead>
                    <tr>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Profesi</th>
                    <th>Email</th>
                    <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($listNakes as $nakes)
                    <tr>
                        <td width="60">
                        <img src="{{ $nakes->foto_path ? asset('storage/'.$nakes->foto_path) : asset('assets/img/avatar/avatar-1.png') }}" 
                            alt="Foto {{ $nakes->nama }}" 
                            class="rounded-circle" 
                            width="40" height="40">
                        </td>
                        <td>
                        {{ $nakes->nama }}
                        </td>
                        <td>
                        {{ $nakes->email }}
                        </td>
                        <td>
                        {{ $nakes->role == 'dokter_umum' ? 'Dokter' : ucfirst($nakes->role) }}
                        </td>                        
                        <td>
                        <a href="{{ url('/admin/data-nakes/'.$nakes->id) }}" 
                            class="btn btn-info btn-sm btn-action" 
                            data-toggle="tooltip" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada tenaga kesehatan.</td>
                    </tr>
                    @endforelse
                </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>


    <div class="col-lg-4 col-md-12 col-12 col-sm-12">
        <div class="card">
            <div class="card-header">
            <h4>Log Aktivitas</h4>
            </div>
            <div class="card-body">
            <ul class="list-unstyled list-unstyled-border mb-0">
                @forelse($logAktivitas as $log)
                <li class="media">
                <img class="mr-3 rounded-circle" width="50" src="assets/img/avatar/avatar-1.png" alt="avatar">
                <div class="media-body">
                    <div class="float-right text-primary">{{ $log->created_at->diffForHumans() }}</div>
                    <div class="media-title">{{ $log->user ? $log->user->name : 'Admin' }}</div>
                    <span class="text-small text-muted">{!! $log->description !!}</span>
                </div>
                </li>
                @empty
                <li class="media">
                <div class="media-body text-center">
                    <div class="text-muted">Belum ada aktivitas</div>
                </div>
                </li>
                @endforelse
            </ul>
            </div>
        </div>
        </div>

</div>
