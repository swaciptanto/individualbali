<?php
unset($_REQUEST['controller']);
unset($_REQUEST['action']);

$_REQUEST['controller'] = "GzFront";
$_REQUEST['action'] = "inquiry_form";

ob_start();

require_once INSTALL_PATH . '/load.php';

$content = ob_get_contents();
ob_end_clean();

$content = preg_replace('/\r\n|\n|\t/', '', $content);
$content = str_replace("'", "\"", $content);
echo "document.writeln('$content');"
?>
