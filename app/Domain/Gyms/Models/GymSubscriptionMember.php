<?php

namespace Domain\Gyms\Models;

use Database\Factories\GymSubscriptionMemberFactory;
use Domain\Clients\Models\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;

class GymSubscriptionMember extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'gym_subscription_members';

    /**
     * @var string[]
     */
    protected $casts = [
        'gym_subscription_id' => 'integer',
        'client_id' => 'integer',
        'date_from' => 'date',
        'date_to' => 'date'
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'gym_subscription_id',
        'client_id',
        'date_from',
        'date_to'
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
     * @return GymSubscriptionMemberFactory
     */
    protected static function newFactory(): GymSubscriptionMemberFactory
    {
        return GymSubscriptionMemberFactory::new();
    }

    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return BelongsTo
     */
    public function gymSubscription(): BelongsTo
    {
        return $this->belongsTo(GymSubscription::class);
    }

}
