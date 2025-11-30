<?php if ($this->session->flashdata('pengumuman_list')): ?>
    <?php $pengumuman_list = $this->session->flashdata('pengumuman_list'); ?>
    <?php foreach ($pengumuman_list as $p): ?>
        <div class="alert alert-info alert-dismissible fade show mt-2" role="alert">
            <strong><?= html_escape($p->judul) ?></strong><br>
            <?= nl2br(html_escape($p->isi)) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
        <?= $this->session->flashdata('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $this->session->flashdata('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>