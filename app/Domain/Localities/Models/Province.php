<?php

namespace Domain\Localities\Models;

use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;

class Province extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    /**
     * @var string
     */
    protected $table = 'provinces';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
    ];
}
