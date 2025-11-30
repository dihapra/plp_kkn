 <main>
     <div class="container-fluid px-4">
         <h1 class="mt-4">Sekolah Tanpa Kepala Sekolah</h1>
         <ol class="breadcrumb mb-4">
             <li class="breadcrumb-item active">Sekolah Tanpa Kepala Sekolah</li>
         </ol>
         <div class="card mb-4">
             <div class="card-header">
                 <i class="fas fa-table me-1"></i>
                 Daftar Sekolah Tanpa Kepala Sekolah
             </div>
             <div class="card-body">
                 <table id="datatablesSimple" class="table table-bordered dt-responsive nowrap w-100" data-url="<?= base_url('admin/datatable/sekolah-tanpa-kepsek') ?>">
                     <thead>
                         <tr>
                             <th>Nama Sekolah</th>
                             <!-- <th>Alamat</th>
                                            <th>Kecamatan</th>
                                            <th>Kabupaten</th> -->
                         </tr>
                     </thead>
                     <tbody>

                     </tbody>
                 </table>
             </div>
         </div>
     </div>
 </main>
 <script src="<?= base_url('assets/js/pages/admin/sekolah_tanpa_kepsek.js') ?>"></script>