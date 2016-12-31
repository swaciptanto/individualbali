<?php

require_once CONTROLLERS_PATH . 'App.php';

class ABCalendar extends App {

    var $view_month = 1;
    var $show_weeks = true;
    var $calendar_id = null;
    var $startDay = 0;
    var $prevLink = "&lt;";
    var $nextLink = "&gt;";
    var $weekTitle = "#";
    var $dayNames = array();
    var $monthNames = array();
    var $calendar_capacity = 1;
    var $calendar_arr = array();
    var $reservationsInfo = array();
    var $option_arr = array();
    var $month = 1;
    var $year = 1;
    var $default_language = array();
    var $prices = array();

    function __construct($m, $day, $y, $cid, $view_month, $option_arr_values, $default_language) {
        Object::loadFiles('Model', array('Calendar'));
        $CalendarModel = new CalendarModel();
        $this->default_language = $default_language;
        $this->month = $m;
        $this->year = $y;
        $this->dayNames = __('dayNames');
        $this->monthNames = __('monthNames');
        $this->weekTitle = __('weekTitle');
        $this->calendar_id = $cid;
        $this->view_month = $view_month;
        $this->option_arr = $option_arr_values;
        $this->calendar_arr = $CalendarModel->getI18n($cid);
        $this->calendar_capacity = $this->calendar_arr['limit'];
        $this->startDay = $option_arr_values['week_first_day'];
        $this->serReservationsInfo($m, $day, $y);
        $this->serPricesArr($m, $day, $y);
    }

    function serPricesArr($m, $day, $y) {
        Object::loadFiles('Model', array('Price'));
        $PriceModel = new PriceModel();
        $now = mktime(0, 0, 0, $m, 0, $y);

        $from = $now - (1 * 24 * 60 * 60);
        $to = $now + ($this->view_month * 60 * 24 * 60 * 60);

        $opts = array();

        $opts['from_date'] = $from;
        $opts['to_date'] = $to;
        $arr = $PriceModel->getCalendarPrices($opts, $this->calendar_id);

        foreach ($arr as $k => $v) {
            for ($i = $from; $i <= $to; $i += 86400) {
                if (($i<=$v['to_date'] && $i>=$v['from_date']) && ($i >= $from && $i <= $to)) {
                    switch (date('N', $i)) {
                        case 1:
                            $this->prices[$i]['price'] = Util::currenctFormat($this->option_arr['currency'], $v['monday']);
                            break;
                        case 2:
                            $this->prices[$i]['price'] = Util::currenctFormat($this->option_arr['currency'], $v['tuesday']);
                            break;
                        case 3:
                            $this->prices[$i]['price'] = Util::currenctFormat($this->option_arr['currency'], $v['wednesday']);
                            break;
                        case 4:
                            $this->prices[$i]['price'] = Util::currenctFormat($this->option_arr['currency'], $v['thursday']);
                            break;
                        case 5:
                            $this->prices[$i]['price'] = Util::currenctFormat($this->option_arr['currency'], $v['friday']);
                            break;
                        case 6:
                            $this->prices[$i]['price'] = Util::currenctFormat($this->option_arr['currency'], $v['saturday']);
                            break;
                        case 7:
                            $this->prices[$i]['price'] = Util::currenctFormat($this->option_arr['currency'], $v['sunday']);
                            break;
                    }
                }
            }
        }
    }

    function serReservationsInfo($m, $day, $y) {
        Object::loadFiles('Model', array('Booking'));
        $BookingModel = new BookingModel();

        $now = mktime(0, 0, 0, $m, 0, $y);
        $from = $now - (60 * 24 * 60 * 60);
        $to = $now + ($this->view_month * 60 * 24 * 60 * 60);

        $sql = "SELECT MIN(`date_from`) AS `min`, MAX(`date_to`) AS `max` FROM " . $BookingModel->getTable() . " WHERE date_from BETWEEN " . $from . "  AND " . $to . " OR date_to BETWEEN " . $from . "  AND " . $to . " AND calendar_id = " . $this->calendar_id . " ";
        $arr = $BookingModel->execute($sql); // min and max reseravation datefor this period

        $opts = array();
        $opts['(date_from BETWEEN :from AND :to OR date_to BETWEEN :from AND :to) AND calendar_id = :calendar_id'] = array(':from' => $from, ':to' => $to, ':calendar_id' => $this->calendar_id);
        $_arr = $BookingModel->getAll($opts); // ALL reservations for this period

        if (!empty($arr[0]['min']) && !empty($arr[0]['max'])) {

            for ($i = $arr[0]['min'] - (60 * 24 * 60 * 60); $i <= $arr[0]['max']; $i = strtotime('+1 day', $i)) {

                if (count($_arr) > 0) {

                    foreach ($_arr as $k => $v) {

                        if ($v['date_from'] <= $i && ($v['date_to'] >= $i)) {

                            $this->reservationsInfo[$i]['date'] = date('y-m-d', $i);

                            if (empty($this->reservationsInfo[$i])) {

                                $this->reservationsInfo[$i]['arr'] = array();
                                $this->reservationsInfo[$i]['arr'][] = $v;
                            }
                            if ($i == $v['date_from']) {
                                if (empty($this->reservationsInfo[$i]['date_from'])) {
                                    $this->reservationsInfo[$i]['date_from']['count'] = 1;
                                } else {
                                    $this->reservationsInfo[$i]['date_from']['count'] ++;
                                }
                                $this->reservationsInfo[$i]['date_from']['status'] = $v['status'];
                            } elseif ($i == $v['date_to']) {
                                if (empty($this->reservationsInfo[$i]['date_to'])) {
                                    $this->reservationsInfo[$i]['date_to']['count'] = 1;
                                } else {
                                    $this->reservationsInfo[$i]['date_to']['count'] ++;
                                }
                                $this->reservationsInfo[$i]['date_to']['status'] = $v['status'];
                            } else {
                                if (empty($this->reservationsInfo[$i]['count'])) {
                                    $this->reservationsInfo[$i]['count'] = 1;
                                } else {
                                    $this->reservationsInfo[$i]['count'] ++;
                                }
                                $this->reservationsInfo[$i]['status'] = $v['status'];
                            }

                            $this->reservationsInfo[$i]['status'] = $v['status'];
                        }
                    }
                }
            }
        }

        Object::loadFiles('Model', array('Blocking'));
        $BlockingModel = new BlockingModel();

        $now = mktime(0, 0, 0, $m, 0, $y);
        $from = $now - (60 * 24 * 60 * 60);
        $to = $now + ($this->view_month * 60 * 24 * 60 * 60);

        $sql = "SELECT MIN(`from_date`) AS `min`, MAX(`to_date`) AS `max` FROM " . $BlockingModel->getTable() . " WHERE from_date BETWEEN " . $from . "  AND " . $to . " OR to_date BETWEEN " . $from . "  AND " . $to . " AND calendar_id = " . $this->calendar_id . " ";
        $arr = $BlockingModel->execute($sql); // min and max reseravation datefor this period

        $opts = array();
        $opts['(from_date BETWEEN :from AND :to OR to_date BETWEEN :from AND :to) AND calendar_id = :calendar_id'] = array(':from' => $from, ':to' => $to, ':calendar_id' => $this->calendar_id);
        $_arr = $BlockingModel->getAll($opts); // ALL reservations for this period


        if (is_array($arr) && !empty($arr[0])) {
            if (count($_arr) > 0) {
                for ($i = $arr[0]['min'] - (60 * 24 * 60 * 60); $i <= ($arr[0]['max'] + 60 * 24 * 60 * 60); $i += 86400) {
                    
                    foreach ($_arr as $k => $v) {
                        //echo $v['to_date'] . ' ' . date('Y-m-d', $v['to_date']).'<br />';
                        if ($v['from_date'] <= $i && ($v['to_date'] >= $i)) {
                            $this->reservationsInfo[$i]['date'] = date('y-m-d', $i);
                            if ($i == $v['from_date']) {
                                $this->reservationsInfo[$i]['date_from']['count'] = 100;
                                $this->reservationsInfo[$i]['date_from']['status'] = 'confirmed';
                            } elseif ($i == $v['to_date']) {
                                $this->reservationsInfo[$i]['date_to']['count'] = 100;
                                $this->reservationsInfo[$i]['date_to']['status'] = 'confirmed';
                            } else {
                                $this->reservationsInfo[$i]['count'] = 100;
                                $this->reservationsInfo[$i]['status'] = 'confirmed';
                            }
                            $this->reservationsInfo[$i]['status'] = 'confirmed';
                        }
                    }
                }
            }
        }
    }

    function setPrevLink($value) {
        $this->prevLink = $value;
    }

    function setNextLink($value) {
        $this->nextLink = $value;
    }

    function getPrevLink() {
        return $this->prevLink;
    }

    function getNextLink() {
        return $this->nextLink;
    }

    function getMonthView($month = null, $year = null) {
        if (!empty($month)) {
            $this->month = $month;
        }
        if (!empty($year)) {
            $this->year = $year;
        }
        if (empty($this->month)) {
            $this->month = date('n');
        }
        if (empty($this->year)) {
            $this->year = date('Y');
        }

        $date = getdate(mktime(12, 0, 0, $this->month, 1, $this->year));

        $prevMonth = ($this->month - 1) < 1 ? 12 : ($this->month - 1);
        $prevYear = ($this->month - 1) < 1 ? ($this->year - 1) : $this->year;

        $nextMonth = ($this->month + 1) > 12 ? 1 : ($this->month + 1);
        $nextYear = ($this->month + 1) > 12 ? ($this->year + 1) : $this->year;

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);

        $cols = $this->show_weeks ? 8 : 7;
        $cal = "";
        $cal .= "<table class=\"gzABCalendarTable\" cellspacing=\"0\" cellpadding=\"0\">";
        if ($this->view_month == 1) {
            $cal .= "<tr>";
            $cal .= "<td class=\"gzABCalCellArrow\" data-timestamp=\"" . mktime(0, 0, 0, $this->month, 1, $this->year) . "\" rev=\"" . $this->calendar_id . "\" data-month=\"" . $prevMonth . "\" data-year=\"" . $prevYear . "\"><a href=\"javascript:\"><i class=\"fa fa-fw fa-chevron-circle-left\"></i></a></td>";
            $cal .= "<td class=\"gzABCalCellMonth\" colspan=\"" . ($cols - 2) . "\">" . @$this->monthNames[$this->month] . " " . $this->year . "</td>";
            $cal .= "<td class=\"gzABCalCellArrow\" data-timestamp=\"" . mktime(0, 0, 0, $this->month, $daysInMonth, $this->year) . "\" rev=\"" . $this->calendar_id . "\" data-month=\"" . $nextMonth . "\" data-year=\"" . $nextYear . "\"><a href=\"javascript:\"><i class=\"fa fa-fw fa-chevron-circle-right\"></i></a></td>";
            $cal .= "</tr>";
        } else {
            $cal .= "<tr>";
            $cal .= "<td class=\"gzABCalCellMonth\" data-timestamp=\"" . mktime(0, 0, 0, $this->month, 1, $this->year) . "\" rev=\"" . $this->calendar_id . "\" data-month=\"" . $prevMonth . "\" data-year=\"" . $prevYear . "\"></td>";
            $cal .= "<td class=\"gzABCalCellMonth\" colspan=\"" . ($cols - 2) . "\">" . @$this->monthNames[$this->month] . " " . $this->year . "</td>";
            $cal .= "<td class=\"gzABCalCellMonth\" data-timestamp=\"" . mktime(0, 0, 0, $this->month, $daysInMonth, $this->year) . "\" rev=\"" . $this->calendar_id . "\" data-month=\"" . $nextMonth . "\" data-year=\"" . $nextYear . "\"></td>";
            $cal .= "</tr>";
        }
        $cal .= "<tr>";
        if ($this->show_weeks) {
            $cal .= "<td class=\"gzABCalCellWeekDay\">" . $this->weekTitle . "</td>";
        }
        $cal .= "<td class=\"gzABCalCellWeekDay\">" . $this->dayNames[($this->startDay - 1) % 7] . "</td>";
        $cal .= "<td class=\"gzABCalCellWeekDay\">" . $this->dayNames[($this->startDay ) % 7] . "</td>";
        $cal .= "<td class=\"gzABCalCellWeekDay\">" . $this->dayNames[($this->startDay + 1) % 7] . "</td>";
        $cal .= "<td class=\"gzABCalCellWeekDay\">" . $this->dayNames[($this->startDay + 2) % 7] . "</td>";
        $cal .= "<td class=\"gzABCalCellWeekDay\">" . $this->dayNames[($this->startDay + 3) % 7] . "</td>";
        $cal .= "<td class=\"gzABCalCellWeekDay\">" . $this->dayNames[($this->startDay + 4) % 7] . "</td>";
        $cal .= "<td class=\"gzABCalCellWeekDay\">" . $this->dayNames[($this->startDay + 5) % 7] . "</td>";
        $cal .= "</tr>";

        $day = $this->startDay + 1 - $date["wday"];
        while ($day > 0) {
            $day -= 7;
        }
        $rows = 0;

        while ($day <= $daysInMonth) {
            $cal .= "<tr>";

            $timestamp = mktime(0, 0, 0, $this->month, $day, $this->year);

            if ($this->show_weeks) {
                $cal .= '<td class="gzABCalCellWeek">' . date("W", $timestamp) . '</td>';
            }
            for ($i = 0; $i < 7; $i++) {
                $timestamp = mktime(0, 0, 0, $this->month, $day, $this->year);
                $class = $this->getCalendarDateClass($day, $this->year, $this->month);
                $color = $this->getColors($class);

                $tooltip = '';
                $error = __('not_availability_period');

                if (!in_array($class, array('gzABCalCellEmpty', 'gzABCalCellPast', 'gzABCalCellPending', 'gzABCalCellReserved'))) {
                    $tooltip = @$this->prices[$timestamp]['price'];
                }

                if ($timestamp == mktime(0, 0, 0, date('n'), date('d'), date('Y'))) {
                    $class .= " gzABCalCellToDay";
                }
                if ($class != 'gzABCalCellEmpty') {
                    if (!empty($tooltip)) {
                        $cal .= '<td data-timestamp="' . $timestamp . '" class="gzABCalCell ' . $class . '" id="' . $this->calendar_id . '_' . $timestamp . '" title="' . $tooltip . '">';
                    } else {
                        $cal .= '<td data-timestamp="' . $timestamp . '" class="gzABCalCell ' . $class . '" id="' . $this->calendar_id . '_' . $timestamp . '" >';
                    }
                } else {
                    $cal .= '<td class="gzABCalCell ' . $class . '" id="' . $this->calendar_id . '_' . $timestamp . '">';
                }
                $cal .= '<div class="gzABCalCellDivInner" title="' . $error . '">';
                if ($day > 0 && $day <= $daysInMonth) {
                    $cal .= '<span class="fa fa-fw fa-times close"></span>';
                    $cal .= '<div class="gzABCalDate">' . $day . '</div>';
                } else {
                    $cal .= "&nbsp;";
                }
                if (!empty($color)) {
                    $cal .= '<div class="gzABCalCellDivImg"><img class="gzABCalCellImg" src="' . INSTALL_URL . CSS_PATH . 'front/image.php?color1=' . $color[0] . '&color2=' . $color[1] . '"></div>';
                }
                $cal .= '</div>';
                $cal .= "</td>";
                $day++;
            }
            $cal .= "</tr>";
            $rows++;
        }
        if ($rows == 5) {
            $cal .= "<tr>" . str_repeat('<td class="calendarEmpty">&nbsp;</td>', $cols) . "</tr>";
        }
        $cal .= "</table>";

        return $cal;
    }

    function getMultiViewMonth() {
        if ($this->view_month < 1 && $this->view_month > 12) {
            return false;
        }
        $month = array();

        $month[1] = $this->month;
        foreach (range(2, 12) as $i) {
            $month[$i] = ($month[1] + $i - 1) > 12 ? $month[1] + $i - 1 - 12 : $month[1] + $i - 1;
        }

        $year[1] = $this->year;
        foreach (range(2, 12) as $i) {
            $year[$i] = ($month[1] + $i - 1) > 12 ? $year[1] + 1 : $year[1];
        }

        switch ($this->view_month) {
            case '2':
                $col_sm = "col-sm-6";
                break;
            case '3':
                $col_sm = "col-sm-4";
                break;
            case '4':
                $col_sm = "col-sm-6";
                break;
            case '5':
                $col_sm = "col-sm-4";
                break;
            case '6':
                $col_sm = "col-sm-4";
                break;
            default :
                $col_sm = "col-sm-4";
                break;
        }

        $prevMonth = ($this->month - $this->view_month) < 1 ? (12 + ($this->month - $this->view_month)) : ($this->month - $this->view_month);
        $prevYear = ($this->month - $this->view_month) < 1 ? ($this->year - 1) : $this->year;

        $nextMonth = ($this->month + $this->view_month) > 12 ? (($this->month + $this->view_month) - 12) : ($this->month + $this->view_month);
        $nextYear = ($this->month + $this->view_month) > 12 ? ($this->year + 1) : $this->year;

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $nextMonth, $nextYear);

        $str = "<div class=\"col-sm-12\">";
        $str .= "<h1>";
        $str .= "<div class=\"left\">";
        $str .= "<a href=\"javascript:\" class=\"gzABCalCellArrow\" data-timestamp=\"" . mktime(0, 0, 0, $this->month, 1, $this->year) . "\" rev=\"" . $this->calendar_id . "\" data-month=\"" . $prevMonth . "\" data-year=\"" . $prevYear . "\"><i class=\"fa fa-fw fa-chevron-circle-left\"></i></a>";
        $str .= "</div>";
        $str .= "<div class=\"left\">";
        $str .= "<a href=\"javascript:\" class=\"gzABCalCellArrow\" data-timestamp=\"" . mktime(0, 0, 0, $nextMonth, 1, $daysInMonth) . "\" rev=\"" . $this->calendar_id . "\" data-month=\"" . $nextMonth . "\" data-year=\"" . $nextYear . "\"><i class=\"fa fa-fw fa-chevron-circle-right\"></i></a>";
        $str .= "</div>";
        $str .=  "</h1>";
        $str .= "</div>";

        if (($this->view_month % 3) == 0) {
            foreach (range(1, $this->view_month) as $i) {
                if (($i - 1) % 3 == 0 || $i == 1) {
                    $str .= "<div class=\"row col-sm-12\">";
                }
                $str .= "<div class=\"" . $col_sm . "\">";
                $str .= $this->getMonthView($month[$i], $year[$i]);
                $str .= "</div>";
                if ($i % 3 == 0) {
                    $str .= "</div>";
                }
            }
            $str .= $this->getLegend();
        } elseif (($this->view_month % 4) == 0) {
            foreach (range(1, $this->view_month) as $i) {
                if (($i - 1) % 4 == 0 || $i == 1) {
                    $str .= "<div class=\"row col-sm-12\">";
                }
                $str .= "<div class=\"" . $col_sm . "\">";
                $str .= $this->getMonthView($month[$i], $year[$i]);
                $str .= "</div>";
                if ($i % 4 == 0) {
                    $str .= "</div>";
                }
            }
            $str .= $this->getLegend();
        } else {
            foreach (range(1, $this->view_month) as $i) {
                if (($i - 1) % 2 == 0 || $i == 1) {
                    $str .= "<div class=\"row col-sm-12\">";
                }
                $str .= "<div class=\"" . $col_sm . "\">";
                $str .= $this->getMonthView($month[$i], $year[$i]);
                $str .= "</div>";
                if ($i % 2 == 0) {
                    $str .= "</div>";
                }
            }
            $str .= $this->getLegend();
        }
        return $str;
    }

    function getCalendarDateClass($d, $y, $m) {

        $reservationsInfo = $this->reservationsInfo;
        $calendar_capacity = $this->calendar_capacity;

        $today = getdate(time());
        $timestamp = mktime(0, 0, 0, $m, $d, $y);
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $m, $y);

        if ($y == $today["year"] && $m == $today["mon"] && $d == $today["mday"]) {
            $class = 'gzABCalCellAvil';
        } elseif ($d < 1 || $d > $daysInMonth) {
            $class = 'gzABCalCellEmpty';
        } elseif ($timestamp < $today[0]) {
            $class = 'gzABCalCellPast';
        } else {
            $class = 'gzABCalCellAvil';
        }
        if (($timestamp + 86400) < $today[0]) {
            $class = 'gzABCalCellPast';
        } else {
            if ($d > 0 && $d <= $daysInMonth) {

                if (array_key_exists($timestamp, $reservationsInfo)) {
                    if ($this->option_arr['based_on'] == 'night') {
                        if ($calendar_capacity > 1) {
                            if ((@$reservationsInfo[$timestamp]['date_from']['count'] >= $calendar_capacity && @$reservationsInfo[$timestamp]['date_to']['count'] >= $calendar_capacity) ||
                                    (@$reservationsInfo[$timestamp]['count'] >= $calendar_capacity) ||
                                    ((@$reservationsInfo[$timestamp]['date_from']['count'] + @$reservationsInfo[$timestamp]['count']) >= $calendar_capacity && (@$reservationsInfo[$timestamp]['date_to']['count'] + @$reservationsInfo[$timestamp]['count']) >= $calendar_capacity)) {
                                $class = "gzABCalCellReserved";
                            } else {
                                if ((@$reservationsInfo[$timestamp]['date_from']['count'] + @$reservationsInfo[$timestamp]['count']) >= $calendar_capacity) {
                                    $class = "gzABCalCellReservedNightsStart";
                                } elseif ((@$reservationsInfo[$timestamp]['date_to']['count'] + @$reservationsInfo[$timestamp]['count']) >= $calendar_capacity) {
                                    $class = "gzABCalCellReservedNightsEnd";
                                }
                            }
                        } else {
                            if (array_key_exists($timestamp, @$reservationsInfo)) {
                                if (array_key_exists('count', @$reservationsInfo[$timestamp])) {
                                    switch (@$reservationsInfo[$timestamp]['status']) {
                                        case 'pending':
                                            $class = 'gzABCalCellPending';
                                            break;
                                        case 'confirmed':
                                            $class = 'gzABCalCellReserved';
                                            break;
                                    }
                                } else {
                                    if (array_key_exists('date_from', @$reservationsInfo[$timestamp]) && !array_key_exists('date_to', @$reservationsInfo[$timestamp])) {
                                        switch (@$reservationsInfo[$timestamp]['date_from']['status']) {
                                            case 'pending':
                                                $class = 'gzABCalCellPendingNightsStart';
                                                break;
                                            case 'confirmed':
                                                $class = 'gzABCalCellReservedNightsStart';
                                                break;
                                        }
                                    } elseif (!array_key_exists('date_from', @$reservationsInfo[$timestamp]) && array_key_exists('date_to', @$reservationsInfo[$timestamp])) {

                                        switch (@$reservationsInfo[$timestamp]['date_to']['status']) {
                                            case 'pending':
                                                $class = 'gzABCalCellPendingNightsEnd';
                                                break;
                                            case 'confirmed':
                                                $class = 'gzABCalCellReservedNightsEnd';
                                                break;
                                        }
                                    } elseif (array_key_exists('date_from', @$reservationsInfo[$timestamp]) && array_key_exists('date_to', @$reservationsInfo[$timestamp])) {
                                        if (@$reservationsInfo[$timestamp]['date_from']['status'] == 'pending' && @$reservationsInfo[$timestamp]['date_to']['status'] == 'pending') {
                                            $class = 'gzABCalCellNightsPendingPending';
                                        } elseif (@$reservationsInfo[$timestamp]['date_from']['status'] == 'pending' && @$reservationsInfo[$timestamp]['date_to']['status'] == 'confirmed') {
                                            $class = 'gzABCalCellNightsReservedPending';
                                        } elseif (@$reservationsInfo[$timestamp]['date_from']['status'] == 'confirmed' && @$reservationsInfo[$timestamp]['date_to']['status'] == 'pending') {
                                            $class = 'gzABCalCellNightsPendingReserved';
                                        } elseif (@$reservationsInfo[$timestamp]['date_from']['status'] == 'confirmed' && @$reservationsInfo[$timestamp]['date_to']['status'] == 'confirmed') {
                                            $class = 'gzABCalCellNightsReservedReserved';
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($calendar_capacity > 1) {
                            if ((@$reservationsInfo[$timestamp]['date_from']['count'] >= $calendar_capacity && @$reservationsInfo[$timestamp]['date_to']['count'] >= $calendar_capacity) ||
                                    (@$reservationsInfo[$timestamp]['count'] >= $calendar_capacity) ||
                                    ((@$reservationsInfo[$timestamp]['date_from']['count'] + @$reservationsInfo[$timestamp]['count']) >= $calendar_capacity && (@$reservationsInfo[$timestamp]['date_to']['count'] + @$reservationsInfo[$timestamp]['count']) >= $calendar_capacity)) {
                                $class = "gzABCalCellReserved";
                            } else {
                                if ((@$reservationsInfo[$timestamp]['date_from']['count'] + @$reservationsInfo[$timestamp]['count']) >= $calendar_capacity) {
                                    $class = "gzABCalCellReserved";
                                } elseif ((@$reservationsInfo[$timestamp]['date_to']['count'] + @$reservationsInfo[$timestamp]['count']) >= $calendar_capacity) {
                                    $class = "gzABCalCellReserved";
                                }
                            }
                        } else {
                            if (array_key_exists($timestamp, @$reservationsInfo)) {
                                switch (@$reservationsInfo[$timestamp]['status']) {
                                    case 'pending':
                                        $class = 'gzABCalCellPending';
                                        break;
                                    case 'confirmed':
                                        $class = 'gzABCalCellReserved';
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

    function getColors($class) {

        $color = array();

        switch ($class) {

            case 'gzABCalCellReservedNightsStart':
                $color[0] = str_replace('#', '', $this->option_arr['bg_available']);
                $color[1] = str_replace('#', '', $this->option_arr['bg_booked']);
                break;
            case 'gzABCalCellReservedNightsEnd':
                $color[0] = str_replace('#', '', $this->option_arr['bg_booked']);
                $color[1] = str_replace('#', '', $this->option_arr['bg_available']);
                break;
            case 'gzABCalCellPendingNightsStart':
                $color[0] = str_replace('#', '', $this->option_arr['bg_available']);
                $color[1] = str_replace('#', '', $this->option_arr['bg_pending']);
                break;
            case 'gzABCalCellPendingNightsEnd':
                $color[0] = str_replace('#', '', $this->option_arr['bg_pending']);
                $color[1] = str_replace('#', '', $this->option_arr['bg_available']);
                break;
            case 'gzABCalCellNightsReservedPending':
                $color[0] = str_replace('#', '', $this->option_arr['bg_booked']);
                $color[1] = str_replace('#', '', $this->option_arr['bg_pending']);
                break;
            case 'gzABCalCellNightsPendingReserved':
                $color[0] = str_replace('#', '', $this->option_arr['bg_pending']);
                $color[1] = str_replace('#', '', $this->option_arr['bg_booked']);
                break;
            case 'gzABCalCellNightsReservedReserved':
                $color[0] = str_replace('#', '', $this->option_arr['bg_booked']);
                $color[1] = str_replace('#', '', $this->option_arr['bg_booked']);
                break;
            case 'gzABCalCellNightsPendingPending':
                $color[0] = str_replace('#', '', $this->option_arr['bg_pending']);
                $color[1] = str_replace('#', '', $this->option_arr['bg_pending']);
                break;
        }

        return $color;
    }

    function getLegend() {
        $status_arr = __('status_arr');
        $html = '
		<table class="gzABCalCellLegend" cellspacing="1" cellpadding="2">
			<tbody>
				<tr>
					<td class="gzABCalCellColor gzABCalColorAvil">&nbsp;</td>
					<td class="gzABCalCellLabel">' . __('available') . '</td>
					<td class="gzABCalCellColor gzABCalColorPending">&nbsp;</td>
					<td class="gzABCalCellLabel">' . $status_arr['pending'] . '</td>
					<td class="gzABCalCellColor gzABCalColorReserved">&nbsp;</td>
					<td class="gzABCalCellLabel">' . $status_arr['confirmed'] . '</td>
				</tr>
			</tbody>
		</table>';
        return $html;
    }

}
