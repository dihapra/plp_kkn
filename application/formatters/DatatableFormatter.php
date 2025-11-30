<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Form_validation $form_validation
 */
class DatatableFormatter
{
    protected $CI;
    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function student_formatter($result)
    {
        $formatter = [];
        foreach ($result['query'] as $r) {
            $formatter[] = array(
                'id' => $r->id,
                'name' => $r->name,
                'nim' => $r->nim,
                'email' => $r->email,
                'prodi' => $r->prodi,
                'fakultas' => $r->fakultas,
                'phone' => $r->phone,
                'lecturer_name' => $r->lecturer_name,
                'teacher_name' => $r->teacher_name ?? "",
                'school_name' => $r->school_name,
                'user_role' => $this->CI->session->userdata('role')
            );
        }
        return $formatter;
    }

    public function lecture_formatter($result)
    {
        $formatter = [];
        foreach ($result['query'] as $r) {
            $formatter[] = array(
                'id' => $r->id,
                'name' => $r->name,
                'nip' => $r->nip,
                'email' => $r->email,
                'prodi' => $r->prodi,
                'fakultas' => $r->fakultas,
                'phone' => $r->phone,
            );
        }
        return $formatter;
    }
    public function school_formatter($result)
    {

        $formatter = [];
        foreach ($result['query'] as $r) {
            $formatter[] = array(
                'id' => $r->id,
                'name' => $r->name,
                'principal_name' => $r->principal_name,
                'principal_email' => $r->principal_email,
                'principal_phone' => $r->principal_phone,
                'principal_bank' => $r->principal_bank,
                'principal_account_number' => $r->principal_account_number,
                'principal_account_name' => $r->principal_account_name,
                'principal_nik' => $r->principal_nik,
                'principal_status' => $r->principal_status,
                'principal_book' => $r->principal_book,
                'user_role' => $this->CI->session->userdata('role')
            );
        }
        return $formatter;
    }
    public function teacher_formatter($result)
    {

        $formatter = [];
        foreach ($result['query'] as $r) {
            $formatter[] = array(
                'id' => $r->id,
                'name' => $r->name,
                'school_id' => $r->school_id,
                'nik' => $r->nik,
                'email' => $r->email,
                'status_data' => $r->status_data,
                'status' => $r->status,
                'school_name' => $r->school_name,
                'phone' => $r->phone,
                'bank' => $r->bank,
                'account_number' => $r->account_number,
                'account_name' => $r->account_name,
                'book' => $r->book,
                'user_role' => $this->CI->session->userdata('role')
            );
        }
        return $formatter;
    }
    public function principal_formatter($result)
    {

        $formatter = [];
        foreach ($result['query'] as $r) {
            $formatter[] = array(
                'id' => $r->id,
                'name' => $r->name,
                'school_id' => $r->school_id,
                'nik' => $r->nik,
                'email' => $r->email,
                'status_data' => $r->status_data,
                'status' => $r->status,
                'school_name' => $r->school_name,
                'phone' => $r->phone,
                'bank' => $r->bank,
                'account_number' => $r->account_number,
                'account_name' => $r->account_name,
                'book' => $r->book,
                'user_role' => $this->CI->session->userdata('role') // Keep this for consistency with JS
            );
        }
        return $formatter;
    }
    public function user_formatter($result)
    {

        $formatter = [];
        foreach ($result['query'] as $r) {
            $formatter[] = array(
                'id' => $r->id,
                'username' => $r->username,
                'email' => $r->email,
                'role' => $r->role,
                'nim' => $r->nim,
                'nip' => $r->nip,
                'nik' => $r->nik,
                'principal_nik' => $r->principal_nik,
                'has_change' => $r->has_change,
            );
        }
        return $formatter;
    }
    public function all_data_formatter($result)
    {

        $formatter = [];
        foreach ($result['query'] as $r) {
            $formatter[] = array(
                'id' => $r->id,
                'school_name' => $r->school_name,
                'principal' => $r->principal,
                'principal_phone' => $r->principal_phone,
                'lecturer_name' => $r->lecturer_name,
                'student_email' => $r->student_email,
                'student_name' => $r->student_name,
                'nim' => $r->nim,
                'student_phone' => $r->student_phone,
                'student_prodi' => $r->student_prodi,
                'student_fakultas' => $r->student_fakultas,
                'teacher_name' => $r->teacher_name
            );
        }
        return $formatter;
    }
    public function absensi_formatter($result)
    {
        $formatter = [];
        foreach ($result['query'] as $r) {
            $formatter[] = [
                'student_id'   => $r->student_id,
                'student_name' => $r->student_name,
                'pertemuan_1'  => $r->pertemuan_1 ?? '-',
                'pertemuan_2'  => $r->pertemuan_2 ?? '-',
                'pertemuan_3'  => $r->pertemuan_3 ?? '-',
                'pertemuan_4'  => $r->pertemuan_4 ?? '-',
                'pertemuan_5'  => $r->pertemuan_5 ?? '-',
                'pertemuan_6'  => $r->pertemuan_6 ?? '-',
                'pertemuan_7'  => $r->pertemuan_7 ?? '-',
                'pertemuan_8'  => $r->pertemuan_8 ?? '-',
                'pertemuan_9'  => $r->pertemuan_9 ?? '-',
                'pertemuan_10' => $r->pertemuan_10 ?? '-',
                'pertemuan_11' => $r->pertemuan_11 ?? '-',
                'pertemuan_12' => $r->pertemuan_12 ?? '-',
                'pertemuan_13' => $r->pertemuan_13 ?? '-',
                'pertemuan_14' => $r->pertemuan_14 ?? '-',
                'pertemuan_15' => $r->pertemuan_15 ?? '-',
                'pertemuan_16' => $r->pertemuan_16 ?? '-',
            ];
        }
        return $formatter;
    }
}
