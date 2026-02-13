<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElectionCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'application_deadline',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'application_deadline' => 'datetime',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who created this category.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all applications for this category.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'category_id');
    }

    /**
     * Check if the application deadline has passed.
     */
    public function hasPassedDeadline(): bool
    {
        return now()->isAfter($this->application_deadline);
    }

    /**
     * Get the count of votes for this category.
     * Can be extended to count actual votes when voting system is added.
     */
    public function getVotesCount(): int
    {
        // Placeholder - implement when voting system is added
        return rand(50, 500);
    }
}
