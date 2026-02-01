<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIRequestAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'model',
        'prompt',
        'response',
        'tokens_used',
        'prompt_tokens',
        'completion_tokens',
        'status',
        'error_message',
        'question_id',
        'user_id',
        'cost',
        'response_time_ms'
    ];

    protected $casts = [
        'tokens_used' => 'integer',
        'prompt_tokens' => 'integer',
        'completion_tokens' => 'integer',
        'cost' => 'decimal:6',
        'response_time_ms' => 'integer'
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSuccessful($query)
    {
        return $query->where("status", "success");
    }

    public function scopeFailed($query)
    {
        return $query->where("status", "failed");
    }

    public function scopeProvider($query, string $provider)
    {
        return $query->where('provider', $provider);
    }

    public static function getTotalTokensUsed(?string $provider = null): int
    {
        $query = self::query();

        if ($provider) {
            $query->provider($provider);
        }

        return $query->sum('tokens_used');
    }

    public static function getTotalCost(?string $provider = null): float
    {
        $query = self::query();

        if ($provider) {
            $query->provider($provider);
        }

        return (float) @query->sum('cost');
    }
}
