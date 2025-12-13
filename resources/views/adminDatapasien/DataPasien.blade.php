@extends('layouts.app')
@section('content')
<section class="section">
  <div class="section-header">
    <h1>Data Pasien</h1>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4>Data Pasien Yang Terdaftar Sistem ({{ $pasiens->total() }} pasien)</h4>

          <div class="card-header-form d-flex align-items-center">
              <form class="me-2" method="GET">
                <div class="input-group">
                  <input type="text" name="search" class="form-control" placeholder="Cari nama atau email..." value="{{ request('search') }}">
                  <div class="input-group-btn">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                  </div>
                </div>
              </form>

              <div class="dropdown" style="margin-left: 10px;">
                <button class="btn btn-light" type="button" id="filter-btn" data-toggle="dropdown" data-display="static">
                  <i class="fa-solid fa-filter" style="color:#6777ef;"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right p-2">
                  <form method="GET">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <label class="mb-1">Filter Status</label>
                    <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                      <option value="">Semua Status</option>
                      <option value="reguler" {{ request('status') == 'reguler' ? 'selected' : '' }}>Reguler</option>
                      <option value="langganan" {{ request('status') == 'langganan' ? 'selected' : '' }}>Langganan</option>
                    </select>
                  </form>
                  @if(request('status') || request('search'))
                  <hr class="my-2">
                  <button type="button" class="btn btn-sm btn-secondary w-100" onclick="clearFilters()">
                    <i class="fas fa-times"></i> Clear Filter
                  </button>
                  @endif
                </div>
              </div>
          </div>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive tenaga-table-wrapper">
            <table class="table table-striped tenaga-table">
              <thead>
                <tr>
                  <th style="width: 50px;">No</th>
                  <th>Nama</th>
                  <th style="min-width: 150px;">Email</th>   
                  <th style="min-width: 100px;">Status</th>
                  <th style="min-width: 150px;">Periode Langganan</th>
                </tr>
              </thead>
              <tbody>
                @forelse($pasiens as $index => $pasien)
                  @php
                    $hasActiveSubscription = $pasien->subscriptions->isNotEmpty();
                    $activeSubscription = $hasActiveSubscription ? $pasien->subscriptions->first() : null;
                  @endphp
                  <tr>
                    <td>{{ ($pasiens->currentPage() - 1) * $pasiens->perPage() + $index + 1 }}</td>
                    <td>{{ $pasien->name }}</td>
                    <td>{{ $pasien->email }}</td>
                    <td>
                      @if($hasActiveSubscription)
                        <span class="badge badge-primary">Langganan</span>
                      @else
                        <span class="badge badge-success">Reguler</span>
                      @endif
                    </td>
                    <td>
                      @if($hasActiveSubscription && $activeSubscription)
                        {{ $activeSubscription->expires_at->format('d-m-Y') }}
                      @else
                        -
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center">Tidak ada data pasien</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>      

        @if($pasiens->hasPages())
        <div class="card-footer text-right">
          <nav class="d-inline-block">
            {{ $pasiens->appends(request()->query())->links() }}
          </nav>
        </div>
        @endif
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
// Clear filter functionality
function clearFilters() {
    window.location.href = "{{ route('data.pasien') }}";
}

// Auto submit search on enter
document.querySelector('input[name="search"]').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        this.form.submit();
    }
});
</script>
@endpush
