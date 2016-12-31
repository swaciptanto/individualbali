<section class="content-header">
    <h1>
        <?php echo __('title_price'); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo INSTALL_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo __('home'); ?></a></li>
        <li class="active"><?php echo __('title_price'); ?></li>
    </ol>
</section>
<?php
require_once VIEWS_PATH . 'Layouts/admin/error_notice.php';
?>
<section class="content left width_100">
    <div class="box left width_100">
        <div class="box-body left table-responsive width_100">
            <div class="callout callout-info left width_100">
                <p><?php echo __('price_info'); ?></p>
            </div>
            <div id="example1_wrapper" class="dataTables_wrapper form-inline left width_100" role="grid">
                <form name="price-plan" id="prce-plan-id" action="" method="post">
                    <fieldset class="left width_100">
                        <?php require 'component/price_table.php'; ?>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</section>
<div id="dialogAddPricePlan" title="<?php echo htmlspecialchars(__('Add_Price_Plan')); ?>" style="display:none">
    <?php require 'component/frm_add_price_plan.php'; ?>
</div>
<div id="dialogEditPricePlan" title="<?php echo htmlspecialchars(__('Edit_Price_Plan')); ?>" style="display:none">
    <?php require 'component/frm_edit_price_plan.php'; ?>
</div>
<div id="dialogDelete" title="<?php echo htmlspecialchars(__('price_del_title')); ?>" style="display:none">
    <p><?php echo __('price_del_body'); ?></p>
</div>
<div id="record_id" style="display:none"></div>
<div id="calendar_id" style="display:none"></div>