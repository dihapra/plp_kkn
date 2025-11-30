<?php

namespace Repositories;


class LecturerRepository
{
    protected $CI;
    protected $db;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }

    public function get_by_key($key, $value)
    {
        $this->db->where($key, $value);
        return $this->db->get('lecturers')->row();
    }

    public function insert($data)
    {
        $this->db->insert('lecturers', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('lecturers', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('lecturers');
    }

    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('lecturers')->row();
    }

    public function get_all()
    {
        return $this->db->get('lecturers')->result();
    }
}
