<?php

namespace UseCases\Superadmin;

use UseCases\Superadmin\BaseCase;

defined('BASEPATH') or exit('No direct script access allowed');
class UtilCase extends BaseCase
{
    public function dashboard_data(array $filters = [])
    {
        $programCode = isset($filters['program_code']) ? trim((string) $filters['program_code']) : '';
        $tahunAjaran = isset($filters['tahun_ajaran']) ? trim((string) $filters['tahun_ajaran']) : '';
        $hasFilter = !empty($filters['has_filter']);
        if ($programCode === '' && !$hasFilter) {
            $programCode = 'plp1';
        }

        $summary = [
            'Total Program' => $this->DashboardRepository->get_total_programs(),
            'Total Users' => $this->DashboardRepository->get_total_users(),
            'Mahasiswa' => $this->DashboardRepository->get_total_students(),
            'Guru' => $this->DashboardRepository->get_total_teachers(),
            'Dosen' => $this->DashboardRepository->get_total_lecturers(),
        ];

        $viewData = [
            'summary' => $summary,
            'users_by_role' => $this->DashboardRepository->get_users_by_role(),
            'groups_by_program' => $this->DashboardRepository->get_groups_by_program(),
            'users_by_program' => $this->DashboardRepository->get_users_by_program(),
            'plp1_registrants_by_prodi' => $this->DashboardRepository->get_registrants_by_prodi([
                'program_code' => $programCode,
                'tahun_ajaran' => $tahunAjaran,
            ]),
            'program_code_options' => $this->DashboardRepository->get_program_codes(),
            'program_year_options' => $this->DashboardRepository->get_program_years(),
            'selected_program_code' => $programCode,
            'selected_tahun_ajaran' => $tahunAjaran,
        ];
        return $viewData;
    }

}
