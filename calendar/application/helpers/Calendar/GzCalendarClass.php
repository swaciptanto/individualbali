<?php

require_once CONTROLLERS_PATH . 'App.php';

class GzCalendarClass extends App {

    var $option_arr = array();
    var $reservationsInfo = array();
    var $calendar_capacity = array();

    function __construct() {
        Object::loadFiles('Model', 'Option');
        $OptionModel = new OptionModel();

        $this->option_arr = $OptionModel->getAllCalendarsPairValues();
    }

    function getDaysInMonth($month, $year) {
        $daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

        if ($month < 1 || $month > 12) {
            return 0;
        }

        $d = $daysInMonth[$month - 1];

        if ($month == 2) {
            if ($year % 4 == 0) {
                if ($year % 100 == 0) {
                    if ($year % 400 == 0) {
                        $d = 29;
                    }
                } else {
                    $d = 29;
                }
            }
        }

        return $d;
    }

    function getCalendarDateClass($cid, $m, $d, $y, $reservationsInfo, $calendar_capacity) {

        $option_arr = $this->option_arr[$cid];

        $today = getdate(time());
        $timestamp = mktime(0, 0, 0, $m, $d, $y);
        $date = date('Y-m-d', $timestamp);
        $daysInMonth = $this->getDaysInMonth($m, $y);

        /* if ($y == $today["year"] && $m == $today["mon"] && $d == $today["mday"]) {
          $class = 'calendar';
          } elseif ($d < 1 || $d > $daysInMonth) {
          $class = 'calendarEmpty';
          } elseif ($timestamp < $today[0]) {
          $class = 'calendarPast';
          } else {
          $class = 'calendar';
          } */

        $class = 'calendar';

        if ($d > 0 && $d <= $daysInMonth) {

            if (!empty($reservationsInfo)) {

                if (array_key_exists($date, $reservationsInfo)) {
                    if ($option_arr['based_on'] == 'night') {
                        if ($calendar_capacity > 1) {
                            if ((@$reservationsInfo[$date]['date_from']['count'] >= $calendar_capacity && @$reservationsInfo[$date]['date_to']['count'] >= $calendar_capacity) ||
                                    (@$reservationsInfo[$date]['count'] >= $calendar_capacity) ||
                                    ((@$reservationsInfo[$date]['date_from']['count'] + @$reservationsInfo[$date]['count']) >= $calendar_capacity && (@$reservationsInfo[$date]['date_to']['count'] + @$reservationsInfo[$date]['count']) >= $calendar_capacity)) {
                                $class = "calendarReserved";
                            } else {
                                if ((@$reservationsInfo[$date]['date_from']['count'] + @$reservationsInfo[$date]['count']) >= $calendar_capacity) {
                                    $class = "calendarReservedNightsStart";
                                } elseif ((@$reservationsInfo[$date]['date_to']['count'] + @$reservationsInfo[$date]['count']) >= $calendar_capacity) {
                                    $class = "calendarReservedNightsEnd";
                                }
                            }
                        } else {
                            if (array_key_exists($date, @$reservationsInfo)) {
                                if (array_key_exists('count', @$reservationsInfo[$date])) {
                                    switch (@$reservationsInfo[$date]['status']) {
                                        case 'pending':
                                            $class = 'calendarPending';
                                            break;
                                        case 'confirmed':
                                            $class = 'calendarReserved';
                                            break;
                                    }
                                } else {
                                    if (array_key_exists('date_from', @$reservationsInfo[$date]) && !array_key_exists('date_to', @$reservationsInfo[$date])) {
                                        switch (@$reservationsInfo[$date]['date_from']['status']) {
                                            case 'pending':
                                                $class = 'calendarPendingNightsStart';
                                                break;
                                            case 'confirmed':
                                                $class = 'calendarReservedNightsStart';
                                                break;
                                        }
                                    } elseif (!array_key_exists('date_from', @$reservationsInfo[$date]) && array_key_exists('date_to', @$reservationsInfo[$date])) {

                                        switch (@$reservationsInfo[$date]['date_to']['status']) {
                                            case 'pending':
                                                $class = 'calendarPendingNightsEnd';
                                                break;
                                            case 'confirmed':
                                                $class = 'calendarReservedNightsEnd';
                                                break;
                                        }
                                    } elseif (array_key_exists('date_from', @$reservationsInfo[$date]) && array_key_exists('date_to', @$reservationsInfo[$date])) {

                                        if (@$reservationsInfo[$date]['date_from']['status'] == 'pending' && @$reservationsInfo[$date]['date_to']['status'] == 'pending') {
                                            $class = 'calendarPendingPending';
                                        } elseif (@$reservationsInfo[$date]['date_from']['status'] == 'pending' && @$reservationsInfo[$date]['date_to']['status'] == 'confirmed') {
                                            $class = 'calendarReservedPending';
                                        } elseif (@$reservationsInfo[$date]['date_from']['status'] == 'confirmed' && @$reservationsInfo[$date]['date_to']['status'] == 'pending') {
                                            $class = 'calendarPendingReserved';
                                        } elseif (@$reservationsInfo[$date]['date_from']['status'] == 'confirmed' && @$reservationsInfo[$date]['date_to']['status'] == 'confirmed') {
                                            $class = 'calendarReservedReserved';
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($calendar_capacity > 1) {
                            if ((@$reservationsInfo[$date]['date_from']['count'] >= $calendar_capacity && @$reservationsInfo[$date]['date_to']['count'] >= $calendar_capacity) ||
                                    (@$reservationsInfo[$date]['count'] >= $calendar_capacity) ||
                                    ((@$reservationsInfo[$date]['date_from']['count'] + @$reservationsInfo[$date]['count']) >= $calendar_capacity && (@$reservationsInfo[$date]['date_to']['count'] + @$reservationsInfo[$date]['count']) >= $calendar_capacity)) {
                                $class = "calendarReserved";
                            } else {
                                if ((@$reservationsInfo[$date]['date_from']['count'] + @$reservationsInfo[$date]['count']) >= $calendar_capacity) {
                                    $class = "calendarReserved";
                                } elseif ((@$reservationsInfo[$date]['date_to']['count'] + @$reservationsInfo[$date]['count']) >= $calendar_capacity) {
                                    $class = "calendarReserved";
                                }
                            }
                        } else {
                            if (array_key_exists($date, @$reservationsInfo)) {
                                switch (@$reservationsInfo[$date]['status']) {
                                    case 'pending':
                                        $class = 'calendarPending';
                                        break;
                                    case 'confirmed':
                                        $class = 'calendarReserved';
                                        break;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $class;
    }

    function getSummaryCalendar() {
        Object::loadFiles('Model', array('Languages'));
        $LanguagesModel = new LanguagesModel();

        $default_language = $LanguagesModel->getAll(array('isdefault' => 1), 'order');

        Object::loadFiles('Model', array('Booking', 'Calendar', 'CalendarGallery'));
        $BookingModel = new BookingModel();
        $CalendarModel = new CalendarModel();
        $CalendarGalleryModel = new CalendarGalleryModel();

        if (empty($_GET['month'])) {
            $_GET['month'] = date('m');
        }

        if (empty($_GET['year'])) {
            $_GET['year'] = date('y');
        }

        if ($_GET['month'] == 13) {
            $_GET['month'] = 1;
            $_GET['year'] ++;
        }
        if (!empty($_GET['month']) && $_GET['month'] == 0) {
            $_GET['month'] = 12;
            $_GET['year'] = $_GET['year'] - 1;
        }

        $y = $_GET['year'];
        $m = $_GET['month'];

        if (empty($_GET['day'])) {
            $day = date('d', time());
        } else {
            $day = $_GET['day'];
        }

        if (empty($_GET['view'])) {
            $_GET['view'] = '1';
        }

        $daysInMonth = $this->getDaysInMonth($m, $y);
        $daysInNextMonth = $this->getDaysInMonth($m + 1, $y);
        $_arr = $CalendarModel->getI18nAll();
        $calendars = array();

        $i = 0;
        foreach ($_arr as $k => $v) {
            $calendars[$i] = $v;
            $calendars[$i]['gallery'] = $CalendarGalleryModel->getOne(array('calendar_id' => $v['id']));
            $i++;
        }

        $opts = array();
        $info_arr = array();

        $now = mktime(0, 0, 0, $m, $day, $y);

        $from = $now - (60 * 24 * 60 * 60);
        $to = $now + (60 * 24 * 60 * 60);

        $sql = "SELECT MIN(`date_from`) AS `min`, MAX(`date_to`) AS `max` FROM " . $BookingModel->getTable() . " WHERE date_from BETWEEN " . $from . "  AND " . $to . " OR date_to BETWEEN " . $from . "  AND " . $to . "  ";
        $arr = $BookingModel->execute($sql); // min and max reseravation datefor this period

        $opts['date_from > ?'] = "1 AND date_from BETWEEN " . $from . "  AND " . $to . " OR date_to BETWEEN " . $from . "  AND " . $to . " ";

        $_arr = $BookingModel->getAllBookingWithCalendar($opts); // ALL reservations for this period

        if (is_array($arr) && !empty($arr[0])) {
            for ($i = $arr[0]['min'] - (60 * 24 * 60 * 60); $i <= $arr[0]['max']; $i += 86400) {

                $date = date('Y-m-d', $i);

                if (count($_arr) > 0) {
                    foreach ($_arr as $k => $v) {

                        $from_date = date('Y-m-d', $v['date_from']);
                        $to_date = date('Y-m-d', $v['date_to']);

                        if (strtotime($from_date) <= strtotime($date) && strtotime($to_date) >= strtotime($date)) {

                            $this->reservationsInfo[$v['calendar_id']][$date]['date'] = date('y-m-d', $i);
                            if (empty($this->reservationsInfo[$v['calendar_id']][$date])) {

                                $this->reservationsInfo[$v['calendar_id']][$date]['arr'] = array();
                                $this->reservationsInfo[$v['calendar_id']][$date]['arr'][] = $v;
                            }
                            if ($date == date('Y-m-d', $v['date_from'])) {
                                if (empty($this->reservationsInfo[$v['calendar_id']][$date]['date_from'])) {
                                    $this->reservationsInfo[$v['calendar_id']][$date]['date_from']['count'] = 1;
                                } else {
                                    $this->reservationsInfo[$v['calendar_id']][$date]['date_from']['count'] ++;
                                }
                                $this->reservationsInfo[$v['calendar_id']][$date]['date_from']['status'] = $v['status'];
                            } elseif ($date == date('Y-m-d', $v['date_to'])) {
                                if (empty($this->reservationsInfo[$v['calendar_id']][$date]['date_to'])) {
                                    $this->reservationsInfo[$v['calendar_id']][$date]['date_to']['count'] = 1;
                                } else {
                                    $this->reservationsInfo[$v['calendar_id']][$date]['date_to']['count'] ++;
                                }
                                $this->reservationsInfo[$v['calendar_id']][$date]['date_to']['status'] = $v['status'];
                            } else {
                                if (empty($this->reservationsInfo[$v['calendar_id']][$date]['count'])) {
                                    $this->reservationsInfo[$v['calendar_id']][$date]['count'] = 1;
                                } else {
                                    $this->reservationsInfo[$v['calendar_id']][$date]['count'] ++;
                                }
                                $this->reservationsInfo[$v['calendar_id']][$date]['status'] = $v['status'];
                            }
                            $this->reservationsInfo[$v['calendar_id']][$date]['status'] = $v['status'];
                        }
                    }
                }
            }
        }

        Object::loadFiles('Model', array('Blocking'));
        $BlockingModel = new BlockingModel();

        $now = mktime(0, 0, 0, $m, $day, $y);
        $from = $now - (60 * 24 * 60 * 60);
        $to = $now + (60 * 24 * 60 * 60);

        $sql = "SELECT MIN(`from_date`) AS `min`, MAX(`to_date`) AS `max` FROM " . $BlockingModel->getTable() . " WHERE from_date BETWEEN " . $from . "  AND " . $to . " OR to_date BETWEEN " . $from . "  AND " . $to . " ";
        $arr = $BlockingModel->execute($sql); // min and max reseravation datefor this period

        $opts = array();
        $opts['(from_date BETWEEN :from AND :to OR to_date BETWEEN :from AND :to)'] = array(':from' => $from, ':to' => $to);
        $_arr = $BlockingModel->getAll($opts); // ALL reservations for this period

        if (is_array($arr) && !empty($arr[0])) {

            for ($i = strtotime(date('Y-m-d', $arr[0]['min'])) - (60 * 24 * 60 * 60); $i <= $arr[0]['max']; $i += 86400) {

                $date = date('Y-m-d', $i);

                if (count($_arr) > 0) {
                    foreach ($_arr as $k => $v) {
                        $from_date = date('Y-m-d', $v['from_date']);
                        $to_date = date('Y-m-d', $v['to_date']);

                        if (strtotime($from_date) <= strtotime($date) && strtotime($to_date) >= strtotime($date)) {

                            $this->reservationsInfo[$v['calendar_id']][$date]['date'] = date('Y-m-d', $i);

                            if ($date == date('Y-m-d', $v['from_date'])) {
                                $this->reservationsInfo[$v['calendar_id']][$date]['date_from']['count'] = 100;
                                $this->reservationsInfo[$v['calendar_id']][$date]['date_from']['status'] = 'confirmed';
                            } elseif ($date == date('Y-m-d', $v['to_date'])) {
                                $this->reservationsInfo[$v['calendar_id']][$date]['date_to']['count'] = 100;
                                $this->reservationsInfo[$v['calendar_id']][$date]['date_to']['status'] = 'confirmed';
                            } else {
                                $this->reservationsInfo[$v['calendar_id']][$date]['count'] = 100;
                                $this->reservationsInfo[$v['calendar_id']][$date]['status'] = 'confirmed';
                            }
                            $this->reservationsInfo[$v['calendar_id']][$date]['status'] = 'confirmed';
                        }
                    }
                }
            }
        }
        
        

        $_arr_capacity = $CalendarModel->getAll();
        $this->calendar_capacity = array();

        foreach ($_arr_capacity as $k => $v) {
            $this->calendar_capacity[$v['id']] = $v['limit'];
        }

        require_once 'view/summary_calendars.php';
    }

}

?>