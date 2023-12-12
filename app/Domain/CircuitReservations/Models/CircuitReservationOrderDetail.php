<?php

namespace Domain\CircuitReservations\Models;

use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;

class CircuitReservationOrderDetail extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'circuit_reservations_order_details';

    protected $fillable = [
        'id',
        'order_detail_id',
    ];
}
