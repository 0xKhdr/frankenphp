<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sensor extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'model_number',
        'serial_number',
        'unit_id',
        'specifications',
        'last_calibration_date',
        'next_calibration_date',
        'status',
        'metadata',
    ];

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
        return $this->belongsTo(Unit::class);
    }
}
