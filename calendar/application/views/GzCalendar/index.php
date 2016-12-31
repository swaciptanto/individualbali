<section class="content-header">
    <h1>
        <?php echo __('calendars'); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo INSTALL_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo __('home'); ?></a></li>
        <li class="active"><?php echo __('calendars'); ?></li>
    </ol>
</section>
<?php
require_once VIEWS_PATH . 'Layouts/admin/error_notice.php';
?>
<!-- Main content -->
<section class="content">
    <div class="box">
        <div class="box-body table-responsive">
            <div id="example1_wrapper" class="dataTables_wrapper form-inline" role="grid">
                <div class="overlay"></div>
                <div class="loading-img"></div>
                <form name="table-frm" id="table-frm-id" method="post" action="<?php echo INSTALL_URL; ?>GzCalendar/deleteSelected">
                    <?php require 'component/calendars_table.php'; ?>
                </form>
            </div>
        </div>
    </div>
</section><!-- /.content -->
<div id="dialogDelete" title="<?php echo __('calendar_del_title'); ?>" style="display:none">
    <p><?php echo __('calendar_del_body'); ?></p>
</div>
<div id="record_id" style="display:none"></div>
<div id="dialogDeleteSelected" title="<?php echo htmlspecialchars(__('calendar_del_selected_title')); ?>" style="display:none">
    <p><?php echo __('calendar_del_selected_body'); ?></p>
</div>