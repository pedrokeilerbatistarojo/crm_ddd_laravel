<?php

namespace Domain\TreatmentScheduleNotes\DataTransferObjects;

use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class TreatmentScheduleNoteEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public ?int $employee_id;
    public ?string $date;
    public string $from_hour;
    public string $to_hour;
    public string $note;
}
