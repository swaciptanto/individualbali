<section class="content-header">
    <h1>
        <?php echo __('people_statistic'); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo INSTALL_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo __('home'); ?></a></li>
        <li class="active"><?php echo __('people_statistic'); ?></li>
    </ol>
</section>
<section class="content left width_100">
    <form id="statistic_booking_id" class="frm-class" action="<?php echo INSTALL_URL; ?>GzStatistic/people" method="post" name="filter_statistic">
        <input type="hidden" name="controller" value="GzStatistic" /> 
        <input type="hidden" name="action" value="people" /> 
        <div class="padding-19 nav-tabs-custom left width_100">
            <fieldset>
                <section class="col-lg-7 connectedSortable">
                    <div class="form-group">
                        <label class="control-label" for="booking_range"><?php echo __('booking_range'); ?>:</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-clock-o"></i>
                            </div>
                            <input id="reservationtime" name="date_range" class="form-control pull-right" type="text" value="<?php echo (!empty($_POST['date_range'])) ? $_POST['date_range'] : ""; ?>">
                        </div>
                    </div>
                    <div class="form-group">       
                        <button id="submit" class="btn btn-primary" autocomplete="off" value="<?php echo __('search'); ?>" name="<?php echo __('search'); ?>" tabindex="9" type="submit"><i class="fa fa-fw fa-search"></i>&nbsp;&nbsp;<?php echo __('search'); ?></button>
                    </div>
                </section>
                <section class="col-lg-5 connectedSortable ui-sortable">
                </section>
            </fieldset>
        </div>
    </form>
    <div class="box box-danger left">
        <div class="box-header">
            <h3 class="box-title"><?php echo __('people_chart'); ?></h3>
        </div>
        <div class="chart tab-pane active" id="tab-1" style="position: relative; height: 300px;">
            <div class="box-body chart-responsive">
                <div class="chart" id="line-chart" style="height: 250px;"></div> 
            </div> 
        </div>
    </div>
</section>
<script type="text/javascript">
// LINE CHART
    var line = new Morris.Line({
        element: 'line-chart',
        resize: true,
        data: [
<?php
foreach ($tpl['month_chart'] as $k => $v) {
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
</script>