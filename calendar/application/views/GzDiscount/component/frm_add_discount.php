<form name="add_discount" id="add_discount_id" method="post" action=""> 
    <input type="hidden" id="hidden_room_type_id" name="room_type_id" id="frm_add_room_type_id" value="<?php echo $tpl['default_room_type']['id']; ?>" />
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
        <div class="form-group">
            <label class="control-label" for="title">
                <?php echo __('title'); ?>:
            </label>
            <input data-rule-required="true" class="form-control" type="text" placeholder="Enter ..." name="title">
        </div>
        <div class="form-group">
            <label class="control-label" for="price_deducted">
                <?php echo __('promo_code'); ?>:
            </label>
            <div class="input-group">
                <input data-rule-required="true" class="form-control" type="text" name="promo_code">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label" for="discount">
                <?php echo __('discount'); ?>:
            </label>
            <div class="input-group">
                <span class="input-group-addon">$/%</span>
                <input data-rule-required="true" class="form-control" type="text" name="discount">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label" for="type">
                <?php echo __('type'); ?>:
            </label>
            <select data-rule-required="true" name="type"  class="form-control input-sm" >
                <?php
                $discount_type = __('discount_type');
                foreach ($discount_type as $k => $v) {
                    ?>
                    <option value="<?php echo $k; ?>" ><?php echo $v; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>      
    </fieldset>
</form>