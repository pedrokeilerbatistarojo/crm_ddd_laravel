<?php

namespace Domain\SaleSessions\Models;

use Domain\SaleSessions\Enums\SessionStatus;
use Domain\SaleSessions\Enums\SessionType;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;

class SaleSession extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    /**
     * @var string
     */
    protected $table = 'sale_sessions';

    /**
     * @var string[]
     */
    protected $casts = [
        'session_status' => SessionStatus::class,
        'session_type' => SessionType::class,
        'employee_id' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'start_amount' => 'decimal:2',
        'end_amount' => 'decimal:2'
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'session_status',
        'session_type',
        'employee_id',
        'start_date',
        'end_date',
        'start_amount',
        'end_amount',
        'closed_by'
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
