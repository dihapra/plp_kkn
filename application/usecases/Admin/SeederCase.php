<?php

namespace UseCases\Admin;

use Exception;

defined('BASEPATH') or exit('No direct script access allowed');

class SeederCase
{
    private $CI;
    private $data;
    private $dept;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->database();
    }

    public function init_dept_data()
    {
        $this->dept = [
            ['dept_code' => 1, 'nama' => 'PPM Pendidikan'],
            ['dept_code' => 2, 'nama' => 'PPM Soshum'],
            ['dept_code' => 3, 'nama' => 'PPM Saintek'],
            ['dept_code' => 4, 'nama' => 'Pusat KI'],
            ['dept_code' => 5, 'nama' => 'KKN & PLP'],
            ['dept_code' => 6, 'nama' => 'Pusat Inovasi'],
            ['dept_code' => 7, 'nama' => 'Keuangan'],
            ['dept_code' => 8, 'nama' => 'Bagian Administrasi'],
        ];
    }

    public function admin_seeder()
    {
        return [
            [
                'username'   => 'Anada Leo Virganta, S.Pd., M.Pd.',
                'email'      => 'admin.anadaleovirganta@unimed.ac.id',
                'password'   => password_hash('markombur2026', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS ILMU PENDIDIKAN',
                'has_change' => 1,
            ],
            [
                'username'   => 'Rini Juliana Sipahutar, M.Kom.',
                'email'      => 'admin.rinijuliana@unimed.ac.id',
                'password'   => password_hash('markombur2026', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS ILMU PENDIDIKAN',
                'has_change' => 1,
            ],
            [
                'username'   => 'Merdy Roy Sunarya Togatorop, S.Pd., M.Sn.',
                'email'      => 'admin.togatorop.roy@unimed.ac.id',
                'password'   => password_hash('markombur2026', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS BAHASA DAN SENI',
                'has_change' => 1,
            ],
            [
                'username'   => 'Lili Tansliova, M.Pd.',
                'email'      => 'admin.lilitans@unimed.ac.id',
                'password'   => password_hash('markombur2026', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS BAHASA DAN SENI',
                'has_change' => 1,
            ],
            [
                'username'   => 'Abd. Haris Nasution, S.Pd., M.Pd.',
                'email'      => 'admin.abdharisnasution@unimed.ac.id',
                'password'   => password_hash('markombur2026', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS ILMU SOSIAL',
                'has_change' => 1,
            ],
            [
                'username'   => 'Mulhady Putra, S.Pd., M.Sc.',
                'email'      => 'admin.mulhadyputra@unimed.ac.id',
                'password'   => password_hash('markombur2026', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS ILMU SOSIAL',
                'has_change' => 1,
            ],
            [
                'username'   => 'Suvriadi Panggabean, M.Si.',
                'email'      => 'admin.suvriadi@unimed.ac.id',
                'password'   => password_hash('markombur2026', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM',
                'has_change' => 1,
            ],
            [
                'username'   => 'Freddy Tua Musa Panggabean, S.Pd., M.Pd.',
                'email'      => 'admin.freddypanggabean@unimed.ac.id',
                'password'   => password_hash('markombur2026', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM',
                'has_change' => 1,
            ],
            [
                'username'   => 'Riansyah Putra, M.Pd.',
                'email'      => 'admin.riansyahputra@unimed.ac.id',
                'password'   => password_hash('markombur2026', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS TEKNIK',
                'has_change' => 1,
            ],
            [
                'username'   => 'Aditiya Pratama Daryana, S.Par., M.M.',
                'email'      => 'admin.aditiya@unimed.ac.id',
                'password'   => password_hash('markombur2026', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS TEKNIK',
                'has_change' => 1,
            ],
            [
                'username'   => 'Dr. Samsuddin Siregar, S.Pd., M.Or.',
                'email'      => 'admin.samsuddinsiregar@unimed.ac.id',
                'password'   => password_hash('markombur2026', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS ILMU KEOLAHRAGAAN',
                'has_change' => 1,
            ],
            [
                'username'   => 'Dr. Nurman Hasibuan, S.Pd., M.Or.',
                'email'      => 'admin.nurmanhasibuan@unimed.ac.id',
                'password'   => password_hash('markombur2026', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS ILMU KEOLAHRAGAAN',
                'has_change' => 1,
            ],
            [
                'username'   => 'Andi Taufiq Umar, M.Pd.',
                'email'      => 'admin.a.taufiq.u@unimed.ac.id',
                'password'   => password_hash('markombur2026', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS EKONOMI',
                'has_change' => 1,
            ],
            [
                'username'   => 'Jabal Ahsan, S.Pd., M.Pd.',
                'email'      => 'admin.jabalahsan@unimed.ac.id',
                'password'   => password_hash('markombur2026', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS EKONOMI',
                'has_change' => 1,
            ],
        ];
    }

    public function initialize_data()
    {
        $this->data = $this->admin_seeder();
    }

    private function insert_users_if_missing(array $accounts, string $label)
    {
        $newData = [];
        foreach ($accounts as $row) {
            $existing = $this->CI->db
                ->select('email')
                ->from('users')
                ->where('email', $row['email'])
                ->get()
                ->row();

            if (!$existing) {
                $newData[] = $row;
            } else {
                log_message('info', ucfirst($label) . ' email ' . $row['email'] . ' sudah terdaftar, dilewati.');
            }
        }

        if (empty($newData)) {
            echo "Semua {$label} sudah terdaftar. Tidak ada data baru yang perlu diinsert.";
            return;
        }

        $this->CI->db->trans_start();
        $this->CI->db->insert_batch('users', $newData);
        $this->CI->db->trans_complete();

        if ($this->CI->db->trans_status() === false) {
            throw new Exception('Seeding ' . $label . ' failed.');
        }

        $this->assignActiveProgramsToUsers(array_column($newData, 'email'));

        echo "Seeding {$label} completed successfully!";
    }

    private function assignActiveProgramsToUsers(array $emails): void
    {
        if (empty($emails)) {
            return;
        }

        $programs = $this->CI->db
            ->select('id')
            ->from('program')
            ->where('active', 1)
            ->get()
            ->result_array();

        if (empty($programs)) {
            return;
        }

        $programIds = array_map('intval', array_column($programs, 'id'));

        $users = $this->CI->db
            ->select('id')
            ->from('users')
            ->where_in('email', $emails)
            ->get()
            ->result_array();

        if (empty($users)) {
            return;
        }

        $userIds = array_map('intval', array_column($users, 'id'));

        $existingRows = $this->CI->db
            ->select('id_user, id_program')
            ->from('akses_modul_user')
            ->where_in('id_user', $userIds)
            ->where_in('id_program', $programIds)
            ->get()
            ->result_array();

        $existingMap = [];
        foreach ($existingRows as $row) {
            $existingMap[$row['id_user'] . ':' . $row['id_program']] = true;
        }

        $now = date('Y-m-d H:i:s');
        $rows = [];
        foreach ($userIds as $userId) {
            foreach ($programIds as $programId) {
                $key = $userId . ':' . $programId;
                if (isset($existingMap[$key])) {
                    continue;
                }
                $rows[] = [
                    'id_user' => $userId,
                    'id_program' => $programId,
                    'aktif' => 1,
                    'created_at' => $now,
                ];
            }
        }

        if (!empty($rows)) {
            $this->CI->db->insert_batch('akses_modul_user', $rows);
        }
    }

    public function wd_seeder()
    {
        $wd1_accounts = [
            [
                'username'   => 'WD1 FIS',
                'email'      => 'wd1fis@gmail.com',
                'password'   => password_hash('wd1fis', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS ILMU SOSIAL',
                'has_change' => 1,
            ],
            [
                'username'   => 'WD1 FIP',
                'email'      => 'wd1fip@gmail.com',
                'password'   => password_hash('wd1fip', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS ILMU PENDIDIKAN',
                'has_change' => 1,
            ],
            [
                'username'   => 'WD1 FIK',
                'email'      => 'wd1fik@gmail.com',
                'password'   => password_hash('wd1fik', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS ILMU KEOLAHRAGAAN',
                'has_change' => 1,
            ],
            [
                'username'   => 'WD1 FMIPA',
                'email'      => 'wd1fmipa@gmail.com',
                'password'   => password_hash('wd1fmipa', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM',
                'has_change' => 1,
            ],
            [
                'username'   => 'WD1 FT',
                'email'      => 'wd1ft@gmail.com',
                'password'   => password_hash('wd1ft', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS TEKNIK',
                'has_change' => 1,
            ],
            [
                'username'   => 'WD1 FBS',
                'email'      => 'wd1fbs@gmail.com',
                'password'   => password_hash('wd1fbs', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS BAHASA DAN SENI',
                'has_change' => 1,
            ],
            [
                'username'   => 'WD1 FE',
                'email'      => 'wd1fe@gmail.com',
                'password'   => password_hash('wd1fe', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS EKONOMI',
                'has_change' => 1,
            ],
            [
                'username'   => 'Ketua LPPM',
                'email'      => 'ketualppm@gmail.com',
                'password'   => password_hash('lppm2025', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => null,
                'has_change' => 1,
            ],
            [
                'username'   => 'Sekertaris LPPM',
                'email'      => 'sekertarislppm@gmail.com',
                'password'   => password_hash('lppm2025', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => null,
                'has_change' => 1,
            ],
        ];

        try {
            $this->insert_users_if_missing($wd1_accounts, 'WD1 accounts');
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function kaprodi_seeder()
    {
        try {
            $prodis = $this->CI->db
                ->select('id, nama, fakultas')
                ->from('prodi')
                ->get()
                ->result_array();

            if (empty($prodis)) {
                echo 'Data prodi kosong. Tidak ada akun kaprodi yang dibuat.';
                return;
            }

            $existingKaprodiRows = $this->CI->db
                ->select('id_prodi, email')
                ->from('kaprodi')
                ->get()
                ->result_array();

            $existingKaprodiMap = [];
            $usedEmails = [];
            foreach ($existingKaprodiRows as $row) {
                $existingKaprodiMap[(int) $row['id_prodi']] = true;
                if (!empty($row['email'])) {
                    $usedEmails[strtolower($row['email'])] = true;
                }
            }

            $existingUserRows = $this->CI->db
                ->select('id, email, role')
                ->from('users')
                ->like('email', 'kaprodi', 'after')
                ->get()
                ->result_array();

            $existingUserMap = [];
            foreach ($existingUserRows as $row) {
                $emailKey = strtolower($row['email']);
                $existingUserMap[$emailKey] = [
                    'id' => (int) $row['id'],
                    'role' => $row['role'],
                ];
            }

            $this->CI->db->trans_begin();

            $created = 0;
            $skipped = 0;
            $now = date('Y-m-d H:i:s');

            foreach ($prodis as $prodi) {
                $prodiId = (int) $prodi['id'];
                if (isset($existingKaprodiMap[$prodiId])) {
                    $skipped++;
                    continue;
                }

                $initials = $this->buildProdiInitials($prodi['nama']);
                $baseLocal = 'kaprodi' . ($initials !== '' ? $initials : $prodiId);

                $suffix = 1;
                $localPart = $baseLocal;
                while (true) {
                    $email = $localPart . '@gmail.com';
                    $emailKey = strtolower($email);

                    if (isset($usedEmails[$emailKey])) {
                        $suffix++;
                        $localPart = $baseLocal . $suffix;
                        continue;
                    }

                    if (isset($existingUserMap[$emailKey]) && $existingUserMap[$emailKey]['role'] !== 'kaprodi') {
                        $usedEmails[$emailKey] = true;
                        $suffix++;
                        $localPart = $baseLocal . $suffix;
                        continue;
                    }

                    $usedEmails[$emailKey] = true;
                    break;
                }

                $email = $localPart . '@gmail.com';
                $emailKey = strtolower($email);

                if (isset($existingUserMap[$emailKey])) {
                    $userId = $existingUserMap[$emailKey]['id'];
                } else {
                    $userData = [
                        'username'   => 'Kaprodi ' . $prodi['nama'],
                        'email'      => $email,
                        'password'   => password_hash($email, PASSWORD_BCRYPT),
                        'role'       => 'kaprodi',
                        'fakultas'   => $prodi['fakultas'],
                        'has_change' => 0,
                        'id_program' => null,
                        'created_at' => $now,
                    ];

                    $this->CI->db->insert('users', $userData);
                    $userId = (int) $this->CI->db->insert_id();

                    if ($userId <= 0) {
                        throw new Exception('Gagal membuat user kaprodi untuk prodi ' . $prodi['nama']);
                    }

                    $existingUserMap[$emailKey] = [
                        'id' => $userId,
                        'role' => 'kaprodi',
                    ];
                }

                $kaprodiData = [
                    'id_user'    => $userId,
                    'id_prodi'   => $prodiId,
                    'nama'       => 'Kaprodi ' . $prodi['nama'],
                    'no_hp'      => null,
                    'email'      => $email,
                    'created_at' => $now,
                ];

                $this->CI->db->insert('kaprodi', $kaprodiData);
                $created++;
            }

            if ($this->CI->db->trans_status() === false) {
                $this->CI->db->trans_rollback();
                throw new Exception('Seeding kaprodi failed.');
            }

            $this->CI->db->trans_commit();
            echo "Seeding kaprodi completed. {$created} dibuat, {$skipped} dilewati.";
        } catch (Exception $e) {
            $this->CI->db->trans_rollback();
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function plotting_seeder()
    {
        try {
            $now = date('Y-m-d H:i:s');
            $programRow = $this->CI->db
                ->select('id')
                ->from('program')
                ->where('active', 1)
                ->order_by('updated_at', 'DESC')
                ->order_by('id', 'DESC')
                ->limit(1)
                ->get()
                ->row_array();

            if ($programRow) {
                $programId = (int) $programRow['id'];
            } else {
                $programData = [
                    'kode' => 'plp_kkn_test',
                    'nama' => 'PLP KKN Test Plotting',
                    'tahun_ajaran' => '2025/2026',
                    'semester' => 'Ganjil',
                    'active' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $this->CI->db->insert('program', $programData);
                $programId = (int) $this->CI->db->insert_id();
                if ($programId <= 0) {
                    throw new Exception('Gagal membuat program test.');
                }
            }

            $prodiName = 'Prodi Test Plotting';
            $prodiRow = $this->CI->db
                ->select('id, fakultas')
                ->from('prodi')
                ->where('nama', $prodiName)
                ->limit(1)
                ->get()
                ->row_array();

            if ($prodiRow) {
                $prodiId = (int) $prodiRow['id'];
                $prodiFakultas = $prodiRow['fakultas'] ?? 'FAKULTAS TEST';
            } else {
                $prodiData = [
                    'nama' => $prodiName,
                    'fakultas' => 'FAKULTAS TEST',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $this->CI->db->insert('prodi', $prodiData);
                $prodiId = (int) $this->CI->db->insert_id();
                $prodiFakultas = 'FAKULTAS TEST';
                if ($prodiId <= 0) {
                    throw new Exception('Gagal membuat prodi test.');
                }
            }

            $kaprodiEmailBase = 'kaprodi.plotting';
            $kaprodiPassword = 'kaprodi.plotting';
            $kaprodiEmail = $kaprodiEmailBase . '@gmail.com';
            $suffix = 1;
            while (true) {
                $existingUser = $this->CI->db
                    ->select('id')
                    ->from('users')
                    ->where('email', $kaprodiEmail)
                    ->limit(1)
                    ->get()
                    ->row_array();
                if (!$existingUser) {
                    break;
                }
                $suffix++;
                $kaprodiEmail = $kaprodiEmailBase . $suffix . '@gmail.com';
            }

            $this->CI->db->trans_begin();

            $this->CI->db->insert('users', [
                'username' => 'Kaprodi Plotting Test',
                'email' => $kaprodiEmail,
                'password' => password_hash($kaprodiPassword, PASSWORD_BCRYPT),
                'role' => 'kaprodi',
                'fakultas' => $prodiFakultas,
                'has_change' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $kaprodiUserId = (int) $this->CI->db->insert_id();
            if ($kaprodiUserId <= 0) {
                throw new Exception('Gagal membuat user kaprodi.');
            }

            $this->CI->db->insert('kaprodi', [
                'id_user' => $kaprodiUserId,
                'id_prodi' => $prodiId,
                'nama' => 'Kaprodi Plotting Test',
                'email' => $kaprodiEmail,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $aksesRow = $this->CI->db
                ->select('id')
                ->from('akses_modul_user')
                ->where('id_user', $kaprodiUserId)
                ->where('id_program', $programId)
                ->limit(1)
                ->get()
                ->row_array();
            if (!$aksesRow) {
                $this->CI->db->insert('akses_modul_user', [
                    'id_user' => $kaprodiUserId,
                    'id_program' => $programId,
                    'aktif' => 1,
                    'created_at' => $now,
                ]);
            }

            $dosenRows = [];
            $dosenUsers = [
                ['name' => 'DPL Plotting A', 'email' => 'dpl.plotting.a@gmail.com'],
                ['name' => 'DPL Plotting B', 'email' => 'dpl.plotting.b@gmail.com'],
            ];

            foreach ($dosenUsers as $index => $dosenUser) {
                $email = $dosenUser['email'];
                $userRow = $this->CI->db
                    ->select('id')
                    ->from('users')
                    ->where('email', $email)
                    ->limit(1)
                    ->get()
                    ->row_array();
                if (!$userRow) {
                    $this->CI->db->insert('users', [
                        'username' => $dosenUser['name'],
                        'email' => $email,
                        'password' => password_hash($email, PASSWORD_BCRYPT),
                        'role' => 'dosen',
                        'fakultas' => $prodiFakultas,
                        'has_change' => 0,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                    $userId = (int) $this->CI->db->insert_id();
                } else {
                    $userId = (int) $userRow['id'];
                }

                $dosenRow = $this->CI->db
                    ->select('id')
                    ->from('dosen')
                    ->where('id_user', $userId)
                    ->limit(1)
                    ->get()
                    ->row_array();
                if (!$dosenRow) {
                    $this->CI->db->insert('dosen', [
                        'id_user' => $userId,
                        'nama' => $dosenUser['name'],
                        'nidn' => 'NIDNTEST' . ($index + 1),
                        'email' => $email,
                        'no_hp' => null,
                        'id_prodi' => $prodiId,
                        'fakultas' => $prodiFakultas,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                    $dosenId = (int) $this->CI->db->insert_id();
                } else {
                    $dosenId = (int) $dosenRow['id'];
                }

                $dosenRows[] = $dosenId;

                $programDosenRow = $this->CI->db
                    ->select('id')
                    ->from('program_dosen')
                    ->where('id_program', $programId)
                    ->where('id_dosen', $dosenId)
                    ->limit(1)
                    ->get()
                    ->row_array();
                if (!$programDosenRow) {
                    $this->CI->db->insert('program_dosen', [
                        'id_program' => $programId,
                        'id_dosen' => $dosenId,
                        'status' => 'active',
                        'valid_from' => $now,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }

            $schoolNames = ['SMA Plotting 1', 'SMA Plotting 2', 'SMA Plotting 3'];
            $schoolIds = [];
            foreach ($schoolNames as $schoolName) {
                $schoolRow = $this->CI->db
                    ->select('id')
                    ->from('sekolah')
                    ->where('nama', $schoolName)
                    ->limit(1)
                    ->get()
                    ->row_array();
                if ($schoolRow) {
                    $schoolId = (int) $schoolRow['id'];
                } else {
                    $this->CI->db->insert('sekolah', [
                        'nama' => $schoolName,
                        'alamat' => 'Alamat ' . $schoolName,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                    $schoolId = (int) $this->CI->db->insert_id();
                }

                $schoolIds[] = $schoolId;

                $programSekolahRow = $this->CI->db
                    ->select('id')
                    ->from('program_sekolah')
                    ->where('id_program', $programId)
                    ->where('id_sekolah', $schoolId)
                    ->limit(1)
                    ->get()
                    ->row_array();
                if (!$programSekolahRow) {
                    $this->CI->db->insert('program_sekolah', [
                        'id_program' => $programId,
                        'id_sekolah' => $schoolId,
                        'status' => 'verified',
                        'valid_from' => $now,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                    $programSekolahId = (int) $this->CI->db->insert_id();
                } else {
                    $programSekolahId = (int) $programSekolahRow['id'];
                }

                $programSekolahProdiRow = $this->CI->db
                    ->select('id')
                    ->from('program_sekolah_prodi')
                    ->where('id_program_sekolah', $programSekolahId)
                    ->where('id_prodi', $prodiId)
                    ->limit(1)
                    ->get()
                    ->row_array();
                if (!$programSekolahProdiRow) {
                    $this->CI->db->insert('program_sekolah_prodi', [
                        'id_program_sekolah' => $programSekolahId,
                        'id_prodi' => $prodiId,
                        'status' => 'verified',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }

            $studentCount = 22;
            $studentIds = [];
            for ($i = 1; $i <= $studentCount; $i++) {
                $email = 'mhs.plotting' . $i . '@gmail.com';
                $nim = 'TST2026' . str_pad((string) $i, 3, '0', STR_PAD_LEFT);

                $userRow = $this->CI->db
                    ->select('id')
                    ->from('users')
                    ->where('email', $email)
                    ->limit(1)
                    ->get()
                    ->row_array();
                if ($userRow) {
                    $userId = (int) $userRow['id'];
                } else {
                    $this->CI->db->insert('users', [
                        'username' => 'Mahasiswa Plotting ' . $i,
                        'email' => $email,
                        'password' => password_hash($email, PASSWORD_BCRYPT),
                        'role' => 'mahasiswa',
                        'fakultas' => $prodiFakultas,
                        'has_change' => 0,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                    $userId = (int) $this->CI->db->insert_id();
                }

                $mahasiswaRow = $this->CI->db
                    ->select('id')
                    ->from('mahasiswa')
                    ->where('id_user', $userId)
                    ->limit(1)
                    ->get()
                    ->row_array();
                if ($mahasiswaRow) {
                    $mahasiswaId = (int) $mahasiswaRow['id'];
                } else {
                    $nimRow = $this->CI->db
                        ->select('id')
                        ->from('mahasiswa')
                        ->where('nim', $nim)
                        ->limit(1)
                        ->get()
                        ->row_array();
                    if ($nimRow) {
                        $mahasiswaId = (int) $nimRow['id'];
                    } else {
                        $this->CI->db->insert('mahasiswa', [
                            'id_user' => $userId,
                            'nama' => 'Mahasiswa Plotting ' . $i,
                            'nim' => $nim,
                            'email' => $email,
                            'no_hp' => '081200000' . str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                            'id_prodi' => $prodiId,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                        $mahasiswaId = (int) $this->CI->db->insert_id();
                    }
                }

                $studentIds[] = $mahasiswaId;
            }

            $dosenAId = $dosenRows[0] ?? 0;
            $dosenBId = $dosenRows[1] ?? 0;
            $schoolAId = $schoolIds[0] ?? 0;
            $schoolBId = $schoolIds[1] ?? 0;

            foreach ($studentIds as $index => $mahasiswaId) {
                $assignedDosen = null;
                $assignedSchool = null;

                if ($index < 5) {
                    $assignedDosen = $dosenAId;
                    $assignedSchool = $schoolAId;
                } elseif ($index < 10) {
                    $assignedDosen = $dosenAId;
                    $assignedSchool = $schoolBId;
                }

                $existingPm = $this->CI->db
                    ->select('id')
                    ->from('program_mahasiswa')
                    ->where('id_program', $programId)
                    ->where('id_mahasiswa', $mahasiswaId)
                    ->limit(1)
                    ->get()
                    ->row_array();

                $pmData = [
                    'id_program' => $programId,
                    'id_mahasiswa' => $mahasiswaId,
                    'id_sekolah' => $assignedSchool,
                    'id_dosen' => $assignedDosen,
                    'status' => 'verified',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if ($existingPm) {
                    $this->CI->db
                        ->where('id', (int) $existingPm['id'])
                        ->update('program_mahasiswa', $pmData);
                } else {
                    $this->CI->db->insert('program_mahasiswa', $pmData);
                }
            }

            if ($this->CI->db->trans_status() === false) {
                $this->CI->db->trans_rollback();
                throw new Exception('Seeding plotting failed.');
            }

            $this->CI->db->trans_commit();
            echo 'Seeder plotting completed. Kaprodi login: ' . $kaprodiEmail . ' / ' . $kaprodiPassword;
        } catch (Exception $e) {
            $this->CI->db->trans_rollback();
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function run()
    {
        $this->super_admin_seeder();
    }

    public function mahasiswa_seeder()
    {
        $data = [
            'user' => [
                'username' => 'Dimas Pratama',
                'email' => 'dimas@example.com',
                'password' => password_hash('123456', PASSWORD_BCRYPT),
                'role' => 'mahasiswa',
                'fakultas' => 'FAKULTAS ILMU PENDIDIKAN',
                'has_change' => 1,
            ],
            'mahasiswa' => [
                ''
            ]
        ];
    }

    public function user_seeder()
    {
        try {
            $this->insert_users_if_missing($this->data, 'users');
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function super_admin_seeder()
    {
        $superAdminAccounts = [
            [
                'username'   => 'Roni Sinaga',
                'email'      => 'ronisinaga@unimed.ac.id',
                'password'   => password_hash('112390_Roni@2025', PASSWORD_BCRYPT),
                'role'       => 'super_admin',
                'fakultas'   => null,
                'has_change' => 1,
            ],
        ];

        try {
            $this->insert_users_if_missing($superAdminAccounts, 'super admin');
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    private function buildProdiInitials(string $name): string
    {
        $clean = preg_replace('/[^a-zA-Z0-9 ]+/', ' ', $name);
        $parts = preg_split('/\s+/', trim($clean));
        if (empty($parts)) {
            return '';
        }

        $initials = '';
        foreach ($parts as $part) {
            if ($part === '') {
                continue;
            }
            $initials .= strtolower($part[0]);
        }

        return $initials;
    }
}
