<?php

namespace API\AbasteceFacil\Services;

use DateTime;
use DateTimeZone;

class DateHandler
{
    private $DateTime;
    private $month;
    private $numericMonth;
    private $year;

    public function __construct($month = null, $year = null)
    {
        $this->DateTime = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));

        if (empty($month)) {
            $this->numericMonth = $this->DateTime->format('m');
            $this->year = $this->DateTime->format('Y');
            $this->month = $this->mapMonth($this->numericMonth);
        }

        if (!empty($month) && !empty($year)) {
            $this->numericMonth = $month;
            $this->year = $year;
            $this->month = $this->mapMonth($month);
        }
    }

    private function mapMonth($month) {
        $months = [
            '01' => 'janeiro',
            '02' => 'fevereiro',
            '03' => 'marco',
            '04' => 'abril',
            '05' => 'maio',
            '06' => 'junho',
            '07' => 'julho',
            '08' => 'agosto',
            '09' => 'setembro',
            '10' => 'outubro',
            '11' => 'novembro',
            '12' => 'dezembro'
        ];
        return $months[$month];
    }

    public function setPreviousMonth() {
        $previousMonth = $this->DateTime->modify('-1 month');
        $this->numericMonth = $previousMonth->format('m');
        $this->year = $previousMonth->format('Y');
        $this->month = $this->mapMonth($this->numericMonth);
    }

    public function getMonth()
    {
        return $this->month;
    }

    public function getNumericMonth()
    {
        return $this->numericMonth;
    }

    public function getYear()
    {
        return $this->year;
    }

}
