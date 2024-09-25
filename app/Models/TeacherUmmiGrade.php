<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherUmmiGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'teacher_ummi_id',
        'ummi_grade_id',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
    
    public function teacherUmmi()
    {
        return $this->belongsTo(TeacherUmmi::class);
    }

    public function ummiGrade()
    {
        return $this->belongsTo(UmmiGrade::class);
    }
}
