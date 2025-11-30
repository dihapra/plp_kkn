<?php

$route['guru'] = 'guru/index';
$route['guru/datatable/mahasiswa'] = 'guru/datatable_student';
$route['guru/list-aktivitas'] = 'guru/get_activity';
$route['guru/mahasiswa'] = 'guru/mahasiswa';
$route['guru/absensi'] = 'guru/absensi';
$route['guru/absensi/datatable'] = 'guru/datatable_absensi';
$route['guru/absensi/simpan'] = 'guru/save_absensi';

$route['guru/logbook'] = 'guru/logbook';
$route['guru/logbook/detail/(:num)'] = 'guru/get_logbook_detail/$1';
$route['guru/logbook/save-feedback'] = 'guru/save_logbook_feedback';
$route['guru/save_logbook_feedback'] = 'guru/save_logbook_feedback';
$route['guru/penilaian'] = 'guru/penilaian';
$route['guru/penilaian/intrakurikuler'] = 'guru/penilaian_intrakurikuler';
$route['guru/penilaian/ekstrakurikuler'] = 'guru/penilaian_ekstrakurikuler';
$route['guru/penilaian/sikap'] = 'guru/penilaian_sikap';

$route['guru/intra/(:num)'] = 'guru/intra/$1';
$route['guru/intra/edit/(:num)'] = 'guru/intra_edit/$1';
$route['guru/intra/view/(:num)'] = 'guru/intra_view/$1';

$route['guru/ekstra/(:num)'] = 'guru/ekstra/$1';
$route['guru/ekstra/edit/(:num)'] = 'guru/ekstra_edit/$1';
$route['guru/ekstra/view/(:num)'] = 'guru/ekstra_view/$1';

$route['guru/sikap/(:num)'] = 'guru/sikap/$1';
$route['guru/sikap/edit/(:num)'] = 'guru/sikap_edit/$1';
$route['guru/sikap/view/(:num)'] = 'guru/sikap_view/$1';

$route['guru/insert_nilai_extra_intra_sikap'] = 'guru/insert_nilai_extra_intra_sikap';
