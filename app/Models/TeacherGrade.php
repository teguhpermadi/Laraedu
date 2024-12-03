<?php

namespace App\Models;

use App\Models\Scopes\AcademicYearScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'ulid',
        'academic_year_id',
        'academic_year_ulid',
        'teacher_id',
        'teacher_ulid',
        'grade_id',
        'grade_ulid',
        'curriculum',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
     /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new AcademicYearScope);
    }

    public function academic()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function scopeMyGrade(Builder $query, $teacher_id = null): void
    {
        if(is_null($teacher_id)){
            $teacher_id = auth()->user()->userable->userable_id;
        }

        $query->where('teacher_id', $teacher_id)->with('grade.StudentGrade.student');
    }
}
