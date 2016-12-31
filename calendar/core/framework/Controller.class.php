<?php
require_once FRAMEWORK_PATH . 'Object.class.php';

class Controller extends Object {

    var $tpl;
    var $js = array();
    var $css = array();
    var $default_user = 'admin_user';
    var $default_order = 'gz_shopping_cart_order';
    var $front_user = 'front_user';
    var $default_language = 'lang';
    var $default_product = 'GzAvailabilityBookingCalendar';
    var $layout = 'default';
    var $default_currency = 'currency';
    var $isAjax = false;

    function Controller() {
        
    }

    function beforeFilter() {
        
    }

    function beforeRender() {
        
    }

    function index() {
        
    }

    function isAjax() {
        return $this->isAjax;
    }

    function setIsAjax($bool) {
        $this->isAjax = $bool;
    }

    function getLayout() {
        return $this->layout;
    }

    function getLanguage() {
        return (!empty($_SESSION[$this->default_language])) ? $_SESSION[$this->default_language] : false;
    }

    function setLanguage($lang) {
        $_SESSION[$this->default_language] = $lang;
    }

    function getUserId() {
        return isset($_SESSION[$this->default_user]) && array_key_exists('id', $_SESSION[$this->default_user]) ? $_SESSION[$this->default_user]['id'] : false;
    }

    function getFrontUserId() {
        return isset($_SESSION[$this->front_user]) && array_key_exists('id', $_SESSION[$this->front_user]) ? $_SESSION[$this->front_user]['id'] : false;
    }

    function getType() {
        return isset($_SESSION[$this->default_user]) && array_key_exists('type', $_SESSION[$this->default_user]) ? $_SESSION[$this->default_user]['type'] : false;
    }

    function isLoged() {
        if (isset($_SESSION[$this->default_user]) && count($_SESSION[$this->default_user]) > 0) {
            return true;
        }
        return false;
    }

    function isFrontLoged() {

        if (isset($_SESSION[$this->front_user]) && count($_SESSION[$this->front_user]) > 0) {
            return true;
        }
        return false;
    }

    function getFrontUser() {
        if (isset($_SESSION[$this->front_user]) && count($_SESSION[$this->front_user]) > 0) {
            return $_SESSION[$this->front_user];
        }
        return false;
    }

    function getUser() {
        if (isset($_SESSION[$this->default_user]) && count($_SESSION[$this->default_user]) > 0) {
            return $_SESSION[$this->default_user];
        }
        return false;
    }

    function isAdmin() {
        return $this->getType() == 1;
    }
    function isEditor() {
        return $this->getType() == 2;
    }

    function isXHR() {
        return @$_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    function getRandomPassword($n = 6, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890') {
        srand((double) microtime() * 1000000);
        $m = strlen($chars);
        $randPassword = "";
        while ($n--) {
            $randPassword .= substr($chars, rand() % $m, 1);
        }
        return $randPassword;
    }
    
    function setcurrency($currency) {
        $_SESSION[$this->default_currency] = $currency;
    }

    function getcurrency() {
        return (!empty($_SESSION[$this->default_currency])) ? $_SESSION[$this->default_currency] : false;
    }
    
    function setDefaultProduct($str) {
        $this->default_product = $str;
        return $this;
    }

}