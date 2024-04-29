<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicYear extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'year',
        'semester',
        'active',
        'teacher_id',
        'date_report',
        'date_report_half',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'date_report' => 'date:d-m-Y',
        'date_report_half' => 'date:d-m-Y',
    ];

    public function scopeActive(Builder $builder)
    {
        return $builder->where('active',1);
    }

    public static function setActive($yearId)
    {
        // Menonaktifkan semua tahun ajaran
        self::query()->update(['active' => false]);

        // Mengaktifkan tahun ajaran yang diberikan
        self::where('id', $yearId)->update(['active' => true]);

        return self::where('active', true)->first();
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
