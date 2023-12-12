<?php

namespace Domain\Clients\Models;

use Database\Factories\ClientNoteFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;
use Support\Models\Traits\UserTracking;

class ClientNote extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use UserTracking;

    /**
     * @var string
     */
    protected $table = 'client_notes';

    /**
     * @var string[]
     */
    protected $fillable = [
        'client_id',
        'note',
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
     * @return ClientNoteFactory
     */
    protected static function newFactory(): ClientNoteFactory
    {
        return ClientNoteFactory::new();
    }

    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
