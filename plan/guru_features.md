# Guru (Teacher) Features Overview

This document provides a comprehensive overview of the functionalities and implementation plan for the 'Guru Pamong' (Teacher) role within the PLP2 application. It incorporates all confirmed details regarding student evaluation, attendance management, and logbook viewing.

## 1. Role Overview

The Guru Pamong plays a crucial role in the PLP2 program, focusing on direct student supervision and assessment in practical settings. Their responsibilities include evaluating student performance in specific academic and behavioral aspects, managing attendance for their assigned students, and reviewing student logbook entries.

## 2. Core Functionalities

### 2.1. Dashboard

- **Overview:** A personalized dashboard providing key statistics and quick access to relevant sections for the teacher, such as the total number of assigned students.
  - **Controller Method:** `Guru::index()`
  - **View:** `guru/index.php`
  - **Use Case:** `UseCases\Teacher\DashboardData` (to fetch teacher-specific dashboard metrics).

### 2.2. Student Management

- **View Assigned Students:** Teachers can view a list of students specifically assigned to them for guidance and evaluation.
  - **Controller Method:** `Guru::mahasiswa()`
  - **View:** `guru/mahasiswa.php`
  - **Datatable:** Students will be displayed in a dynamic, searchable, and sortable table.
    - **Controller Method:** `Guru::datatable_student()`
    - **Use Case:** `UseCases\Datatable\StudentCase` (specifically `teacher_datatable` and `student_formatter` to filter students by the logged-in teacher's ID).

### 2.3. Attendance Management

- **View Attendance Records:** Teachers can view the attendance records of their assigned students.
  - **Controller Method:** `Guru::absensi()`
  - **View:** `guru/absensi/index.php`
  - **Datatable:** Attendance data will be presented in a tabular format.
    - **Controller Method:** `Guru::datatable_absensi()`
    - **Use Case:** `UseCases\Teacher\AbsensiCase` (specifically `datatable` and `absensi_formatter` tailored for teacher's view).
- **Mark/Verify Attendance:** Teachers are responsible for marking student attendance for specific meetings.
  - **Controller Method:** `Guru::save_absensi()`
  - **Use Case:** `UseCases\Teacher\AbsensiCase` (specifically `verif_by_teacher`).
  - **Meeting Restrictions:** Teachers will fill attendance for meetings _other than_ [1, 2, 3, 8, 15, 16]. These specific meetings are reserved for Dosen. The `AbsensiCase` will include logic to enforce this restriction based on the meeting number and the user's role.

### 2.4. Logbook Viewing

- **View Student Logbook Entries:** Teachers can view the detailed logbook entries submitted by their assigned students. This functionality is read-only; teachers do not review, approve, or edit logbook entries.
  - **Controller Method:** `Guru::logbook()`
  - **View:** `guru/logbook/index.php`
  - **UI/Content:** The display will be similar to the student's activity view (`application/views/mahasiswa/aktivitas/index.php`), presenting a chronological timeline of activities and logbook entries. All input, editing, or approval functionalities present in the student's view will be removed.
  - **Use Case:** `UseCases\Teacher\LogbookCase` (responsible for fetching logbook data for the teacher's assigned students).

### 2.5. Evaluation/Assessment

- **Overall Evaluation Page:** A central entry point for teachers to access various student evaluation forms.
  - **Controller Method:** `Guru::penilaian()`
  - **View:** `guru/penilaian/index.php`
  - **Use Case:** `UseCases\Teacher\EvaluationPageCase` (to fetch students assigned to the teacher for evaluation).
- **Specific Evaluation Types:** Teachers will assess students across three key areas. The indicators and score ranges for these evaluations are **identical to those used by Dosen**.
  - **Intrakurikuler (Intracurricular):** Assessment of student performance in classroom or academic-related activities.
    - **Controller Methods:** `Guru::penilaian_intrakurikuler()`, `Guru::intra($student_id)`, `Guru::intra_edit($student_id)`, `Guru::intra_view($student_id)`
    - **Views:** `guru/penilaian/intra/index.php`, `guru/penilaian/intra/penilaian.php`, `guru/penilaian/intra/edit.php`, `guru/penilaian/intra/view.php`
  - **Ekstrakurikuler (Extracurricular/Co-curricular):** Assessment of student participation and performance in activities outside the regular curriculum but related to their development.
    - **Controller Methods:** `Guru::penilaian_ekstrakurikuler()`, `Guru::ekstra($student_id)`, `Guru::ekstra_edit($student_id)`, `Guru::ekstra_view($student_id)`
    - **Views:** `guru/penilaian/ekstrakurikuler/index.php`, `guru/penilaian/ekstrakurikuler/penilaian.php`, `guru/penilaian/ekstrakurikuler/edit.php`, `guru/penilaian/ekstrakurikuler/view.php`
  - **Sikap Mahasiswa (Student Attitude):** Assessment of student behavior, discipline, and overall attitude.
    - **Controller Methods:** `Guru::penilaian_sikap()`, `Guru::sikap($student_id)`, `Guru::sikap_edit($student_id)`, `Guru::sikap_view($student_id)`
    - **Views:** `guru/penilaian/sikap/index.php`, `guru/penilaian/sikap/penilaian.php`, `guru/penilaian/sikap/edit.php`, `guru/penilaian/sikap/view.php`
- **Saving Evaluation Scores:**
  - **Controller Method:** `Guru::insert_nilai_extra_intra_sikap($type)` (This method will handle saving scores for Intrakurikuler, Ekstrakurikuler, and Sikap based on the provided type).
  - **Use Case:** `UseCases\Teacher\EvaluationCase` (responsible for saving and retrieving evaluation scores, ensuring `teacher_id` is correctly associated).

## 3. Implementation Plan

### 3.1. Phase 1: Core Setup & Evaluation Features

1.  **Update `application/controllers/Guru.php`:**

    - Add `logbook()` method to load the logbook viewing page.
    - Add `penilaian()` method to load the main evaluation page.
    - Add `penilaian_intrakurikuler()`, `penilaian_ekstrakurikuler()`, `penilaian_sikap()` methods to load specific evaluation overview pages.
    - Add `intra($student_id)`, `intra_edit($student_id)`, `intra_view($student_id)` methods for Intrakurikuler evaluation forms.
    - Add `ekstra($student_id)`, `ekstra_edit($student_id)`, `ekstra_view($student_id)` methods for Ekstrakurikuler evaluation forms.
    - Add `sikap($student_id)`, `sikap_edit($student_id)`, `sikap_view($student_id)` methods for Sikap Mahasiswa evaluation forms.
    - Add `insert_nilai_extra_intra_sikap()` method to handle saving evaluation scores.

2.  **Create Views (`application/views/guru/`):**

    - `guru/index.php`: Teacher Dashboard (already exists, ensure it reflects new features).
    - `guru/mahasiswa.php`: List of assigned students (already exists).
    - `guru/absensi/index.php`: Attendance management page (already exists).
    - `guru/logbook/index.php`: New view for logbook viewing. Content will be adapted from `mahasiswa/aktivitas/index.php` (read-only).
    - `guru/penilaian/index.php`: New main evaluation page.
    - `guru/penilaian/intra/index.php`: New overview page for Intrakurikuler evaluation.
    - `guru/penilaian/intra/penilaian.php`: New form for creating Intrakurikuler evaluation.
    - `guru/penilaian/intra/edit.php`: New form for editing Intrakurikuler evaluation.
    - `guru/penilaian/intra/view.php`: New view for displaying Intrakurikuler evaluation details.
    - `guru/penilaian/ekstrakurikuler/index.php`, `penilaian.php`, `edit.php`, `view.php`: Similar new views for Ekstrakurikuler evaluation.
    - `guru/penilaian/sikap/index.php`, `penilaian.php`, `edit.php`, `view.php`: Similar new views for Sikap Mahasiswa evaluation.

3.  **Create Use Cases (`application/usecases/Teacher/`):**
    - `DashboardData.php`: Fetches data for the teacher dashboard.
    - `StudentCase.php`: Adapts `UseCases\Datatable\StudentCase` for teacher-specific student listings.
    - `AbsensiCase.php`: Handles teacher-specific attendance logic, including meeting number validation.
    - `LogbookCase.php`: Fetches logbook entries for viewing by the teacher.
    - `EvaluationPageCase.php`: Fetches students assigned to the teacher for evaluation pages.
    - `EvaluationCase.php`: Handles saving and retrieving evaluation scores for Intrakurikuler, Ekstrakurikuler, and Sikap, ensuring `teacher_id` is correctly used.

### 3.2. Phase 2: Data Table & API Endpoints

1.  **Update `application/controllers/admincontroller/Datatable.php`:**

    - Add methods for teacher-specific datatables if needed (e.g., `api_get_teacher_students()`, `api_get_teacher_absensi()`). These will likely be similar to existing `api_get_teacher_unverified` but for assigned students/attendance.

2.  **Update `application/repositories/AdminDatatable.php`:**

    - Add methods to fetch raw data for teacher-specific datatables (e.g., `datatable_teacher_students()`, `datatable_teacher_absensi()`).

3.  **Update `application/formatters/DatatableFormatter.php`:**
    - Add methods to format data for teacher-specific datatables (e.g., `teacher_student_formatter()`, `teacher_absensi_formatter()`).

### 3.3. Phase 3: Routing & Sidebar Navigation

1.  **Update `application/routes/guru.php`:**

    - Add routes for all new controller methods, ensuring proper mapping for evaluation forms and data submissions.

2.  **Update `application/views/layout/sidebar.php`:**
    - Add new menu items under the 'Guru' section for:
      - Absensi
      - Penilaian (with sub-menus for Intrakurikuler, Ekstrakurikuler, Sikap)
      - Logbook

## 4. Data Storage Considerations

- **Evaluations:** Scores for Intrakurikuler, Ekstrakurikuler, and Sikap will be stored in dedicated tables (e.g., `assist_intracurricular`, `assist_extracurricular`, `student_attitude`). Each record will explicitly link to the `teacher_id` who performed the evaluation and the `student_id` being evaluated.
- **Attendance:** Attendance records will be stored in the `attendance` table, linked to `student_id` and the specific meeting number. The system will ensure that teachers only modify meetings they are responsible for.
- **Logbook:** Logbook entries will be stored in the `logbook` table, linked to `student_id`. Teachers will only perform read operations on this table.

## 5. Key Dependencies & Shared Components

- **`AspekPenilaian`:** This class will be crucial for defining the specific aspects and indicators for Intrakurikuler, Ekstrakurikuler, and Sikap evaluations. Since the indicators and score ranges are the same as Dosen's, this class can be directly reused or extended.
- **Datatable Helpers:** `get_param_datatable()`, `datatable_response()` will be used for handling server-side processing of datatables.
- **Database Interaction:** Direct database queries (`$this->db`) or dedicated repository methods will be used for data persistence and retrieval.

This detailed plan provides a clear roadmap for implementing the teacher-specific features, ensuring consistency with existing patterns and addressing all specified requirements.
