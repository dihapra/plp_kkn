<?php

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
