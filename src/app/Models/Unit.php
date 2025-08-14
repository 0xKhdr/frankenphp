<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'symbol',
        'description',
        'type',
        'si_unit',
        'conversion_factor',
        'dimension',
        'system',
        'is_base_unit',
        'category',
        'unit_system',
        'unit_group',
        'is_active',
        'sort_order',
        'metadata',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'conversion_factor' => 'decimal:10',
        'is_base_unit' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'metadata' => 'array',
    ];

    /**
     * Get the users associated with this unit.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the users who have access to this unit.
     */
    public function accessibleUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'unit_user', 'unit_uuid', 'user_uuid', 'id', 'uuid')
            ->withPivot(['role', 'permissions'])
            ->withTimestamps();
    }

    /**
     * Get the driver associated with the unit.
     */
    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class, 'unit_uuid', 'id');
    }

    /**
     * Get the sensors for the unit.
     */
    public function sensors(): HasMany
    {
        return $this->hasMany(Sensor::class, 'unit_uuid', 'id');
    }

    /**
     * Get the creator of the unit.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_uuid', 'uuid');
    }

    /**
     * Get the last user who updated the unit.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_uuid', 'uuid');
    }
}
