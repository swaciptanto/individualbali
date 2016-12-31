<div class="overlay"></div>
<div class="loading-img"></div>
<link rel="stylesheet" href="<?php echo INSTALL_URL . 'application/helpers/Calendar/css/style.css'; ?>" />
<?php
$next_day = $day;
$prev_day = $day;
$prev_month = $m - 1;
$next_month = $m + 1;
$remainDaysInCurrentMonth = $daysInMonth;

$cal_scroll_width = 76 * $remainDaysInCurrentMonth + 76 * $daysInNextMonth;
?>
<br />
<div class="gz-cal-head-row">
    <div class="float_right summary-nav">
        <a href="#" class="gz-summary-link-month btn btn-default btn-flat" rel="<?php echo $prev_day; ?>-<?php echo $prev_month; ?>-<?php echo $y ?>-<?php echo (!empty($_REQUEST['view'])) ? $_REQUEST['view'] : "1"; ?>"> <i class="fa fa-caret-left"></i> </a>  
        <a href="#" class="gz-summary-link-month btn btn-default btn-flat" rel="<?php echo $next_day; ?>-<?php echo $next_month; ?>-<?php echo $y ?>-<?php echo (!empty($_REQUEST['view'])) ? $_REQUEST['view'] : "1"; ?>"> <i class="fa fa-caret-right"></i> </a>
    </div>
</div>

<div class="gz-calendars">
    <div class="gz-calendars-head">
        &nbsp;
    </div>
    <?php
    foreach ($calendars as $k => $calendar) {
        ?><div class="gz-cal-title">
        <?php
        if (!empty($calendar['gallery']['thumb']) && is_file(INSTALL_PATH . UPLOAD_PATH . "calendars/thumb/" . $calendar['gallery']['thumb'])) {
            ?>
                <div class="view view-tenth">
                    <img src='<?php echo INSTALL_URL . UPLOAD_PATH . "calendars/thumb/" . $calendar['gallery']['thumb']; ?>' class='preview'>
                    <div class="mask">
                        <a href="<?php echo INSTALL_URL; ?>GzCalendar/edit/<?php echo $calendar['id']; ?>" class=""><?php echo $calendar['title']; ?></a>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <a href="<?php echo INSTALL_URL; ?>GzCalendar/edit/<?php echo $calendar['id']; ?>"><?php echo $calendar['i18n'][$default_language[0]['id']]['title']; ?></a>
                <?php
            }
            ?>
        </div>
        <?php
    }
    ?>
</div>
<div class="gz-cal-dates">
    <div class="gz-cal-scroll" style="width: <?php echo $cal_scroll_width; ?>px">
        <div class="gz-cal-head">
            <div class="gz-cal-head-row">
                <span class="month-head-span" style="width: <?php echo 76 * $remainDaysInCurrentMonth - 1; ?>px"><?php echo date('M', mktime(0, 0, 0, $m, $day, $y)); ?> <?php echo date('Y', mktime(0, 0, 0, $m, 1, $y)); ?></span>
                <?php
                if (!empty($daysInNextMonth)) {
                    ?>
                    <span class="month-head-span" style="width: <?php echo 76 * $daysInNextMonth - 1; ?>px"><?php echo date('M', mktime(0, 0, 0, $m + 1, 1, $y)); ?> <?php echo date('Y', mktime(0, 0, 0, $m, 1, $y)); ?></span>
                    <?php
                }
                ?>
            </div>
            <div class="gz-cal-head-row">
                <?php
                $n = 1;
                while ($n <= $daysInMonth + $daysInNextMonth) {
                    ?>
                    <span>
                        <?php echo date('d', mktime(0, 0, 0, $m, $n, $y)); ?><br />
                        <?php echo date('D', mktime(0, 0, 0, $m, $n, $y)); ?>
                    </span>
                    <?php
                    $n++;
                }
                ?>
            </div>
        </div>
        <?php
        $k = 0;
        foreach ($calendars as $k => $calendar) {
            ?>
            <div class="gz-cal-program" id="gz-cal-program-<?php echo $calendar['id']; ?>">
                <?php
                $d = 1;
                while ($d <= $daysInMonth + $daysInNextMonth) {
                    $class = $this->getCalendarDateClass($calendar['id'], date('m', mktime(0, 0, 0, $m, $d, $y)), date('d', mktime(0, 0, 0, $m, $d, $y)), date('Y', mktime(0, 0, 0, $m, $d, $y)), @$this->reservationsInfo[$calendar['id']], @$this->calendar_capacity[$calendar['id']]);
                    ?>
                    <a href="" class="<?php echo $class; ?>" rev="<?php echo mktime(0, 0, 0, $m, $d, $y); ?>" rel="<?php echo $calendar['id']; ?>">
                        <?php if (!empty($info_arr[$calendar['id']][mktime(0, 0, 0, $m, $d, $y)]['count'])) { ?>
                            <span class="date-span-tool"><?php echo @$this->reservationsInfo[$calendar['id']][mktime(0, 0, 0, $m, $d, $y)]['count'] . '/' . @$this->calendar_capacity[$calendar['id']] ?></span>
                        <?php } ?>
                        <?php echo date('d', mktime(0, 0, 0, $m, $d, $y)); ?>
                    </a>
                    <?php
                    $d++;
                }
                ?>
            </div>
            <?php
            $k++;
        }
        ?>
    </div>
</div>