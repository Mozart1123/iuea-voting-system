<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'statement',
        'manifesto_url',
        'status',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the student who submitted this application.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category for this application.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ElectionCategory::class);
    }

    /**
     * Get the admin who reviewed this application.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Check if this application can be withdrawn (only if pending).
     */
    public function canBeWithdrawn(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the category deadline has passed.
     */
    public function hasCategoryDeadlinePassed(): bool
    {
        return $this->category->hasPassedDeadline();
    }

    /**
     * Approve the application.
     */
    public function approve(User $reviewer): void
    {
        $this->update([
            'status' => 'approved',
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Reject the application.
     */
    public function reject(User $reviewer): void
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Register the application (mark as registered after approval).
     */
    public function register(): void
    {
        $this->update([
            'status' => 'registered',
            'reviewed_at' => $this->reviewed_at ?? now(),
        ]);
    }
}
