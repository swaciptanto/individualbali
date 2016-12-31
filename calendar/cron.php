<?php
ini_set('max_execution_time', 90000000000000);
ini_set('memory_limit', '-1');
ini_set("display_errors", "On");

if (!headers_sent()) {
    @session_name('GzAvailabilityBookingCalendar');
    @session_start();
}
header("Content-type: text/html; charset=utf-8");

if (!defined("ROOT_PATH")) {
    define("ROOT_PATH", dirname(__FILE__) . '/');
}

if (!defined("INSTALL_FOLDER")) {
    $pathinfo = pathinfo($_SERVER["PHP_SELF"]);
    define("INSTALL_FOLDER", $pathinfo['dirname'] . '/');
}

require_once ROOT_PATH . 'application/config/config.php';
require_once FRAMEWORK_PATH . 'I18n.php';
if (@$_REQUEST['controller'] != "Installer") {
    $I18n = new I18n();
}

$requestURI = explode('/', $_SERVER['REQUEST_URI']);
$scriptName = explode('/', $_SERVER['SCRIPT_NAME']);

for ($i = 0; $i < sizeof($scriptName); $i++) {
    if ($requestURI[$i] == $scriptName[$i]) {
        unset($requestURI[$i]);
    }
}

$command = array_values($requestURI);

if (empty($_REQUEST['controller']) && !empty($command[0])) {
    $_REQUEST['controller'] = $command[0];
}
if (empty($_REQUEST['action']) && !empty($command[1])) {
    $_REQUEST['action'] = $command[1];
}
if (empty($_GET['id']) && !empty($command[2])) {
    $_GET['id'] = $command[2];
}

$_REQUEST['controller'] = 'GzCron';
$_REQUEST['action'] = 'index';

$app = new Bootstrap();
$app->setController(@$_REQUEST['controller']);
$app->setAction(@$_REQUEST['action']);
$app->init();