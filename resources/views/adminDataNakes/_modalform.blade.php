<div class="modal fade" id="modalTenaga" tabindex="-1">
  <div class="modal-dialog modal-md">
    <form id="formTenaga" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" id="idTenaga" name="idTenaga">

      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Input Data Tenaga Kesehatan</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        
        <div class="modal-body">
          <!-- Foto -->
          <div class="form-group">
            <label>Foto</label>
            <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
            <small class="text-muted">Format: JPG, PNG (Max 2MB)</small>
          </div>

          <!-- Nama -->
          <div class="form-group">
            <label>Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="nama" id="nama" class="form-control" required>
          </div>

          <!-- Email -->
          <div class="form-group">
            <label>Email <span class="text-danger">*</span></label>
            <input type="email" name="email" id="email" class="form-control" required>
          </div>

          <!-- HP -->
          <div class="form-group">
            <label>No. Handphone <span class="text-danger">*</span></label>
            <input type="text" name="hp" id="hp" class="form-control" required placeholder="08xxxxxxxxxx">
          </div>

          <!-- STR -->
          <div class="form-group">
            <label>No. STR (Surat Tanda Registrasi)</label>
            <input type="text" name="str" id="str" class="form-control" placeholder="Contoh: 123456789012345">
          </div>

          <!-- SIP -->
          <div class="form-group">
            <label>No. SIP (Surat Izin Praktik)</label>
            <input type="text" name="sip" id="sip" class="form-control" placeholder="Contoh: 503/SIP/2024">
          </div>

          <!-- Role -->
          <div class="form-group">
            <label>Role <span class="text-danger">*</span></label>
            <select name="role" id="role" class="form-control" required>
              <option value="">-- Pilih Role --</option>
              <option value="dokter_umum">Dokter Umum</option>
              <option value="admin">Admin</option>
            </select>
          </div>

          <!-- Tahun Mulai -->
          <div class="form-group">
            <label>Tahun Mulai Praktik</label>
            <input type="number" name="tahun_mulai" id="tahun_mulai" class="form-control" min="1980" max="2099" placeholder="Contoh: 2020">
            <small class="text-muted">Opsional - Pengalaman akan dihitung otomatis dari tahun ini</small>
          </div>

          <!-- Jadwal Shift -->
          <div class="form-group">
            <label>Jadwal Shift</label>
            <small class="text-muted d-block mb-2">Opsional - Pilih hari dan jam kerja</small>
            <div id="jadwal-container">
              <div class="jadwal-item border p-3 mb-2 rounded">
                <div class="row">
                  <div class="col-12 mb-2">
                    <label class="small mb-1">Hari</label>
                    <select name="jadwal[0][hari]" class="form-control form-control-sm jadwal-hari">
                      <option value="">-- Pilih Hari --</option>
                      <option value="Senin">Senin</option>
                      <option value="Selasa">Selasa</option>
                      <option value="Rabu">Rabu</option>
                      <option value="Kamis">Kamis</option>
                      <option value="Jumat">Jumat</option>
                      <option value="Sabtu">Sabtu</option>
                      <option value="Minggu">Minggu</option>
                    </select>
                  </div>
                  <div class="col-6">
                    <label class="small mb-1">Jam Mulai</label>
                    <input type="time" name="jadwal[0][jam_mulai]" class="form-control form-control-sm">
                  </div>
                  <div class="col-6">
                    <label class="small mb-1">Jam Selesai</label>
                    <input type="time" name="jadwal[0][jam_selesai]" class="form-control form-control-sm">
                  </div>
                </div>
                <div class="row mt-2">
                  <div class="col-12">
                    <button type="button" class="btn btn-danger btn-sm btn-remove-jadwal btn-block" style="display:none;">
                      <i class="fas fa-trash"></i> Hapus
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <button type="button" class="btn btn-sm btn-info" id="btn-add-jadwal">
              <i class="fas fa-plus"></i> Tambah Hari
            </button>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>


