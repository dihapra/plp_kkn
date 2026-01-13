<?php

$route['admin'] = 'admin/index';
$route['admin/plp1'] = 'admincontroller/plp1/index';
$route['admin/program'] = 'admin/program';
$route['admin/program/select'] = 'admin/set_program';

// Admin PLP I module
$route['admin/plp1/activities'] = 'admincontroller/plp1/activities';
$route['admin/plp1/report'] = 'admincontroller/plp1/report';
$route['admin/plp1/absensi'] = 'admincontroller/plp1/absensi';
$route['admin/plp1/master-data'] = 'admincontroller/plp1/master_data';
$route['admin/plp1/master-data/sekolah'] = 'admincontroller/plp1/master_data_sekolah';
$route['admin/plp1/master-data/sekolah/datatable'] = 'admincontroller/plp1/master_data_sekolah_datatable';
$route['admin/plp1/master-data/sekolah/store'] = 'admincontroller/plp1/master_data_sekolah_store';
$route['admin/plp1/master-data/sekolah/update/(:num)'] = 'admincontroller/plp1/master_data_sekolah_update/$1';
$route['admin/plp1/master-data/sekolah/delete/(:num)'] = 'admincontroller/plp1/master_data_sekolah_delete/$1';
$route['admin/plp1/master-data/sekolah/import'] = 'admincontroller/plp1/master_data_sekolah_import';
$route['admin/plp1/master-data/mahasiswa-true'] = 'admincontroller/plp1/master_data_mahasiswa_true';
$route['admin/plp1/master-data/mahasiswa-true/datatable'] = 'admincontroller/plp1/master_data_mahasiswa_true_datatable';
$route['admin/plp1/master-data/mahasiswa-true/store'] = 'admincontroller/plp1/master_data_mahasiswa_true_store';
$route['admin/plp1/master-data/mahasiswa-true/update/(:num)'] = 'admincontroller/plp1/master_data_mahasiswa_true_update/$1';
$route['admin/plp1/master-data/mahasiswa-true/delete/(:num)'] = 'admincontroller/plp1/master_data_mahasiswa_true_delete/$1';
$route['admin/plp1/master-data/mahasiswa-true/import'] = 'admincontroller/plp1/master_data_mahasiswa_true_import';
$route['admin/plp1/master-data/dosen'] = 'admincontroller/plp1/master_data_dosen';
$route['admin/plp1/master-data/dosen/datatable'] = 'admincontroller/plp1/master_data_dosen_datatable';
$route['admin/plp1/master-data/mahasiswa'] = 'admincontroller/plp1/master_data_mahasiswa';
$route['admin/plp1/master-data/mahasiswa/datatable'] = 'admincontroller/plp1/master_data_mahasiswa_datatable';
$route['admin/plp1/master-data/guru'] = 'admincontroller/plp1/master_data_guru';
$route['admin/plp1/master-data/guru/datatable'] = 'admincontroller/plp1/master_data_guru_datatable';
$route['admin/plp1/master-data/kepsek'] = 'admincontroller/plp1/master_data_kepsek';
$route['admin/plp1/master-data/kepsek/datatable'] = 'admincontroller/plp1/master_data_kepsek_datatable';
$route['admin/plp1/verifikasi/mahasiswa'] = 'admincontroller/plp1/verifikasi_mahasiswa';
$route['admin/plp1/verifikasi/mahasiswa/datatable'] = 'admincontroller/plp1/verifikasi_mahasiswa_datatable';
$route['admin/plp1/verifikasi/mahasiswa/detail/(:num)'] = 'admincontroller/plp1/verifikasi_mahasiswa_detail/$1';
$route['admin/plp1/verifikasi/mahasiswa/status/(:num)'] = 'admincontroller/plp1/verifikasi_mahasiswa_update_status/$1';
$route['admin/plp1/verifikasi/mahasiswa/delete/(:num)'] = 'admincontroller/plp1/verifikasi_mahasiswa_delete/$1';
$route['admin/plp1/verifikasi/sekolah'] = 'admincontroller/plp1/verifikasi_sekolah';
$route['admin/plp1/verifikasi/sekolah/datatable'] = 'admincontroller/plp1/verifikasi_sekolah_datatable';
$route['admin/plp1/verifikasi/sekolah/detail/(:num)'] = 'admincontroller/plp1/verifikasi_sekolah_detail/$1';
$route['admin/plp1/verifikasi/sekolah/status/(:num)'] = 'admincontroller/plp1/verifikasi_sekolah_update_status/$1';
$route['admin/plp1/verifikasi/guru'] = 'admincontroller/plp1/verifikasi_guru';
$route['admin/plp1/verifikasi/kepsek'] = 'admincontroller/plp1/verifikasi_kepsek';

// Admin filter endpoints (select2 helpers)
$route['admin/filter/sekolah'] = 'admincontroller/plp1/filter_sekolah';
$route['admin/filter/prodi'] = 'admincontroller/plp1/filter_prodi';
$route['admin/filter/fakultas'] = 'admincontroller/plp1/filter_fakultas';
$route['admin/filter/program'] = 'admincontroller/plp1/filter_program';

$route['admin/mahasiswa/insert'] = 'admincontroller/student/insert_page';
$route['admin/mahasiswa/simpan'] = 'admincontroller/student/create';
$route['admin/mahasiswa/edit/(:num)'] = 'admincontroller/student/show/$1';
$route['admin/mahasiswa/edit_page/(:num)'] = 'admincontroller/student/edit_page/$1';
$route['admin/mahasiswa/update/(:num)'] = 'admincontroller/student/update/$1';
$route['admin/mahasiswa/hapus/(:num)'] = 'admincontroller/student/delete/$1';

// Dosen (Lecturer) Management
$route['admin/dosen/insert'] = 'admincontroller/lecturer/insert_page';
$route['admin/dosen/simpan'] = 'admincontroller/lecturer/create';
$route['admin/dosen/edit/(:num)'] = 'admincontroller/lecturer/show/$1';
$route['admin/dosen/edit_page/(:num)'] = 'admincontroller/lecturer/edit_page/$1';
$route['admin/dosen/update/(:num)'] = 'admincontroller/lecturer/update/$1';
$route['admin/dosen/hapus/(:num)'] = 'admincontroller/lecturer/delete/$1';


$route['admin/datatable/mahasiswa'] = 'admincontroller/datatable/api_get_student';
$route['admin/datatable/semua-data'] = 'admincontroller/datatable/api_get_all_data';
$route['admin/datatable/sekolah'] = 'admincontroller/datatable/api_get_school';
$route['admin/datatable/guru'] = 'admincontroller/datatable/api_get_teacher';
$route['admin/datatable/guru/unverified'] = 'admincontroller/datatable/api_get_teacher_unverified';
$route['admin/datatable/dosen'] = 'admincontroller/datatable/api_get_lecturer';
$route['admin/datatable/user'] = 'admincontroller/datatable/api_get_user';

$route['admin/verifikasi-guru'] = 'admin/verifikasi_guru';

// history
$route['admin/histori-verifikasi-guru'] = 'admin/histori_verifikasi_guru';
$route['admin/histori-verifikasi-kepsek'] = 'admin/histori_verifikasi_kepsek';

// datatable
$route['admin/histori/data/guru'] = 'admincontroller/datatable/teacher_verified_history';
$route['admin/histori/data/kepsek'] = 'admincontroller/datatable/principal_verified_history';


$route['admin/unverified-teacher/(:num)'] = 'admin/show_unverified_teacher/$1';
$route['admin/semua-data'] = 'admin/semua_data';
$route['admin/export-all-data'] = 'admincontroller/export/export_all_data';
$route['admin/sekolah'] = 'admin/school';
$route['admin/absensi'] = 'admin/absensi';
$route['admin/export-teachers'] = 'admincontroller/export/export_teachers';
$route['admin/export-kepsek'] = 'admincontroller/export/export_principals';
$route['admin/validate_teacher/(:num)'] = 'auth/validate_teacher/$1';
$route['admin/verifikasi-kepala-sekolah'] = 'admin/verifikasi_kepala_sekolah';
$route['admin/datatable/kepala-sekolah/unverified'] = 'admincontroller/datatable/api_get_principal_unverified';
$route['admin/unverified-principal/(:num)'] = 'admin/show_unverified_principal/$1';
$route['admin/validate_principal/(:num)'] = 'admin/validate_principal/$1';
$route['admin/reject_principal/(:num)'] = 'admin/reject_principal/$1';
$route['admin/reject_teacher/(:num)'] = 'auth/reject_teacher/$1';
// $route['admin/datatable/lecture_activity'] = 'admincontroller/datatable/api_get_lecture_activity';
// $route['admin/datatable/student_activity'] = 'admincontroller/datatable/api_get_student_activity';
// $route['admin/datatable/teacher_activity'] = 'admincontroller/datatable/api_get_teacher_activity';


$route['admin/import_lecture_student_school'] = 'admincontroller/import/import_lecture_student_school';
$route['admin/sekolah/import'] = 'admincontroller/import/import_school';
$route['admin/dosen/import'] = 'admincontroller/import/import_lecturer';
$route['admin/sekolah/simpan'] = 'admin/insert_school';
$route['admin/sekolah/update/(:num)'] = 'admincontroller/school/update/$1';
$route['admin/guru/simpan'] = 'admincontroller/teacher/create';
$route['admin/guru/update/(:num)'] = 'admincontroller/teacher/update/$1';
$route['admin/guru/hapus/(:num)'] = 'admincontroller/teacher/delete/$1';
$route['admin/aktivitas/guru'] = 'admin/teacher_activity';
$route['admin/aktivitas/dosen'] = 'admin/lecturer_activity';
$route['admin/aktivitas/mahasiswa'] = 'admin/student_activity';



// activity
$route['admin/aktifitas/guru/insert'] = 'admincontroller/activity/insert_teacher';
$route['admin/aktifitas/guru/update/(:num)'] = 'admincontroller/activity/update_teacher/$1';
$route['admin/aktifitas/guru/edit/(:num)'] = 'admincontroller/activity/show_teacher/$1';
$route['admin/aktifitas/dosen/insert'] = 'admincontroller/activity/insert_lecturer';
$route['admin/aktifitas/dosen/update/(:num)'] = 'admincontroller/activity/update_lecturer/$1';
$route['admin/aktifitas/dosen/edit/(:num)'] = 'admincontroller/activity/show_lecturer/$1';
$route['admin/aktifitas/mahasiswa/insert'] = 'admincontroller/activity/insert_student';
$route['admin/aktifitas/mahasiswa/update/(:num)'] = 'admincontroller/activity/update_student/$1';
$route['admin/aktifitas/mahasiswa/edit/(:num)'] = 'admincontroller/activity/show_student/$1';


$route['admin/datatable/lecture_activity'] = 'admincontroller/datatable/api_get_lecture_activity';
$route['admin/datatable/student_activity'] = 'admincontroller/datatable/api_get_student_activity';
$route['admin/datatable/teacher_activity'] = 'admincontroller/datatable/api_get_teacher_activity';

$route['admin/datatable/absensi'] = 'admincontroller/datatable/datatable_absensi';
// users
$route['admin/users/reset-password/(:num)'] = 'admin/reset_password/$1';
$route['admin/sekolah-tanpa-kepsek'] = 'admin/sekolah_tanpa_kepsek';
$route['admin/mahasiswa-tanpa-guru'] = 'admin/mahasiswa_tanpa_guru';
$route['admin/datatable/sekolah-tanpa-kepsek'] = 'admincontroller/datatable/api_get_sekolah_tanpa_kepsek';
$route['admin/datatable/mahasiswa-tanpa-guru'] = 'admincontroller/datatable/api_get_mahasiswa_tanpa_guru';

