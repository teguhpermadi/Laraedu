<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentUmmiGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'ummi_grade_id',
        'student_id',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function ummiGrade()
    {
        return $this->belongsTo(UmmiGrade::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
