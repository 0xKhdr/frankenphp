<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sensor extends BaseModel
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'type',
        'model_number',
        'serial_number',
        'unit_uuid',
        'specifications',
        'last_calibration_date',
        'next_calibration_date',
        'status',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'last_calibration_date' => 'datetime',
        'next_calibration_date' => 'datetime',
        'specifications' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Get the unit that the sensor belongs to.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_uuid', 'id');
    }
}
