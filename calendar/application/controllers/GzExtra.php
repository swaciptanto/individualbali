<?php

require_once CONTROLLERS_PATH . 'App.php';

class GzExtra extends App {

    var $layout = 'admin';

    function beforeFilter() {
        Object::loadFiles('Model', 'Option');
        $OptionModel = new OptionModel();
        $this->option_arr = $OptionModel->getAllPairValues();
        $this->tpl['option_arr'] = $OptionModel->getAllPairs();
        $this->tpl['option_arr_values'] = $this->option_arr;

        $this->tpl['js_format'] = Util::getJsDateFormat($this->tpl['option_arr_values']['date_format']);
        $this->tpl['iso_format'] = Util::getISODateFormat($this->tpl['option_arr_values']['date_format']);

        date_default_timezone_set($this->tpl['option_arr_values']['timezone']);

        if (!($this->isLoged() && $this->isAdmin()) && $_REQUEST['action'] != 'login') {
            $_SESSION['err'] = 2;
            Util::redirect(INSTALL_URL . "GzAdmin/login");
        }

        $this->css[] = array('file' => 'admin/gzstyling/bootstrap.min.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/gzstyling/font-awesome.min.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/gzstyling/ionicons.min.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/gzstyling/gzstyle.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/gzstyling/daterangepicker/daterangepicker-bs3.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'ui-custom.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/admin.css', 'path' => CSS_PATH);

        $this->js[] = array('file' => 'jquery/jquery-1.9.1.min.js', 'path' => LIBS_PATH);
        $this->js[] = array('file' => 'gzadmin/bootstrap.min.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'gzadmin/plugins/datatables/jquery.dataTables.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'gzadmin/plugins/datatables/dataTables.bootstrap.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'gzadmin/gzadmin/app.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'jquery.ui.core.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
        $this->js[] = array('file' => 'jquery.ui.widget.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
        $this->js[] = array('file' => 'jquery.ui.button.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
        $this->js[] = array('file' => 'jquery.ui.position.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
        $this->js[] = array('file' => 'jquery.ui.dialog.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');

        //$this->js[] = array('file' => 'ajax-upload/das.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'ajax-upload/jquery.form.js', 'path' => JS_PATH);

        $this->js[] = array('file' => 'jquery/jquery-validation-1.13.0/dist/jquery.validate.js', 'path' => LIBS_PATH);
        $this->js[] = array('file' => 'jquery/ui/jquery-ui.min.js', 'path' => LIBS_PATH);

        $this->js[] = array('file' => 'gzadmin/plugins/daterangepicker/daterangepicker.js', 'path' => JS_PATH);

        switch (@$_REQUEST['action']) {
            case 'extra':
                $this->js[] = array('file' => '/ajax-upload/jquery.form.js', 'path' => JS_PATH);
                break;
            case 'cropImage':
                $this->css[] = array('file' => 'admin/jcrop/main.css', 'path' => CSS_PATH);
                $this->css[] = array('file' => 'admin/jcrop/jquery.Jcrop.css', 'path' => CSS_PATH);
                $this->js[] = array('file' => '/jcrop/jquery.Jcrop.js', 'path' => JS_PATH);
                break;
        }

        $this->js[] = array('file' => 'admin.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'GzExtra.js', 'path' => JS_PATH);
    }

    function extra() {

        Object::loadFiles('Model', array('Extra'));
        $ExtraModel = new ExtraModel();

        $opts = array();

        $this->tpl['extras'] = $ExtraModel->getAll($opts, 'id desc');
    }

    function get_extra_table() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Extra'));
        $ExtraModel = new ExtraModel();

        $opts = array();

        $this->tpl['extras'] = $ExtraModel->getAll($opts, 'id desc');
    }

    function add_extra() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Extra'));
        $ExtraModel = new ExtraModel();

        $data = array();

        if (!empty($_FILES['img'])) {

            require_once APP_PATH . 'helpers/uploader/class.upload.php';
            $handle = new upload($_FILES['img']);

            $img_name = time();

            if ($handle->uploaded) {

                $thumb_dest = INSTALL_PATH . UPLOAD_PATH . 'extra/thumb';

                $handle->file_new_name_body = $img_name;
                $handle->image_resize = true;
                $handle->image_x = 300;
                $handle->image_ratio_y = true;
                $handle->process($thumb_dest);

                if ($handle->processed) {
                    // $handle->clean();
                }
                $data['img'] = $handle->file_dst_name;
            }
        }
        $ExtraModel->save(array_merge($_POST, $data));

        $opts = array();

        $this->tpl['extras'] = $ExtraModel->getAll($opts, 'id desc');
    }

    function get_frm_edit_extra() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Extra'));

        $ExtraModel = new ExtraModel();

        if (!empty($_REQUEST['id'])) {
            $this->tpl['extra'] = $ExtraModel->get($_REQUEST['id']);
        }
    }

    function edit() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Extra'));
        $ExtraModel = new ExtraModel();

        $data = array();

        if (!empty($_FILES['img'])) {

            require_once APP_PATH . 'helpers/uploader/class.upload.php';

            $handle = new upload($_FILES['img']);

            $img_name = time();

            if ($handle->uploaded) {

                $thumb_dest = INSTALL_PATH . UPLOAD_PATH . 'extra/thumb';

                $handle->file_new_name_body = $img_name;
                $handle->image_resize = true;
                $handle->image_x = 300;
                $handle->image_ratio_y = true;
                $handle->process($thumb_dest);

                if ($handle->processed) {
                    // $handle->clean();
                }
                $data['img'] = $handle->file_dst_name;
            }
        }

        $ExtraModel->update(array_merge($data, $_POST));

        $opts = array();

        $this->tpl['extras'] = $ExtraModel->getAll($opts, 'id desc');
    }

    function delete() {
        $this->isAjax = true;

        $id = $_REQUEST['id'];

        Object::loadFiles('Model', array('Extra'));
        $ExtraModel = new ExtraModel();

        $ExtraModel->deleteFrom($ExtraModel->getTable())
                ->where('id', $id)->execute();

        $opts = array();

        $this->tpl['extras'] = $ExtraModel->getAll($opts, 'id desc');
    }

    function cropImage() {
        $id = $_REQUEST['id'];
        Object::loadFiles('Model', array('Extra'));
        $ExtraModel = new ExtraModel();

        if (!empty($_POST['crop_img'])) {

            $extra = $ExtraModel->get($id);

            $filename = basename(INSTALL_PATH . UPLOAD_PATH . "extra/" . $_POST['type'] . '/' . $extra['img']);
            $file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));

            $img_name = time();
            $dest = INSTALL_PATH . UPLOAD_PATH . "extra/thumb/" . $extra['img'];
            $crop_dest = INSTALL_PATH . UPLOAD_PATH . "extra/thumb/" . $img_name . '.' . $file_ext;

            $x1 = $_POST["x"];
            $y1 = $_POST["y"];
            $w = $_POST["w"];
            $h = $_POST["h"];
            //Scale the image to the thumb_width set above
            Util::resizeThumbnailImage($crop_dest, $dest, $w, $h, $x1, $y1);

            $data = array();
            $data['id'] = $id;
            $data['img'] = $img_name . '.' . $file_ext;

            $ExtraModel->update($data);

            unlink($dest);

            Util::redirect(INSTALL_URL . "GzExtra/cropImage/" . $id);
        }

        $this->tpl['arr'] = $ExtraModel->get($id);
    }

    function deleteImage() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Extra'));
        $ExtraModel = new ExtraModel();

        if (!empty($_POST['id'])) {

            $id = $_POST['id'];

            $extra = $ExtraModel->get($id);

            $dest = INSTALL_PATH . UPLOAD_PATH . "extra/thumb/" . $extra['img'];
            if (is_file($dest)) {
                unlink($dest);
            }

            $data = array();
            $data['img'] = '';

            $ExtraModel->update(array_merge($_POST, $data));
        }

        $opts = array();

        $this->tpl['extras'] = $ExtraModel->getAll($opts, 'id desc');
    }

}

?>