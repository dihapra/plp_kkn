<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_DB_query_builder $db
 */


class User_model extends CI_Model
{

    public function get_by_id($id)
    {
        return $this->db->where('id', $id)->get('users')->row();
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update('users', $data);
    }
    public function get_user_by_email_with_password($email)
    {
        $this->db->select('id, has_change, email, username, password,fakultas,role');
        $this->db->where(['email' => $email]);
        return $this->db->get('users')->row();
    }
}
