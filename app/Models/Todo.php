<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'due_date',
        'status',
        'category_type',
        'extra_data',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'extra_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCategoryAttribute(): ?\App\Categories\TaskCategory
    {
        if (!$this->category_type) {
            return null;
        }

        try {
            return \App\Categories\TaskCategoryFactory::create(
                $this->category_type,
                $this->getExtraDataArray()
            );
        } catch (\InvalidArgumentException $e) {
            // Log the error or handle it as appropriate
            return null;
        }
    }

    /**
     * Get the extra_data attribute as an array, handling various formats.
     *
     * @return array
     */
    private function getExtraDataArray(): array
    {
        if (is_array($this->extra_data)) {
            return $this->extra_data;
        }

        if (is_string($this->extra_data)) {
            // Handle the specific string '[object Object]' which might come from frontend
            if ($this->extra_data === '[object Object]') {
                return [];
            }
            // Attempt to decode JSON string
            $decoded = json_decode($this->extra_data, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        // Default to an empty array if data is null or unhandled format
        return [];
    }
}
