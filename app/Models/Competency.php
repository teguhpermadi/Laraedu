<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competency extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_subject_id',
        'code',
        'code_skill',
        'description',
        'description_skill',
        'passing_grade',
        'half_semester',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $cast = [
        'code' => 'string',
        'code_skill' => 'string',
    ];
    
    protected static function booted(): void
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->whereHas('teacherSubject');
        });
    }

    public function teacherSubject()
    {
        return $this->belongsTo(TeacherSubject::class,'teacher_subject_id');
    }

    public function studentCompetency()
    {
        return $this->hasMany(StudentCompetency::class);
    }
    
    public function scopeActive(Builder $query): void
    {
        $query->whereHas('teacherSubject');
    }
}
