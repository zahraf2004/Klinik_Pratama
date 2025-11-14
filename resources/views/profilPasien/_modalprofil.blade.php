<div class="modal fade" id="modalProfil" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="formProfil" method="POST" enctype="multipart/form-data" data-action="{{ route('pasien.profil.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" id="idProfil" name="id">

            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Edit Profil Pasien</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                
                <div class="modal-body">
                    <!-- Foto Profil -->
                    <div class="form-group text-center">
                        <div class="avatar-preview mb-3">
                            @if(isset($profilPasien) && $profilPasien->foto)
                                <img src="{{ asset('storage/' . $profilPasien->foto) }}" alt="Foto Profil" id="avatarPreview" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <div id="avatarText" class="rounded-circle d-inline-flex align-items-center justify-content-center bg-secondary text-white" style="width: 120px; height: 120px; font-size: 2rem;">
                                    {{ substr(Auth::user()->name, 0, 2) }}
                                </div>
                            @endif
                        </div>
                        <input type="file" name="foto" id="fotoInput" accept="image/*" class="d-none">
                        <label for="fotoInput" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-camera"></i> Ganti Foto
                        </label>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">Nama Lengkap *</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ Auth::user()->name }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ Auth::user()->email }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="no_hp">No. Telepon</label>
                            <input type="text" id="no_hp" name="no_hp" class="form-control" value="{{ $profilPasien->no_hp ?? '' }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control" value="{{ $profilPasien->tanggal_lahir ?? '' }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin" class="form-control">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ (isset($profilPasien) && $profilPasien->jenis_kelamin == 'Laki-laki') ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ (isset($profilPasien) && $profilPasien->jenis_kelamin == 'Perempuan') ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="golongan_darah">Golongan Darah</label>
                            <select id="golongan_darah" name="golongan_darah" class="form-control">
                                <option value="">Pilih Golongan Darah</option>
                                <option value="A" {{ (isset($profilPasien) && $profilPasien->golongan_darah == 'A') ? 'selected' : '' }}>A</option>
                                <option value="B" {{ (isset($profilPasien) && $profilPasien->golongan_darah == 'B') ? 'selected' : '' }}>B</option>
                                <option value="AB" {{ (isset($profilPasien) && $profilPasien->golongan_darah == 'AB') ? 'selected' : '' }}>AB</option>
                                <option value="O" {{ (isset($profilPasien) && $profilPasien->golongan_darah == 'O') ? 'selected' : '' }}>O</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="berat_badan">Berat Badan (kg)</label>
                            <input type="number" id="berat_badan" name="berat_badan" step="0.1" class="form-control" value="{{ $profilPasien->berat_badan ?? '' }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tinggi_badan">Tinggi Badan (cm)</label>
                            <input type="number" id="tinggi_badan" name="tinggi_badan" step="0.1" class="form-control" value="{{ $profilPasien->tinggi_badan ?? '' }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea id="alamat" name="alamat" class="form-control" rows="3">{{ $profilPasien->alamat ?? '' }}</textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.avatar-preview {
    margin: 0 auto;
    width: 120px;
    height: 120px;
}

.avatar-preview img,
.avatar-preview div {
    border: 3px solid #3498db;
}
</style>