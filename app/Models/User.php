<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Althinect\FilamentSpatieRolesPermissions\Concerns\HasSuperAdmin;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;
    use HasPanelShield;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ulid',
        'name',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();

        // Menggunakan event 'creating' untuk menambahkan kode unik ke username sebelum disimpan
        static::creating(function ($user) {
            $user->username = $user->generateUniqueUsername($user->username);
        });
    }


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function userable()
    {
        return $this->hasOne(Userable::class);
    }

    public function scopeTeacher(Builder $query)
    {
        $teacher = Userable::where('userable_type', 'App\Models\Teacher')->pluck('user_id');
        return $query->whereIn('id', $teacher);
    }

    public function scopeStudent(Builder $query)
    {
        $student = Userable::where('userable_type', 'App\Models\Student')->pluck('user_id');
        return $query->whereIn('id', $student);
    }

    protected function generateUniqueUsername($username)
    {
        $newUsername = str_replace(' ', '', $username);
        $uniqueCode = '_' . str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT);
        // Generate a unique code with 2 digits using random_int() and str_pad()
        return $newUsername . $uniqueCode;
    }
}
