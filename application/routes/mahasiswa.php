<?php

$route['mahasiswa/upload-tugas/(:num)'] = 'mahasiswa/upload_tugas/$1';
$route['mahasiswa/pilih-program'] = 'mahasiswa/pilih_program';
$route['mahasiswa/program/select'] = 'mahasiswa/set_program';
$route['mahasiswa/plp1'] = 'mahasiswa/plp1';
$route['mahasiswa/logbook/simpan'] = 'mahasiswa/save_logbook';
$route['mahasiswa/logbook/update'] = 'mahasiswa/update_logbook';
$route['mahasiswa/list-aktivitas'] = 'mahasiswa/get_activity';
$route['mahasiswa/kehadiran'] = 'mahasiswa/get_attendance';
$route['mahasiswa/logbooks'] = 'mahasiswa/get_logbook_by_student';
$route['mahasiswa/logbooks/meeting/(:num)'] = 'mahasiswa/get_logbook_by_meeting/$1';
