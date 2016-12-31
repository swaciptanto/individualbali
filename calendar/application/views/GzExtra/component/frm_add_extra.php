<form name="add_extra" id="frm_add_extra_id" method="post" action="<?php echo INSTALL_URL; ?>GzExtra/add_extra" enctype="multipart/form-data"> 
    <input type="hidden" name="from_date" id="from_date" value="" />
    <input type="hidden" name="to_date" id="to_date" value="" />
    <fieldset>
        <div class="form-group">
            <label class="control-label" for="title">
                <?php echo __('name'); ?>:
            </label>
            <input data-rule-required="true" class="form-control" type="text" placeholder="Enter ..." name="title" value="<?php echo @$_POST['title']; ?>">
        </div>  
        <div class="form-group">
            <label class="control-label" for="price">
                <?php echo __('price'); ?>:
            </label>
            <div class="input-group">
                <input data-rule-required="true" class="form-control" type="text" name="price" value="<?php echo @$_POST['extra']['price']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label" for="type">
                <?php echo __('type'); ?>:
            </label>
            <select data-rule-required="true" name="type"  class="form-control input-sm" >
                <?php
                $extra_type = __('extra_type');
                print_r($extra_type);
                foreach ($extra_type as $k => $v) {
                    ?>
                    <option <?php echo (@$_POST['type'] == $k) ? "selected='selected'" : ""; ?> value="<?php echo $k; ?>" ><?php echo $v; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label class="control-label" for="per">
                <?php echo __('calculate'); ?>:
            </label>
            <select data-rule-required="true" name="per" class="form-control input-sm" >
                <?php
                $extra_per = __('extra_per');
                foreach ($extra_per as $k => $v) {
                    ?>
                    <option <?php echo (@$_POST['extra']['type'] == $k) ? "selected='selected'" : ""; ?> value="<?php echo $k; ?>" ><?php echo $v; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label class="control-label" for="description">
                <?php echo __('description'); ?>:
            </label>
            <textarea data-rule-required="true" class="form-control" name="description"></textarea>
        </div>
        <div class="form-group">
            <label class="control-label" for="img">
                <?php echo __('image'); ?>:
            </label>
            <input class="form-control" type="file" name="img">
        </div>
    </fieldset>
    <fieldset>
        <button id="submit" class="btn btn-primary" autocomplete="off" value="Submit" name="submit" tabindex="9" type="submit"><i class="fa fa-fw fa-save"></i>&nbsp;&nbsp;<?php echo __('save'); ?></button>
    </fieldset>
</form>