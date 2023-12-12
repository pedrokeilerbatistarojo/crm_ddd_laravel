<?php

namespace Domain\TreatmentScheduleNotes\Models;

use Domain\Employees\Models\Employee;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;

class TreatmentScheduleNote extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * @var string
     */
    protected $table = 'treatment_schedule_notes';

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'employee_id',
        'date',
        'from_hour',
        'to_hour',
        'note',
    ];

    /**
     * @return BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

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
