<?php

namespace Domain\CircuitReservations\Models;

use Database\Factories\CircuitReservationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;

class CircuitReservation extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'circuit_reservations';

    /**
     * @var string[]
     */
    protected $casts = [
        'used' => 'boolean'
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'client_id',
        'date',
        'time',
        'duration',
        'adults',
        'children',
        'used',
        'notes',
        'schedule_note',
        'created_by',
        'last_modified_by',
        'treatment_reservations'
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
     * @return CircuitReservationFactory
     */
    protected static function newFactory(): CircuitReservationFactory
    {
        return CircuitReservationFactory::new();
    }
}
