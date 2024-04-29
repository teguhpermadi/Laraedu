<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Valuestore\Valuestore;

class StudentCompetency extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'teacher_subject_id',
        'student_id',
        'competency_id',
        'score',
        'score_skill',
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
        $query->join('competencies', 'competencies.id', '=', 'student_competencies.competency_id')
            ->select('student_competencies.*', 
                    DB::raw('CASE WHEN score >= passing_grade THEN "LULUS" ELSE "TIDAK LULUS" END as result'),
                    DB::raw('CASE WHEN score_skill >= passing_grade THEN "LULUS" ELSE "TIDAK LULUS" END as result_skill'),
                    DB::raw('competencies.description'),
                    DB::raw('competencies.description_skill'),
                    DB::raw('CASE 
                        WHEN score >= 90 THEN "Amat Baik"
                        WHEN score >= 80 THEN "Baik"
                        WHEN score >= 70 THEN "Cukup"
                        WHEN score >= 60 THEN "Sedang"
                        ELSE "Kurang" 
                    END as predicate_desc'),
                    DB::raw('CASE 
                        WHEN score_skill >= 90 THEN "Amat Baik"
                        WHEN score_skill >= 80 THEN "Baik"
                        WHEN score_skill >= 70 THEN "Cukup"
                        WHEN score_skill >= 60 THEN "Sedang"
                        ELSE "Kurang" 
                    END as predicate_desc_skill'),
                    DB::raw('CASE 
                        WHEN score >= 90 THEN "A"
                        WHEN score >= 80 THEN "B"
                        WHEN score >= 70 THEN "C"
                        WHEN score >= 60 THEN "D"
                        ELSE "Kurang" 
                    END as predicate'),
                    DB::raw('CASE 
                        WHEN score_skill >= 90 THEN "A"
                        WHEN score_skill >= 80 THEN "B"
                        WHEN score_skill >= 70 THEN "C"
                        WHEN score_skill >= 60 THEN "D"
                        ELSE "Kurang" 
                    END as predicate_skill'),
                    // DB::raw('CONCAT(CASE 
                    //                     WHEN score >= 90 THEN "Amat baik"
                    //                     WHEN score >= 80 THEN "Baik"
                    //                     WHEN score >= 70 THEN "Cukup"
                    //                     ELSE "Sedang" END, 
                    //         " dalam aspek ", 
                    //         competencies.description) as result_predicate_description'),
                    DB::raw('CONCAT(CASE WHEN score > passing_grade THEN "sudah menguasai" 
                                        WHEN score = passing_grade THEN "cukup menguasai"
                                        ELSE "belum menguasai" END, 
                            " dalam aspek ", 
                            competencies.description) as result_description'),
                    DB::raw('CONCAT(CASE WHEN score_skill > passing_grade THEN "sudah menguasai" 
                                        WHEN score_skill = passing_grade THEN "cukup menguasai"
                                        ELSE "belum menguasai" END, 
                            " dalam aspek ", 
                            competencies.description_skill) as result_description_skill'),
                );

        return $query;
    }

    public function scopeHalfSemester(Builder $query)
    {
        $query->join('competencies', 'competencies.id', '=', 'student_competencies.competency_id')
        ->where('half_semester',1)
        ->select('student_competencies.*', 
                    DB::raw('CASE WHEN score >= passing_grade THEN "LULUS" ELSE "TIDAK LULUS" END as result'),
                    DB::raw('CASE WHEN score_skill >= passing_grade THEN "LULUS" ELSE "TIDAK LULUS" END as result_skill'),
                    DB::raw('competencies.description'),
                    DB::raw('competencies.description_skill'),
                    DB::raw('CASE 
                        WHEN score >= 90 THEN "Amat Baik"
                        WHEN score >= 80 THEN "Baik"
                        WHEN score >= 70 THEN "Cukup"
                        WHEN score >= 60 THEN "Sedang"
                        ELSE "Kurang" 
                    END as predicate_desc'),
                    DB::raw('CASE 
                        WHEN score_skill >= 90 THEN "Amat Baik"
                        WHEN score_skill >= 80 THEN "Baik"
                        WHEN score_skill >= 70 THEN "Cukup"
                        WHEN score_skill >= 60 THEN "Sedang"
                        ELSE "Kurang" 
                    END as predicate_desc_skill'),
                    DB::raw('CASE 
                        WHEN score >= 90 THEN "A"
                        WHEN score >= 80 THEN "B"
                        WHEN score >= 70 THEN "C"
                        WHEN score >= 60 THEN "D"
                        ELSE "Kurang" 
                    END as predicate'),
                    DB::raw('CASE 
                        WHEN score_skill >= 90 THEN "A"
                        WHEN score_skill >= 80 THEN "B"
                        WHEN score_skill >= 70 THEN "C"
                        WHEN score_skill >= 60 THEN "D"
                        ELSE "Kurang" 
                    END as predicate_skill'),
                    DB::raw('CONCAT(CASE WHEN score > passing_grade THEN "sudah menguasai" 
                                        WHEN score = passing_grade THEN "cukup menguasai"
                                        ELSE "belum menguasai" END, 
                            " dalam aspek ", 
                            competencies.description) as result_description'),
                    DB::raw('CONCAT(CASE WHEN score_skill > passing_grade THEN "sudah menguasai" 
                                        WHEN score_skill = passing_grade THEN "cukup menguasai"
                                        ELSE "belum menguasai" END, 
                            " dalam aspek ", 
                            competencies.description_skill) as result_description_skill'),
                );

        return $query;
    }
}
