<?php
namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\Event\Event;
use ArrayObject;
use Cake\I18n\Date;

class SimpleDateBehavior extends Behavior {
    protected $_defaultConfig = [
        'date_string_field' => 'date_string',
        'date_field' => 'date',
        'allow_year_in_string' => false,
    ];

    private function dateStringField() {
        return $this->getConfig()['date_string_field'];
    }

    private function dateField() {
        return $this->getConfig()['date_field'];
    }

    private function allowYear() {
        return $this->getConfig()['allow_year_in_string'];
    }

    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options) {
        $dateString = $data[$this->dateStringField()];
        if (!is_null($dateString) && $this->isValidDateString($dateString))
            $data[$this->dateField()] = $this->buildDate($dateString);
    }

    protected function buildDate($dateString) {
        // FIXME include year based on $this->allowYear();
        $hasSlash = substr_count($dateString, '/') === 1;
        $monthDay = $hasSlash ? explode('/', $dateString) : explode('-', $dateString);

        $date = new Date();
        $year = $date->year;
        $currentMonth = $date->month;
        $month = intval($monthDay[0]);
        $day = intval($monthDay[1]);
        $july = 7;
        if ($currentMonth < $july && $month >= $july)
            $year -= 1;
        $date
            ->year($year)
            ->month($month)
            ->day($day);
        return $date;
    }

    public function isValidDateString($dateString) {
        $len = strlen($dateString);
        if ($len < 3 || $len > 5)
            return false;
        $hasSlash = substr_count($dateString, '/') === 1;
        $hasDash = substr_count($dateString, '-' )=== 1;
        if (!$hasSlash && !$hasDash || ($hasSlash && $hasDash))
            return false;
        $monthDay = $hasSlash ? explode('/', $dateString) : explode('-', $dateString);
        $month = $monthDay[0];
        $day = $monthDay[1];
        $monthInt = intval($month);
        $dayInt = intval($day);
        if ($monthInt <= 0 || $monthInt > 12)
            return false;
        $monthDays = [
            1 => 31, 
            2 => 29,
            3 => 31,
            4 => 30,
            5 => 31,
            6 => 30,
            7 => 31,
            8 => 31,
            9 => 30,
            10 => 31,
            11 => 30,
            12 => 31
        ];
        $maxDay = $monthDays[$monthInt];
        return $dayInt > 0 && $dayInt <= $maxDay;
    }

    public function getDocumentDate($dateString) {
        if (!$this->isValidDateString($dateString))
            return false;
        
        $date = $this->buildDate($dateString);
        $docDate = $date->year . '-' . $date->month . '-' . $date->day;
        return $docDate;
    }
}