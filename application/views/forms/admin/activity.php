<div class="mb-3">
    <label for="meeting" class="form-label">Meeting</label>
    <input type="number" class="form-control" id="meeting" name="meeting" required>
</div>
<div class="mb-3">
    <label for="startDate" class="form-label">Tanggal Dibuka</label>
    <input type="date" class="form-control" id="startDate" name="startDate" required>
</div>
<div class="mb-3">
    <label for="endDate" class="form-label">Tanggal Ditutup</label>
    <input type="date" class="form-control" id="endDate" name="endDate" required>
</div>
<div class="mb-3">
    <label for="description" class="form-label">Deskripsi</label>
    <div id="<?= isset($mode) && $mode === 'edit' ? 'editDescription' : 'createDescription' ?>"></div>
</div>