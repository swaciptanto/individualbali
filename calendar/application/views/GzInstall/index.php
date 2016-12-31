<section class="content-header">
    <h1>
        <?php echo __('install_code'); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo INSTALL_URL; ?>"><i class="fa fa-dashboard"></i><?php echo __('home'); ?></a></li>
        <li class="active"><?php echo __('install_code'); ?></li>
    </ol>
</section>
<!-- Main content -->
<section class="content left width_100">
    <?php
    require_once VIEWS_PATH . 'Layouts/admin/error_notice.php';
    ?>
    <form id="install_frm" class="frm-class" action="<?php echo INSTALL_URL; ?>GzPreview/index" method="post" name="" target="_blank">
        <div class="padding-19 nav-tabs-custom left width_100">
            <fieldset>
                <div class="callout callout-info">
                    <p>
                        <?php echo __('install_script'); ?>
                    </p>
                </div>
                <div class="form-group">
                    <label class="control-label" for="calendars_id"><?php echo __('calendars'); ?>:</label>
                    <select multiple="" id="calendars_id" name="calendars_id[]" class="select2" style="width: 100%;">
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
                </div>
                <div class="form-group">
                    <label for="months"><?php echo __('months'); ?>:</label>
                    <select id="months_id" class="form-control input-sm mini" name="months">
                        <?php for ($i = 1; $i <= 12; $i++) {
                            ?>
                            <option <?php echo ($i == 3)?"selected = 'selected'":""; ?> value="<?php echo $i; ?>">
                                <?php
                                echo $i;
                                ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <textarea id="install_code" class="form-control textarea-resizable">&lt;script type="text/javascript" src="<?php echo INSTALL_URL; ?>index.php?controller=GzFront&action=load&cid[]=1&view_month=3" &gt; &lt;/script&gt;                  
                    </textarea>
                </div>
                <div class="form-group">
                    <label for=""><?php echo __('inquiry_form'); ?>:</label>
                    <textarea id="inquiry_form_install_code" class="form-control textarea-resizable">&lt;script type="text/javascript" src="<?php echo INSTALL_URL; ?>index.php?controller=GzFront&action=load_inquiry_form&cid[]=1" &gt; &lt;/script&gt;                  
                    </textarea>
                </div>
                <div class="form-group">
                    <label for="">Modal Form:</label>
                    <textarea id="modal_form_install_code" class="form-control textarea-resizable">&lt;script type="text/javascript" src="<?php echo INSTALL_URL; ?>index.php?controller=GzFront&action=load_modal_form&cid[]=1" &gt; &lt;/script&gt;                  
                    </textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-fw fa-eye"></i>&nbsp;&nbsp;<?php echo __('preview'); ?></button>
             
                    <a target="blank" href="<?php echo INSTALL_URL; ?>index.php?controller=GzPreview&action=inquiry_form" class="btn btn-primary btn-flat"><i class="fa fa-fw fa-eye"></i>&nbsp;&nbsp;<?php echo __('inquiry_form'); ?></a>
                </div>
            </fieldset>
        </div>
    </form>
</section><!-- /.content -->