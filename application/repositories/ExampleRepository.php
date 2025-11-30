<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ExampleRepository
{
    use SearchTrait;
    protected $CI;
    protected $db;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }

    public function searchByKeyword($keyword, $limit = 10)
    {
        // $this->applySearch($keyword, ['nama', 'kode', 'kategori']);
        return $this->db
            ->select('id, nama as text')
            ->from('produk')
            ->like('nama', $keyword)
            ->limit($limit)
            ->get()
            ->result();
    }
}
