<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extracurricular extends Model
{
    use HasFactory;

    protected $fillable = [
        'ulid',
        'name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function studentExtracurricular()
    {
        return $this->hasMany(StudentExtracurricular::class);
    }
}
