<?php

namespace App\Services;

use Carbon\Carbon;

class UtilDateService extends BaseService
{

    /**
     * CategoriaService constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getWeekRangeDate(Carbon $startDate, Carbon $endDate, $startTime = '12:00') {
        $weekList = [];
        $tmpStartDate = $startDate->copy()->addHours($startTime);
        $tmpEndDate = $endDate->copy()->addHours($startTime);

        /**
         * @var Carbon $weekCurrent
         */
        $weekCurrent = $tmpStartDate;

        if ($weekCurrent->day !== Carbon::THURSDAY){
            $weekCurrent->modify('next thursday '.$startTime);
        }

        while ($weekCurrent < $tmpEndDate) {
            $weekData = [$weekCurrent->toDateTimeString()];

            if ($tmpEndDate > $weekCurrent) {
                $weekCurrent->modify('next thursday '.$startTime);
                $weekData[] = $weekCurrent->toDateTimeString();
            }

            if (count($weekData) > 0) {
                $weekList[] = $weekData;
            }
        }

        return $weekList;
    }
}