<section class="content-header">
    <h1>
        <?php echo __('dashboard'); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo INSTALL_URL; ?>"><i class="fa fa-dashboard"></i><?php echo __('home'); ?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <?php
    require_once VIEWS_PATH . 'Layouts/admin/error_notice.php';
    ?>
    <?php if ($this->controller->isAdmin()) { ?>
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>
                        <?php echo $tpl['availability_calendars']; ?>
                    </h3>
                    <p>
                        <?php echo __('available_calendars'); ?>
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="<?php echo INSTALL_URL; ?>GzBooking/availability" class="small-box-footer">
                    <?php echo __('more_info'); ?> <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>
                        <?php echo $tpl['today_reservation']['today_reservation']; ?>
                    </h3>
                    <p>
                        <?php echo __('today_resrvations'); ?>
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?php echo INSTALL_URL; ?>GzBooking/index" class="small-box-footer">
                    <?php echo __('more_info'); ?> <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>
                        <?php echo $tpl['departure']['departure']; ?>
                    </h3>
                    <p>
                        <?php echo __('departures_clients'); ?>
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-person"></i>
                </div>
                <a href="<?php echo INSTALL_URL; ?>GzBooking/index" class="small-box-footer">
                    <?php echo __('more_info'); ?> <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>
                        <?php echo $tpl['arrival']['arrival']; ?>
                    </h3>
                    <p>
                        <?php echo __('arrival_clients'); ?>
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-person"></i>
                </div>
                <a href="<?php echo INSTALL_URL; ?>GzBooking/index" class="small-box-footer">
                    <?php echo __('more_info'); ?> <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div><!-- ./col -->
    </div>
    <div class="row">
        <section class="col-lg-6 connectedSortable">   
            <!-- Custom tabs (Charts with tabs)-->
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?php echo __('reservatio_numbers'); ?></h3>
                </div>
                <div class="box-body chart-responsive">
                    <div class="chart" id="bar-chart" style="height: 300px;"></div>
                </div><!-- /.box-body -->
            </div>
        </section>
        <section class="col-lg-6 connectedSortable ui-sortable">
            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title"><?php echo __('people_chart'); ?></h3>
                </div>
                <div class="chart tab-pane active" id="tab-1" style="position: relative; height: 300px;">
                    <div class="box-body chart-responsive">
                        <div class="chart" id="line-chart" style="height: 250px;"></div> 
                    </div> 
                </div>
            </div><!-- /.box -->
        </section>
        <section class="connectedSortable" style="float: left; width: 100%; padding: 0 15px;">   
            <!-- Chat box -->
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?php echo __('last_reservations'); ?></h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-default btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                        <button class="btn btn-default btn-sm" title="" data-toggle="tooltip" data-widget="remove" data-original-title="Remove">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <table id="<?php echo (count($tpl['arr'])) ? "gzhotel-booking-booking-id" : ""; ?>" class="gzblog-table" cellpadding="0" cellspacing="0" >
                        <thead>
                            <tr>
                                <th class="date-th"><?php echo __('booking_range'); ?></th>
                                <th class="title-th"><?php echo __('client_name'); ?></th>
                                <th><?php echo __('amount'); ?></th>
                                <th><?php echo __('label_status'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $status_arr = __('status_arr');
                            $count = count($tpl['arr']);
                            if ($count > 0) {
                                for ($i = 0; $i < $count; $i++) {
                                    ?>
                                    <tr  class="<?php echo $i % 2 === 0 ? 'odd' : 'even'; ?>">
                                        <td><?php echo date('F j, Y, h:i:s A', $tpl['arr'][$i]['date_from']) . ' - ' . date('F j, Y, h:i:s A', $tpl['arr'][$i]['date_to']); ?></td>
                                        <td><?php echo $tpl['arr'][$i]['first_name'] . ' ' . $tpl['arr'][$i]['second_name']; ?></td>
                                        <td><?php echo $tpl['arr'][$i]['total']; ?></td>
                                        <td><?php echo $status_arr[$tpl['arr'][$i]['status']]; ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="7">
                                        <?php
                                        echo __('no_booking');
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">

                </div>
            </div>
        </section>
        <section class="col-lg-12 connectedSortable">
            <div class="box left">
                <div class="box-header">
                    <h3 class="box-title">
                        <?php echo __('availability'); ?>
                    </h3>
                </div>
                <div class="box-body table-responsive left width_100">
                    <div id="cal-container" class="dataTables_wrapper form-inline" role="grid">
                        <?php
                        echo $tpl['calendar']->getSummaryCalendar();
                        ?>
                    </div>
                    <form name="table-frm" id="table-frm-id" method="post" action="<?php echo INSTALL_URL ?>GzBooking/deleteSelected">
                        <div id="booking_container"></div>
                    </form>
                </div>
            </div>
        </section>
    </div>
    <?php } ?>
</section><!-- /.content -->
<script type="text/javascript">
    $(function() {
        var line = new Morris.Line({
            element: 'line-chart',
            resize: true,
            data: [
<?php
foreach ($tpl['chart']['people'] as $k => $v) {
    ?>
                    {month: '<?php echo $k ?>', adults: <?php echo (!empty($v['adults'])) ? $v['adults'] : 0; ?>, children: <?php echo (!empty($v['children'])) ? $v['children'] : 0; ?>},
    <?php
}
?>
            ],
            xkey: 'month',
                    ykeys: ['adults', 'children'],
            labels: ['adults', 'children'],
                    lineColors: ['#3c8dbc', '#a0d0e0'],
            hideHover: 'auto'
        });

        var bar = new Morris.Bar({
            element: 'bar-chart',
            resize: true,
            data: [
<?php foreach ($tpl['chart']['booking'] as $k => $v) { ?>
                    {y: '<?php echo $k ?>', a: <?php echo $v['count']; ?>},
<?php } ?>
            ],
            barColors: ['#00a65a', '#f56954'],
                    xkey: 'y',
            ykeys: ['a'],
                    labels: ['CPU', 'DISK'],
            hideHover: 'auto'
        });
    });
</script>