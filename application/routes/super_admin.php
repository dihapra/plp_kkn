<?php

$route['super-admin'] = 'superadmin/dashboard';
$route['super-admin/dashboard'] = 'superadmin/dashboard';
$route['super-admin/login'] = 'superadmin/login';
$route['super-admin/login/authenticate'] = 'superadmin/authenticate';
$route['super-admin/logout'] = 'superadmin/logout';

$route['super-admin/program'] = 'superadmin/program';
$route['super-admin/datatable/program'] = 'superadmin/program_datatable';
$route['super-admin/program/store'] = 'superadmin/program_store';
$route['super-admin/program/update/(:num)'] = 'superadmin/program_update/$1';
$route['super-admin/program/toggle/(:num)'] = 'superadmin/program_toggle/$1';

$route['super-admin/sekolah'] = 'superadmin/sekolah';
$route['super-admin/datatable/sekolah'] = 'superadmin/sekolah_datatable';
$route['super-admin/sekolah/store'] = 'superadmin/sekolah_store';
$route['super-admin/sekolah/update/(:num)'] = 'superadmin/sekolah_update/$1';
$route['super-admin/sekolah/delete/(:num)'] = 'superadmin/sekolah_delete/$1';

$route['super-admin/prodi'] = 'superadmin/prodi';
$route['super-admin/datatable/prodi'] = 'superadmin/prodi_datatable';
$route['super-admin/prodi/store'] = 'superadmin/prodi_store';
$route['super-admin/prodi/update/(:num)'] = 'superadmin/prodi_update/$1';
$route['super-admin/prodi/import'] = 'superadmin/prodi_import';

$route['super-admin/kepala-sekolah'] = 'superadmin/kepsek';
$route['super-admin/datatable/kepsek'] = 'superadmin/kepsek_datatable';
$route['super-admin/kepsek/store'] = 'superadmin/kepsek_store';
$route['super-admin/kepsek/update/(:num)'] = 'superadmin/kepsek_update/$1';

$route['super-admin/dosen'] = 'superadmin/dosen';
$route['super-admin/datatable/dosen'] = 'superadmin/dosen_datatable';
$route['super-admin/dosen/store'] = 'superadmin/dosen_store';
$route['super-admin/dosen/update/(:num)'] = 'superadmin/dosen_update/$1';

$route['super-admin/mahasiswa'] = 'superadmin/mahasiswa';
$route['super-admin/datatable/mahasiswa'] = 'superadmin/mahasiswa_datatable';
$route['super-admin/mahasiswa/store'] = 'superadmin/mahasiswa_store';
$route['super-admin/mahasiswa/update/(:num)'] = 'superadmin/mahasiswa_update/$1';
$route['super-admin/mahasiswa-true'] = 'superadmin/mahasiswa_true';
$route['super-admin/datatable/mahasiswa-true'] = 'superadmin/mahasiswa_true_datatable';
$route['super-admin/mahasiswa-true/store'] = 'superadmin/mahasiswa_true_store';
$route['super-admin/mahasiswa-true/update/(:num)'] = 'superadmin/mahasiswa_true_update/$1';
$route['super-admin/mahasiswa-true/delete/(:num)'] = 'superadmin/mahasiswa_true_delete/$1';
$route['super-admin/mahasiswa-true/import'] = 'superadmin/mahasiswa_true_import';

$route['super-admin/kaprodi'] = 'superadmin/kaprodi';
$route['super-admin/datatable/kaprodi'] = 'superadmin/kaprodi_datatable';
$route['super-admin/kaprodi/store'] = 'superadmin/kaprodi_store';
$route['super-admin/kaprodi/update/(:num)'] = 'superadmin/kaprodi_update/$1';

$route['super-admin/guru'] = 'superadmin/guru';
$route['super-admin/datatable/guru'] = 'superadmin/guru_datatable';
$route['super-admin/guru/store'] = 'superadmin/guru_store';
$route['super-admin/guru/update/(:num)'] = 'superadmin/guru_update/$1';

$route['super-admin/admin-pic'] = 'superadmin/admin_pic';
$route['super-admin/datatable/admin-pic'] = 'superadmin/admin_pic_datatable';
$route['super-admin/admin-pic/store'] = 'superadmin/admin_pic_store';
$route['super-admin/admin-pic/update/(:num)'] = 'superadmin/admin_pic_update/$1';
$route['super-admin/admin-pic/delete/(:num)'] = 'superadmin/admin_pic_delete/$1';

$route['super-admin/filter/sekolah']  = 'superadmin/filter_sekolah';
$route['super-admin/filter/prodi']    = 'superadmin/filter_prodi';
$route['super-admin/filter/fakultas'] = 'superadmin/filter_fakultas';
$route['super-admin/filter/program']  = 'superadmin/filter_program';

// Modul PLP I
$route['super-admin/plp/activities']                = 'superadmincontroller/plp1/activities';
$route['super-admin/plp/report']                    = 'superadmincontroller/plp1/report';
$route['super-admin/plp/absensi']                   = 'superadmincontroller/plp1/absensi';
$route['super-admin/plp/master-data']               = 'superadmincontroller/plp1/master_data';
$route['super-admin/plp/master-data/sekolah']       = 'superadmincontroller/plp1/master_data_sekolah';
$route['super-admin/plp/master-data/sekolah/datatable'] = 'superadmincontroller/plp1/master_data_sekolah_datatable';
$route['super-admin/plp/master-data/sekolah/store'] = 'superadmincontroller/plp1/master_data_sekolah_store';
$route['super-admin/plp/master-data/sekolah/update/(:num)'] = 'superadmincontroller/plp1/master_data_sekolah_update/$1';
$route['super-admin/plp/master-data/sekolah/delete/(:num)'] = 'superadmincontroller/plp1/master_data_sekolah_delete/$1';
$route['super-admin/plp/master-data/sekolah/import'] = 'superadmincontroller/plp1/master_data_sekolah_import';
$route['super-admin/plp/master-data/mahasiswa-true'] = 'superadmincontroller/plp1/master_data_mahasiswa_true';
$route['super-admin/plp/master-data/dosen']         = 'superadmincontroller/plp1/master_data_dosen';
$route['super-admin/plp/master-data/dosen/datatable']   = 'superadmincontroller/plp1/master_data_dosen_datatable';
$route['super-admin/plp/master-data/mahasiswa']     = 'superadmincontroller/plp1/master_data_mahasiswa';
$route['super-admin/plp/master-data/mahasiswa/datatable'] = 'superadmincontroller/plp1/master_data_mahasiswa_datatable';
$route['super-admin/plp/master-data/guru']          = 'superadmincontroller/plp1/master_data_guru';
$route['super-admin/plp/master-data/guru/datatable']    = 'superadmincontroller/plp1/master_data_guru_datatable';
$route['super-admin/plp/master-data/kepsek']        = 'superadmincontroller/plp1/master_data_kepsek';
$route['super-admin/plp/master-data/kepsek/datatable']  = 'superadmincontroller/plp1/master_data_kepsek_datatable';
$route['super-admin/plp/verifikasi/mahasiswa']      = 'superadmincontroller/plp1/verifikasi_mahasiswa';
$route['super-admin/plp/verifikasi/mahasiswa/datatable'] = 'superadmincontroller/plp1/verifikasi_mahasiswa_datatable';
$route['super-admin/plp/verifikasi/mahasiswa/detail/(:num)'] = 'superadmincontroller/plp1/verifikasi_mahasiswa_detail/$1';
$route['super-admin/plp/verifikasi/mahasiswa/status/(:num)'] = 'superadmincontroller/plp1/verifikasi_mahasiswa_update_status/$1';
$route['super-admin/plp/verifikasi/guru']           = 'superadmincontroller/plp1/verifikasi_guru';
$route['super-admin/plp/verifikasi/kepsek']         = 'superadmincontroller/plp1/verifikasi_kepsek';

// Modul PLP II
$route['super-admin/plp2/activities']           = 'superadmincontroller/plp2/activities';
$route['super-admin/plp2/report']               = 'superadmincontroller/plp2/report';
$route['super-admin/plp2/absensi']              = 'superadmincontroller/plp2/absensi';
$route['super-admin/plp2/verifikasi/mahasiswa'] = 'superadmincontroller/plp2/verifikasi_mahasiswa';
$route['super-admin/plp2/verifikasi/guru']      = 'superadmincontroller/plp2/verifikasi_guru';
$route['super-admin/plp2/verifikasi/kepsek']    = 'superadmincontroller/plp2/verifikasi_kepsek';

// Modul KKN
$route['super-admin/kkn/activities'] = 'superadmincontroller/kkn/activities';
$route['super-admin/kkn/report']     = 'superadmincontroller/kkn/report';
