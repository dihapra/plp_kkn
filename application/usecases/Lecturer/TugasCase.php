<?php

namespace UseCases\Lecturer;

use Exception;

class TugasCase
{

    public $CI;
    public $db;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }
    public function get_group_submissions()
    {
        $nip = $this->CI->session->userdata('nip');
        $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
        if (!$lecturer) {
            throw new Exception("Dosen tidak ditemukan");
        }
        $lid = (int) $lecturer->id;

        return $this->db->select("
        sg.id   AS group_id,
        sg.name AS group_name,
        GROUP_CONCAT(DISTINCT s.name ORDER BY s.name SEPARATOR '<br>') AS members,

        /* id submission TERBARU per tipe */
        (SELECT x.id FROM submission x 
         WHERE x.group_id = sg.id AND x.type = 1 
         ORDER BY x.id DESC LIMIT 1) AS sub1_id,
        (SELECT x.id FROM submission x 
         WHERE x.group_id = sg.id AND x.type = 2 
         ORDER BY x.id DESC LIMIT 1) AS sub2_id,

        /* uploaded flags */
        EXISTS(SELECT 1 FROM submission x WHERE x.group_id = sg.id AND x.type = 1) AS sub1_uploaded,
        EXISTS(SELECT 1 FROM submission x WHERE x.group_id = sg.id AND x.type = 2) AS sub2_uploaded,

        /* scored-by-me flags (lecturer ini) */
        EXISTS(
          SELECT 1 
          FROM submission x JOIN report_score r 
                ON r.submission_id = x.id AND r.lecture_id = {$lid}
          WHERE x.group_id = sg.id AND x.type = 1
        ) AS sub1_scored_by_me,
        EXISTS(
          SELECT 1 
          FROM submission x JOIN report_score r 
                ON r.submission_id = x.id AND r.lecture_id = {$lid}
          WHERE x.group_id = sg.id AND x.type = 2
        ) AS sub2_scored_by_me
    ", FALSE)
            ->from("student_group sg")
            ->join("student s", "s.group_id = sg.id")
            ->where("s.lecture_id", $lid) // scope dosen pembimbing
            ->group_by("sg.id, sg.name")  // aman; agregasi hanya di members
            ->order_by("sg.name", "asc")
            ->get()
            ->result();
    }


    public function get_individual_submissions()
    {
        $nip = $this->CI->session->userdata('nip');
        $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
        if (!$lecturer) {
            throw new Exception("Dosen tidak ditemukan");
        }

        return $this->db->select("
    s.id   AS student_id,
    s.name AS student_name,

    sub3.id AS sub3_id,
    CASE WHEN sub3.id IS NULL THEN 0 ELSE 1 END AS sub3_uploaded,
    CASE WHEN EXISTS (
        SELECT 1 FROM report_score rs
        WHERE rs.submission_id = sub3.id
        AND rs.lecture_id = " . $this->db->escape($lecturer->id) . "
    ) THEN 1 ELSE 0 END AS sub3_scored_by_me,

    sub4.id AS sub4_id,
    CASE WHEN sub4.id IS NULL THEN 0 ELSE 1 END AS sub4_uploaded,
    CASE WHEN EXISTS (
        SELECT 1 FROM report_score rs
        WHERE rs.submission_id = sub4.id
        AND rs.lecture_id = " . $this->db->escape($lecturer->id) . "
    ) THEN 1 ELSE 0 END AS sub4_scored_by_me,

    sub5.id AS sub5_id,
    CASE WHEN sub5.id IS NULL THEN 0 ELSE 1 END AS sub5_uploaded,
    CASE WHEN EXISTS (
        SELECT 1 FROM report_score rs
        WHERE rs.submission_id = sub5.id
        AND rs.lecture_id = " . $this->db->escape($lecturer->id) . "
    ) THEN 1 ELSE 0 END AS sub5_scored_by_me
", FALSE)
            ->from("student s")
            ->join("submission sub3", "sub3.student_id = s.id AND sub3.type = 3", "left")
            ->join("submission sub4", "sub4.student_id = s.id AND sub4.type = 4", "left")
            ->join("submission sub5", "sub5.student_id = s.id AND sub5.type = 5", "left")
            ->where("s.lecture_id", $lecturer->id)
            ->order_by("s.name", "asc")
            ->get()
            ->result();
    }

    public function get_group_submissions_by_type($type)
    {
        $nip = $this->CI->session->userdata('nip');
        $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
        if (!$lecturer) {
            throw new Exception("Dosen tidak ditemukan");
        }
        $lid = (int) $lecturer->id;

        return $this->db->select("
        sg.id   AS group_id,
        sg.name AS group_name,
        GROUP_CONCAT(DISTINCT s.name ORDER BY s.name SEPARATOR '<br>') AS members,

        (SELECT x.id FROM submission x 
         WHERE x.group_id = sg.id AND x.type = {$type} 
         ORDER BY x.id DESC LIMIT 1) AS sub_id,

        EXISTS(SELECT 1 FROM submission x WHERE x.group_id = sg.id AND x.type = {$type}) AS sub_uploaded,

        EXISTS(
          SELECT 1 
          FROM submission x JOIN report_score r 
                ON r.submission_id = x.id AND r.lecture_id = {$lid}
          WHERE x.group_id = sg.id AND x.type = {$type}
        ) AS sub_scored_by_me
    ", FALSE)
            ->from("student_group sg")
            ->join("student s", "s.group_id = sg.id")
            ->where("s.lecture_id", $lid) // scope dosen pembimbing
            ->group_by("sg.id, sg.name")
            ->order_by("sg.name", "asc")
            ->get()
            ->result();
    }


    public function get_individual_submissions_by_type($type)
    {
        $nip = $this->CI->session->userdata('nip');
        $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
        if (!$lecturer) {
            throw new Exception("Dosen tidak ditemukan");
        }

        return $this->db->select("
    s.id   AS student_id,
    s.name AS student_name,

    sub.id AS sub_id,
    CASE WHEN sub.id IS NULL THEN 0 ELSE 1 END AS sub_uploaded,
    CASE WHEN EXISTS (
        SELECT 1 FROM report_score rs
        WHERE rs.submission_id = sub.id
        AND rs.lecture_id = " . $this->db->escape($lecturer->id) . "
    ) THEN 1 ELSE 0 END AS sub_scored_by_me
", FALSE)
            ->from("student s")
            ->join("submission sub", "sub.student_id = s.id AND sub.type = {$type}", "left")
            ->where("s.lecture_id", $lecturer->id)
            ->order_by("s.name", "asc")
            ->get()
            ->result();
    }

    public function get_group_report_submission_by_id($type, $submisi_id)
    {
        // Validasi
        $type = (int) $type;
        if (!in_array($type, [1, 2], true)) {
            throw new Exception("Tipe laporan tidak valid. Gunakan 1 (kemajuan) atau 2 (akhir).");
        }
        $submisi_id = (int) $submisi_id;
        if ($submisi_id <= 0) {
            throw new Exception("Parameter submisi_id wajib diisi.");
        }

        // Dosen dari session
        $nip = $this->CI->session->userdata('nip');
        $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
        if (!$lecturer) {
            throw new Exception("Dosen tidak ditemukan");
        }
        $lid = (int) $lecturer->id;

        // Query: 1 submission (type sesuai), dalam scope dosen (s.lecture_id),
        // plus daftar anggota & flag sudah dinilai oleh dosen ini
        $sql = "
        SELECT
            sg.id   AS group_id,
            sg.name AS group_name,

            sub.id        AS submission_id,
            sub.file      AS file,
            sub.status    AS status,
            sub.created_at,

            GROUP_CONCAT(DISTINCT s.id   ORDER BY s.name SEPARATOR ',')    AS member_ids,
            GROUP_CONCAT(DISTINCT s.name ORDER BY s.name SEPARATOR '<br>') AS members,

            EXISTS(
                SELECT 1
                FROM report_score r
                WHERE r.submission_id = sub.id
                  AND r.lecture_id    = ?
            ) AS scored_by_me

            -- Optional: ketua
            -- ,(SELECT s2.id   FROM student s2 WHERE s2.group_id = sg.id AND s2.leader = 1 LIMIT 1) AS leader_id
            -- ,(SELECT s2.name FROM student s2 WHERE s2.group_id = sg.id AND s2.leader = 1 LIMIT 1) AS leader_name

        FROM submission sub
        JOIN student_group sg ON sg.id = sub.group_id
        JOIN student s        ON s.group_id = sg.id

        WHERE sub.id   = ?
          AND sub.type = ?
          AND s.lecture_id = ?

        GROUP BY
            sg.id, sg.name,
            sub.id, sub.file, sub.status, sub.created_at
        LIMIT 1
    ";

        $row = $this->db->query($sql, [$lid, $submisi_id, $type, $lid])->row();

        if (!$row) {
            // Bisa karena: submisi_id tidak ada, type tidak cocok, atau bukan dalam scope dosen ini
            throw new Exception("Submisi tidak ditemukan atau di luar cakupan Anda.");
        }

        return $row;
    }
    /**
     * Ambil detail 1 submisi INDIVIDU (type: 3/4/5) dalam cakupan dosen yang login.
     *
     * @param int $type         3=Modul Ajar, 4=Bahan Ajar, 5=Modul Projek
     * @param int $submisi_id   ID pada tabel submission
     * @return object           Row detail submisi
     * @throws Exception
     */
    public function get_individual_report_submission_by_id($type, $submisi_id)
    {
        // Validasi
        $type = (int) $type;
        if (!in_array($type, [3, 4, 5], true)) {
            throw new Exception("Tipe laporan tidak valid. Gunakan 3 (modul ajar), 4 (bahan ajar), atau 5 (modul projek).");
        }
        $submisi_id = (int) $submisi_id;
        if ($submisi_id <= 0) {
            throw new Exception("Parameter submisi_id wajib diisi.");
        }

        // Dosen dari session
        $nip = $this->CI->session->userdata('nip');
        $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
        if (!$lecturer) {
            throw new Exception("Dosen tidak ditemukan");
        }
        $lid = (int) $lecturer->id;

        // Query: 1 submission individu (type sesuai), dalam scope dosen (s.lecture_id)
        // Kembalikan juga flag sudah dinilai oleh dosen ini
        $sql = "
        SELECT
            s.id        AS student_id,
            s.name      AS student_name,

            sub.id        AS submission_id,
            sub.file      AS file,
            sub.status    AS status,
            sub.created_at,

            -- Samakan bentuk dengan versi grup agar view bisa reuse:
            CAST(s.id AS CHAR) AS member_ids,
            s.name             AS members,

            EXISTS(
                SELECT 1
                FROM report_score r
                WHERE r.submission_id = sub.id
                  AND r.lecture_id    = ?
            ) AS scored_by_me

        FROM submission sub
        JOIN student   s ON s.id = sub.student_id

        WHERE sub.id   = ?
          AND sub.type = ?
          AND s.lecture_id = ?

        LIMIT 1
    ";

        $row = $this->db->query($sql, [$lid, $submisi_id, $type, $lid])->row();

        if (!$row) {
            // Bisa karena: submisi_id tidak ada, type tidak cocok, atau bukan dalam scope dosen ini
            throw new Exception("Submisi individu tidak ditemukan atau di luar cakupan Anda.");
        }

        return $row;
    }
}
