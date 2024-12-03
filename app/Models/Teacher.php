<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Teacher extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUlids;

    protected $fillable = [
        'ulid',
        'name',
        'gender',
        'active',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected $dates = ['deleted_at'];

    // public function academics()
    // {
    //     return $this->belongsToMany(AcademicYear::class, 'teacher_subject');
    // }

    // public function grades()
    // {
    //     return $this->belongsToMany(Grade::class, 'teacher_subject');
    // }
    
    // public function subjects()
    // {
    //     return $this->belongsToMany(Subject::class, 'teacher_subject');
    // }

    public function dataTeacher()
    {
        return $this->hasOne(DataTeacher::class);
    }

    public function teacherSubject()
    {
        return $this->hasMany(TeacherSubject::class);
    }

    public function teacherGrade()
    {
        // return $this->hasMany(TeacherGrade::class);
        return $this->hasOne(TeacherGrade::class);
    }

    public function scopeActive(Builder $query)
    {
        $query->where('active', 1);
    }

    public function userable()
    {
        // return $this->belongsTo(Userable::class,'id', 'userable_id');
        return $this->morphOne(Userable::class,'userable');

    }

    public static function setUserable($id)
    {
        $teacher = self::find($id);

        $user = User::create([
            'name' => $teacher->name,
            'email' => Str::slug($teacher->name).'@teacher.com',
            'password' => Hash::make('password'),
        ]);

        Userable::create([
            'user_id' => $user->id,
            'userable_id' => $teacher->id,
            'userable_type' => 'Teacher',
        ]);

        return self::find($id);
    }

    public function hasUserable()
    {
        // Lakukan pengecekan apakah guru memiliki Userable
        return $this->userable !== null;
    }

    public function teacherExtracurricular()
    {
        return $this->hasMany(TeacherExtracurricular::class);
    }

    public function project()
    {
        return $this->hasMany(Project::class);
    }

    public function projectCoordinator()
    {
        return $this->hasMany(ProjectCoordinator::class);
    }

    public function academicYear()
    {
        return $this->hasMany(AcademicYear::class);
    }
}
