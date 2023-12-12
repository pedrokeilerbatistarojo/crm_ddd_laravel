<?php

namespace Domain\Clients\Models;

use Database\Factories\ClientFactory;
use Domain\Localities\Models\Locality;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;

class Client extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'clients';

    /**
     * @var string[]
     */
    protected $fillable = [
        'external_id',
        'email',
        'document',
        'name',
        'phone',
        'birthdate',
        'address',
        'postcode',
        'locality_id',
        'opt_in',
        'lopd_agree',
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
     * @return ClientFactory
     */
    protected static function newFactory(): ClientFactory
    {
        return ClientFactory::new();
    }

    /**
     * @return HasMany
     */
    public function clientNotes(): HasMany
    {
        return $this->hasMany(ClientNote::class);
    }

    /**
     * @return HasMany
     */
    public function clientFiles(): HasMany
    {
        return $this->hasMany(ClientFile::class);
    }

    /**
     * @return BelongsTo
     */
    public function locality(): BelongsTo
    {
        return $this->belongsTo(Locality::class, 'locality_id');
    }
}
