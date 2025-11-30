<?php

/** @var stdClass[] $groups */ ?>
<div class="container mt-4">
    <h2 class="">Daftar Kelompok</h2>


    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th style="width:160px;">Nama Kelompok</th>
                        <th>Anggota</th>
                        <th style="width:220px;">Ketua</th>
                        <th style="width:140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($groups as $g): ?>
                        <tr id="row-<?= htmlspecialchars($g->id) ?>">
                            <td class="fw-semibold"><?= htmlspecialchars($g->name) ?></td>

                            <td>
                                <?php
                                // members sudah berbentuk string "A, B, C"
                                // tampilkan sebagai list rapi
                                $names = array_filter(array_map('trim', explode(',', $g->members ?? '')));
                                ?>
                                <?php if (!empty($names)): ?>
                                    <ul class="mb-0 ps-3">
                                        <?php foreach ($names as $nm): ?>
                                            <li><?= htmlspecialchars($nm) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <span class="text-muted">Belum ada anggota</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php
                                // kalau kamu punya kolom leader_name/leader_id, kirim dari controller
                                $leaderName = isset($g->leader_name) ? $g->leader_name : '-';
                                $leaderId   = isset($g->leader_id) ? $g->leader_id : '';
                                ?>
                                <span id="leader-name-<?= htmlspecialchars($g->id) ?>">
                                    <?= htmlspecialchars($leaderName) ?>
                                </span>
                            </td>

                            <td>
                                <button
                                    type="button"
                                    class="btn btn-outline-primary btn-sm edit-leader"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editLeaderModal"
                                    data-group-id="<?= htmlspecialchars($g->id) ?>"
                                    data-leader-id="<?= htmlspecialchars($leaderId) ?>">
                                    <i class="bi bi-pencil-square me-1"></i>Edit Ketua
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal Edit Ketua -->
<div class="modal fade" id="editLeaderModal" tabindex="-1" aria-labelledby="editLeaderLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="leaderForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLeaderLabel">Ubah Ketua Kelompok</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="group_id" id="group_id">
                <div class="mb-3">
                    <label for="leaderSelect" class="form-label fw-bold">Pilih Ketua</label>
                    <select id="leaderSelect" name="leader_id" class="form-select" required>
                        <!-- opsi diisi via fetch saat modal dibuka -->
                    </select>
                    <div class="form-text">Hanya anggota kelompok yang bisa dipilih sebagai ketua.</div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" id="btnSaveLeader" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<style>
    .table thead th {
        text-transform: uppercase;
        letter-spacing: .4px;
        font-size: .85rem;
    }

    .badge.bg-success {
        font-weight: 600;
    }
</style>

<script>
    (() => {
        const modalEl = document.getElementById('editLeaderModal');
        const form = document.getElementById('leaderForm');
        const select = document.getElementById('leaderSelect');
        const groupInp = document.getElementById('group_id');
        const btnSave = document.getElementById('btnSaveLeader');

        document.addEventListener('click', async (e) => {
            const btn = e.target.closest('.edit-leader');
            if (!btn) return;

            const gid = btn.getAttribute('data-group-id');
            let currentLeaderId = btn.getAttribute('data-leader-id') || '';

            groupInp.value = gid;
            select.innerHTML = '<option value="" disabled>Memuat anggota...</option>';
            select.disabled = true;

            try {
                const res = await fetch('<?= base_url("dosen/kelompok/member/"); ?>' + gid);
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Gagal memuat anggota.');

                const members = (data && data.data) ? data.data : [];
                select.innerHTML = '';

                if (!members.length) {
                    const opt = document.createElement('option');
                    opt.value = '';
                    opt.textContent = 'Belum ada anggota';
                    select.appendChild(opt);
                    select.disabled = true;
                    return;
                }

                // Jika belum ada currentLeaderId dari tombol, cari dari data.leader==1
                if (!currentLeaderId) {
                    const cur = members.find(m => Number(m.leader) === 1);
                    if (cur) currentLeaderId = String(cur.id);
                }

                for (const m of members) {
                    const opt = document.createElement('option');
                    opt.value = m.id;
                    opt.textContent = Number(m.leader) === 1 ? `${m.name} (ketua saat ini)` : m.name;
                    if (String(m.id) === String(currentLeaderId)) opt.selected = true;
                    select.appendChild(opt);
                }
                select.disabled = false;

            } catch (err) {
                select.innerHTML = '<option value="">Gagal memuat anggota</option>';
                select.disabled = true;
                if (window.Swal) Swal.fire('Gagal', err.message || 'Tidak bisa memuat anggota.', 'error');
                else alert(err.message || 'Tidak bisa memuat anggota.');
            }
        });

        // submit perubahan ketua
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            btnSave.disabled = true;
            btnSave.textContent = 'Menyimpan...';

            try {
                const fd = new FormData(form);
                const res = await fetch('<?= base_url("dosen/update_leader"); ?>', {
                    method: 'POST',
                    body: fd
                });
                const data = await res.json().catch(() => ({
                    status: false,
                    message: 'Response tidak valid'
                }));
                if (!res.ok) {
                    throw new Error(data.message || 'Gagal memperbarui ketua.');
                }

                const gid = fd.get('group_id');
                const lid = fd.get('leader_id');

                // update label ketua di tabel

                if (window.Swal) {
                    Swal.fire('Berhasil', data.message || 'Ketua diperbarui.', 'success')
                        .then((result) => {
                            if (result.isConfirmed) {
                                location.reload(); // reload halaman
                            }
                        });
                } else {
                    alert(data.message || 'Ketua diperbarui.');
                    location.reload();
                }


            } catch (err) {
                if (window.Swal) Swal.fire('Gagal', err.message, 'error');
                else alert(err.message);
            } finally {
                btnSave.disabled = false;
                btnSave.textContent = 'Simpan';
            }
        });

    })();
</script>