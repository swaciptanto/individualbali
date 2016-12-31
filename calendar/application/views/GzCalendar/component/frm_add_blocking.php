<form name="add_blockingt" id="add_blockingt_id" method="post" action=""> 
    <input type="hidden" name="create_blocking" value="1" />
    <input type="hidden" name="from_date" id="from_date" value="" />
    <input type="hidden" name="to_date" id="to_date" value="" />
    <fieldset>
        <div class="form-group">
            <label class="control-label" for="date_range"><?php echo __('date_range'); ?>:</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                </div>
                <input data-rule-required="true" date-format="<?php echo $tpl['iso_format']; ?>" id="daterange-btn" name="date_range" class="form-control pull-right" type="text" value="">
            </div>
        </div>
        <?php
        require_once 'blocking_calendars.php';
        ?>
    </fieldset>
</form>