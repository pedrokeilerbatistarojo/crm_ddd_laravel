<?php

namespace Domain\Gyms\Models;

use Database\Factories\GymFeeTypeFactory;
use Domain\Gyms\Enums\GymFeeTypePeriodType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;

class GymFeeType extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'gym_fee_types';

    /**
     * @var string[]
     */
    protected $casts = [
        'payment_day' => 'integer',
        'duration_number_of_days' => 'integer',
        'biweekly_payment_day' => 'integer',
        'period_type' => GymFeeTypePeriodType::class,
        'monday_access' => 'boolean',
        'tuesday_access' => 'boolean',
        'wednesday_access' => 'boolean',
        'thursday_access' => 'boolean',
        'friday_access' => 'boolean',
        'saturday_access' => 'boolean',
        'sunday_access' => 'boolean',
        'unlimited_access' => 'boolean'
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'price',
        'period_type',
        'payment_day',
        'biweekly_payment_day',
        'hour_from',
        'hour_to',
        'monday_access',
        'tuesday_access',
        'wednesday_access',
        'thursday_access',
        'friday_access',
        'saturday_access',
        'sunday_access',
        'unlimited_access',
        'duration_number_of_days'
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
     * @return GymFeeTypeFactory
     */
    protected static function newFactory(): GymFeeTypeFactory
    {
        return GymFeeTypeFactory::new();
    }

}
