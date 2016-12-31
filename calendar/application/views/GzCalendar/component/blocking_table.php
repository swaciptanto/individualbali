<div class="overlay"></div>
<div class="loading-img"></div>
<table id="<?php echo (count($tpl['arr'])) ? "gz-abc-table-blocking-id" : ""; ?>" class="gzblog-table" cellpadding="0" cellspacing="0" >
    <thead>
        <tr>
            <th class="">
                <input class="simple" type="checkbox" name="mark-all" id="block-mark-all-id" value="all"/>
            </th>
            <th><?php echo __('from_date'); ?></th>
            <th><?php echo __('to_date'); ?></th>
            <th class="title-th"><?php echo __('calendar'); ?></th>
            <th class="icon-th"></th>
            <th class="icon-th"></th>
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
                    <td><span style="display: none;"><?php echo $tpl['arr'][$i]['from_date']; ?></span><?php echo date($tpl['option_arr_values']['date_format'], $tpl['arr'][$i]['from_date']); ?></td>
                    <td><span style="display: none;"><?php echo $tpl['arr'][$i]['to_date']; ?></span><?php echo date($tpl['option_arr_values']['date_format'], $tpl['arr'][$i]['to_date']); ?></td>
                    <td><?php echo $tpl['arr'][$i]['i18n'][$this->controller->tpl['default_language']['id']]['title']; ?></td>
                    <td><a class="btn btn-default btn-sm icon-edit" href="#" rel="<?php echo $tpl['arr'][$i]['id']; ?>"><span class="glyphicon glyphicon-pencil"></span></a></td>
                    <td><a class="btn btn-default btn-sm icon-block-delete" rev="<?php echo $tpl['arr'][$i]['id']; ?>" href="#"><span class="glyphicon glyphicon-remove"></span></a></td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="7">
                    <?php
                    echo __('no_blocking');
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
                        <li><a id="delete-block-selected-id" href="javascript:"><?php echo __('delete_selected'); ?></a></li>
                        <li class="divider"></li>
                        <li><a href="javascript:" id="add_blocking"><?php echo __('add_blocking'); ?></a></li>
                    </ul>
                </div>
            </td>
        </tr>
    </tfoot>
</table>