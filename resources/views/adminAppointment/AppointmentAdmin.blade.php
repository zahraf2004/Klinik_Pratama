@extends('layouts.app')
@section('content')
<section class="section">
  <div class="section-header">
    <h1>Data Janji Berobat</h1>
  </div>
  
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4>Data Janji Berobat Pasien</h4>

          <div class="card-header-form d-flex align-items-center">
              <form class="me-2">
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Search">
                  <div class="input-group-btn">
                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                  </div>
                </div>
              </form>

              <!-- Di file blade Anda (bagian dropdown) -->
              <div class="dropdown" style="margin-left: 10px;">
                <button class="btn btn-light" type="button" id="filter-btn" data-toggle="dropdown" data-display="static">
                  <i class="fa-solid fa-filter" style="color:#6777ef;"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right p-2">
                  <label class="mb-1">Filter Data</label>
                  <select id="filter-appointment" class="form-control form-control-sm">
                    <option value="">Semua Janji</option>
                    <option value="Menunggu">Menunggu</option>
                    <option value="Disetujui">Disetujui</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Dibatalkan">Dibatalkan</option>
                  </select>
                </div>
              </div>
          </div>
        </div>
        <div class="card-body p-0">
          <!-- hanya satu table-responsive -->
          <div class="table-responsive tenaga-table-wrapper">
            <table class="table table-striped tenaga-table">
              <thead>
                <tr>
                  <th style="width: 50px;">No</th>
                  <th class="sort-nama" style="cursor: pointer;">Nama <i class="fas fa-sort"></i></th>
                  <th style="min-width: 120px;">Tanggal Lahir</th> 
                  <th style="min-width: 90px;">Handphone</th>
                  <th >Alamat</th>
                  <th style="min-width: 180px;">Keluhan</th>
                  <th>Tanggal Janji</th>
                  <th>Jam</th>
                  <th>Status</th>
                  <th style="min-width: 180px;">Catatan</th>
                  <th >Aksi</th>
                </tr>
              </thead>

              <tbody>
                @foreach ($appointments as $key => $a)
                <tr>
                  <td>{{ $key + 1 }}</td>
                  <td>{{ $a->nama }}</td>
                  <td>{{ $a->tanggal_lahir }}</td>
                  <td>{{ $a->no_hp }}</td>
                  <td class="desc-col">{{ $a->alamat }}</td>
                  <td class="desc-col">{{ $a->keluhan }}</td>
                  <td>{{ $a->tanggal }}</td>
                  <td>{{ substr($a->jam, 0, 5) }}</td>
                  <td>
                    <span class="badge 
                      @if($a->status == 'Disetujui') badge-success 
                      @elseif($a->status == 'Menunggu') badge-secondary 
                      @elseif($a->status == 'Dibatalkan') badge-danger 
                      @else badge-info @endif">
                      {{ $a->status }}
                    </span>
                  </td>
                  <td class="desc-col">{{ $a->admin_notes ?? '-' }}</td>
                  <td>
                    <a href="#" class="btn btn-primary btn-sm btnEditJanji" data-id="{{ $a->id }}">
                      <i class="far fa-edit"></i>
                    </a>
                    <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#editModal{{ $a->id }}">
                      <i class="fas fa-trash"></i>
                    </a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>      

        <div class="card-footer text-right">
          <nav class="d-inline-block">
            <ul class="pagination mb-0">
              <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1"><i class="fas fa-chevron-left"></i></a>
              </li>
              <li class="page-item active">
                <a class="page-link" href="#">1 <span class="sr-only">(current)</span></a>
              </li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item">
                <a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
          
</section>

<!-- Modal Popup -->
  @include('adminAppointment._modalformadmJanji')

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
  $(document).ready(function() {
    // Inisialisasi dropdown
    $('.dropdown-toggle').dropdown();
    
    // Pastikan dropdown filter berfungsi
    $('#filter-btn').on('click', function() {
      $(this).next('.dropdown-menu').toggleClass('show');
    });
    
    // Menerapkan filter saat nilai berubah
    $('#filter-profesi').on('change', function() {
      let profesi = $(this).val();
      loadData(profesi);
    });/
  });
</script>


<script src="{{ asset('js/data_janjiAdmin.js') }}"></script>

@endsection