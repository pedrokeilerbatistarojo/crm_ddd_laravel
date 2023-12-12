<?php

namespace Domain\Payments\Models;

use Domain\Payments\Enums\PaymentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;

class Payment extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'payments';

    /**
     * @var string[]
     */
    protected $casts = [
        'order_id' => 'integer',
        'due_date' => 'datetime',
        'paid_date' => 'datetime',
        'type' => PaymentType::class,
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'returned_amount' => 'decimal:2'
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'order_id',
        'due_date',
        'paid_date',
        'type',
        'amount',
        'paid_amount',
        'returned_amount'
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

}
