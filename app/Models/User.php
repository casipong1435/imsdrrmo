<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'mobile_number',
        'role',
        'username',
        'barangay_id',
        'purok',
        'sex',
        'date_of_birth',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'mobile_number_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected function role(): Attribute
    {
        return new Attribute (
            get: fn($value) => ['team', 'admin', 'resident'][$value],
        );
    }

    public function barangay(){
        return $this->belongsTo(Barangay::class);
    }
}
