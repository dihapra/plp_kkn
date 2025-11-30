 <main>
     <div class="container-fluid px-4">
         <h1 class="mt-4">Mahasiswa Tanpa Guru Pamong</h1>
         <ol class="breadcrumb mb-4">
             <li class="breadcrumb-item active">Mahasiswa Tanpa Guru Pamong</li>
         </ol>
         <div class="card mb-4">
             <div class="card-header">
                 <i class="fas fa-table me-1"></i>
                 Daftar Mahasiswa Tanpa Guru Pamong
             </div>
             <div class="card-body">
                 <table id="datatablesSimple" class="table table-bordered dt-responsive nowrap w-100" data-url="<?= base_url('admin/datatable/mahasiswa-tanpa-guru') ?>">
                     <thead>
                         <tr>
                             <th>NIM</th>
                             <th>Nama Mahasiswa</th>
                             <th>Sekolah</th>
                             <th>Dosen Pembimbing</th>
                         </tr>
                     </thead>
                     <tbody>

                     </tbody>
                 </table>
             </div>
         </div>
     </div>
 </main>
 <script src="<?= base_url('assets/js/pages/admin/mahasiswa_tanpa_guru.js') ?>"></script>