<?php
defined('BASEPATH') or exit('No direct script access allowed');

function view_with_layout($view, $title, $role = null, $data = [], $head = null, $script = null)
{
    $CI =& get_instance();

    // Ambil konten dari view yang diinginkan
    $data['content'] = $CI->load->view($view, $data, TRUE);
    $data['head'] = !empty($head) ? $CI->load->view($head, [], TRUE) : '';

    // Cek jika $script tidak kosong sebelum memuat
    $data['script'] = !empty($script) ? $CI->load->view($script, [], TRUE) : '';
    $data['title'] = $title;
    // Muat layout utama dengan data
    if($role == 'super_admin'){
        $CI->load->view('super_admin/layout/app', $data);
    }else{
        $CI->load->view('layout/app', $data);

    }
}
