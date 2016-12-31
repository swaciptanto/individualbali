<section class="content-header">
    <h1>
        <?php echo __('extras'); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo INSTALL_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo __('home'); ?></a></li>
        <li class="active"><?php echo __('extras'); ?></li>
    </ol>
</section>
<?php
require_once VIEWS_PATH . 'Layouts/admin/error_notice.php';
?>
<section class="content">
    <div class="box">
        <div class="box-body table-responsive">
            <div class="callout callout-info ">
                <p><?php echo __('extra_info'); ?></p>
            </div>
            <div id="example1_wrapper" class="dataTables_wrapper form-inline" role="grid">
                <form name="price-plan" id="prce-plan-id" action="" method="post">
                    <fieldset>
                        <?php require 'component/extra_table.php'; ?>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</section>
<div id="dialogAddExtra" title="<?php echo htmlspecialchars(__('Add_Extra')); ?>" style="display:none">
    <?php require 'component/frm_add_extra.php'; ?>
</div>
<div id="dialogEditExtra" title="<?php echo htmlspecialchars(__('Edit_Extra')); ?>" style="display:none">
    <?php require 'component/frm_edit_extra.php'; ?>
</div>
<div id="dialogDelete" title="<?php echo htmlspecialchars(__('extra_del_title')); ?>" style="display:none">
    <p><?php echo __('extra_del_body'); ?></p>
</div>
<div id="dialogDeleteGallery" title="<?php echo htmlspecialchars(__('gallery_del_title')); ?>" style="display:none">
    <p><?php echo __('gallery_del_body'); ?></p>
</div>
<div id="record_id" style="display:none"></div>
<div id="room_type_id" style="display:none"></div>