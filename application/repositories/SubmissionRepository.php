<?php

namespace Repositories;


class SubmissionRepository
{
    protected $CI;
    protected $db;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }
    public function get_by_student_id($id)
    {
        $this->db->select('submission.*, submission_revisions.deskripsi AS revisi_catatan');
        $this->db->from('submission');
        $this->db->where('submission.student_id', $id);
        $this->db->join('submission_revisions', 'submission.id = submission_revisions.submission_id', 'left');

        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->result() : [];
    }
    public function get_by_group_id($id)
    {
        $this->db->select('submission.*,  submission_revisions.deskripsi AS revisi_catatan');
        $this->db->from('submission');
        $this->db->where('submission.group_id', $id);
        $this->db->join('submission_revisions', 'submission.id = submission_revisions.submission_id', 'left');

        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->result() : [];
    }
    public function update_laporan($data, $id)
    {
        return $this->db->where('id', $id)->update('submission', $data);
    }
    public function upload_laporan($data)
    {
        return $this->db->insert('submission', $data);
    }
}
