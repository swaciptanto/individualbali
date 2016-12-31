<section class="content-header">
    <h1>
        <?php echo __('calendars'); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo INSTALL_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo __('home'); ?></a></li>
        <li><a href="<?php echo INSTALL_URL; ?>index.php?controller=GzBooking&action=index"><?php echo __('booking'); ?></a></li>
        <li class="active"><?php echo __('calendar'); ?></li>
    </ol>
</section>
<!-- Main content -->
<section class="content left width_100">
    <?php
    require_once VIEWS_PATH . 'Layouts/admin/error_notice.php';
    ?>
    <div class="box left">
        <div class="box-header">
            <h3 class="box-title" style="margin: 0; padding: 10px 0 10px 10px;">
                <?php echo __('booking_calendar'); ?>
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
</section><!-- /.content -->
<div id="dialogDelete" title="<?php echo htmlspecialchars(__('book_del_title')); ?>" style="display:none">
    <p><?php echo __('book_del_body'); ?></p>
</div>
<div id="dialogDeleteSelected" title="<?php echo htmlspecialchars(__('book_del_selected_title')); ?>" style="display:none">
    <p><?php echo __('book_del_selected_body'); ?></p>
</div>
<div id="record_id" style="display:none"></div>