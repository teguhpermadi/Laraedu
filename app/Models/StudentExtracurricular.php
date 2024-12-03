<?php

namespace App\Models;

use App\Models\Scopes\AcademicYearScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class StudentExtracurricular extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'student_id',
        'student_ulid',
        'extracurricular_id',
        'score',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new AcademicYearScope);
    }

    public function academic()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function extracurricular()
    {
        return $this->belongsTo(Extracurricular::class);
    }

    public function scopeMyExtracurricular(Builder $query)
    {
        $teacher_id = auth()->user()->userable->userable_id;
        $extra = Extracurricular::where('teacher_id', $teacher_id)->first();

        $query->where('extracurricular_id', $extra->id);
    }

    public function scopeDescription(Builder $builder)
    {
        $builder
        ->join('extracurriculars', 'extracurriculars.id', '=', 'student_extracurriculars.extracurricular_id')
        ->select(['student_extracurriculars.*','extracurriculars.name', 
                    DB::raw('CONCAT(CASE WHEN score = "A" THEN "Amat Baik" 
                            WHEN score = "B" THEN "Baik"
                            WHEN score = "C" THEN "Cukup baik"
                            ELSE "Tidak memiliki nilai" END, 
                            " dalam ekstrakurikuler ", 
                            extracurriculars.name) as 
                            description'),
        ]);
    }
}
