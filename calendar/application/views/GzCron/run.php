<section class="content-header">
    <h1>
        <?php echo 'Cron'; ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo INSTALL_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo __('home'); ?></a></li>
        <li class="active"><?php echo 'Cron'; ?></li>
    </ol>
</section>
<?php
require_once VIEWS_PATH . 'Layouts/admin/error_notice.php';
?>
<section class="content left width_100">
    <div class="box left width_100">
        <div class="box-body left table-responsive width_100">
            <div class="callout callout-info left width_100">
                <p><?php echo '<i>Please inform IT if cron failed!</i><h3>Execute Status:</h3>'; ?></p>
            </div>
            <div>
                <p><?php echo $tpl['arr']['cron_result'] ?></p>
            </div>
        </div>
    </div>
</section>