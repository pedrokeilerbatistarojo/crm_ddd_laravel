<?php

namespace Domain\Orders\Models;

use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;
use Support\Models\Traits\UserTracking;

class OrderApproval extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use UserTracking;

    /**
     * @var string
     */
    protected $table = 'orders_approval';

    /**
     * @var string[]
     */
    protected $casts = [
        'order_data' => 'json',
        'is_duplicated' => 'boolean',
        'is_reservation' => 'boolean',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'locator',
        'order_data',
        'is_duplicated',
        'is_reservation',
    ];

}
