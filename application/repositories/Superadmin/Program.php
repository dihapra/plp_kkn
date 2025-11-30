<?php
namespace Repositories\Superadmin;
require_once(APPPATH . 'traits/SearchTrait.php');
defined('BASEPATH') or exit('No direct script access allowed');


class Program
{
    use \SearchTrait;
    protected $CI;
    protected $db;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->db = $this->CI->db;
    }
    public function get_latest_program()
    {
        $program = $this->db
            ->order_by('tahun_ajaran', 'DESC')
            ->limit(1)
            ->where('active', 1)
            ->get('program')
            ->row();
        return $program->id;
    }

    public function datatable($params)
    {
        $this->db->select('id,
        nama,tahun_ajaran,active as status
    ');
        $this->db->from('program');
        $count_total = $this->db->count_all_results('', false);


        $search_columns = ['nama', 'tahun_ajaran'];
        $this->applySearch($params['search'], $search_columns);

        $count_filtered = $this->db->count_all_results('', false);

        // Menambahkan batasan dan pengurutan
        $this->db->limit($params['length'], $params['start']);
        $this->db->order_by($params['order_column'], $params['order_dir']);

        // Melakukan kueri dan mengembalikan hasilnya
        $query = $this->db->get();
        return [
            'query' => $query->result(),
            'count_total' => $count_total,
            'count_filtered' => $count_filtered
        ];
    }

    public function create(array $data): int
    {
        $this->db->insert('program', $data);
        return (int) $this->db->insert_id();
    }

    public function update(int $id, array $data): void
    {
        $this->db->where('id', $id)->update('program', $data);
    }

    public function toggleActive(int $id): int
    {
        $row = $this->db->select('active')
            ->from('program')
            ->where('id', $id)
            ->get()
            ->row();

        if (!$row) {
            throw new \InvalidArgumentException('Program tidak ditemukan.');
        }

        $newStatus = $row->active ? 0 : 1;
        $this->db->where('id', $id)->update('program', ['active' => $newStatus]);

        return $newStatus;
    }

    public function list_simple(): array
    {
        return $this->db
            ->select('id, nama')
            ->from('program')
            ->order_by('nama', 'ASC')
            ->get()
            ->result_array();
    }
}
