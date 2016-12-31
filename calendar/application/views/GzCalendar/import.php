<section class="content-header">
    <h1>
        <?php echo __('import'); ?>
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="<?php echo INSTALL_URL; ?>"><i class="fa fa-dashboard"></i> 
                <?php
                echo __('home');
                ?>
            </a>
        </li>
        <li><a href="<?php echo INSTALL_URL; ?>GzCalendar/index"><?php echo __('calendars'); ?></a></li>
        <li class="active"><?php echo __('import'); ?></li>
    </ol>
</section>
<?php
require_once VIEWS_PATH . 'Layouts/admin/error_notice.php';
?>
<section class="content">
    <div class="row">
        <div class="col-md-12 ui-sortable">
            <div class="panel panel-inverse">
                <div class="panel-body">
                    <form id="import-frm-id" class="frm-class import-frm-class" action="<?php echo INSTALL_URL; ?>index.php?controller=GzCalendar&action=import&id=<?php echo $_GET['id']; ?>" method="post" name="import-frm" enctype="multipart/form-data">
                        <div class="callout callout-info">
                            <p><?php echo __('import_ics_info'); ?></p>
                        </div>
                        <div class="padding-19 nav-tabs-custom left width_100">
                            <div class="overlay"></div>
                            <div class="loading-img"></div>
                            <fieldset class="scheduler-border bg-light-orange">
                                <legend class="scheduler-border"><?php echo __('import'); ?></legend>
                                <br />
                                <div class="form-group">
                                    <label class="control-label" for="csv_file">
                                        <?php echo __('label_ics_file'); ?>:
                                    </label>
                                    <input class="form-control" type="file" name="ics_file" accept="text/calendar">
                                </div>
                            </fieldset>
                            <fieldset class="form-actions">
                                <input type="hidden" name="import" value="1" /> 
                                <button id="import-submit-id" class="btn btn-default" autocomplete="off" value="<?php echo __('import'); ?>" name="submit" tabindex="9" type="submit">
                                    <?php echo __('import'); ?>
                                </button>
                            </fieldset>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>