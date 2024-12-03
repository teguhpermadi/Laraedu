<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'name',
        'code',
        'order',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $dates = ['deleted_at'];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function scopeTeacher(Builder $query): void
    {
        $userable_type = auth()->user()->userable->userable_type;
        $userable_id = auth()->user()->userable->userable_id;
        $subjects_id = TeacherSubject::where('teacher_id', $userable_id)->orderBy('subject_id')->pluck('subject_id')->unique();
        $query->whereIn('id', $subjects_id);
    }
    
    public function scopeStudent(Builder $query): void
    {
        $userable_type = auth()->user()->userable->userable_type;
        $userable_id = auth()->user()->userable->userable_id;
        $grade_id = StudentGrade::where('student_id', $userable_id)->orderBy('grade_id')->pluck('grade_id')->unique();
        $subjects_id = TeacherSubject::where('grade_id', $grade_id)->orderBy('subject_id')->pluck('subject_id')->unique();
        $query->whereIn('id', $subjects_id);
    }

    public function teacherSubject()
    {
        return $this->hasMany(TeacherSubject::class);
    }

}
