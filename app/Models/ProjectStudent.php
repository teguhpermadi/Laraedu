<?php

namespace App\Models;

use App\Models\Scopes\AcademicYearScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ProjectStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'student_id',
        'student_ulid',
        'project_target_id',
        'score'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected static function booted(): void
    {
        static::addGlobalScope(new AcademicYearScope);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function projectTarget()
    {
        return $this->belongsTo(ProjectTarget::class);
    }

    
}
