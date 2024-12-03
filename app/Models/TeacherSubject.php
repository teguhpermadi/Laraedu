<?php

namespace App\Models;

use App\Models\Scopes\AcademicYearScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TeacherSubject extends Model
{
    use HasFactory;

    public $timestamps = false;

    // protected $table = 'teacher_subject';

    protected $fillable = [
        'ulid',
        'academic_year_id',
        'academic_year_ulid',
        'grade_id',
        'grade_ulid',
        'teacher_id',
        'teacher_ulid',
        'subject_id',
        'subject_ulid',
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
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
    
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function competencies()
    {
        return $this->hasMany(Competency::class, 'teacher_subject_id');
    }

    public function scopeMyGrade(Builder $query, $teacher_id = null):void
    {
        if(is_null($teacher_id)){
            $teacher_id = auth()->user()->userable->userable_id;
        }

        $query->where('teacher_id', $teacher_id)->with('grade');
    }

    public function scopeMySubject(Builder $query, $teacher_id = null):void 
    {
        if(is_null($teacher_id)){
            $teacher_id = auth()->user()->userable->userable_id;
        }

        $query->where('teacher_id', $teacher_id)->with('subject');
    }

    public function scopeMySubjectByGrade(Builder $query, $grade_id, $teacher_id = null):void
    {
        if(is_null($teacher_id)){
            $teacher_id = auth()->user()->userable->userable_id;
        }

        $query->where('teacher_id', $teacher_id)
            ->where('grade_id', $grade_id)
            ->with('subject');
    }

    public function studentCompetency()
    {
        return $this->hasMany(StudentCompetency::class, 'teacher_subject_id', 'id');
    }

    public function exam()
    {
        return $this->hasMany(Exam::class, 'teacher_subject_id', 'id');
    }

    public function studentGrade()
    {
        return $this->hasMany(StudentGrade::class, 'grade_id', 'grade_id');
    }

    public function teacherGrade()
    {
        return $this->belongsTo(TeacherGrade::class, 'grade_id', 'grade_id');
    }
}
