@extends('layouts.app')
@section('content')
<section class="section">
  <div class="section-header">
    <h1>Data Obat</h1>
  </div>
          
  <div class="buttons mb-3">
    <a href="javascript:void(0)" class="btn btn-primary" id="btnTambah">+ Tambah Data</a>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4>Data Informasi Obat</h4>

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
                  <label class="mb-1">Filter Obat</label>
                  <select id="filter-profesi" class="form-control form-control-sm">
                    <option value="">Semua Obat</option>
                    <option value="bebas">Obat Bebas</option>
                    <option value="Herbal">Herbal</option>
                    <option value="keras">Obat Keras</option>
                    <option value="jamu">Jamu</option>
                    <option value="Fitofarmaka">Fitofarmaka</option>
                    <option value="Narkotika">Narkotika</option>
                  </select>
                </div>
              </div>
          </div>
        </div>
        <div class="card-body p-0">
          <!-- hanya satu table-responsive -->
          <div class="table-responsive obat-table-wrapper">
            <table class="table table-striped obat-table">
              <thead>
                <tr>
                  <th style="width: 50px;">No</th>
                  <th style="width: 70px;">Foto</th>
                  <th class="sort-nama" style="cursor: pointer;">Nama Obat <i class="fas fa-sort"></i></th>
                  <th >Kategori</th>
                  <th >Bentuk</th>   
                  <th >Kode</th>                  
                  <th >Klasifikasi</th>
                  <th >Deskripsi</th>
                  <th >Dosis & Aturan Pakai</th>
                  <th >Aksi</th>
                </tr>
              </thead>

              <tbody>
                
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

<!-- bagian modal-->
@include('adminDataObat._modalFormObat')

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
    $('#filter-obat').on('change', function() {
      let obat = $(this).val();
      loadData(obat);
    });
  });
</script>



<script src="{{ asset('js/data_obat.js') }}"></script>
<script src="{{ asset('js/obat.js') }}"></script>
@endsection