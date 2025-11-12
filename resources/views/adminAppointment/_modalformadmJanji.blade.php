<div class="modal fade" id="modalJanji" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form id="formJanji" method="POST">
      @csrf
      <input type="hidden" id="idJanji" name="id">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Detail Janji Pasien</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label>Nama Pasien</label>
            <input type="text" name="nama" id="nama" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>No. Telepon</label>
            <input type="tel" name="no_hp" id="no_hp" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>Alamat Rumah</label>
            <textarea name="alamat" id="alamat" class="form-control" readonly></textarea>
          </div>
          <div class="form-group">
            <label>Tanggal Janji</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>Waktu Janji</label>
            <input type="time" name="jam" id="jam" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>Keluhan</label>
            <textarea name="keluhan" id="keluhan" class="form-control" rows="3" readonly></textarea>
          </div>

          <hr>

          <div class="form-group">
            <label>Status Janji</label>
            <select name="status" id="status" class="form-control" required>
              <option value="Menunggu">Menunggu</option>
              <option value="Disetujui">Disetujui</option>
              <option value="Selesai">Selesai</option>
              <option value="Dibatalkan">Dibatalkan</option>
            </select>
          </div>

          <div class="form-group" id="catatan-group" style="display: none;">
            <label>Catatan Admin (Alasan Pembatalan)</label>
            <textarea name="admin_notes" id="admin_notes" class="form-control" rows="3"></textarea>
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
