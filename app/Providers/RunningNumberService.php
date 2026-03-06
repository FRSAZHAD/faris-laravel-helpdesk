<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RunningNumberService
{
    public static function generateTicketNo(): string
    {
        return DB::transaction(function () {

            $today = Carbon::today()->toDateString(); // YYYY-MM-DD
            $prefix = Carbon::today()->format('Ymd'); // YYYYMMDD

            // Lock row for today's counter
            $counter = DB::table('running_numbers')
                ->where('date', $today)
                ->lockForUpdate()
                ->first();

            if (!$counter) {
                $nextNumber = 1;

                DB::table('running_numbers')->insert([
                    'date' => $today,
                    'last_number' => $nextNumber,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $nextNumber = $counter->last_number + 1;

                DB::table('running_numbers')
                    ->where('date', $today)
                    ->update([
                        'last_number' => $nextNumber,
                        'updated_at' => now(),
                    ]);
            }

            return $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        });
    }
}
