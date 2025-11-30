<?php

$route['dosen'] = 'dosen/index';
$route['dosen/mahasiswa'] = 'dosen/mahasiswa';
$route['dosen/nilai'] = 'dosen/nilai_akhir';
$route['dosen/absensi'] = 'dosen/absensi';
$route['dosen/absensi/datatable'] = 'dosen/datatable_absensi';
$route['dosen/absensi/simpan'] = 'dosen/save_absensi';
$route['dosen/datatable/mahasiswa'] = 'dosen/datatable_student';
$route['dosen/list-aktivitas'] = 'dosen/get_activity';
$route['dosen/logbook'] = 'dosen/logbook';
$route['dosen/logbook/detail/(:num)'] = 'dosen/get_logbook_detail/$1';
$route['dosen/logbook/save-feedback'] = 'dosen/save_logbook_feedback';

$route['dosen/kelompok'] = 'dosen/kelompok';
$route['dosen/kelompok/member/(:num)'] = 'dosen/member/$1';
$route['dosen/update_leader'] = 'dosen/update_leader';
// crud laporan
$route['dosen/laporan/save/(:num)'] = 'dosen/insert_nilai_laporan/$1';
$route['dosen/ajar/save/(:num)'] = 'dosen/insert_nilai_ajar/$1';
$route['dosen/submisi/revisi/(:num)'] = 'dosencontroller/tugas/report_revision/$1';
$route['dosen/nilai-intra-ekstra-sikap/save/(:num)'] = 'dosen/insert_nilai_extra_intra_sikap/$1';

// laporan view
$route['dosen/laporan-kemajuan/nilai/(:num)'] = 'dosencontroller/tugas/laporan_kemajuan/$1';
$route['dosen/laporan-kemajuan/edit/(:num)'] = 'dosencontroller/tugas/laporan_kemajuan_edit/$1';
$route['dosen/laporan-kemajuan/view/(:num)'] = 'dosencontroller/tugas/laporan_kemajuan_view/$1';
// laporan view
$route['dosen/laporan-akhir/nilai/(:num)'] = 'dosencontroller/tugas/laporan_akhir/$1';
$route['dosen/laporan-akhir/edit/(:num)'] = 'dosencontroller/tugas/laporan_akhir_edit/$1';
$route['dosen/laporan-akhir/view/(:num)'] = 'dosencontroller/tugas/laporan_akhir_view/$1';
// laporan view
$route['dosen/modul-ajar/nilai/(:num)'] = 'dosencontroller/tugas/modul_ajar/$1';
$route['dosen/modul-ajar/edit/(:num)'] = 'dosencontroller/tugas/modul_ajar_edit/$1';
$route['dosen/modul-ajar/view/(:num)'] = 'dosencontroller/tugas/modul_ajar_view/$1';
// laporan view
$route['dosen/bahan-ajar/nilai/(:num)'] = 'dosencontroller/tugas/bahan_ajar/$1';
$route['dosen/bahan-ajar/edit/(:num)'] = 'dosencontroller/tugas/bahan_ajar_edit/$1';
$route['dosen/bahan-ajar/view/(:num)'] = 'dosencontroller/tugas/bahan_ajar_view/$1';
// laporan view
$route['dosen/modul-projek/nilai/(:num)'] = 'dosencontroller/tugas/modul_projek/$1';
$route['dosen/modul-projek/edit/(:num)'] = 'dosencontroller/tugas/modul_projek_edit/$1';
$route['dosen/modul-projek/view/(:num)'] = 'dosencontroller/tugas/modul_projek_view/$1';


// laporan view
$route['dosen/penilaian/intrakurikuler/(:num)'] = 'dosen/intra/$1';
$route['dosen/penilaian/intrakurikuler/edit/(:num)'] = 'dosen/intra_edit/$1';
$route['dosen/penilaian/intrakurikuler/view/(:num)'] = 'dosen/intra_view/$1';
// laporan view
$route['dosen/penilaian/ekstrakurikuler/(:num)'] = 'dosen/ekstra/$1';
$route['dosen/penilaian/ekstrakurikuler/edit/(:num)'] = 'dosen/ekstra_edit/$1';
$route['dosen/penilaian/ekstrakurikuler/view/(:num)'] = 'dosen/ekstra_view/$1';
// laporan view
$route['dosen/penilaian/analisis'] = 'dosen/penilaian_analisis';
$route['dosen/penilaian/analisis/(:num)'] = 'dosen/analisis/$1';
$route['dosen/penilaian/analisis/edit/(:num)'] = 'dosen/analisis_edit/$1';
$route['dosen/penilaian/analisis/view/(:num)'] = 'dosen/analisis_view/$1';
// laporan view
// $route['dosen/penilaian/intrakurikuler/(:num)'] = 'dosen/intra/$1';
// $route['dosen/penilaian/intrakurikuler/edit/(:num)'] = 'dosen/modul_projek_edit/$1';
// $route['dosen/penilaian/intrakurikuler/view/(:num)'] = 'dosen/modul_projek_view/$1';


$route['dosen/tugas/laporan-kemajuan'] = 'dosen/tugas_laporan_kemajuan';
$route['dosen/tugas/laporan-akhir'] = 'dosen/tugas_laporan_akhir';
$route['dosen/tugas/modul-ajar'] = 'dosen/tugas_modul_ajar';
$route['dosen/tugas/modul-proyek'] = 'dosen/tugas_modul_proyek';
$route['dosen/tugas/bahan-ajar'] = 'dosen/tugas_bahan_ajar';
$route['dosen/penilaian/intrakurikuler'] = 'dosen/penilaian_intrakurikuler';
$route['dosen/penilaian/ekstrakurikuler'] = 'dosen/penilaian_ekstrakurikuler';
$route['dosen/penilaian/sikap'] = 'dosen/penilaian_sikap';
$route['dosen/penilaian/sikap/(:num)'] = 'dosen/sikap/$1';
$route['dosen/penilaian/sikap/edit/(:num)'] = 'dosen/sikap_edit/$1';
$route['dosen/penilaian/sikap/view/(:num)'] = 'dosen/sikap_view/$1';
