<?php

namespace UseCases\Superadmin;

defined('BASEPATH') or exit('No direct script access allowed');

class MahasiswaCase extends BaseCase
{
    public function create(array $payload): void
    {
        $db = $this->CI->db;

        try {
            $db->trans_begin();

            $data = $this->normalizeAndValidate($payload);

            $nim = $data['nim'];
            $email = $data['email'];

            $userData = [
                'email' => $email,
                'username' => $data['nama'],
                'password' => password_hash($nim, PASSWORD_BCRYPT),
                'role' => 'mahasiswa',
                'fakultas' => null,
                'has_change' => 0,
                'id_program' => null,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $db->insert('users', $userData);
            $userId = (int) $db->insert_id();

            if ($userId <= 0) {
                throw new \RuntimeException('Gagal membuat user untuk mahasiswa.');
            }

            $data['id_user'] = $userId;
            $data['created_at'] = date('Y-m-d H:i:s');

            $this->MahasiswaRepository->create($data);

            $db->trans_commit();
        } catch (\Throwable $e) {
            $db->trans_rollback();
            throw $e;
        }
    }

    public function update(int $id, array $payload): void
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID mahasiswa tidak valid.');
        }

        $db = $this->CI->db;

        try {
            $row = $this->MahasiswaRepository->find($id);
            if (!$row) {
                throw new \InvalidArgumentException('Data mahasiswa tidak ditemukan.');
            }

            $db->trans_begin();

            $data = $this->normalizeAndValidate($payload);

            if (!empty($row->id_user)) {
                $userUpdate = [
                    'email' => $data['email'],
                    'username' => $data['nama'],
                ];

                if (!empty($data['nim']) && $data['nim'] !== $row->nim) {
                    $userUpdate['password'] = password_hash($data['nim'], PASSWORD_BCRYPT);
                }

                $db->where('id', $row->id_user)->update('users', $userUpdate);
            }

            $this->MahasiswaRepository->update($id, $data);

            $db->trans_commit();
        } catch (\Throwable $e) {
            $db->trans_rollback();
            throw $e;
        }
    }

    public function datatable(array $params): array
    {
        $result = $this->MahasiswaRepository->datatable($params);

        return [
            'formatted' => $this->formatter($result),
            'count_total' => $result['count_total'],
            'count_filtered' => $result['count_filtered'],
        ];
    }

    public function updateVerificationStatus(int $id, string $status): void
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID mahasiswa tidak valid.');
        }

        $normalizedStatus = strtolower(trim($status));
        $allowedStatuses = ['verified', 'rejected'];
        if (!in_array($normalizedStatus, $allowedStatuses, true)) {
            throw new \InvalidArgumentException('Status verifikasi tidak dikenal.');
        }

        $student = $this->MahasiswaRepository->findDetail($id);
        if (!$student) {
            throw new \InvalidArgumentException('Data mahasiswa tidak ditemukan.');
        }

        $updatePayload = [
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->getCurrentUserId(),
        ];
        $this->MahasiswaRepository->update($id, $updatePayload);
        $this->updateProgramMahasiswaVerification($student, $normalizedStatus);
        $this->sendVerificationEmail($student, $normalizedStatus);
    }

    public function detailForVerification(int $id): array
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID mahasiswa tidak valid.');
        }

        $registration = $this->MahasiswaRepository->findDetail($id);
        if (!$registration) {
            throw new \InvalidArgumentException('Data pendaftaran tidak ditemukan.');
        }

        $registrationData = [
            'id' => (int) $registration->id,
            'nama' => $registration->nama,
            'nim' => $registration->nim,
            'email' => $registration->email,
            'no_hp' => $registration->no_hp,
            'nama_prodi' => $registration->nama_prodi,
            'fakultas' => $registration->fakultas,
            'kode_program' => $registration->kode_program,
            'nama_program' => $registration->nama_program,
            'tahun_ajaran' => $registration->tahun_ajaran,
            'status' => $registration->status,
            'agama' => $registration->agama,
            'created_at' => $registration->created_at,
            'updated_at' => $registration->updated_at,
            'id_program' => $registration->id_program,
            'id_prodi' => $registration->id_prodi,
        ];

        $referenceData = null;
        if (!empty($registration->nim)) {
            $referenceRow = $this->MahasiswaTrueRepository->findByNim($registration->nim);
            if ($referenceRow) {
                $referenceData = [
                    'id' => (int) $referenceRow->id,
                    'nama' => $referenceRow->nama,
                    'nim' => $referenceRow->nim,
                    'email' => $referenceRow->email,
                    'no_hp' => $referenceRow->no_hp,
                    'nama_prodi' => $referenceRow->nama_prodi,
                    'fakultas' => $referenceRow->fakultas,
                    'kode_program' => $referenceRow->kode_program,
                    'nama_program' => $referenceRow->nama_program,
                    'tahun_ajaran_program' => $referenceRow->tahun_ajaran_program,
                    'created_at' => $referenceRow->created_at,
                    'updated_at' => $referenceRow->updated_at,
                    'id_program' => $referenceRow->id_program,
                    'id_prodi' => $referenceRow->id_prodi,
                ];
            }
        }

        $syaratRow = $this->MahasiswaRepository->getSyaratMapelByMahasiswa($registration->id);
        $syaratData = null;
        if ($syaratRow) {
            $syaratData = [
                'total_sks' => isset($syaratRow['total_sks']) ? (int) $syaratRow['total_sks'] : null,
                'filsafat_pendidikan' => $syaratRow['filsafat_pendidikan'] ?? null,
                'profesi_kependidikan' => $syaratRow['profesi_kependidikan'] ?? null,
                'perkembangan_peserta_didik' => $syaratRow['perkembangan_peserta_didik'] ?? null,
                'psikologi_pendidikan' => $syaratRow['psikologi_pendidikan'] ?? null,
                'updated_at' => $syaratRow['updated_at'] ?? null,
            ];
        }

        return [
            'pendaftaran' => $registrationData,
            'referensi' => $referenceData,
            'syarat' => $syaratData,
        ];
    }

    private function sendVerificationEmail($student, string $status): void
    {
        $emailAddress = $student->email ?? '';
        if ($emailAddress === '') {
            throw new \RuntimeException('Email mahasiswa tidak tersedia sehingga tidak dapat mengirim notifikasi.');
        }

        $smtpConfig = $this->resolveSmtpConfig();
        $this->CI->load->library('email');
        $this->CI->email->initialize($smtpConfig);

        $senderEmail = $smtpConfig['smtp_user'] ?? 'no-reply@plp-kkn.test';
        $senderName = 'Super Admin PLP';
        $studentName = $student->nama ?? 'Mahasiswa';
        $nim = $student->nim ?? '-';
        $loginUrl = base_url('login');

        if ($status === 'verified') {
            $subject = 'Akun PLP/KKN Anda Telah Diverifikasi';
            $message = "
                <p>Halo <strong>{$studentName}</strong>,</p>
                <p>Selamat! Pendaftaran PLP/KKN Anda telah <strong>disetujui</strong>.</p>
                <p>Silakan gunakan kredensial berikut untuk masuk ke sistem:</p>
                <ul>
                    <li><strong>Alamat Email</strong>: {$emailAddress}</li>
                    <li><strong>Password</strong>: {$nim}</li>
                </ul>
                <p>Anda dapat masuk melalui tautan berikut: <a href=\"{$loginUrl}\" target=\"_blank\">{$loginUrl}</a></p>
                <p>Disarankan segera mengganti password setelah berhasil login.</p>
                <p>Terima kasih.</p>
            ";
        } else {
            $subject = 'Status Pendaftaran PLP/KKN';
            $message = "
                <p>Halo <strong>{$studentName}</strong>,</p>
                <p>Mohon maaf, pendaftaran PLP/KKN Anda belum dapat kami setujui saat ini.</p>
                <p>Silakan lengkapi kembali data Anda atau hubungi admin PLP untuk informasi lebih lanjut.</p>
                <p>Terima kasih.</p>
            ";
        }

        $this->CI->email->from($senderEmail, $senderName);
        $this->CI->email->to($emailAddress);
        $this->CI->email->subject($subject);
        $this->CI->email->message($message);

        if (!$this->CI->email->send()) {
            $errorMsg = $this->CI->email->print_debugger(['headers']);
            log_message('error', 'Gagal mengirim email verifikasi mahasiswa: ' . $errorMsg);
            throw new \RuntimeException('Gagal mengirim email notifikasi ke mahasiswa.');
        }
    }

    private function resolveSmtpConfig(): array
    {
        if (!isset($this->CI))
            $this->CI = &get_instance();
        $this->CI->config->load('environment', TRUE);
        $env = $this->CI->config->item('env', 'environment');
        $smtpUser = $env['SMTP_USER'] ?: 'supplpkkn@gmail.com';
        $smtpPass = $env['SMTP_PASS'];
        $smtpHost = 'smtp.gmail.com';
        $smtpPort = 587;
        $smtpCrypto = 'tls';

        if ($smtpUser === '' || $smtpPass === '') {
            throw new \RuntimeException('Konfigurasi SMTP tidak lengkap.');
        }

        return [
            'protocol' => 'smtp',
            'smtp_host' => $smtpHost,
            'smtp_user' => $smtpUser,
            'smtp_pass' => $smtpPass,
            'smtp_port' => (int) $smtpPort,
            'smtp_crypto' => $smtpCrypto,
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n",
            'crlf' => "\r\n",
        ];
    }

    private function getCurrentUserId(): ?int
    {
        $userId = $this->CI->session->userdata('id_user');
        return $userId ? (int) $userId : null;
    }

    private function updateProgramMahasiswaVerification($student, string $status): void
    {
        if (empty($student) || empty($student->id_program_mahasiswa)) {
            return;
        }

        $now = date('Y-m-d H:i:s');
        $userId = $this->getCurrentUserId();

        $payload = [
            'status' => $status,
            'verified_by' => $userId,
            'verified_at' => $now,
            'updated_at' => $now,
            'updated_by' => $userId,
        ];

        $this->CI->db
            ->where('id', (int) $student->id_program_mahasiswa)
            ->update('program_mahasiswa', $payload);
    }

    private function formatter(array $result): array
    {
        $formatter = [];
        foreach ($result['query'] as $r) {
            $formatter[] = [
                'id' => $r->id,
                'id_user' => $r->id_user,
                'nama' => $r->nama,
                'nim' => $r->nim,
                'email' => $r->email,
                'no_hp' => $r->no_hp,
                'id_prodi' => $r->id_prodi,
                'fakultas' => $r->fakultas,
                'nama_prodi' => $r->nama_prodi,
                'nama_sekolah' => $r->nama_sekolah,
                'id_program' => $r->id_program,
                'id_program_mahasiswa' => $r->id_program_mahasiswa ?? null,
                'nama_program' => $r->nama_program,
                'kode_program' => $r->kode_program,
                'tahun_ajaran' => $r->tahun_ajaran,
                'status' => $r->status,
            ];
        }

        return $formatter;
    }

    private function normalizeAndValidate(array $input): array
    {
        $nama = isset($input['nama']) ? trim($input['nama']) : '';
        $nim = isset($input['nim']) ? trim($input['nim']) : '';
        $email = isset($input['email']) ? trim($input['email']) : '';
        $noHp = isset($input['no_hp']) ? trim($input['no_hp']) : null;
        $idProdi = isset($input['id_prodi']) ? (int) $input['id_prodi'] : 0;

        if ($nama === '' || $nim === '' || $email === '' || $idProdi <= 0) {
            throw new \InvalidArgumentException('Nama, NIM, email, dan prodi wajib diisi.');
        }

        $data = [
            'nama' => $nama,
            'nim' => $nim,
            'email' => $email,
            'no_hp' => $noHp,
            'id_prodi' => $idProdi,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        return $data;
    }
}
