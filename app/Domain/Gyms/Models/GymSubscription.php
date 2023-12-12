<?php

namespace Domain\Gyms\Models;

use Database\Factories\GymSubscriptionFactory;
use Domain\Gyms\Enums\GymSubscriptionPaymentType;
use Domain\Clients\Models\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;

class GymSubscription extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'gym_subscriptions';

    /**
     * @var string[]
     */
    protected $casts = [
        'client_id' => 'integer',
        'gym_fee_type_id' => 'integer',
        'payment_day' => 'integer',
        'biweekly_payment_day' => 'integer',
        'payment_type' => GymSubscriptionPaymentType::class,
        'activation_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'expiration_date' => 'date'
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'client_id',
        'gym_fee_type_id',
        'gym_fee_type_name',
        'price',
        'activation_date',
        'start_date',
        'end_date',
        'expiration_date',
        'payment_day',
        'biweekly_payment_day',
        'payment_type'
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
     * @return GymSubscriptionFactory
     */
    protected static function newFactory(): GymSubscriptionFactory
    {
        return GymSubscriptionFactory::new();
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
    public function gymFeeType(): BelongsTo
    {
        return $this->belongsTo(GymFeeType::class);
    }

}
