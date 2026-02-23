<?php

namespace App\Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public const KEY_MAX_CHECK_IN_TIME = 'max_check_in_time';

    /** Default time (H:i) after which check-in counts as late. */
    public const DEFAULT_MAX_CHECK_IN_TIME = '09:30';

    public static function getMaxCheckInTime(): string
    {
        $row = self::where('key', self::KEY_MAX_CHECK_IN_TIME)->first();
        $value = $row?->value;
        if ($value !== null && $value !== '') {
            return $value;
        }
        return self::DEFAULT_MAX_CHECK_IN_TIME;
    }

    public static function setMaxCheckInTime(string $time): void
    {
        self::updateOrCreate(
            ['key' => self::KEY_MAX_CHECK_IN_TIME],
            ['value' => $time]
        );
    }
}
