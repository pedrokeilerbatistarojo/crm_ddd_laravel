<?php

namespace Domain\Invoices\Models;

use Database\Factories\InvoiceFactory;
use Domain\Clients\Models\Client;
use Domain\Invoices\Enums\InvoiceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;

class Invoice extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'invoices';

    /**
     * @var string[]
     */
    protected $casts = [
        'client_id' => 'integer',
        'invoice_date' => 'date',
        'invoice_type' => InvoiceType::class,
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'client_id',
        'number',
        'description',
        'invoice_type',
        'invoice_type_id',
        'invoice_date',
        'address',
        'zip_code',
        'locality',
        'province',
        'observations'
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
     * @return InvoiceFactory
     */
    protected static function newFactory(): InvoiceFactory
    {
        return InvoiceFactory::new();
    }

    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

}
