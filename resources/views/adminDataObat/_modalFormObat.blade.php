<div class="modal fade" id="modalObat" tabindex="-1">
  <div class="modal-dialog modal-lg"> <!-- modal-lg biar agak lebar -->
    <form id="formObat">
        @csrf
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" style="margin-bottom:15px;">Input Data Informasi Obat</h5>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            
            <div class="modal-body">
                <input type="hidden" id="idObat">

                <div class="form-row">
                    <div class="form-group col-md-6">
                    <label>Foto</label>
                    <input  width="150" height="50" type="file" id="foto" name="foto" class="form-control" accept="image/*">
                    </div>
                    <div class="form-group col-md-6">
                    <label>Nama Obat</label>
                    <input type="text" id="nama_obat" name="nama_obat" class="form-control" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Kategori</label>
                        <select id="kategori" name="kategori" class="form-control">
                            <option value="">-- Pilih kategori --</option>
                            <option value="Obat Bebas">Obat Bebas</option>
                            <option value="Obat Bebas Terbatas">Obat Bebas Terbatas</option>
                            <option value="Obat Herbal">Obat Herbal</option>
                            <option value="Jamu">Jamu</option>
                            <option value="Fitofarmaka">Fitofarmaka</option>
                            <option value="Obat keras">Obat Keras</option>
                            <option value="Narkotika">Narkotika</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Bentuk</label>
                        <select id="bentuk" name="bentuk" class="form-control">
                            <option value="">-- Pilih bentuk --</option>
                            <option value="Cair">Cair</option>
                            <option value="Tablet">Tablet</option>
                            <option value="Kapsul">Kapsul</option>
                            <option value="Puyer">Puyer</option>
                            <option value="Supositoria">Supositoria</option>
                            <option value="Salep/krim/gel">Salep/krim/gel</option>
                            <option value="Aerosol">Aerosol</option>
                            <option value="Inhalasi">Inhalasi</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">                    
                    <div class="form-group col-md-6">
                        <label>Klasifikasi</label>
                        <input type="text" name="klasifikasi" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                    <label>Deskripsi</label>
                    <textarea class="form-text" style="padding: 15px;" name="deskripsi" id="deskripsi" required></textarea>
                </div>
                <div class="form-group col-md-6">
                    <label>Dosis & Aturan Pakai</label>
                    <textarea class="form-text" style="padding: 15px;" name="dosis" id="dosis" required></textarea>
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