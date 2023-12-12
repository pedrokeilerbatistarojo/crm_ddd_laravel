<?php

namespace Domain\Gyms\Models;

use Database\Factories\GymSubscriptionMemberAccessFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;

class GymSubscriptionMemberAccess extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'gym_subscription_member_access';

    /**
     * @var string[]
     */
    protected $casts = [
        'member_id' => 'integer',
        'date_from' => 'date',
        'date_to' => 'date'
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'member_id',
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
     * @return GymSubscriptionMemberAccessFactory
     */
    protected static function newFactory(): GymSubscriptionMemberAccessFactory
    {
        return GymSubscriptionMemberAccessFactory::new();
    }

    /**
     * @return BelongsTo
     */
    public function gymSubscriptionMember(): BelongsTo
    {
        return $this->belongsTo(GymSubscriptionMember::class, 'member_id');
    }

}
