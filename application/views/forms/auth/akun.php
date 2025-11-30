  <div class="mb-3">
      <label for="name">Nama Lengkap</label>
      <input type="text" class="form-control" id="name" name="name">
  </div>
  <div class="mb-3">
      <label for="name">NIK</label>
      <input type="text" class="form-control" id="status" name="nik">
  </div>

  <div class="mb-3">
      <label for="email">Phone</label>
      <input type="text" class="form-control" id="phone" name="phone">
  </div>
  <div class="mb-3">
      <label for="status">Status Pernikahan</label>
      <select type="text" class="form-control" id="status" name="status"
          required>
          <option value="Kawin Nol Anak">Kawin Nol Anak</option>
          <option value="Kawin 1 Anak">Kawin 1 Anak</option>
          <option value="Kawin 2 Anak">Kawin 2 Anak</option>
          <option value="Kawin 3 Anak">Kawin 3 Anak</option>
          <option value="Tidak Kawin Nol Anak">Tidak Kawin Nol Anak</option>
          <option value="Tidak Kawin 1 Anak">Tidak Kawin 1 Anak</option>
          <option value="Tidak Kawin 2 Anak">Tidak Kawin 2 Anak</option>
          <option value="Tidak Kawin 3 Anak">Tidak Kawin 3 Anak</option>
      </select>
  </div>
  <div class="mb-3">
      <label for="email">Email</label>
      <input type="email" class="form-control" id="email" name="email">
  </div>
  <div class="mb-3">
      <label for="identification_card" class="form-label">Foto KTP</label>
      <input type="file"
          class="form-control"
          id="identification_card"
          name="identification_card"
          accept=".jpg,.jpeg,.png"
          required>
      <span class="form-text text-muted">
          Harus berupa gambar (format: JPG, JPEG, atau PNG) & Maks 2 MB.
      </span>
  </div>