<?php

namespace Domain\Products\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;

class ProductDiscount extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'product_discounts';

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'product_id',
        'discount_id',
        'price'
    ];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
