<?php

namespace Domain\Companies\Models;

use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;

class Company extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * @var string
     */
    protected $table = 'companies';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'cif',
        'email',
        'phone',
        'address',
        'zip_code',
        'locality',
        'province',
    ];
}
