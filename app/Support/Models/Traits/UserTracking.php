<?php

namespace Support\Models\Traits;

use Illuminate\Support\Facades\Auth;

trait UserTracking
{
    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($record) {
            if (method_exists(static::class, 'beforeCreate')) {
                static::beforeCreate($record);
            }
            $record->created_by = Auth::id();
            $record->last_modified_by = Auth::id();
        });

        static::saving(static function ($record) {
            $record->last_modified_by = Auth::id();
        });

        static::updating(static function ($record) {
            $record->last_modified_by = Auth::id();
        });
    }
}
