<?php

namespace Domain\Localities\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;

class Locality extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    /**
     * @var string
     */
    protected $table = 'localities';

    /**
     * @var string[]
     */
    protected $fillable = [
        'zip_code',
        'municipio_id',
        'locality',
        'population_unit_code',
        'singular_entity_name',
        'population',
        'province_id'
    ];

    /**
     * @return BelongsTo
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
}
