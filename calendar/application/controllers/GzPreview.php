<?php

require_once CONTROLLERS_PATH . 'App.php';

class GzPreview extends App {

    var $layout = 'empty';

    function beforeFilter() {
        
    }

    function index() {
        Object::loadFiles('Model', array('Calendar'));
        $CalendarModel = new CalendarModel();
        
        $this->tpl['arr'] = $CalendarModel->getI18nAll();
    }
    
    function inquiry_form(){
         Object::loadFiles('Model', array('Calendar'));
        $CalendarModel = new CalendarModel();
        
        $this->tpl['arr'] = $CalendarModel->getI18nAll();
    }

}
?>

