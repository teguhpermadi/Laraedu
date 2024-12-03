<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'student_ulid',
        'student_address',
        'student_province',
        'student_city',
        'student_district',
        'student_village',
        'religion',
        'previous_school',
        'father_name',
        'father_education',
        'father_occupation',
        'father_phone',
        'mother_name',
        'mother_education',
        'mother_occupation',
        'mother_phone',
        'guardian_name',
        'guardian_education',
        'guardian_occupation',
        'guardian_phone',
        'guardian_address',
        // 'guardian_village',
        'parent_address',
        'parent_province',
        'parent_city',
        'parent_district',
        'parent_village',
        'date_received',
        'grade_received',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
