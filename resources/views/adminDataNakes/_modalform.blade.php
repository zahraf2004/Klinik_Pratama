<div class="modal fade" id="modalTenaga" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form id="formTenaga" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" id="idTenaga" name="idTenaga">

      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Input Data Tenaga Kesehatan</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Foto</label>
              <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
            <div class="form-group col-md-6">
              <label>Nama</label>
              <input type="text" name="nama" class="form-control" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Tanggal Lahir</label>
              <input type="date" name="tanggal_lahir" class="form-control" required>
            </div>
            <div class="form-group col-md-6">
              <label>Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>HP</label>
              <input type="text" name="hp" class="form-control" required>
            </div>
            <div class="form-group col-md-6">
              <label>Alumnus</label>
              <input type="text" name="alumnus" class="form-control" required>
            </div>
          </div>

          <div class="form-group">
            <label>Profesi</label>
            <select name="profesi" class="form-control" required>
              <option value="dokter">Dokter</option>
              <option value="perawat">Perawat</option>
              <option value="bidan">Bidan</option>
            </select>
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
