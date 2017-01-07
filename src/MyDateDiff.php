<?php

class MyDateDiff
{
    const MONTHS_PER_YEAR = 12;

    protected $start_date;
    protected $end_date;

    protected $years = 0;
    protected $months = 0;
    protected $days = 0;
    protected $total_days = 0;
    protected $invert = false;

    private function __construct($start_date, $end_date)
    {
        if ($start_date->isAfter($end_date)) {
            $temp_date = $start_date;
            $start_date = $end_date;
            $end_date = $temp_date;
            $this->invert = true;
        }

        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public static function fromDates(MyDate $start_date, MyDate $end_date)
    {
        $diff = new static($start_date, $end_date);
        $diff->processDates();

        return $diff->toObject();
    }

    private function processDates()
    {
        $this->processDiffs();
        $this->calculateTotalDaysBetween($this->start_date, $this->end_date);
    }

    private function calculateTotalDaysBetween($start_date, $end_date)
    {
        // echo "\n" . $start_date . ' -> ' . $end_date . "\n";
        // echo "months: " . $this->months . "\n";

        if (! $end_date->isSameYear($start_date)) {
            // echo "not same year \n";
            $this->total_days += $end_date->diffFromStartOfMonth();
            $this->total_days++;
            $this->months++;
            // echo $this->total_days . "\n";
            return $this->calculateTotalDaysBetween($start_date, $end_date->endOfPreviousMonth());
        }

        if (! $end_date->isSameMonth($start_date)) {
            // echo "not same month \n";
            $this->total_days += $end_date->diffFromStartOfMonth();
            $this->total_days++;
            $this->months++;
            // echo $this->total_days . "\n";
            return $this->calculateTotalDaysBetween($start_date, $end_date->endOfPreviousMonth());
        }

        $this->total_days += $end_date->diffInDays($start_date);
        // echo "+ days " . $end_date->diffInDays($start_date);
        // echo $this->total_days . "\n";
            // return $this->calculateTotalDaysBetween($start_date, $end_date->endOfPreviousMonth());
        $this->convertMonthsToYear();
    }

    private function processDiffs()
    {
        // $this->years = $this->end_date->diffInYears($this->start_date);
        // $this->months = $this->end_date->diffInMonths($this->start_date);
        $this->days = $this->end_date->diffInDays($this->start_date);
    }

    private function convertMonthsToYear()
    {
        $this->years = intval($this->months / static::MONTHS_PER_YEAR);

        if ($this->months > static::MONTHS_PER_YEAR) {
            $this->months = $this->months % static::MONTHS_PER_YEAR - 1;
        }
    }

    private function toObject()
    {
        // dd($this);
        return (object) [
            'years' => $this->years,
            'months' => $this->months,
            'days' => $this->days,
            'total_days' => $this->total_days,
            'invert' => $this->invert,
        ];
    }
}
