<table id="<?php echo (count($tpl['arr'])) ? "gz-booking-calendar-id" : ""; ?>" class="gzblog-table" cellpadding="0" cellspacing="0" >
    <thead>
        <tr>
            <th class="">
                <input class="simple" type="checkbox" name="mark-all" id="mark-all-id" value="all"/>
            </th>
            <th><?php echo __('image'); ?></th>
            <!--modified: add new-->
            <th><?php echo __('calendar_id'); ?></th>
            <th class="title-th"><?php echo __('calendar_title'); ?></th>
            <th><?php echo __('calendar_owner'); ?></th>
            <th class="icon-th"></th>
            <th class="icon-th"></th>
            <th class="icon-th"></th>
<!--        modified: hide (import ics & export ics)
            <th class="icon-th"></th>
            <th class="icon-th"></th>-->
        </tr>
    </thead>
    <tbody>
        <?php
        $count = count($tpl['arr']);
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                ?>
                <tr  class="<?php echo $i % 2 === 0 ? 'odd' : 'even'; ?>">
                    <td>
                        <input class="simple mark" type="checkbox" name="mark[]"  id="mark-<?php echo $tpl['arr'][$i]['id']; ?>" value="<?php echo $tpl['arr'][$i]['id']; ?>"/>
                    </td>
                    <td>
                        <?php
                        if (!empty($tpl['arr'][$i]['gallery'][0]['thumb']) && is_file(INSTALL_PATH . UPLOAD_PATH . "calendars/thumb/" . $tpl['arr'][$i]['gallery'][0]['thumb'])) {
                            ?>
                            <img src='<?php echo INSTALL_URL . UPLOAD_PATH . "calendars/thumb/" . $tpl['arr'][$i]['gallery'][0]['thumb']; ?>' >
                            <?php
                        }
                        ?>
                    </td>
                    <!--modified: add new-->
                    <td><?php echo $tpl['arr'][$i]['id']; ?></td>
                    <!--modified: add new-->
                    <td><?php echo ($tpl['arr'][$i]['title'] !== '' && !is_null($tpl['arr'][$i]['title'])) ? stripslashes($tpl['arr'][$i]['title']) : stripslashes($tpl['arr'][$i]['i18n'][$this->controller->tpl['default_language']['id']]['title']); ?></td>
<!--                modified: change                    
                    <td><?php echo stripslashes($tpl['arr'][$i]['i18n'][$this->controller->tpl['default_language']['id']]['title']); ?></td>-->
                    <td>
                        <?php
                        if (!empty($tpl['arr'][$i]['first']) || !empty($tpl['arr'][$i]['last'])) {
                            echo $tpl['arr'][$i]['first'] . ' ' . $tpl['arr'][$i]['last'];
                        } else {
                            //modifed to show villa_owner_email when exist
                            echo ($tpl['arr'][$i]['villa_owner_email'] != '' && !is_null($tpl['arr'][$i]['villa_owner_email']))
                            ? $tpl['arr'][$i]['villa_owner_email'] : $tpl['arr'][$i]['email'];
                            //echo $tpl['arr'][$i]['email'];
                        }
                        ?>
                    </td>
<!--                modified: hide    
                    <td>
                        <a class="btn btn-default btn-sm" href="<?php echo INSTALL_URL; ?>GzCalendar/export/<?php echo $tpl['arr'][$i]['id']; ?>">
                            ics <?php echo __('export'); ?> 
                        </a>
                    </td>
                    <td>
                        <a class="btn btn-default btn-sm" href="<?php echo INSTALL_URL; ?>GzCalendar/import/<?php echo $tpl['arr'][$i]['id']; ?>">
                            ics <?php echo __('import'); ?> 
                        </a>
                    </td>-->
                    <td><a class="btn btn-default btn-sm" href="<?php echo INSTALL_URL; ?>GzPrice/price/<?php echo $tpl['arr'][$i]['id']; ?>"><i class="fa fa-fw fa-dollar"></i></a></td>
                    <td><a class="btn btn-default btn-sm" href="<?php echo INSTALL_URL; ?>GzCalendar/settings/<?php echo $tpl['arr'][$i]['id']; ?>"><i class="fa fa-fw fa-cogs"></i></a></td>
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" type="button">
                                <span class="sr-only">Toggle navigation</span>
                                <i class="fa fa-fw fa-align-justify"></i>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="<?php echo INSTALL_URL; ?>GzCalendar/view/<?php echo $tpl['arr'][$i]['id']; ?>" rev="<?php echo $tpl['arr'][$i]['id']; ?>" target="_blank">
                                        <?php echo __('_view'); ?>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="<?php echo INSTALL_URL; ?>GzCalendar/edit/<?php echo $tpl['arr'][$i]['id']; ?>" rev="<?php echo $tpl['arr'][$i]['id']; ?>" >
                                        <?php echo __('_edit'); ?>
                                    </a>
                                </li>
                                <?php if ($tpl['arr'][$i]['id'] != 1) { ?>
                                    <li class="divider"></li>
                                    <li>
                                        <a class="icon-delete" rev="<?php echo $tpl['arr'][$i]['id']; ?>" href="<?php echo INSTALL_URL; ?>GzCalendar/delete/<?php echo $tpl['arr'][$i]['id']; ?>" >
                                            <?php echo __('_delete'); ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="8">
                    <?php
                    echo __('no_calendar');
                    ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="8">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary btn-flat"><?php echo __('action'); ?></button>
                    <button type="button" class="btn btn-primary btn-flat dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a id="delete-selected-id" href="javascript:;"><?php echo __('delete_selected'); ?></a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo INSTALL_URL; ?>GzCalendar/create"><?php echo __('add_calendars'); ?></a></li>
                    </ul>
                </div>
            </td>
        </tr>
    </tfoot>
</table>