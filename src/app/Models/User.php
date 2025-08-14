<?php

namespace App\Models;

use App\Models\Traits\HasUuidAndMetadata;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuidAndMetadata;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'unit_id',
        'status',
        'phone',
        'last_login_at',
        'metadata',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    /**
     * Get the unit that the user belongs to.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    /**
     * Get the units that the user has access to (for managers/admins).
     */
    public function accessibleUnits(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'unit_user', 'user_uuid', 'unit_uuid', 'uuid', 'id')
            ->withPivot(['role', 'permissions'])
            ->withTimestamps();
    }

    /**
     * Get the driver record associated with the user (if any).
     */
    public function driver()
    {
        return $this->hasOne(Driver::class, 'user_id', 'uuid');
    }
}
