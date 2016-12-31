<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active">
            <a data-toggle="tab" href="#invoice_tab_1"><?php echo __('invoice_template'); ?></a>
        </li>
        <li class="">
            <a data-toggle="tab" href="#invoice_tab_2"><?php echo __('inovice_company'); ?></a>
        </li>
    </ul>    
    <div class="tab-content">
        <div id="invoice_tab_1" class="tab-pane active">
            <div class="callout callout-info" style=" width: 100%; height: 300px;">
                <h4><?php echo __('invoice_token_info'); ?></h4>
                <p>
                    <?php
                    $invoice_token = __('invoice_token');
                    echo '<div class="email-token-class">' . implode('</div><div class="email-token-class">', $invoice_token) . '</div>';
                    ?>
                </p>
            </div>
            <div style="width: 100%; height: 300px;">
                <?php
                foreach ($tpl['invoice']['template'] as $option) {
                    if ($option['type'] == 'string') {
                        ?>
                        <div class="form-group">
                            <label class="control-label" for="title"><?php echo $option['title'] ?>:</label>
                            <input id="<?php echo $option['key']; ?>" class="form-control input-sm" type="text" name="<?php echo $option['key'] ?>" size="25" value="<?php echo $option['value']; ?>" title="Title" placeholder="title">
                            <div class="control-group"></div>
                        </div>
                        <?php
                    } elseif ($option['type'] == 'text') {
                        ?>
                        <div class="form-group">
                            <label class="control-label" for="body"><?php echo $option['title'] ?>:</label>
                            <textarea name="<?php echo $option['key']; ?>" id="<?php echo $option['key'] ?>" class="form-control input-sm height_300" ><?php echo $option['value']; ?></textarea>
                            <div class="control-group"></div>
                        </div>
                        <?php
                    } elseif ($option['type'] == 'enum') {
                        $default = explode("::", $option['value']);
                        $enum = explode("|", $default[0]);

                        $enumLabels = array();
                        if (!empty($option['label']) && strpos($option['label'], "|") !== false) {
                            $enumLabels = explode("|", $option['label']);
                        }
                        ?>
                        <div class="form-group">
                            <label class="control-label" for="<?php echo $option['key']; ?>"><?php echo $option['title'] ?>:</label>
                            <select name="<?php echo $option['key']; ?>" id="<?php echo $option['key']; ?>" class="select-sm" >
                                <?php
                                foreach ($enum as $k => $el) {
                                    if ($default[1] == $el) {
                                        ?><option value="<?php echo $default[0] . '::' . $el; ?>" selected="selected"><?php echo array_key_exists($k, $enumLabels) ? stripslashes($enumLabels[$k]) : stripslashes($el); ?></option><?php
                                    } else {
                                        ?><option value="<?php echo $default[0] . '::' . $el; ?>"><?php echo array_key_exists($k, $enumLabels) ? stripslashes($enumLabels[$k]) : stripslashes($el); ?></option><?php
                                    }
                                }
                                ?>
                            </select>
                            <div class="control-group"></div>
                        </div>
                        <?php
                    } elseif ($option['type'] == 'int') {
                        ?>
                        <div class="form-group">
                            <label class="control-label" for="title"><?php echo $option['title'] ?>:</label>
                            <input id="<?php echo $option['key'] ?>" class="form-control input-sm int" type="text" name="<?php echo $option['key'] ?>" size="25" value="<?php echo $option['value']; ?>" title="Title" placeholder="title">
                            <div class="control-group"></div>
                        </div>
                        <?php
                    } elseif ($option['type'] == 'float') {
                        ?>
                        <div class="form-group">
                            <label class="control-label" for="title"><?php echo $option['title'] ?>:</label>
                            <input id="<?php echo $option['key'] ?>" class="form-control input-sm float" type="text" name="<?php echo $option['key'] ?>" size="25" value="<?php echo $option['value']; ?>" title="Title" placeholder="title">
                            <div class="control-group"></div>
                        </div>
                        <?php
                    } elseif ($option['type'] == 'price') {
                        ?>
                        <div class="form-group">
                            <label class="control-label" for="title"><?php echo $option['title'] ?>:</label>
                            <input id="<?php echo $option['key'] ?>" class="form-control input-sm float" type="text" name="<?php echo $option['key'] ?>" size="25" value="<?php echo $option['value']; ?>" title="Title" placeholder="title">
                            <div class="control-group"></div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <div id="invoice_tab_2" class="tab-pane">
            <?php
            foreach ($tpl['invoice']['company'] as $option) {
                if ($option['type'] == 'string') {
                    ?>
                    <div class="form-group">
                        <label class="control-label" for="title"><?php echo $option['title'] ?>:</label>
                        <input id="<?php echo $option['key']; ?>" class="form-control input-sm" type="text" name="<?php echo $option['key'] ?>" size="25" value="<?php echo $option['value']; ?>" title="Title" placeholder="title">
                        <div class="control-group"></div>
                    </div>
                    <?php
                } elseif ($option['type'] == 'text') {
                    ?>
                    <div class="form-group">
                        <label class="control-label" for="body"><?php echo $option['title'] ?>:</label>
                        <textarea name="<?php echo $option['key']; ?>" id="<?php echo $option['key'] ?>" class="form-control input-sm height_300" ><?php echo $option['value']; ?></textarea>
                        <div class="control-group"></div>
                    </div>
                    <?php
                } elseif ($option['type'] == 'enum') {
                    $default = explode("::", $option['value']);
                    $enum = explode("|", $default[0]);

                    $enumLabels = array();
                    if (!empty($option['label']) && strpos($option['label'], "|") !== false) {
                        $enumLabels = explode("|", $option['label']);
                    }
                    ?>
                    <div class="form-group">
                        <label class="control-label" for="<?php echo $option['key']; ?>"><?php echo $option['title'] ?>:</label>
                        <select name="<?php echo $option['key']; ?>" id="<?php echo $option['key']; ?>" class="select-sm" >
                            <?php
                            foreach ($enum as $k => $el) {
                                if ($default[1] == $el) {
                                    ?><option value="<?php echo $default[0] . '::' . $el; ?>" selected="selected"><?php echo array_key_exists($k, $enumLabels) ? stripslashes($enumLabels[$k]) : stripslashes($el); ?></option><?php
                                } else {
                                    ?><option value="<?php echo $default[0] . '::' . $el; ?>"><?php echo array_key_exists($k, $enumLabels) ? stripslashes($enumLabels[$k]) : stripslashes($el); ?></option><?php
                                }
                            }
                            ?>
                        </select>
                        <div class="control-group"></div>
                    </div>
                    <?php
                } elseif ($option['type'] == 'int') {
                    ?>
                    <div class="form-group">
                        <label class="control-label" for="title"><?php echo $option['title'] ?>:</label>
                        <input id="<?php echo $option['key'] ?>" class="form-control input-sm int" type="text" name="<?php echo $option['key'] ?>" size="25" value="<?php echo $option['value']; ?>" title="Title" placeholder="title">
                        <div class="control-group"></div>
                    </div>
                    <?php
                } elseif ($option['type'] == 'float') {
                    ?>
                    <div class="form-group">
                        <label class="control-label" for="title"><?php echo $option['title'] ?>:</label>
                        <input id="<?php echo $option['key'] ?>" class="form-control input-sm float" type="text" name="<?php echo $option['key'] ?>" size="25" value="<?php echo $option['value']; ?>" title="Title" placeholder="title">
                        <div class="control-group"></div>
                    </div>
                    <?php
                } elseif ($option['type'] == 'price') {
                    ?>
                    <div class="form-group">
                        <label class="control-label" for="title"><?php echo $option['title'] ?>:</label>
                        <input id="<?php echo $option['key'] ?>" class="form-control input-sm float" type="text" name="<?php echo $option['key'] ?>" size="25" value="<?php echo $option['value']; ?>" title="Title" placeholder="title">
                        <div class="control-group"></div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>