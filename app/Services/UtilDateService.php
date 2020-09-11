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

    public function getWeekRangeDate(Carbon $startDate, Carbon $endDate) {
        $weekList = [];
        $tmpStartDate = $startDate->copy();
        $tmpEndDate = $endDate->copy();

        /**
         * @var Carbon $weekCurrent
         */
        $weekCurrent = $tmpStartDate;
        $timeWeekCurrent = $tmpStartDate->toTimeString();

        /**
         * TODO: revisar julio 30 = agosto 06
         */
        if ($weekCurrent->day !== Carbon::THURSDAY){
            $weekCurrent->modify('next thursday '.$timeWeekCurrent);
        }

        while ($weekCurrent < $tmpEndDate) {
            $weekData = [$weekCurrent->toDateTimeString()];

            $weekCurrent->modify('next thursday '.$timeWeekCurrent);
            if ($tmpEndDate > $weekCurrent) {
                $weekData[] = $weekCurrent->toDateTimeString();
            } else {
                $weekData[] = $endDate->toDateTimeString();
            }

            if (count($weekData) > 0) {
                $weekList[] = $weekData;
            }
        }

        return $weekList;
    }
}