<?php

namespace UseCases\Teacher;

use UseCases\Datatable\StudentCase as BaseStudentCase;

class StudentCase
{
    protected $baseStudentCase;

    public function __construct()
    {
        $this->baseStudentCase = new BaseStudentCase();
    }

    public function teacher_datatable($param)
    {
        return $this->baseStudentCase->teacher_datatable($param);
    }

    public function student_formatter($result)
    {
        return $this->baseStudentCase->student_formatter($result);
    }
}