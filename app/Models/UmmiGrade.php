<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmmiGrade extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'jilid',
    ];

    public function studentUmmi()
    {
        return $this->hasMany(StudentUmmiGrade::class);
    }
}
