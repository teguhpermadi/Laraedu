<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userable extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_ulid',
        'userable_id',
        'userable_ulid',
        'userable_type',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userable()
    {
        return $this->morphTo();
    }

    protected function userableType(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => class_basename($value),
        );
    }
}
