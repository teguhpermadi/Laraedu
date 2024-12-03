<?php

namespace App\Models;

use App\Models\Scopes\AcademicYearScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherExtracurricular extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'academic_year_ulid',
        'teacher_id',
        'teacher_ulid',
        'extracurricular_id',
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
        return $this->belongsTo(AcademicYear::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function extracurricular()
    {
        return $this->belongsTo(Extracurricular::class);
    }

    public function scopeMyExtracurricular(Builder $query, $teacher_id = null):void 
    {
        if(is_null($teacher_id)){
            $teacher_id = auth()->user()->userable->userable_id;
        }

        $query->where('teacher_id', $teacher_id)->with('extracurricular');
    }
}
