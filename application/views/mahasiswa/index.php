<h1 class="text-center m-4">Dashboard Mahasiswa</h1>

<?php
$statusRaw = strtolower((string) ($registration->status ?? ''));
$statusLabel = 'Menunggu verifikasi';
$statusClass = 'alert-warning';
if ($statusRaw === 'verified') {
    $statusLabel = 'Terverifikasi';
    $statusClass = 'alert-success';
} elseif ($statusRaw === 'rejected') {
    $statusLabel = 'Ditolak';
    $statusClass = 'alert-danger';
}
$programLabel = '-';
if (!empty($registration->program_name)) {
    $programLabel = $registration->program_name;
    if (!empty($registration->tahun_ajaran)) {
        $programLabel .= ' (' . $registration->tahun_ajaran . ')';
    }
}
?>

<div class="alert <?= $statusClass ?> mt-4" role="alert">
    <strong>Status Verifikasi:</strong> <?= $statusLabel ?>
    <div class="small text-muted mt-1">Program: <?= $programLabel ?></div>
</div>

<!-- Dashboard cards dinonaktifkan sementara. -->
