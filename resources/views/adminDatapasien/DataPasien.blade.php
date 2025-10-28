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
          <h4>Data Pasien Yang Terdaftar Sistem</h4>

          <div class="card-header-form d-flex align-items-center">
              <form class="me-2">
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Search">
                  <div class="input-group-btn">
                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                  </div>
                </div>
              </form>

              <div class="dropdown" style="margin-left: 10px;">
                <button class="btn btn-light" type="button" id="filter-btn" data-toggle="dropdown" data-display="static">
                  <i class="fa-solid fa-filter" style="color:#6777ef;"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right p-2">
                  <label class="mb-1">Filter Status</label>
                  <select id="filter-profesi" class="form-control form-control-sm">
                    <option value="">Semua Status</option>
                    <option value="Reguler">Reguler</option>
                    <option value="Langganan">Langganan</option>
                  </select>
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
                <tr>
                  <td>1</td>
                  <td>Andi Pratama</td>
                  <td>andi@mail.com</td>
                  <td><span class="badge badge-primary">Langganan</span></td>
                  <td>2025-12-31</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>Siti Aminah</td>
                  <td>siti@mail.com</td>
                  <td><span class="badge badge-success">Reguler</span></td>
                  <td>-</td>
                </tr>
                <tr>
                  <td>3</td>
                  <td>Budi Santoso</td>
                  <td>budi@mail.com</td>
                  <td><span class="badge badge-primary">Langganan</span></td>
                  <td>2026-03-15</td>
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
@endsection
