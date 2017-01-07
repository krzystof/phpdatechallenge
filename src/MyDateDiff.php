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

    /**
     * Instantiate and process the difference
     * between two dates
     *
     * @param MyDate $start_date
     * @param MyDate $end_date
     */
    private function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->initialize();
    }

    /**
     * Initialize the interval and calculate
     * the difference between the two dates
     *
     * @return void
     */
    private function initialize()
    {
        $this->swapDatesIfInvert();
        $this->calculateTotalDaysBetween($this->start_date, $this->end_date);
        $this->setIntervals();
    }

    /**
     * Calculate the difference between the two given dates
     *
     * @param  MyDate $start_date
     * @param  MyDate $end_date
     * @return object
     */
    public static function fromDates(MyDate $start_date, MyDate $end_date)
    {
        $diff = new static($start_date, $end_date);
        return $diff->toObject();
    }

    /**
     * Swap the two dates on the current instance
     * if the start date is after the end date
     *
     * @return void
     */
    private function swapDatesIfInvert()
    {
        if ($this->start_date->isAfter($this->end_date)) {
            $temp_date = $this->start_date;
            $this->start_date = $this->end_date;
            $this->end_date = $temp_date;
            $this->invert = true;
        }
    }

    /**
     * Process the interval between the two given dates
     *
     * @param  MyDate $start_date
     * @param  MyDate $end_date
     * @return void
     */
    private function calculateTotalDaysBetween(MyDate $start_date, MyDate $end_date)
    {
        if (! $end_date->isSameYear($start_date) || ! $end_date->isSameMonth($start_date)) {
            $this->total_days += $end_date->diffFromPreviousEndOfMonth();
            $this->months++;

            return $this->calculateTotalDaysBetween($start_date, $end_date->endOfPreviousMonth());
        }

        $this->total_days += $end_date->diffInDays($start_date);
    }

    /**
     * Set the various intervals for the years, months and days
     *
     * @return  void
     */
    private function setIntervals()
    {
        $this->years = intval($this->months / static::MONTHS_PER_YEAR);

        if ($this->months > static::MONTHS_PER_YEAR) {
            $this->months = $this->months % static::MONTHS_PER_YEAR - 1;
        }

        $this->days = $this->end_date->diffInDays($this->start_date);
    }

    /**
     * Convert the interval to an object
     *
     * @return object
     */
    protected function toObject()
    {
        return (object) [
            'years' => $this->years,
            'months' => $this->months,
            'days' => $this->days,
            'total_days' => $this->total_days,
            'invert' => $this->invert,
        ];
    }
}
