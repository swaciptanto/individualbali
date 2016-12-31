<form name="frm_edit_extra" id="frm_edit_extra_id" method="post" action="<?php echo INSTALL_URL; ?>GzExtra/edit" enctype="multipart/form-data"> 
    <input type="hidden" name="id" value="<?php echo @$tpl['extra']['id']; ?>" id="edit_extra_id" />
    <fieldset>
        <div class="form-group">
            <label class="control-label" for="title">
                <?php echo __('name'); ?>:
            </label>
            <input data-rule-required="true" class="form-control" type="text" placeholder="Enter ..." name="title" value="<?php echo @$tpl['extra']['title']; ?>">
            <div class="control-group"></div>
        </div>
        <div class="form-group">
            <label class="control-label" for="price">
                <?php echo __('price'); ?>:
            </label>
            <div class="input-group">
                <input data-rule-required="true" class="form-control" type="text" name="price" value="<?php echo @$tpl['extra']['price']; ?>">
            </div>
            <div class="control-group"></div>
        </div>
        <div class="form-group">
            <label class="control-label" for="type">
                <?php echo __('type'); ?>:
            </label>
            <select data-rule-required="true" name="type"  class="form-control input-sm" >
                <?php 
                $extra_type = __('extra_type');
                foreach ($extra_type as $k => $v) {
                    ?>
                    <option <?php echo ($tpl['extra']['type'] == $k) ? "selected='selected'" : ""; ?> value="<?php echo $k; ?>" ><?php echo $v; ?></option>
                    <?php
                }
                ?>
            </select>
            <div class="control-group"></div>
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
                    <option <?php echo ($tpl['extra']['per'] == $k) ? "selected='selected'" : ""; ?> value="<?php echo $k; ?>" ><?php echo $v; ?></option>
                    <?php
                }
                ?>
            </select>
            <div class="control-group"></div>
        </div>
        <div class="form-group">
            <label class="control-label" for="description">
                <?php echo __('description'); ?>:
            </label>
            <textarea data-rule-required="true" class="form-control" name="description"><?php echo $tpl['extra']['description']; ?></textarea>
        </div>
        <div class="form-group">
            <label class="control-label" for="img">
                <?php echo __('image'); ?>:
            </label>
            <?php if (is_file(INSTALL_PATH . UPLOAD_PATH . 'extra/thumb/' . $tpl['extra']['img'])) { ?>
                <img src="<?php echo INSTALL_URL . UPLOAD_PATH . 'extra/thumb/' . $tpl['extra']['img']; ?>" />
            <?php } else {
                ?>
                <input class="form-control" type="file" name="img">
            <?php } ?>
            <div class="control-group"></div>
        </div>
    </fieldset>
    <fieldset style="margin-bottom: 10px;">
        <button id="submit" class="btn btn-primary" autocomplete="off" value="Submit" name="submit" tabindex="9" type="submit"><i class="fa fa-fw fa-save"></i>&nbsp;&nbsp;<?php echo __('save'); ?></button>
    </fieldset>
</form>