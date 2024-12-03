<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'name',
        'grade',
        'fase',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected $dates = ['deleted_at'];

    public function teacherSubject()
    {
        return $this->hasMany(TeacherSubject::class)->orderBy('academic_year_id', 'asc');
    }

    public function teacherGrade()
    {
        return $this->hasOne(TeacherGrade::class);
    }

    public function studentGrade()
    {
        return $this->hasMany(StudentGrade::class)->orderBy('academic_year_id', 'asc');
    }

    public function studentCompetency()
    {
        return $this->hasManyThrough(
            StudentCompetency::class,
            StudentGrade::class,
            'grade_id',
            'student_grade_id'
        );
    }

    public function scopeTeacher(Builder $query): void
    {
        $userable_type = auth()->user()->userable->userable_type;
        $userable_id = auth()->user()->userable->userable_id;
        $grade_id = TeacherSubject::where('teacher_id', $userable_id)->orderBy('grade_id')->pluck('grade_id')->unique();
        $query->whereIn('id', $grade_id);
    }
    
    public function scopeStudent(Builder $query): void
    {
        $userable_type = auth()->user()->userable->userable_type;
        $userable_id = auth()->user()->userable->userable_id;
        $grade_id = StudentGrade::where('student_id', $userable_id)->orderBy('grade_id')->pluck('grade_id')->unique();
        $query->whereIn('id', $grade_id);
    }

    public function attitude()
    {
        return $this->hasMany(Attitude::class);
    }

    public function project()
    {
        return $this->hasMany(Project::class);
    }

    public function projectCoordinator()
    {
        return $this->hasMany(ProjectCoordinator::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
}
