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

    const TOTAL_DAYS_IN_FEBRUARY = 28;
    const TOTAL_DAYS_IN_LEAP_YEAR_FEBRUARY = 29;
    const TOTAL_DAYS_IN_EVEN_MONTH = 30;
    const TOTAL_DAYS_IN_ODD_MONTH = 31;

    const THIRTY_ONE_DAYS_MONTHS = [
        self::JANUARY,
        self::MARCH,
        self::MAY,
        self::JULY,
        self::AUGUST,
        self::OCTOBER,
        self::DECEMBER,
    ];

    // thirtyOneDaysMonths

    private $year;
    private $month;
    private $day;

    public function __construct($year, $month, $day)
    {
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
    }

    public static function diff($start, $end)
    {
        $start_date = static::parse($start);
        $end_date = static::parse($end);
        return MyDateDiff::fromDates($start_date, $end_date);
    }

    private static function parse($string)
    {
        $parts = explode('/', $string);

        if (count($parts) !== 3) {
            throw new InvalidArgumentException("Cannot parse the string $string to a date");
        }

        return new static((int) $parts[0], (int) $parts[1], (int) $parts[2]);
    }

    public function year()
    {
        return $this->year;
    }

    public function month()
    {
        return $this->month;
    }

    public function day()
    {
        return $this->day;
    }

    public function isAfter(MyDate $date)
    {
        if ($this->year < $date->year) {
            return false;
        }

        if ($this->year === $date->year && $this->month < $date->month) {
            return false;
        }

        if ($this->year === $date->year && $this->month === $date->month && $this->day < $date->day) {
            return false;
        }

        return true;
    }

    public function diffInYears(MyDate $date)
    {
        return abs($this->year - $date->year);
    }

    public function diffInMonths(MyDate $date)
    {
        return abs($this->month - $date->month);
    }

    public function diffInDays(MyDate $date)
    {
        return abs($this->day - $date->day);
    }

    public function isSameYear($date)
    {
        return $this->year === $date->year;
    }

    public function isSameMonth($date)
    {
        return $this->month === $date->month;
    }

    public function subMonth()
    {
        $previous_month = $this->month === 1 ? 12 : $this->month - 1;
        return new static($this->year, $previous_month, $this->day);
    }

    public function diffFromStartOfMonth()
    {
        echo '+days: ' . $this->day . "\n";
        return $this->day - 1;
    }

    public function endOfPreviousMonth()
    {
        $month = $this->month === static::JANUARY ? static::DECEMBER : $this->month - 1;
        $year = $month === static::DECEMBER ? $this->year - 1 : $this->year;
        $day = $this->getLastDayOfMonth($month);

        return new static($year, $month, $day);
    }

    private function getLastDayOfMonth($month)
    {
        if ($month === static::FEBRUARY) {
            return $this->getDaysForFebruary();
        }

        return in_array($month, static::THIRTY_ONE_DAYS_MONTHS) ? 31 : 30;
    }

    private function getDaysForFebruary()
    {
        if ($this->isLeapYear()) {
            return static::TOTAL_DAYS_IN_LEAP_YEAR_FEBRUARY;
        } else {
            return static::TOTAL_DAYS_IN_FEBRUARY;
        }
    }

    private function isLeapYear()
    {
        if ($this->year % 100 === 0) {
            return $this->year % 400 === 0;
        }

        return $this->year % 4 === 0;
    }

    public function __toString()
    {
        return $this->year .'/'.$this->month.'/'.$this->day;
    }
}
