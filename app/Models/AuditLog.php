<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Audit Log Model
 * Tracks all critical actions for security and compliance
 */
class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'changes',
        'ip_address',
        'user_agent',
        'timestamp',
    ];

    protected $casts = [
        'changes' => 'json',
        'timestamp' => 'datetime',
    ];

    /**
     * Get the user who performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Virtual description attribute for easier display
     */
    public function getDescriptionAttribute(): string
    {
        if ($this->action === 'manual_vote_adjustment' && isset($this->changes['reason'])) {
            return "Ajustement: " . $this->changes['reason'];
        }
        return $this->action;
    }

    /**
     * Create audit log entry
     */
    public static function log(
        ?int $userId,
        string $action,
        string $modelType,
        ?int $modelId = null,
        ?array $changes = null
    ): self {
        return self::create([
            'user_id' => $userId,
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'changes' => $changes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'timestamp' => now(),
        ]);
    }
}
