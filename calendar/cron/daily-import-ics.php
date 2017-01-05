<?php

//optional
set_time_limit(0);

//every cron must include this
require_once 'config.php';
require_once FRAMEWORK_PATH . 'Object.class.php';

function autoImportICSForBlocking()
{
    Object::loadFiles('Model', array('Option', 'Calendar', 'Blocking', 'DrupalICalURL'));
    $OptionModel = new OptionModel();
    $CalendarModel = new CalendarModel();
    
    //set default timezone same with GzCalendar
    $option_arr = $OptionModel->getAllPairValues();
    date_default_timezone_set($option_arr['timezone']);
    
    $calendar_villa_datas = $CalendarModel
            ->from($CalendarModel->getTable())
            ->where('villa_node_id > ?', 0)
            ->fetchAll();
    $blocked_added = array();
    if (is_array($calendar_villa_datas) && count($calendar_villa_datas) > 0) {
        $DrupalICalURLModel = new DrupalICalModel;
        $BlockingModel = new BlockingModel();
        //loop all calendar villa
        foreach ($calendar_villa_datas as $calendar_villa_data) {
            $ical_url = $DrupalICalURLModel
                    ->get($calendar_villa_data['villa_node_id'])['field_ical_url_value'];
            if ($ical_url !== '') {
                /* sample of ical url:
                 * https://calendar.google.com/calendar/ical/6crft8iheds9if997fkf5t2vkg%40group.calendar.google.com/public/basic.ics
                 */
                $events = icalGetEventsDate($ical_url);
                $blocked_added[$calendar_villa_data['title']] = 0;
                if (count($events) > 0) {
                    foreach ($events as $event) {
                        if ($BlockingModel->from($BlockingModel->getTable())
                                ->where(array(
                                    'calendar_id' => $calendar_villa_data['id'],
                                    'from_date' => $event['date_from'],
                                    'to_date' => $event['date_to'],
                                ))->count() == 0) {
                            $data_blocking = array(
                                'from_date' => $event['date_from'],
                                'to_date' => $event['date_to'],
                                'calendar_id' => $calendar_villa_data['id'],
                            );
                            $BlockingModel->save($data_blocking);
                            $blocked_added[$calendar_villa_data['title']]++;
                        }
                    }
                }
            }
        }
    }
    if (count($blocked_added) > 0) {
        echo '<h3>ICS Import for Blocking Date Status:</h3>';
        foreach ($blocked_added as $villa_title => $total) {
            echo "&bull;&nbsp;$villa_title: $total event(s) added!<br/>";
        }
    } else {
        echo "No blocking date found could be added!<br/>";
    }
}

function icalGetEventsDate($ical_filename_drupal)
{
    require_once APP_PATH . '/helpers/iCalReader/class.iCalReader.php';
    $result = array();
    if (!isset(parse_url($ical_filename_drupal)['scheme'])) {
        $url_file_ical = DRUPAL_URL . '/sites/default/files/ical/' . $ical_filename_drupal;
    } else {
        $url_file_ical = $ical_filename_drupal;
    }
    $file_headers = @get_headers($url_file_ical);
    if ($file_headers[0] !== 'HTTP/1.0 404 Not Found'){
        $ical = new ical();
        $ics_data = $ical->get_fcontent($url_file_ical);
        if ($ics_data !== false) {
            //reverse: read event from beginning
            $events = $ical->sortEventsWithOrder($ical->events());
            if (is_array($events) && count($events) > 0) {
                $total_events = count($events);
                $ev = array();
                $n_event = 0;
                foreach ($events as $event) {
                    $n_event++;
                    if (!empty($event['DTSTART']) && !empty($event['DTEND'])) {
                        $dtstart_timestamp = $ical->iCalDateToUnixTimestamp($event['DTSTART'], true);
                        $dtend_timestamp = $ical->iCalDateToUnixTimestamp($event['DTEND'], true);
                        if (count($ev) == 0) {
                            $ev['date_from'] = $dtstart_timestamp;
                            $ev['date_to'] = $dtend_timestamp;
                        } elseif ($ev['date_to'] == $dtstart_timestamp) {
                            $ev['date_to'] = $dtend_timestamp;
                        } elseif ($ev['date_to'] !== $dtstart_timestamp) {
                            $result[] = $ev;
                            $ev['date_from'] = $dtstart_timestamp;
                            $ev['date_to'] = $dtend_timestamp;
                        }
                        if ($total_events == 1 || $n_event == $total_events) {
                            $result[] = $ev;
                            $ev = array();
                        } else {
                            continue;
                        }
                        //$result[] = array('date_from' => $dtstart_timestamp, 'date_to' => $dtend_timestamp);
                    }
                }
            }
        }
    }
    return $result;
}

echo "Start on " . date('d-m-Y H:i:s') . "<br/>";
autoImportICSForBlocking();
echo "<br/>Done on " . date('d-m-Y H:i:s') . "<br/>";

