<?php

require_once(ROOT_PATH . 'application/config/functions.inc.php');

$stop = false;
if (isset($_GET['controller']) && $_GET['controller'] == 'Installer') {
    $stop = true;
    if (isset($_GET['install'])) {
        switch ($_GET['install']) {
            case 1:
                $stop = true;
                break;
            default:
                $stop = false;
                break;
        }
    }
}

if (!$stop) {

    define("DEFAULT_HOST", "localhost");
    define("DEFAULT_USER", "root");
    define("DEFAULT_PASS", "");
    define("DEFAULT_DB", "individualbali_calendar");
    define("DEFAULT_PREFIX", "");
    define("DEFAULT_CALENDAR_ID", 2);

    if (preg_match('/\[hostname\]/', DEFAULT_HOST) || preg_match('/\[username\]/', DEFAULT_USER)) {
        Util::redirect("index.php?controller=Installer&action=step0&install=1");
    }
    
    //modified: get drupal database settings
    define('DRUPAL_ROOT_PATH', realpath(__DIR__ . '/../../..'));
    define('DRUPAL_SITES_PATH', DRUPAL_ROOT_PATH . '/sites/');
    $drupal_settings_filename = DRUPAL_SITES_PATH . 'default/settings.php';
    $drupal_settings = explode(';', file_get_contents($drupal_settings_filename));
    foreach ($drupal_settings as $drupal_setting) {
        if (preg_match('/\bdatabases\b/i', $drupal_setting)) {
            eval(str_replace('$databases', '$drupal_database_setting', $drupal_setting) . ';');
        }
    }
    if (isset($drupal_database_setting) && count($drupal_database_setting) > 0) {
        define("DRUPAL_HOST", $drupal_database_setting['default']['default']['host']);
        define("DRUPAL_USER", $drupal_database_setting['default']['default']['username']);
        define("DRUPAL_PASS", $drupal_database_setting['default']['default']['password']);
        define("DRUPAL_DB", $drupal_database_setting['default']['default']['database']);
        define("DRUPAL_URL", "http://individualbali.com.dev");
    }
}

if (!defined("INSTALL_PATH")) {
    define("INSTALL_PATH", "D:/Development/individualbali_git/calendar/");
}
if (!defined("INSTALL_URL")) {
    define("INSTALL_URL", "http://individualbali.com.dev/calendar/");
}
if (!defined("INSTALL_FOLDER")) {
    define("INSTALL_FOLDER", "/calendar");
}

if (!defined("APP_PATH")) {
    define("APP_PATH", ROOT_PATH . "application/");
}
if (!defined("CORE_PATH")) {
    define("CORE_PATH", ROOT_PATH . "core/");
}
if (!defined("LIBS_PATH")) {
    define("LIBS_PATH", "core/libs/");
}
if (!defined("FRAMEWORK_PATH")) {
    define("FRAMEWORK_PATH", CORE_PATH . "framework/");
}
if (!defined("CONFIG_PATH")) {
    define("CONFIG_PATH", APP_PATH . "config/");
}
if (!defined("CONTROLLERS_PATH")) {
    define("CONTROLLERS_PATH", APP_PATH . "controllers/");
}
if (!defined("COMPONENTS_PATH")) {
    define("COMPONENTS_PATH", APP_PATH . "controllers/components/");
}
if (!defined("MODELS_PATH")) {
    define("MODELS_PATH", APP_PATH . "models/");
}
if (!defined("VIEWS_PATH")) {
    define("VIEWS_PATH", APP_PATH . "views/");
}
if (!defined("WEB_PATH")) {
    define("WEB_PATH", APP_PATH . "web/");
}
if (!defined("CSS_PATH")) {
    define("CSS_PATH", "application/web/css/");
}
if (!defined("IMG_PATH")) {
    define("IMG_PATH", "application/web/img/");
}
if (!defined("JS_PATH")) {
    define("JS_PATH", "application/web/js/");
}
if (!defined("UPLOAD_PATH")) {
    define("UPLOAD_PATH", "application/web/upload/");
}
?>