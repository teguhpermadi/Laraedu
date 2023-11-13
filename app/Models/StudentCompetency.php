<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StudentCompetency extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'teacher_subject_id',
        'student_id',
        'competency_id',
        'score',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function competency()
    {
        return $this->belongsTo(Competency::class);
    }

    public function scopeResult(Builder $query)
    {
        return $query->join('competencies', 'competencies.id', '=', 'student_competencies.competency_id')
                    ->select('student_competencies.*', 
                            DB::raw('CASE WHEN score >= passing_grade THEN "LULUS" ELSE "TIDAK LULUS" END as result'),
                            DB::raw('competencies.description'),
                            DB::raw('CONCAT(CASE WHEN score > passing_grade THEN "sudah menguasai" 
                                                WHEN score = passing_grade THEN "cukup menguasai"
                                                ELSE "belum menguasai" END, 
                                    " dalam aspek ", 
                                    competencies.description) as result_description'),
                        );
    }
}
