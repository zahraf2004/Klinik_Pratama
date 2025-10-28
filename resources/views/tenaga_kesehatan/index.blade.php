@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Tenaga Kesehatan</h3>
                    <div class="card-tools">
                        <a href="{{ route('tenaga-kesehatan.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Tenaga Kesehatan
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <form action="{{ route('tenaga-kesehatan.index') }}" method="GET" class="form-inline">
                            <div class="form-group mr-2">
                                <select name="profesi" class="form-control">
                                    <option value="">Semua Profesi</option>
                                    <option value="dokter" {{ request('profesi') == 'dokter' ? 'selected' : '' }}>Dokter</option>
                                    <option value="perawat" {{ request('profesi') == 'perawat' ? 'selected' : '' }}>Perawat</option>
                                    <option value="bidan" {{ request('profesi') == 'bidan' ? 'selected' : '' }}>Bidan</option>
                                </select>
                            </div>
                            <div class="form-group mr-2">
                                <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </form>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>Profesi</th>
                                    <th>Email</th>
                                    <th>No. HP</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($q as $index => $item)
                                <tr>
                                    <td>{{ $q->firstItem() + $index }}</td>
                                    <td>
                                        @if($item->foto_path)
                                        <img src="{{ asset('storage/' . $item->foto_path) }}" alt="Foto {{ $item->nama }}" width="50">
                                        @else
                                        <span class="badge badge-secondary">Tidak ada foto</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ ucfirst($item->profesi) }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->hp }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-info edit-btn" data-id="{{ $item->id }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <form action="{{ route('tenaga-kesehatan.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data tenaga kesehatan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $q->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Tenaga Kesehatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Form fields will be loaded dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Delete confirmation
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                this.submit();
            }
        });
        
        // Edit button click
        $('.edit-btn').on('click', function() {
            const id = $(this).data('id');
            // Load data for editing
            $.ajax({
                url: `/tenaga-kesehatan/${id}/edit`,
                method: 'GET',
                success: function(response) {
                    $('#editForm').attr('action', `/tenaga-kesehatan/${id}`);
                    $('#editModal .modal-body').html(response);
                    $('#editModal').modal('show');
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan saat memuat data');
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
@endpush