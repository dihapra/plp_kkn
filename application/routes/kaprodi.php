<?php
$route['kaprodi'] = 'kaprodi/index';
$route['kaprodi/mahasiswa'] = 'kaprodi/mahasiswa';
$route['kaprodi/dosen'] = 'kaprodi/dosen';
$route['kaprodi/datatable/dosen'] = 'kaprodi/dosen_datatable';
$route['kaprodi/dosen/store'] = 'kaprodi/dosen_store';
$route['kaprodi/dosen/update/(:num)'] = 'kaprodi/dosen_update/$1';
$route['kaprodi/dosen/delete/(:num)'] = 'kaprodi/dosen_delete/$1';
$route['kaprodi/dosen/export'] = 'kaprodi/dosen_export';
$route['kaprodi/laporan'] = 'kaprodi/laporan';
