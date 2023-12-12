<?php

namespace Domain\Products\Models;

use Database\Factories\ProductFactory;
use Domain\Products\Enums\PriceType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;

class Product extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'products';

    protected $casts = [
        'price' => 'decimal:2',
        'price_type' => PriceType::class,
        'online_sale' => 'boolean',
        'editable' => 'boolean',
        'available' => 'boolean',
        'all_reserves_on_same_day' => 'boolean'
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'description',
        'editable',
        'available',
        'image',
        'name',
        'online_sale',
        'price',
        'price_type',
        'priority',
        'active',
        'all_reserves_on_same_day',
        'product_type_id',
        'circuit_sessions',
        'treatment_sessions',
        'duration_circuit_schedule',
        'duration_treatment_schedule',
        'short_description',
        'background_color',
        'text_color',
    ];

    /**
     * @param string|null $value
     * @return void
     */
    public function setBackgroundColorAttribute(?string $value): void
    {
        $this->attributes['background_color'] = empty($value) ? null : $value;
    }

    /**
     * @param string|null $value
     * @return void
     */
    public function setTextColorAttribute(?string $value): void
    {
        $this->attributes['text_color'] = empty($value) ? null : $value;
    }

    /**
     * @return HasMany
     */
    public function discounts(): HasMany
    {
        return $this->hasMany(ProductDiscount::class);
    }

    /**
     * @return BelongsTo
     */
    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    /**
     * @return ProductFactory
     */
    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }
}
