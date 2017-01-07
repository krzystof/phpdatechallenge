<?php

class MyDate
{
    const JANUARY = 1;
    const FEBRUARY = 2;
    const MARCH = 3;
    const MAY = 5;
    const JULY = 7;
    const AUGUST = 8;
    const OCTOBER = 10;
    const DECEMBER = 12;

    const THIRTY_ONE_DAYS_MONTHS = [
        self::JANUARY,
        self::MARCH,
        self::MAY,
        self::JULY,
        self::AUGUST,
        self::OCTOBER,
        self::DECEMBER,
    ];

    private $year;
    private $month;
    private $day;

    public function __construct($year, $month, $day)
    {
        $this->year = (int) $year;
        $this->month = (int) $month;
        $this->day = (int) $day;
    }

    /**
     * Get the date intervals between two given dates
     *
     * @param  string $start
     * @param  string $end
     * @return object
     */
    public static function diff($start, $end)
    {
        return MyDateDiff::fromDates(static::parse($start), static::parse($end));
    }

    /**
     * Parse a string into a MyDate object
     *
     * @param  string $date
     * @return MyDate
     */
    private static function parse($string)
    {
        $parts = explode('/', $string);

        if (count($parts) !== 3) {
            throw new InvalidArgumentException("Cannot parse the string '$string' to a date");
        }

        return new static($parts[0], $parts[1], $parts[2]);
    }

    /**
     * Check if the date is after the passed in date
     *
     * @param  MyDate  $date
     * @return boolean
     */
    public function isAfter(MyDate $date)
    {
        if ($this->year !== $date->year) {
            return $this->year > $date->year;
        }

        if ($this->month !== $date->month) {
            return $this->month > $date->month;
        }

        return $this->day > $date->day;
    }

    /**
     * Get the difference in days compare to the give date
     *
     * @param  MyDate $date
     * @return integer
     */
    public function diffInDays(MyDate $date)
    {
        return abs($this->day - $date->day);
    }

    /**
     * The date is on the same year as the given date
     *
     * @param  MyDate  $date
     * @return boolean
     */
    public function isSameYear($date)
    {
        return $this->year === $date->year;
    }

    /**
     * The date is on the same month as the given date
     *
     * @param  MyDate  $date
     * @return boolean
     */
    public function isSameMonth($date)
    {
        return $this->month === $date->month;
    }

    /**
     * Get the difference in days compare to
     * the flast day of the previous day
     *
     * @return integer
     */
    public function diffFromPreviousEndOfMonth()
    {
        return $this->day;
    }

    /**
     * Get a new instance of MyDate for the last day of the
     * previous month
     *
     * @return MyDate
     */
    public function endOfPreviousMonth()
    {
        $month = $this->month === static::JANUARY ? static::DECEMBER : $this->month - 1;
        $year = $month === static::DECEMBER ? $this->year - 1 : $this->year;
        $day = $this->getLastDayOfMonth($month);

        return new static($year, $month, $day);
    }

    /**
     * Get the numerical value for the last day
     * of the given month
     *
     * @param  integer $month
     * @return integer
     */
    private function getLastDayOfMonth($month)
    {
        if ($month === static::FEBRUARY) {
            return $this->getLastDayForFebruary();
        }

        return in_array($month, static::THIRTY_ONE_DAYS_MONTHS) ? 31 : 30;
    }

    /**
     * Get the last day for February
     *
     * @return integer
     */
    private function getLastDayForFebruary()
    {
        return $this->isLeapYear() ? 29 : 28;
    }

    /**
     * Check wether the date is in a leap year
     *
     * @return boolean
     */
    private function isLeapYear()
    {
        if ($this->year % 100 === 0) {
            return $this->year % 400 === 0;
        }

        return $this->year % 4 === 0;
    }
}
