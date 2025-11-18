@extends('layouts.dokter')
@section('content')
<section class="section">  
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4>Data Janji Berobat Pasien</h4>
          
        </div>
        <div class="card-body p-0">
          <!-- hanya satu table-responsive -->
          <div class="table-responsive tenaga-table-wrapper">
            <table class="table table-striped tenaga-table">
              <thead>
                <tr>
                  <th style="width: 50px;">No</th>
                  <th class="sort-nama" style="cursor: pointer;">Nama <i class="fas fa-sort"></i></th>
                  <th style="min-width: 80px;">Tanggal Janji</th>
                  <th>Jam</th>
                  <th style="min-width: 180px;">Keluhan</th>                  
                  <th>Status</th>
                </tr>
              </thead>

              <tbody>
                @foreach ($appointments as $key => $a)
                <tr>
                  <td>{{ $key + 1 }}</td>
                  <td>{{ $a->nama }}</td>                  
                  <td style="min-width: 80px;">{{ $a->tanggal }}</td>
                  <td style="min-width: 50px;">{{ substr($a->jam, 0, 5) }}</td>
                  <td class="desc-col">{{ $a->keluhan }}</td>
                  <td>
                    <span class="badge 
                      @if($a->status == 'Disetujui') badge-success 
                      @elseif($a->status == 'Menunggu') badge-secondary 
                      @elseif($a->status == 'Dibatalkan') badge-danger 
                      @else badge-info @endif">
                      {{ $a->status }}
                    </span>
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

@endsection