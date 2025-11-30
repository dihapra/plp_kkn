<?php

namespace UseCases\Superadmin;

use UseCases\Superadmin\BaseCase;

defined('BASEPATH') or exit('No direct script access allowed');
class UtilCase extends BaseCase
{
    public function dashboard_data()
    {
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
        ];
        return $viewData;
    }

}