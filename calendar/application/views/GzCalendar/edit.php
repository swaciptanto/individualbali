<section class="content-header">
    <h1>
        <?php echo __('edit_calendar'); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo INSTALL_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo __('home'); ?></a></li>
        <li><a href="<?php echo INSTALL_URL; ?>GzCalendar/index"><?php echo __('calendars'); ?></a></li>
        <li class="active"><?php echo __('edit_calendar'); ?></li>
    </ol>
</section>
<?php
require_once VIEWS_PATH . 'Layouts/admin/error_notice.php';
?>
<section class="content left width_100">
    <div class="padding-19 nav-tabs-custom left width_100">
        <div class="callout callout-info">
            <p><?php echo __('calendar_edit_info'); ?></p>
        </div>   
        <form id="frm_calendar" class="frm-class" action="<?php echo INSTALL_URL; ?>GzCalendar/edit" method="post" name="edit">
            <!--modified: add new-->
            <input type="hidden" name="limit" value="1" />
            <input type="hidden" name="user_id" value="1" />
            <input type="hidden" name="villa_node_id" value="<?php echo $tpl['arr']['villa_node_id']; ?>">
            <input type="hidden" name="villa_owner_email" value="<?php echo $tpl['arr']['villa_owner_email']; ?>">
            <input type="hidden" name="villa_reservation_email" value="<?php echo $tpl['arr']['villa_reservation_email']; ?>">
            <fieldset>
<!--            modified: hide    
                <div class="form-group">
                    <label class="control-label" for="url">ICS Export URL:</label>
                    <a target="_blank" href="<?php echo INSTALL_URL; ?>iCal/<?php echo $tpl['arr']['id']; ?>/export.ics"><?php echo INSTALL_URL; ?>iCal/<?php echo $tpl['arr']['id']; ?>/export.ics</a>
                </div>-->
                <!-- modified: add -->
                <div class="form-group">
                    <label class="control-label" for="villa_node_id">Villa ID:</label>
                    <input id="villa_node_id" class="form-control input-sm" type="text" size="25" value="<?php echo $tpl['arr']['villa_node_id']; ?>" disabled="disabled">
                </div>
                <!-- modified: add -->
                <div class="form-group">
                    <label class="control-label" for="villa_reservation_email">Villa Reservation Email:</label>
                    <input id="villa_reservation_email" class="form-control input-sm" type="text" size="25" value="<?php echo $tpl['arr']['villa_reservation_email']; ?>" disabled="disabled">
                </div>
<!--            modified: hide
                <div class="form-group">
                    <label class="control-label" for="url">Villa URL:</label>
                    <input id="url" class="form-control input-sm" type="text" name="url" size="25" value="<?php echo $tpl['arr']['url']; ?>">
                </div>-->
<!--            modified: hide
                <div class="form-group">
                    <label class="control-label" for="googleId"><?php echo __('google_calendar_address'); ?>:</label>
                    <input id="googleId" class="form-control input-sm" type="text" name="googleId" size="25" value="<?php echo $tpl['arr']['googleId']; ?>">
                </div>
                modified: changed using input hidden
                <div class="form-group">
                    <label class="control-label" for="user_id"><?php echo __('calendar_owner'); ?>:</label>
                    <select data-rule-required="true" name="user_id"  class="form-control input-sm" >
                        <?php foreach ($tpl['users'] as $user) {
                            ?>
                            <option <?php echo ($tpl['arr']['user_id'] == $user['id']) ? "selected='selected'" : ""; ?> value="<?php echo $user['id']; ?>" >
                                <?php
                                if (!empty($user['first']) || !empty($user['last'])) {
                                    echo $user['first'] . ' ' . $user['last'];
                                } else {
                                    echo $user['email'];
                                }
                                ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </div>-->
                <?php /*
                  <div class="form-group">
                  <label class="control-label" for="adults"><?php echo __('calendar_adults'); ?>:</label>
                  <select data-rule-required="true"  name="adults"  class="form-control input-sm mini" >
                  <?php for ($i = 1; $i <= 20; $i++) {
                  ?>
                  <option <?php echo ($tpl['arr']['adults'] == $i) ? "selected='selected'" : ""; ?> value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                  <?php
                  }
                  ?>
                  </select>
                  </div>
                  <div class="form-group">
                  <label class="control-label" for="children"><?php echo __('calendar_children'); ?>:</label>
                  <select data-rule-required="true"   name="children"  class="form-control input-sm mini" >
                  <?php for ($i = 0; $i <= 20; $i++) {
                  ?>
                  <option <?php echo ($tpl['arr']['children'] == $i) ? "selected='selected'" : ""; ?> value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                  <?php
                  }
                  ?>
                  </select>
                  </div>
                 * 
                 */ ?>
<!--            modified: changed using input hidden
                <div class="form-group">
                    <label class="control-label" for="limit"><?php echo __('calendar_limit'); ?>:</label>
                    <select data-rule-required="true"  name="limit"  class="form-control input-sm mini" >
                        <?php for ($i = 1; $i <= 20; $i++) {
                            ?>
                            <option <?php echo ($tpl['arr']['limit'] == $i) ? "selected='selected'" : ""; ?> value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>-->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <?php
                        $count = count($tpl['languages']);
                        for ($i = 0; $i < $count; $i++) {
                            ?>
                            <li class="<?php echo ($i == 0) ? "active" : ""; ?>">
                                <a data-toggle="tab" href="#language_<?php echo $tpl['languages'][$i]['id']; ?>">
                                    <?php if (is_file(INSTALL_PATH . UPLOAD_PATH . 'flag/' . $tpl['languages'][$i]['flag'])) { ?>
                                        <img src="<?php echo INSTALL_URL . UPLOAD_PATH . 'flag/' . $tpl['languages'][$i]['flag']; ?>" />
                                        <?php
                                    } else {
                                        echo $tpl['languages'][$i]['language'];
                                    }
                                    ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>    

                    <div class="tab-content">
                        <?php
                        $i = 0;
                        foreach ($tpl['languages'] as $language) {
                            ?>
                            <div id="language_<?php echo $language['id']; ?>" class="tab-pane <?php echo ($i == 0) ? "active" : ""; ?>">
                                <div class="form-group">
                                    <label class="control-label" for="title"><?php echo __('calendar_title'); ?>:</label>
                                    <input data-rule-required="true" id="title" class="form-control input-sm" type="text" name="title[<?php echo $language['id']; ?>]" size="25" value="<?php echo $tpl['arr']['i18n'][$language['id']]['title']; ?>" title="<?php echo __('calendar_title'); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="description"><?php echo __('calendar_description'); ?>:</label>
                                    <textarea name="description[<?php echo $language['id']; ?>]" id="body" class="form-control input-sm height_300" ><?php echo $tpl['arr']['i18n'][$language['id']]['description']; ?></textarea>
                                </div>
                            </div>
                            <?php
                            $i++;
                        }
                        ?>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <input type="hidden" name="action" value="edit" /> 
                <input type="hidden" name="id" value="<?php echo $tpl['arr']['id']; ?>" />
                <input type="hidden" name="edit_calendar" value="1" /> 
                <button id="submit" class="btn btn-primary" autocomplete="off" value="Save" name="save" tabindex="9"><i class="fa fa-fw fa-save"></i>&nbsp;<?php echo __('save') ?></button>
            </fieldset>
        </form>
        <br />
        <div class="box box-primary" style="margin: 20px 0; float: left;">
            <div class="box-header" title="" data-toggle="tooltip" data-original-title="Header tooltip">
                <h3 class="box-title"><?php echo __('calendar_type_gallery'); ?></h3>
            </div>
            <div class="overlay"></div>
            <div class="loading-img"></div>
            <form id="galelry-frm-id" class="smart-form" style="width: 40%;" action="<?php echo INSTALL_URL; ?>GzCalendar/uploadImage/<?php echo $tpl['arr']['id']; ?>" method="post" name="gallery-frm"  enctype="multipart/form-data" >
                <label class="label">File input</label>
                <label class="input input-file" for="file" style="margin-left: 10px">
                    <div class="button">
                        <input type="file" onchange="this.parentNode.nextSibling.value = this.value" name="image" id="photoimg">
                        <?php echo __('add_image'); ?>
                    </div>
                    <input type="text" readonly="" placeholder="">
                </label>
            </form>
            <div id='preview'>
                <?php
                require_once 'uploadImage.php';
                ?>
            </div>
            <br />
        </div>
    </div>
</section>
<div id="dialogDeleteGallery" title="<?php echo htmlspecialchars(__('gallery_del_title')); ?>" style="display:none">
    <p><?php echo __('gallery_del_body'); ?></p>
</div>
<div id="image_id" style="display:none"></div>
<div id="type_id" style="display:none"></div>


