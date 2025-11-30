<?php

namespace Traits;

defined('BASEPATH') or exit('No direct script access allowed');

trait DatatableTrait
{
    /**
     * Terapkan sorting dan pagination ala DataTables.
     *
     * @param array $param ['orderColumn', 'orderDir', 'start', 'length']
     * @return void
     */
    public function applyDatatable(array $param)
    {
        if (!empty($param['orderColumn']) && !empty($param['orderDir'])) {
            $this->db->order_by($param['orderColumn'], $param['orderDir']);
        }

        if (isset($param['start']) && isset($param['length']) && $param['length'] != -1) {
            $this->db->limit((int) $param['length'], (int) $param['start']);
        }
    }
}
