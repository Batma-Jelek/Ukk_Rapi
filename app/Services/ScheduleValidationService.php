<?php
namespace App\Services;

use App\Models\Event;

class ScheduleValidationService
{
    /**
     * Memeriksa apakah agenda berbentrokan dengan jadwal yang ada.
     */
    public static function hasConflict($roomId, $date, $startTime, $endTime, $ignoreEventId = null)
    {
        $query = Event::where('room_id', $roomId)
            ->where('date', $date)
            ->whereIn('status', ['pending', 'approved', 'moved'])
            ->where(function ($q) use ($startTime, $endTime) {
                // Logika pertampalan/ overlap waktu:
                // Ada bentrok jika (A_start < B_end) AND (A_end > B_start)
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
            });

        if ($ignoreEventId) {
            $query->where('id', '!=', $ignoreEventId);
        }

        return $query->exists();
    }
}