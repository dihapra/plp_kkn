<?php
defined('BASEPATH') or exit('No direct script access allowed');

trait SearchTrait
{
    /**
     * Terapkan pencarian LIKE untuk beberapa kolom.
     *
     * @param string|null $search Keyword pencarian
     * @param array $columns Kolom yang akan dicari
     * @return void
     */
    public function applySearch($search, array $columns)
    {
        if (empty($search) || empty($columns)) {
            return;
        }

        $this->db->group_start(); // buka bracket ( ... )

        foreach ($columns as $i => $column) {
            if ($i === 0) {
                $this->db->like($column, $search);
            } else {
                $this->db->or_like($column, $search);
            }
        }

        $this->db->group_end(); // tutup bracket
    }
}
