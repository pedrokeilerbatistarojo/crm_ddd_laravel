<?php

namespace Domain\Orders\Models;

use Database\Factories\OrderDetailFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;
use Support\Models\Traits\UserTracking;

class OrderDetail extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use UserTracking;

    /**
     * @var string
     */
    protected $table = 'order_details';

    /**
     * @var string[]
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'price',
        'quantity',
        'circuit_sessions',
        'treatment_sessions',
    ];

    /**
     * @return OrderDetailFactory
     */
    protected static function newFactory(): OrderDetailFactory
    {
        return OrderDetailFactory::new();
    }

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
