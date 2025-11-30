# Dosen (Lecturer) Features Overview

This document outlines the key functionalities available to users with the 'dosen' (lecturer) role within the PLP2 application, based on the `application/controllers/Dosen.php` controller and associated use cases.

## Core Functionalities

### 1. Dashboard
*   **Overview:** Provides a summary or main entry point for the lecturer, likely displaying key metrics or quick links.
    *   **Controller Method:** `index()`
    *   **Use Case:** `DashboardData`

### 2. Student Management
*   **View Assigned Students:** Lecturers can view a list of students assigned to them.
    *   **Controller Method:** `mahasiswa()`
    *   **View:** `dosen/mahasiswa`
*   **Student Datatable:** Provides dynamic, searchable, and sortable lists of students.
    *   **Controller Method:** `datatable_student()`
    *   **Use Case:** `StudentCase` (specifically `lecture_datatable` and `student_formatter`)

### 3. Attendance Management
*   **View Attendance Records:** Lecturers can view attendance records for their assigned students.
    *   **Controller Method:** `absensi()`
    *   **View:** `dosen/absensi`
*   **Attendance Datatable:** Displays attendance data in a tabular format.
    *   **Controller Method:** `datatable_absensi()`
    *   **Use Case:** `AbsensiCase` (specifically `datatable` and `absensi_formatter`)
*   **Verify Attendance:** Lecturers can mark or verify student attendance.
    *   **Controller Method:** `save_absensi()`
    *   **Use Case:** `AbsensiCase` (specifically `verif_by_lecture`)

### 4. Task/Submission Management
*   **Overview of Submissions:** View both individual and group submissions.
    *   **Controller Method:** `tugas()`
    *   **View:** `dosen/tugas/index`
    *   **Use Case:** `TugasCase` (specifically `get_individual_submissions`, `get_group_submissions`)
*   **Specific Submission Types:**
    *   **Progress Reports (Laporan Kemajuan):** View, edit, and review submissions of type 1.
        *   **Controller Methods:** `tugas_laporan_kemajuan()`, `laporan_kemajuan($submisi_id)`, `laporan_kemajuan_edit($submisi_id)`, `laporan_kemajuan_view($submisi_id)`
        *   **Views:** `dosen/tugas/laporan-kemajuan/index`, `dosen/tugas/laporan-kemajuan/edit`, `dosen/tugas/laporan-kemajuan/view`
        *   **Use Cases:** `TugasCase` (`get_group_submissions_by_type`, `get_group_report_submission_by_id`), `AspekPenilaian`, `LaporanCase`
    *   **Final Reports (Laporan Akhir):** View, edit, and review submissions of type 2.
        *   **Controller Methods:** `tugas_laporan_akhir()`, `laporan_akhir($submisi_id)`, `laporan_akhir_edit($submisi_id)`, `laporan_akhir_view($submisi_id)`
        *   **Views:** `dosen/tugas/laporan-akhir/index`, `dosen/tugas/laporan-akhir/edit`, `dosen/tugas/laporan-akhir/view`
        *   **Use Cases:** `TugasCase` (`get_group_submissions_by_type`, `get_group_report_submission_by_id`), `AspekPenilaian`, `LaporanCase`
    *   **Teaching Modules (Modul Ajar):** View, edit, and review submissions of type 3.
        *   **Controller Methods:** `tugas_modul_ajar()`, `modul_ajar($submission_id)`, `modul_ajar_edit($submission_id)`, `modul_ajar_view($submission_id)`
        *   **Views:** `dosen/tugas/modul-ajar/index`, `dosen/tugas/modul-ajar/edit`, `dosen/tugas/modul-ajar/view`
        *   **Use Cases:** `TugasCase` (`get_individual_submissions_by_type`, `get_individual_report_submission_by_id`), `AspekPenilaian`, `LearningCase`
    *   **Teaching Materials (Bahan Ajar):** View, edit, and review submissions of type 4.
        *   **Controller Methods:** `tugas_bahan_ajar()`, `bahan_ajar($submission_id)`, `bahan_ajar_edit($submission_id)`, `bahan_ajar_view($submission_id)`
        *   **Views:** `dosen/tugas/bahan-ajar/index`, `dosen/tugas/bahan-ajar/edit`, `dosen/tugas/bahan-ajar/view`
        *   **Use Cases:** `TugasCase` (`get_individual_submissions_by_type`, `get_individual_report_submission_by_id`), `AspekPenilaian`, `LearningCase`
    *   **Project Modules (Modul Projek):** View, edit, and review submissions of type 5.
        *   **Controller Methods:** `modul_projek($submission_id)`, `modul_projek_edit($submission_id)`, `modul_projek_view($submission_id)`
        *   **Views:** `dosen/tugas/modul-projek/index`, `dosen/tugas/modul-projek/edit`, `dosen/tugas/modul-projek/view`
        *   **Use Cases:** `TugasCase` (`get_individual_report_submission_by_id`), `AspekPenilaian`, `LearningCase`
*   **Report Revision:** Lecturers can provide feedback and mark submissions for revision.
    *   **Controller Method:** `report_revision($submission_id)`

### 5. Evaluation/Assessment
*   **Overall Evaluation Page:** Entry point for various student evaluations.
    *   **Controller Method:** `penilaian()`
    *   **View:** `dosen/penilaian/index`
    *   **Use Case:** `EvaluationPageCase`
*   **Specific Evaluation Types:**
    *   **Intracurricular (Intrakurikuler):** Assess student performance in intrakurikuler activities.
        *   **Controller Methods:** `penilaian_intrakurikuler()`, `intra($student_id)`, `intra_edit($student_id)`, `intra_view($student_id)`
        *   **Views:** `dosen/penilaian/intra/index`, `dosen/penilaian/intra/penilaian`, `dosen/penilaian/intra/edit`, `dosen/penilaian/intra/view`
        *   **Use Cases:** `EvaluationPageCase`, `AspekPenilaian`, `EvaluationCase`
    *   **Extracurricular (Ekstrakurikuler):** Assess student performance in ekstrakurikuler activities.
        *   **Controller Methods:** `penilaian_ekstrakurikuler()`, `ekstra($student_id)`, `ekstra_edit($student_id)`, `ekstra_view($student_id)`
        *   **Views:** `dosen/penilaian/ekstrakurikuler/index`, `dosen/penilaian/ekstra/penilaian`, `dosen/penilaian/ekstra/edit`, `dosen/penilaian/ekstra/view`
        *   **Use Cases:** `EvaluationPageCase`, `AspekPenilaian`, `EvaluationCase`
    *   **Attitude (Sikap):** Assess student attitude and behavior.
        *   **Controller Methods:** `penilaian_sikap()`
        *   **Views:** `dosen/penilaian/sikap/index`
        *   **Use Cases:** `EvaluationPageCase`, `AspekPenilaian`, `EvaluationCase`
    *   **Analysis (Analisis):** Assess student analytical skills.
        *   **Controller Methods:** `penilaian_analisis()`, `analisis($student_id)`, `analisis_edit($student_id)`, `analisis_view($student_id)`
        *   **Views:** `dosen/penilaian/analisis/index`, `dosen/penilaian/analisis/penilaian`, `dosen/penilaian/analisis/edit`, `dosen/penilaian/analisis/view`
        *   **Use Cases:** `EvaluationPageCase`, `AspekPenilaian`, `EvaluationCase`
*   **Saving Scores:**
    *   **Report Scores:** Save scores for progress and final reports.
        *   **Controller Method:** `insert_nilai_laporan()`
        *   **Use Case:** `LaporanCase`
    *   **Learning Material Scores:** Save scores for teaching modules, materials, and project modules.
        *   **Controller Method:** `insert_nilai_ajar($type)`
        *   **Use Case:** `LearningCase`
    *   **General Evaluation Scores:** Save scores for intrakurikuler, ekstrakurikuler, and attitude.
        *   **Controller Method:** `insert_nilai_extra_intra_sikap($type)`
        *   **Use Case:** `EvaluationCase`

### 6. Final Score Calculation
*   **View Final Scores:** Lecturers can view the consolidated final scores for their students.
    *   **Controller Method:** `nilai_akhir()`
    *   **View:** `dosen/nilai/index`
    *   **Use Case:** `FinalScoreCase`

## Key Use Cases/Services/Repositories Involved

*   `DashboardData`
*   `StudentCase`
*   `AbsensiCase`
*   `TugasCase`
*   `AspekPenilaian` (Helper for evaluation aspects)
*   `EvaluationPageCase`
*   `EvaluationCase`
*   `LaporanCase`
*   `LearningCase`
*   `FinalScoreCase`
*   `LecturerRepository` (Used within various use cases)

This overview provides a comprehensive understanding of the 'dosen' role's features and their underlying implementation structure.