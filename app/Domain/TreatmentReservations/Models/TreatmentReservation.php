<?php

namespace Domain\TreatmentReservations\Models;

use Database\Factories\TreatmentReservationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;

class TreatmentReservation extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'treatment_reservations';

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
        'employee_id',
        'client_id',
        'date',
        'time',
        'duration',
        'used',
        'notes',
        'created_by',
        'last_modified_by'
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
     * @return TreatmentReservationFactory
     */
    protected static function newFactory(): TreatmentReservationFactory
    {
        return TreatmentReservationFactory::new();
    }
}
