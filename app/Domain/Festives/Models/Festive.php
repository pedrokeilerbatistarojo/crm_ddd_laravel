<?php

namespace Domain\Festives\Models;

use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;

class Festive extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    /**
     * @var string
     */
    protected $table = 'festives';

    protected $casts = [
        'date' => 'date',
        'closed_hours' => 'array'
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'date',
        'description',
        'type',
        'closed_hours',
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
