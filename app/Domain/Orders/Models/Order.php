<?php

namespace Domain\Orders\Models;

use Database\Factories\OrderFactory;
use Domain\Orders\Enums\Source;
use Domain\Orders\Enums\OrderType;
use Domain\Products\Models\ProductType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;
use Support\Models\Traits\UserTracking;

class Order extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use UserTracking;

    /**
     * @var string
     */
    protected $table = 'orders';


    protected $casts = [
        'source' => Source::class,
        'type' => OrderType::class,
        'used_purchase' => 'boolean',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'sale_session_id',
        'client_id',
        'source',
        'locator',
        'discount',
        'total_price',
        'ticket_number',
        'type',
        'telephone_sale_seq',
        'counter_sale_seq',
        'used_purchase',
        'note',
        'company_id',
        'created_by',
        'last_modified_by'
    ];

    /**
     * @param Order $record
     * @return void
     */
    public static function beforeCreate(Order $record): void
    {
        if (empty($record->locator) && $record->source == 'WEB') {
            $record->locator = strtoupper(uniqid('', false));
        }
    }

    /**
     * @return OrderFactory
     */
    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }

    /**
     * @return HasMany
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
}
