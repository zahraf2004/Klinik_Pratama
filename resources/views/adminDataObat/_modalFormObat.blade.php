<div class="modal fade" id="modalObat" tabindex="-1">
  <div class="modal-dialog modal-lg"> <!-- modal-lg biar agak lebar -->
    <form id="formObat">
        @csrf
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Input Data Informasi Obat</h5>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            
            <div class="modal-body">
                <input type="hidden" id="idObat">

                <div class="form-group">
                    <label>Foto Obat</label>
                    <input type="file" id="foto" name="foto" class="form-control" accept="image/*">
                    <small class="text-muted">Format: JPG, PNG (Max 2MB)</small>
                </div>
                
                <div class="form-group">
                    <label>Nama Obat <span class="text-danger">*</span></label>
                    <input type="text" id="nama_obat" name="nama_obat" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Kategori <span class="text-danger">*</span></label>
                    <select id="kategori" name="kategori" class="form-control" required>
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
                
                <div class="form-group">
                    <label>Bentuk <span class="text-danger">*</span></label>
                    <select id="bentuk" name="bentuk" class="form-control" required>
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

                <div class="form-group">
                    <label>Klasifikasi <span class="text-danger">*</span></label>
                    <input type="text" name="klasifikasi" class="form-control" required placeholder="Contoh: Analgesik, Antibiotik, dll">
                </div>
                
                <div class="form-group">
                    <label>Deskripsi <span class="text-danger">*</span></label>
                    <textarea class="form-text" name="deskripsi" id="deskripsi" required placeholder="Masukkan deskripsi obat..."></textarea>
                </div>
                
                <div class="form-group">
                    <label>Dosis & Aturan Pakai <span class="text-danger">*</span></label>
                    <textarea class="form-text" name="dosis" id="dosis" required placeholder="Contoh: 3x sehari 1 tablet setelah makan"></textarea>
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