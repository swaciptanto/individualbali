<section class="content-header">
    <h1>
        <?php echo __('calendar_blocking'); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo INSTALL_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo __('home'); ?></a></li>
        <li class="active"><?php echo __('calendar_blocking'); ?></li>
    </ol>
</section>
<?php
require_once VIEWS_PATH . 'Layouts/admin/error_notice.php';
?>
<section class="content">
    <div class="box">
        <div class="box-body table-responsive">
            <div id="example1_wrapper" class="dataTables_wrapper form-inline" role="grid">
                <form name="blocking-frm" id="gz-abc-blocking-id" action="<?php echo INSTALL_URL; ?>GzCalendar/deleteSelectedBlock" method="post">
                    <fieldset>
                        <?php require 'component/blocking_table.php'; ?>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</section>
<div id="dialogs-id">
    <div id="dialogAddBlocking" title="<?php echo htmlspecialchars(__('add_blocking')); ?>" style="display:none">
        <?php require 'component/frm_add_blocking.php'; ?>
    </div>
    <div id="dialogEditBlocking" title="<?php echo htmlspecialchars(__('edit_blocking')); ?>" style="display:none">
        <?php require 'component/frm_edit_blocking.php'; ?>
    </div>
    <div id="dialogBlockDelete" title="<?php echo htmlspecialchars(__('blocking_del_title')); ?>" style="display:none">
        <p><?php echo __('blocking_del_body'); ?></p>
    </div>
    <div id="div_block_id" style="display:none"></div>
</div>
<div id="dialogDeleteBlockSelected" title="<?php echo htmlspecialchars(__('block_del_selected_title')); ?>" style="display:none">
    <p><?php echo __('block_del_selected_body'); ?></p>
</div>