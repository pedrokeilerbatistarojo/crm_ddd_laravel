<?php

namespace Domain\TreatmentReservations\Models;

use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;

class TreatmentReservationOrderDetail extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'treatment_reservations_order_details';

    protected $fillable = [
        'id',
        'order_detail_id',
    ];
}
