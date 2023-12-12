<?php

namespace Domain\Products\Models;

use Database\Factories\ProductTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductType extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'product_types';

    /**
     * @var string[]
     */
    protected $fillable = [
        'category_id',
        'name',
        'priority',
        'active',
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
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'product_type_id');
    }

    /**
     * @return ProductTypeFactory
     */
    protected static function newFactory(): ProductTypeFactory
    {
        return ProductTypeFactory::new();
    }
}
