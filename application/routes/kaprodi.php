<?php
$route['kaprodi'] = 'kaprodi/index';
$route['kaprodi/mahasiswa'] = 'kaprodi/mahasiswa';
$route['kaprodi/datatable/mahasiswa'] = 'kaprodi/mahasiswa_datatable';
$route['kaprodi/dosen'] = 'kaprodi/dosen';
$route['kaprodi/datatable/dosen'] = 'kaprodi/dosen_datatable';
$route['kaprodi/dosen/store'] = 'kaprodi/dosen_store';
$route['kaprodi/dosen/update/(:num)'] = 'kaprodi/dosen_update/$1';
$route['kaprodi/dosen/delete/(:num)'] = 'kaprodi/dosen_delete/$1';
$route['kaprodi/dosen/import'] = 'kaprodi/dosen_import';
$route['kaprodi/dosen/export'] = 'kaprodi/dosen_export';
$route['kaprodi/laporan'] = 'kaprodi/laporan';
$route['kaprodi/plotting'] = 'kaprodi/plotting';
$route['kaprodi/plotting/data'] = 'kaprodi/plotting_data';
$route['kaprodi/plotting/store'] = 'kaprodi/plotting_store';
$route['kaprodi/plotting/delete'] = 'kaprodi/plotting_delete';
