<?php

namespace Domain\Gyms\Models;

use Database\Factories\GymSubscriptionQuotaFactory;
use Domain\Gyms\Enums\GymSubscriptionQuotaState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;

class GymSubscriptionQuota extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'gym_subscription_quotas';

    /**
     * @var string[]
     */
    protected $casts = [
        'state' => GymSubscriptionQuotaState::class,
        'date' => 'date',
        'gym_subscription_id' => 'integer',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'amount',
        'date',
        'state',
        'gym_subscription_id'
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($record) {
            $record->created_by = Auth::id();
            $record->last_modified_by = Auth::id();
        });

        static::updating(static function ($record) {
            $record->last_modified_by = Auth::id();
        });
    }

    /**
     * @return GymSubscriptionQuotaFactory
     */
    protected static function newFactory(): GymSubscriptionQuotaFactory
    {
        return GymSubscriptionQuotaFactory::new();
    }

    /**
     * @return BelongsTo
     */
    public function gymSubscription(): BelongsTo
    {
        return $this->belongsTo(GymSubscription::class);
    }

}
