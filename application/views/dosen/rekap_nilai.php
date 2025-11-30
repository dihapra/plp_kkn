<?php
// Helper function to render a score table for a specific category
function render_score_table($title, $scores, $category_key) {
    if (!isset($scores[$category_key])) return;

    echo '<h4>' . htmlspecialchars($title) . '</h4>';
    echo '<table class="table table-bordered table-striped">';
    echo '<tbody>';

    foreach ($scores[$category_key] as $item) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($item['label']) . '</td>';
        echo '<td style="width: 120px; text-align: right;">' . ($item['nilai'] ?: '-') . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Rekapitulasi Nilai Akhir</h1>
    <h2 class="h4 mb-4 text-gray-800">Mahasiswa: <?php echo htmlspecialchars($student_name); ?></h2>

    <div class="card shadow mb-4">
        <div class="card-body">
            <!-- Navigasi Tab -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="dosen-tab" data-toggle="tab" href="#dosen" role="tab" aria-controls="dosen" aria-selected="true">Penilaian Dosen (DPL)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="guru-tab" data-toggle="tab" href="#guru" role="tab" aria-controls="guru" aria-selected="false">Penilaian Guru Pamong</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="total-tab" data-toggle="tab" href="#total" role="tab" aria-controls="total" aria-selected="false">Penilaian Total</a>
                </li>
            </ul>

            <!-- Konten Tab -->
            <div class="tab-content" id="myTabContent">
                <!-- Tab Penilaian Dosen -->
                <div class="tab-pane fade show active" id="dosen" role="tabpanel" aria-labelledby="dosen-tab">
                    <div class="mt-4">
                        <?php 
                        render_score_table('F2 – UTS (20%)', $penilaian_dosen, 'f2_uts');
                        render_score_table('F3 – UAS (30%)', $penilaian_dosen, 'f3_uas');
                        render_score_table('F4 – Case Method & Project (45%)', $penilaian_dosen, 'f4_proyek');
                        ?>
                    </div>
                </div>

                <!-- Tab Penilaian Guru Pamong -->
                <div class="tab-pane fade" id="guru" role="tabpanel" aria-labelledby="guru-tab">
                     <div class="mt-4">
                        <?php 
                        render_score_table('F3 – UAS (30%)', $penilaian_guru, 'f3_uas');
                        ?>
                    </div>
                </div>

                <!-- Tab Penilaian Total -->
                <div class="tab-pane fade" id="total" role="tabpanel" aria-labelledby="total-tab">
                     <div class="mt-4">
                        <?php 
                        render_score_table('F1 – Kehadiran (5%)', $penilaian_total, 'f1_kehadiran');
                        render_score_table('Rekap Nilai Akhir', $penilaian_total, 'rekap');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
