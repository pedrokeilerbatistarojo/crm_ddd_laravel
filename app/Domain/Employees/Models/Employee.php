<?php

namespace Domain\Employees\Models;

use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use Support\Models\Entity;

class Employee extends Entity implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'employees';

    /**
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'second_last_name',
        'email',
        'phone',
        'is_specialist',
        'active'
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($record) {
            $record->created_by = Auth::id();
            $record->last_modified_by = Auth::id();
        });

        static::updating(static function ($record) {
            $record->last_modified_by = Auth::id();
        });
    }

    /**
     * @return HasMany
     */
    public function employeesTimeOff(): HasMany
    {
        return $this->hasMany(EmployeeTimeOff::class, 'employee_id');
    }

    /**
     * @return EmployeeFactory
     */
    protected static function newFactory(): EmployeeFactory
    {
        return EmployeeFactory::new();
    }
}
