<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Seeder_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private $data;
    private $dept;
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
                'password'   => password_hash('markombur2025', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS ILMU PENDIDIKAN',
                'has_change' => 1,
            ],
            [
                'username'   => 'Rini Juliana Sipahutar, M.Kom.',
                'email'      => 'admin.rinijuliana@unimed.ac.id',
                'password'   => password_hash('markombur2025', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS ILMU PENDIDIKAN',
                'has_change' => 1,
            ],
            [
                'username'   => 'Merdy Roy Sunarya Togatorop, S.Pd., M.Sn.',
                'email'      => 'admin.togatorop.roy@unimed.ac.id',
                'password'   => password_hash('markombur2025', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS BAHASA DAN SENI',
                'has_change' => 1,
            ],
            [
                'username'   => 'Lili Tansliova, M.Pd.',
                'email'      => 'admin.lilitans@unimed.ac.id',
                'password'   => password_hash('markombur2025', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS BAHASA DAN SENI',
                'has_change' => 1,
            ],
            [
                'username'   => 'Abd. Haris Nasution, S.Pd., M.Pd.',
                'email'      => 'admin.abdharisnasution@unimed.ac.id',
                'password'   => password_hash('markombur2025', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS ILMU SOSIAL',
                'has_change' => 1,
            ],
            [
                'username'   => 'Mulhady Putra, S.Pd., M.Sc.',
                'email'      => 'admin.mulhadyputra@unimed.ac.id',
                'password'   => password_hash('markombur2025', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS ILMU SOSIAL',
                'has_change' => 1,
            ],
            [
                'username'   => 'Suvriadi Panggabean, M.Si.',
                'email'      => 'admin.suvriadi@unimed.ac.id',
                'password'   => password_hash('markombur2025', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM',
                'has_change' => 1,
            ],
            [
                'username'   => 'Freddy Tua Musa Panggabean, S.Pd., M.Pd.',
                'email'      => 'admin.freddypanggabean@unimed.ac.id',
                'password'   => password_hash('markombur2025', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM',
                'has_change' => 1,
            ],
            [
                'username'   => 'Riansyah Putra, M.Pd.',
                'email'      => 'admin.riansyahputra@unimed.ac.id',
                'password'   => password_hash('markombur2025', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS TEKNIK',
                'has_change' => 1,
            ],
            [
                'username'   => 'Aditiya Pratama Daryana, S.Par., M.M.',
                'email'      => 'admin.aditiya@unimed.ac.id',
                'password'   => password_hash('markombur2025', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS TEKNIK',
                'has_change' => 1,
            ],
            [
                'username'   => 'Dr. Samsuddin Siregar, S.Pd., M.Or.',
                'email'      => 'admin.samsuddinsiregar@unimed.ac.id',
                'password'   => password_hash('markombur2025', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS ILMU KEOLAHRAGAAN',
                'has_change' => 1,
            ],
            [
                'username'   => 'Dr. Nurman Hasibuan, S.Pd., M.Or.',
                'email'      => 'admin.nurmanhasibuan@unimed.ac.id',
                'password'   => password_hash('markombur2025', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS ILMU KEOLAHRAGAAN',
                'has_change' => 1,
            ],
            [
                'username'   => 'Andi Taufiq Umar, M.Pd.',
                'email'      => 'admin.a.taufiq.u@unimed.ac.id',
                'password'   => password_hash('markombur2025', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'fakultas'   => 'FAKULTAS EKONOMI',
                'has_change' => 1,
            ],
            [
                'username'   => 'Jabal Ahsan, S.Pd., M.Pd.',
                'email'      => 'admin.jabalahsan@unimed.ac.id',
                'password'   => password_hash('markombur2025', PASSWORD_BCRYPT),
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
            $existing = $this->db
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

        $this->db->trans_start();
        $this->db->insert_batch('users', $newData);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            throw new Exception('Seeding ' . $label . ' failed.');
        }

        echo "Seeding {$label} completed successfully!";
    }


    public function wd_seeder()
    {
        $wd1_accounts = [
            [
                'username'   => 'WD1 FIS',
                'email'      => 'wd1fis@gmail.com',
                'password'   => password_hash('wd1fis', PASSWORD_BCRYPT),
                'role'       => 'admin_guest',
                'fakultas'   => 'FAKULTAS ILMU SOSIAL',
                'has_change' => 1,
            ],
            [
                'username'   => 'WD1 FIP',
                'email'      => 'wd1fip@gmail.com',
                'password'   => password_hash('wd1fip', PASSWORD_BCRYPT),
                'role'       => 'admin_guest',
                'fakultas'   => 'FAKULTAS ILMU PENDIDIKAN',
                'has_change' => 1,
            ],
            [
                'username'   => 'WD1 FIK',
                'email'      => 'wd1fik@gmail.com',
                'password'   => password_hash('wd1fik', PASSWORD_BCRYPT),
                'role'       => 'admin_guest',
                'fakultas'   => 'FAKULTAS ILMU KEOLAHRAGAAN',
                'has_change' => 1,
            ],
            [
                'username'   => 'WD1 FMIPA',
                'email'      => 'wd1fmipa@gmail.com',
                'password'   => password_hash('wd1fmipa', PASSWORD_BCRYPT),
                'role'       => 'admin_guest',
                'fakultas'   => 'FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM',
                'has_change' => 1,
            ],
            [
                'username'   => 'WD1 FT',
                'email'      => 'wd1ft@gmail.com',
                'password'   => password_hash('wd1ft', PASSWORD_BCRYPT),
                'role'       => 'admin_guest',
                'fakultas'   => 'FAKULTAS TEKNIK',
                'has_change' => 1,
            ],
            [
                'username'   => 'WD1 FBS',
                'email'      => 'wd1fbs@gmail.com',
                'password'   => password_hash('wd1fbs', PASSWORD_BCRYPT),
                'role'       => 'admin_guest',
                'fakultas'   => 'FAKULTAS BAHASA DAN SENI',
                'has_change' => 1,
            ],
            [
                'username'   => 'WD1 FE',
                'email'      => 'wd1fe@gmail.com',
                'password'   => password_hash('wd1fe', PASSWORD_BCRYPT),
                'role'       => 'admin_guest',
                'fakultas'   => 'FAKULTAS EKONOMI',
                'has_change' => 1,
            ],
            [
                'username'   => 'Ketua LPPM',
                'email'      => 'ketualppm@gmail.com',
                'password'   => password_hash('lppm2025', PASSWORD_BCRYPT),
                'role'       => 'admin_guest',
                'fakultas'   => null,
                'has_change' => 1,
            ],
            [
                'username'   => 'Sekertaris LPPM',
                'email'      => 'sekertarislppm@gmail.com',
                'password'   => password_hash('lppm2025', PASSWORD_BCRYPT),
                'role'       => 'admin_guest',
                'fakultas'   => null,
                'has_change' => 1,
            ],

        ];

        try {
            $this->insert_users_if_missing($wd1_accounts, 'WD1 accounts');
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function run()
    {
        // $this->initialize_data();
        $this->super_admin_seeder();
        // $this->user_seeder();
        // $this->wd_seeder();
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
                'hasChange' => 1,
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
            echo "Error: " . $e->getMessage();
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
            echo "Error: " . $e->getMessage();
        }
    }
}
