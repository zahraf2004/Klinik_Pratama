@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/modal-form-fix.css') }}">
@endpush

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
              <div class="input-group me-2">
                <input type="text" id="search-obat" class="form-control" placeholder="Cari nama obat...">
                <div class="input-group-btn">
                  <button class="btn btn-primary" type="button" id="btn-search-obat">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>

              <div class="dropdown" style="margin-left: 10px;">
                <button class="btn btn-light" type="button" id="filter-btn" data-toggle="dropdown" data-display="static">
                  <i class="fa-solid fa-filter" style="color:#6777ef;"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right p-2">
                  <label class="mb-1">Filter Kategori</label>
                  <select id="filter-kategori" class="form-control form-control-sm">
                    <option value="">Semua Kategori</option>
                    <option value="Obat Bebas">Obat Bebas</option>
                    <option value="Obat Bebas Terbatas">Obat Bebas Terbatas</option>
                    <option value="Obat Herbal">Obat Herbal</option>
                    <option value="Jamu">Jamu</option>
                    <option value="Fitofarmaka">Fitofarmaka</option>
                    <option value="Obat keras">Obat Keras</option>
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
    
    // Search functionality
    $('#search-obat').on('keyup', function() {
      performSearch();
    });
    
    $('#btn-search-obat').on('click', function() {
      performSearch();
    });
    
    // Filter functionality
    $('#filter-kategori').on('change', function() {
      performSearch();
    });
    
    // Function to perform search and filter
    function performSearch() {
      let searchTerm = $('#search-obat').val().toLowerCase();
      let filterKategori = $('#filter-kategori').val();
      
      $('.obat-table tbody tr').each(function() {
        let row = $(this);
        let namaObat = row.find('td:nth-child(3)').text().toLowerCase(); // Kolom nama obat
        let kategori = row.find('td:nth-child(4)').text(); // Kolom kategori
        
        let matchSearch = namaObat.includes(searchTerm);
        let matchFilter = filterKategori === '' || kategori === filterKategori;
        
        if (matchSearch && matchFilter) {
          row.show();
        } else {
          row.hide();
        }
      });
      
      // Update nomor urut setelah filter
      updateRowNumbers();
    }
    
    // Function to update row numbers
    function updateRowNumbers() {
      let visibleRows = $('.obat-table tbody tr:visible');
      visibleRows.each(function(index) {
        $(this).find('td:first-child').text(index + 1);
      });
    }
    
    // Reset search when input is cleared
    $('#search-obat').on('input', function() {
      if ($(this).val() === '') {
        performSearch();
      }
    });
  });
</script>



<script src="{{ asset('js/search-filter-obat.js') }}"></script>
<script src="{{ asset('js/data_obat.js') }}"></script>
<script src="{{ asset('js/obat.js') }}"></script>
@endsection