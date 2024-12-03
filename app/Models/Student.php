<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'nisn',
        'nis',
        'name',
        'gender',
        'active',
        'city_born',
        'birthday',
        'nick_name',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'nisn' => 'string',
        'nis' => 'string',
        'active' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected static function booted(): void
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('active', 1);
        });
        
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('id', 'asc');
        });
    }

    // public function getGenderAttribute($value)
    // {
    //     // $this->attributes['gender'] = ($value === 'male') ? 'Laki-laki' : 'Perempuan';
    //     switch ($value) {
    //         case 'male':
    //             return 'Laki-laki';
    //             break;
            
    //         default:
    //             return 'Perempuan';
    //             break;
    //     }
    // }
    
    // public function setGenderAttribute($value)
    // {
    //     $this->attributes['gender'] = ($value === 'laki-laki') ? 'male' : 'female';
    // }

    // public function grades()
    // {
    //     return $this->belongsToMany(Grade::class, 'student_grade');
    // }

    // public function academics()
    // {
    //     return $this->belongsToMany(AcademicYear::class, 'student_grade');
    // }

    public function studentGrade()
    {
        return $this->belongsTo(StudentGrade::class, 'id', 'student_id');
    }

    public function dataStudent()
    {
        return $this->hasOne(DataStudent::class);
    }

    public function scopeActive(Builder $builder)
    {
        return $builder->where('active',1);
    }

    public function studentCompetency()
    {
        return $this->hasMany(StudentCompetency::class);
    }

    public function userable()
    {
        // return $this->belongsTo(Userable::class,'id', 'userable_id');
        return $this->morphOne(Userable::class, 'userable');
    }

    public static function setUserable($id)
    {
        $student = self::find($id);

        $user = User::create([
            'name' => $student->name,
            'email' => Str::slug($student->name).'@student.com',
            'password' => Hash::make('password'),
        ]);

        Userable::create([
            'user_id' => $user->id,
            'userable_id' => $student->id,
            'userable_type' => 'Student',
        ]);

        return self::find($id);
    }

    public function hasUserable()
    {
        // Lakukan pengecekan apakah siswa memiliki Userable
        return $this->userable !== null;
    }

    public function scopeMyStudentGrade(Builder $query, $teacher_id = null)
    {
        if(is_null($teacher_id)){
            $teacher_id = auth()->user()->userable->userable_id;
        }

        $grade = TeacherGrade::where('teacher_id', $teacher_id)->with('grade.StudentGrade')->first();
  
        $myStudents = $grade->grade->studentGrade->pluck('student_id');

        $query->whereIn('id', $myStudents);
    }

    public function examEvaluation()
    {
        return $this->hasOne(Exam::class, 'student_id', 'id');
    }

    public function exam()
    {
        return $this->hasMany(Exam::class, 'student_id', 'id');
    }

    public function attendance()
    {
        return $this->hasOne(Attendance::class, 'student_id', 'id');
    }

    public function extracurricular()
    {
        return $this->hasMany(StudentExtracurricular::class);
    }

    public function attitude()
    {
        return $this->hasOne(Attitude::class);
    }

    public function project()
    {
        return $this->hasMany(ProjectStudent::class);
    }

    public function projectNote()
    {
        return $this->hasMany(ProjectNote::class);
    }
}
