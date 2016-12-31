<table class="table table-condensed">
    <tr>
        <th><?php echo __('calendars'); ?></th>
        <th><?php echo __('count'); ?></th>
        <th></th>
    </tr>
    <?php
    foreach ($tpl['calendars'] as $k => $v) {
        ?>
        <tr>
            <td><?php echo $v['i18n'][$this->controller->tpl['default_language']['id']]['title']; ?></td>
            <td><?php echo $v['selected_count']; ?></td>
            <td>
                <a class="btn btn-default btn-sm icon-room-delete" href="javascript:" rev="<?php echo $v['id']; ?>">
                    <span class="glyphicon glyphicon-remove"></span>
                </a>
            </td>
        </tr>
        <?php
    }
    ?>
</table>