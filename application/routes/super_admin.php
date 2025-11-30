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

$route['super-admin/prodi'] = 'superadmin/prodi';
$route['super-admin/datatable/prodi'] = 'superadmin/prodi_datatable';
$route['super-admin/prodi/store'] = 'superadmin/prodi_store';
$route['super-admin/prodi/update/(:num)'] = 'superadmin/prodi_update/$1';

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

$route['super-admin/kaprodi'] = 'superadmin/kaprodi';
$route['super-admin/datatable/kaprodi'] = 'superadmin/kaprodi_datatable';
$route['super-admin/kaprodi/store'] = 'superadmin/kaprodi_store';
$route['super-admin/kaprodi/update/(:num)'] = 'superadmin/kaprodi_update/$1';

$route['super-admin/guru'] = 'superadmin/guru';
$route['super-admin/datatable/guru'] = 'superadmin/guru_datatable';
$route['super-admin/guru/store'] = 'superadmin/guru_store';
$route['super-admin/guru/update/(:num)'] = 'superadmin/guru_update/$1';

$route['super-admin/filter/sekolah']  = 'superadmin/filter_sekolah';
$route['super-admin/filter/prodi']    = 'superadmin/filter_prodi';
$route['super-admin/filter/fakultas'] = 'superadmin/filter_fakultas';
$route['super-admin/filter/program']  = 'superadmin/filter_program';
