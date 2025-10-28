<div class="form-group">
    <label for="nama">Nama Lengkap</label>
    <input type="text" class="form-control" id="nama" name="nama" value="{{ $tenagaKesehatan->nama }}" required>
</div>

<div class="form-group">
    <label for="profesi">Profesi</label>
    <select class="form-control" id="profesi" name="profesi" required>
        <option value="dokter" {{ $tenagaKesehatan->profesi == 'dokter' ? 'selected' : '' }}>Dokter</option>
        <option value="perawat" {{ $tenagaKesehatan->profesi == 'perawat' ? 'selected' : '' }}>Perawat</option>
        <option value="bidan" {{ $tenagaKesehatan->profesi == 'bidan' ? 'selected' : '' }}>Bidan</option>
    </select>
</div>

<div class="form-group">
    <label for="email">Email</label>
    <input type="email" class="form-control" id="email" name="email" value="{{ $tenagaKesehatan->email }}" required>
</div>

<div class="form-group">
    <label for="hp">Nomor HP</label>
    <input type="text" class="form-control" id="hp" name="hp" value="{{ $tenagaKesehatan->hp }}" required>
</div>



<div class="form-group">
    <label for="foto">Foto (Opsional)</label>
    <input type="file" class="form-control-file" id="foto" name="foto">
    @if($tenagaKesehatan->foto_path)
    <div class="mt-2">
        <img src="{{ asset('storage/' . $tenagaKesehatan->foto_path) }}" alt="Foto saat ini" width="100">
        <p class="text-muted">Foto saat ini. Upload foto baru untuk mengganti.</p>
    </div>
    @endif
</div>