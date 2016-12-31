<?php

//optional
set_time_limit(0);

//every cron must include this
require_once 'config.php';
require_once FRAMEWORK_PATH . 'Object.class.php';

function autoImportICSForBlocking()
{
    Object::loadFiles('Model', array('Calendar', 'Blocking', 'DrupalICal', 'DrupalFileManaged'));
    $CalendarModel = new CalendarModel();
    $calendar_villa_datas = $CalendarModel
            ->from($CalendarModel->getTable())
            ->where('villa_node_id > ?', 0)
            ->fetchAll();
    $blocked_added = array();
    if (is_array($calendar_villa_datas) && count($calendar_villa_datas) > 0) {
        $DrupalICalModel = new DrupalICalModel();
        $DrupalFileManagedModel = new DrupalFileManagedModel();
        $BlockingModel = new BlockingModel();
        //loop all calendar villa
        foreach ($calendar_villa_datas as $calendar_villa_data) {
            $ical_file_id = $DrupalICalModel
                    ->get($calendar_villa_data['villa_node_id'])['field_ical_fid'];
            if ($ical_file_id > 0) {
                $ical_file_uri = $DrupalFileManagedModel->get($ical_file_id)['uri'];
                $tmp = explode('/', $ical_file_uri);
                end($tmp);
                $ics_filename = $tmp[key($tmp)];
                if ($ics_filename !== '') {
                    $blocked_added[$ics_filename] = array();
                    $events = icalGetEventsDate($ics_filename);
                    $blocked_added[$ics_filename][$calendar_villa_data['title']] = 0;
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
                                $blocked_added[$ics_filename][$calendar_villa_data['title']]++;
                            }
                        }
                    }
                }
            }
        }
    }
    if (count($blocked_added) > 0) {
        echo '<h3>ICS Import for Blocking Date Status:</h3>';
        foreach ($blocked_added as $ics_filename => $dts) {
            echo "<strong>File: $ics_filename</strong><br/>";
            foreach ($dts as $villa_title => $total) {
                echo "&bull;&nbsp;$villa_title: $total event(s) added!<br/>";
            }
        }
    } else {
        echo "No blocking date found could be added!<br/>";
    }
}

function icalGetEventsDate($ical_filename_drupal)
{
    //$ical_filename_drupal = '4mu1aaxodluwskipyvzmp8edaspjqdwj.ics';
    require_once APP_PATH . '/helpers/iCalReader/class.iCalReader.php';
    $result = array();
    $url_file_ical = DRUPAL_URL . '/sites/default/files/ical/' . $ical_filename_drupal;
    $file_headers = @get_headers($url_file_ical);
    if ($file_headers[0] !== 'HTTP/1.0 404 Not Found'){
        $ical = new ical();
        $ics_data = $ical->get_fcontent($url_file_ical);
        if ($ics_data !== false) {
            $events = $ical->events();
            if (is_array($events) && count($events) > 0) {
                $total_events = count($events);
                $ev = array();
                $n_event = 0;
                foreach ($events as $event) {
                    $n_event++;
                    if (!empty($event['DTSTART']) && !empty($event['DTEND'])) {
                        $dtstart_timestamp = $ical->iCalDateToUnixTimestamp($event['DTSTART']);
                        $dtend_timestamp = $ical->iCalDateToUnixTimestamp($event['DTEND']);
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
echo "Done on " . date('d-m-Y H:i:s') . "<br/>";

