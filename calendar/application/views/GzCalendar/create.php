<section class="content-header">
    <h1>
        <?php echo __('add_calendar'); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo INSTALL_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo __('home'); ?></a></li>
        <li><a href="<?php echo INSTALL_URL; ?>GzCalendar/index"><?php echo __('calendars'); ?></a></li>
        <li class="active"><?php echo __('add_calendar'); ?></li>
    </ol>
</section>
<?php
require_once VIEWS_PATH . 'Layouts/admin/error_notice.php';
?>
<section class="content left width_100">
    <form id="frm_calendar" class="frm-class" action="<?php echo INSTALL_URL; ?>GzCalendar/create" method="post" name="create">
        <!--modified: add new-->
        <input type="hidden" name="limit" value="1" />
        <!--1 = admin@admin.com-->
        <input type="hidden" name="user_id" value="1" />
        <!--2 = Default Calendar-->
        <input type="hidden" name="option_id" value="2" />
        <div class="padding-19 nav-tabs-custom left width_100">
            <div class="callout callout-info">
                <p><?php echo __('calendar_type_info'); ?></p>
            </div>
            <fieldset>
<!--            modified: hide    
                <div class="form-group">
                    <label class="control-label" for="url">Villa URL:</label>
                    <input id="url" class="form-control input-sm" type="text" name="url" size="25" value="<?php echo $tpl['arr']['url']; ?>">
                </div>-->
                <!-- modified: add -->
                <div class="form-group">
                    <label class="control-label" for="villa_node_id">Villa ID:</label>
                    <input id="villa_node_id" data-rule-required="true" class="form-control input-sm" type="text" name="villa_node_id" size="25" value="<?php echo $tpl['arr']['villa_node_id']; ?>">
                </div>
<!--            modified: hide
                <div class="form-group">
                    <label class="control-label" for="googleId"><?php echo __('google_calendar_address'); ?>:</label>
                    <input id="googleId" class="form-control input-sm" type="text" name="googleId" size="25" value="">
                </div>-->
<!--            modified: changed using input hidden
                <div class="form-group">
                    <label class="control-label" for="user_id"><?php echo __('calendar_owner'); ?>:</label>
                    <select data-rule-required="true" name="user_id"  class="form-control input-sm" >
                        <option>---</option>
                        <?php foreach ($tpl['users'] as $user) {
                            ?>
                            <option value="<?php echo $user['id']; ?>" >
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
<!--            modified: changed using input hidden    
                <div class="form-group">
                    <label class="control-label" for="option_id"><?php echo __('copy_options'); ?>:</label>
                    <select data-rule-required="true" id="option_id" name="option_id"  class="form-control input-sm" >
                        <option value="">---</option>
                        <?php foreach ($tpl['calendars'] as $calendar) {
                            ?>
                            <option value="<?php echo $calendar['id']; ?>">
                                <?php
                                echo $calendar['i18n'][$this->controller->tpl['default_language']['id']]['title'];
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
                            <option value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label" for="children"><?php echo __('calendar_children'); ?>:</label>
                    <select data-rule-required="true"  name="children"  class="form-control input-sm mini" >
                        <?php for ($i = 0; $i <= 20; $i++) {
                            ?>
                            <option value="<?php echo $i; ?>" ><?php echo $i; ?></option>
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
                            <option value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                modified: auto-filled using drupal data
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
                                    <input data-rule-required="true"  id="title" class="form-control input-sm" type="text" name="title[<?php echo $language['id']; ?>]" size="25" value="">
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="description"><?php echo __('calendar_description'); ?>:</label>
                                    <textarea data-rule-required="true"  name="description[<?php echo $language['id']; ?>]" id="body" class="form-control input-sm height_300" ></textarea>
                                </div>
                            </div>
                            <?php
                            $i++;
                        }
                        ?>
                    </div>
                </div>-->
            </fieldset>
            <fieldset>
                <input type="hidden" name="create_calendar" value="1" /> 
                <button id="submit" class="btn btn-primary" autocomplete="off" value="Submit" name="submit" tabindex="9" type="submit"><i class="fa fa-fw fa-save"></i>
                    <?php //modified: changed echo __('save');
                    echo __('create') ?>
                </button>
            </fieldset>
        </div>
    </form>
</section>