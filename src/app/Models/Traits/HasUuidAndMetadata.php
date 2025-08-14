<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Trait HasUuidAndMetadata
 *
 * Provides common functionality for models including:
 * - UUID as primary key
 * - Metadata field support
 * - Common scopes and helpers
 */
trait HasUuidAndMetadata
{
    use HasUuids;

    /**
     * Boot the trait.
     */
    protected static function bootHasUuidAndMetadata(): void
    {
        static::creating(function ($model) {
            // Ensure metadata is always an array
            if (property_exists($model, 'metadata') && is_null($model->metadata)) {
                $model->metadata = [];
            }
        });
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     */
    public function getIncrementing(): bool
    {
        return false;
    }

    /**
     * Get the auto-incrementing key type.
     */
    public function getKeyType(): string
    {
        return 'string';
    }

    /**
     * @return string[]
     */
    public function getCasts(): array
    {
        return [
            'metadata' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * @return string[]
     */
    public function getHidden(): array
    {
        return [
            'deleted_at',
        ];
    }

    /**
     * Scope a query to only include active models.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get a metadata value by key.
     */
    public function getMetadata(string $key, $default = null)
    {
        return data_get($this->metadata ?? [], $key, $default);
    }

    /**
     * Set a metadata value by key.
     */
    public function setMetadata(string $key, $value): void
    {
        $metadata = $this->metadata ?? [];
        data_set($metadata, $key, $value);
        $this->metadata = $metadata;
    }

    /**
     * Check if a metadata key exists.
     */
    public function hasMetadata(string $key): bool
    {
        return data_get($this->metadata ?? [], $key) !== null;
    }

    /**
     * Remove a metadata key.
     */
    public function forgetMetadata(string $key): void
    {
        $metadata = $this->metadata ?? [];
        data_forget($metadata, $key);
        $this->metadata = $metadata;
    }
}
