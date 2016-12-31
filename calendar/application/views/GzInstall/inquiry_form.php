<?php
if (empty($_POST['calendars_id'])) {
   $_POST['calendars_id'][] = '1';
}
?>
<script type="text/javascript" src="<?php echo INSTALL_URL; ?>index.php?controller=GzFront&action=load_inquiry_form&cid[]=<?php echo implode('&cid[]=', $_POST['calendars_id']); ?>"></script>