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
                    <option value="Jadwal_Ulang">Jadwal Ulang</option>
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
                <tr>                     
                  <td>1</td>
                  <td class="nama-col">Ilham pratama</td>
                  <td>2018-01-20</td>                                            
                  <td>085381881683</td>
                  <td>JL mawar</td>
                  <td>Ada benjolan pada leher bagian kiri</td>
                  <td>2025-08-20</td>
                  <td>10:00</td>
                  <td><div class="badge badge-success">Disetujui</div></td>
                  <td></td>
                  <td class="text-nowrap">
                    <a href="#" class="btn btn-icon btn-primary me-1"><i class="far fa-edit"></i></a>
                    <a href="#" class="btn btn-icon btn-danger"><i class="fas fa-times"></i></a>
                  </td>
                </tr>  
                <tr>                     
                  <td>2</td>
                  <td class="nama-col">Yulia</td>
                  <td>2018-01-20</td>                                            
                  <td>085381881683</td>
                  <td>JL melati</td>
                  <td>Demam sudah 3 hari</td>
                  <td>2025-08-20</td>
                  <td>11:00</td>
                  <td><div class="badge badge-secondary">Menunggu</div></td>
                  <td></td>
                  <td class="text-nowrap">
                    <a href="#" class="btn btn-icon btn-primary me-1"><i class="far fa-edit"></i></a>
                    <a href="#" class="btn btn-icon btn-danger"><i class="fas fa-times"></i></a>
                  </td>
                </tr>  
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
  @include('adminDataNakes._modalForm')

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script src="{{ asset('js/data_janjiAdmin.js') }}"></script>
@endsection
@endsection