<div class="modal fade" id="modalJanji" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form id="formJanji" method="POST" action="{{ route('appointment.store') }}">
      @csrf
      <input type="hidden" id="idJanji" name="id">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Buat Janji Berobat</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label>Nama Pasien</label>
            <input type="text" name="nama" class="form-control" required>
          </div>
          <div class="form-group">
            <label>No. Telepon</label>
            <input type="tel" name="no_hp" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" required></textarea>
          </div>
          <div class="form-group">
            <label>Tanggal Janji</label>
            <input type="date" name="tanggal" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Waktu Janji</label>
            <input type="time" name="jam" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Keluhan / Catatan</label>
            <textarea name="keluhan" class="form-control" rows="3" required></textarea>
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
