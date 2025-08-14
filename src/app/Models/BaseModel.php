<?php

namespace App\Models;

use App\Models\Traits\HasUuidAndMetadata;
use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * Base model class that all other models should extend.
 * Provides common functionality including UUIDs and metadata support.
 */
class BaseModel extends EloquentModel
{
    use HasUuidAndMetadata;
}
