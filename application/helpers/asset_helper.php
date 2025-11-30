<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('css_url')) {
    function css_url($file = '')
    {
        return base_url('assets/css/' . ltrim($file, '/'));
    }
}

if (!function_exists('js_url')) {
    function js_url($file = '')
    {
        return base_url('assets/js/' . ltrim($file, '/'));
    }
}

if (!function_exists('img_url')) {
    function img_url($file = '')
    {
        return base_url('assets/img/' . ltrim($file, '/'));
    }
}

if (!function_exists('lib_url')) {
    function lib_url($file = '')
    {
        return base_url('assets/libs/' . ltrim($file, '/'));
    }
}
if (!function_exists('admin_asset')) {
    function admin_asset($file = '')
    {
        return base_url('assets/pages/admin/' . ltrim($file, '/'));
    }
}
if (!function_exists('dosen_asset')) {
    function dosen_asset($file = '')
    {
        return base_url('assets/pages/dosen/' . ltrim($file, '/'));
    }
}
if (!function_exists('mahasiswa_asset')) {
    function mahasiswa_asset($file = '')
    {
        return base_url('assets/pages/mahasiswa/' . ltrim($file, '/'));
    }
}
