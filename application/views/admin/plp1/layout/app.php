<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= $title ?? 'Admin PLP I' ?></title>
    <?php $this->load->view('layout/head') ?>
    <script src="<?php echo base_url("assets/libs/jquery/jquery.min.js") ?>"></script>
    <script>
        let baseUrl = "<?php echo base_url(''); ?>";
        window.filterBasePath = 'admin';
    </script>
    <link href="<?php echo base_url("assets/libs/select2/css/select2.min.css") ?>" rel="stylesheet" type="text/css" />
    <?= $head ?? '' ?>
</head>

<body>
    <?php $this->load->view('layout/nav') ?>
    <div id="layoutSidenav">
        <?php $this->load->view('admin/plp1/layout/sidebar') ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <?php $this->load->view('layout/alert'); ?>
                    <?= $content ?? '' ?>
                </div>
            </main>
            <?php $this->load->view('layout/footer-main') ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="<?= base_url('assets/js/scripts.js') ?>"></script>
    <script src="<?php echo base_url("assets/libs/sweetalert2/sweetalert2.min.js") ?>"></script>
    <script src="<?php echo base_url("assets/libs/select2/js/select2.min.js") ?>"></script>
    <script src="<?php echo base_url("assets/js/components/superadmin-select-school.js") ?>"></script>
    <script src="<?php echo base_url("assets/js/components/superadmin-select-program.js") ?>"></script>
    <script src="<?php echo base_url("assets/js/components/superadmin-select-prodi.js") ?>"></script>
    <?= $script ?? '' ?>
</body>

</html>

