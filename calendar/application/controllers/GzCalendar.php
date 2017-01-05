<?php

require_once CONTROLLERS_PATH . 'App.php';

class GzCalendar extends App {

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

        if (!($this->isLoged() && $this->isAdmin()) && $_REQUEST['action'] != 'login'
                && $_REQUEST['action'] != 'testSendMail') {
            $_SESSION['err'] = 2;
            Util::redirect(INSTALL_URL . "GzAdmin/login");
        }

        $this->css[] = array('file' => 'admin/gzstyling/bootstrap.min.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/gzstyling/font-awesome.min.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/gzstyling/ionicons.min.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/gzstyling/gzstyle.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'ui-custom.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/gzstyling/daterangepicker/daterangepicker-bs3.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'gzadmin/plugins/colorpicker/dist/css/bootstrap-colorpicker.min.css', 'path' => JS_PATH);
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
        $this->js[] = array('file' => 'gzadmin/plugins/daterangepicker/daterangepicker.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'jquery/tinymce/tinymce.min.js', 'path' => LIBS_PATH);
        $this->js[] = array('file' => 'jquery/jquery-validation-1.13.0/dist/jquery.validate.js', 'path' => LIBS_PATH);
        $this->js[] = array('file' => 'jquery/ui/jquery-ui.min.js', 'path' => LIBS_PATH);

        switch (@$_REQUEST['action']) {
            case 'index':
                $this->js[] = array('file' => '/ajax-upload/jquery.form.js', 'path' => JS_PATH);
                break;
            case 'edit':
                $this->js[] = array('file' => '/ajax-upload/jquery.form.js', 'path' => JS_PATH);
                break;
            case 'block':
                $this->js[] = array('file' => '/ajax-upload/jquery.form.js', 'path' => JS_PATH);
                break;
            case 'cropImage':
                $this->css[] = array('file' => 'admin/jcrop/main.css', 'path' => CSS_PATH);
                $this->css[] = array('file' => 'admin/jcrop/jquery.Jcrop.css', 'path' => CSS_PATH);

                $this->js[] = array('file' => '/jcrop/jquery.Jcrop.js', 'path' => JS_PATH);
                break;
        }
        $this->js[] = array('file' => 'gzadmin/plugins/colorpicker/dist/js/bootstrap-colorpicker.min.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'jquery/tinymce/tinymce.min.js', 'path' => LIBS_PATH);
        $this->js[] = array('file' => 'admin.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'GzCalendar.js', 'path' => JS_PATH);
    }

    function index() {

        Object::loadFiles('Model', array('Calendar', 'CalendarGallery'));
        $CalendarModel = new CalendarModel();
        $CalendarGalleryModel = new CalendarGalleryModel();

        $opts = array();

        $arr = $CalendarModel->getI18nAll($opts);
        $result = array();

        Object::loadFiles('Model', array('CalendarGallery'));
        $CalendarGalleryModel = new CalendarGalleryModel();

        foreach ($arr as $key => $value) {
            $result[$key] = $value;
            $opts = array();

            $opts['calendar_id'] = $value['id'];

            $result[$key]['gallery'] = $CalendarGalleryModel->getAll($opts);
        }
        $this->tpl['arr'] = $result;
    }

    function create() {
        //modified: add Drupal Models, load GzPrice
        require_once CONTROLLERS_PATH . 'GzPrice.php';
        
        Object::loadFiles(
                'Model',
                array(
                    'Field',
                    'Calendar',
                    'User',
                    'Option',
                    'DrupalNodeRevision',
                    'DrupalEmailOwner',
                    'DrupalEmailReservation',
                    'DrupalRateLow',
                    )
                );
        $UserModel = new UserModel();
        $CalendarModel = new CalendarModel();
        $OptionModel = new OptionModel();
        $GzPrice = new GzPrice();

        if (!empty($_POST['create_calendar'])) {
            $FieldModel = new FieldModel();
            $data = array();

            //modified: changed
            //$data['title'] = $_POST['title'][$this->tpl['default_language']['id']];
            //$data['description'] = $_POST['description'][$this->tpl['default_language']['id']];

            //modified:
            //get needed data from drupal
            $DrupalNodeRevisionModel = new DrupalNodeRevisionModel();
            $DrupalEmailOwnerModel = new DrupalEmailOwnerModel();
            $DrupalEmailReservationModel = new DrupalEmailReservationModel();
            $node_revision_data = $DrupalNodeRevisionModel->get($_POST['villa_node_id']);
            $email_owner_data = $DrupalEmailOwnerModel->get($_POST['villa_node_id']);
            $email_reservation_data = $DrupalEmailReservationModel->get($_POST['villa_node_id']);
            $_POST['villa_owner_email'] = $email_owner_data['field_email_to_villa_owner_value'];
            $_POST['villa_reservation_email'] = $email_reservation_data['field_email_reservasi_value'];
            $data['title'] = "Villa $_POST[villa_node_id] - $node_revision_data[title]";
            $data['description'] = $node_revision_data['title'];
            
            $id = $CalendarModel->save(array_merge($data, $_POST));

            if (!empty($id)) {
                $opts = array();
                $opts['calendar_id'] = $_POST['option_id'];
                $options = $OptionModel->getAll($opts, 'order');

                foreach ($options as $option) {
                    $data = array();

                    $data['key'] = $option['key'];
                    $data['tab_id'] = $option['tab_id'];
                    $data['group'] = $option['group'];
                    $data['value'] = $option['value'];
                    $data['title'] = $option['title'];
                    $data['description'] = $option['description'];
                    $data['label'] = $option['label'];
                    $data['type'] = $option['type'];
                    $data['order'] = $option['order'];
                    $data['calendar_id'] = $id;

                    $OptionModel->save($data);
                }

                $data = array();

                /* modified: always set to use language_id = 3 (english)
                $data['title'] = $_POST['title'];
                $data['description'] = $_POST['description'];
                 */
                $data['title'][3] = $node_revision_data['title'];
                $data['description'][3] = $node_revision_data['title'];

                $FieldModel->saveI18n($id, $data, $CalendarModel->getTable());
                
                /* modified: add new (auto create new price plan)
                 * data: 
                 *  - use 1 year period start from calendar created
                 *  - use low rate
                 */
                $DrupalRateLowModel = new DrupalRateLowModel();
                $rate_low = (float)$DrupalRateLowModel->get($_POST['villa_node_id'])['field_rate_low_value'];
                $price_plan_date_range_one_year = date('d.m.Y') . '|' . date('d.m.Y', strtotime('+1 year'));
                $_POST = array(
                    'calendar_id' => $id,
                    'title' => 'low season',
                    'date_range' => $price_plan_date_range_one_year,
                    'is_default' => 'T',
                    'rate' => $rate_low,
                );
                $GzPrice->add_price_plan();
            }

            if (!empty($id)) {
                $_SESSION['status'] = 1;
            } else {
                $_SESSION['status'] = 2;
            }

            Util::redirect(INSTALL_URL . "GzCalendar/index");
        }
        $this->tpl['users'] = $UserModel->getAll();
        $this->tpl['calendars'] = $CalendarModel->getI18nAll();
    }

    function edit() {
        //modified: add Drupal Models
        Object::loadFiles('Model', array('Calendar', 'Field', 'User', 'DrupalEmailReservation', 'DrupalEmailOwner'));
        $CalendarModel = new CalendarModel();
        $FieldModel = new FieldModel();
        $UserModel = new UserModel();

        $this->tpl['users'] = $UserModel->getAll();

        if (isset($_POST['edit_calendar'])) {
            //modified: add new
            $DrupalEmailOwner = new DrupalEmailOwnerModel();
            $DrupalEmailReservation = new DrupalEmailReservationModel();
            $_POST['villa_owner_email'] = $DrupalEmailOwner->get($_POST['villa_node_id'])['field_email_to_villa_owner_value'];
            $_POST['villa_reservation_email'] = $DrupalEmailReservation->get($_POST['villa_node_id'])['field_email_reservasi_value'];
            
            $data['title'] = $_POST['title'][$this->tpl['default_language']['id']];
            $data['description'] = $_POST['description'][$this->tpl['default_language']['id']];
            $CalendarModel->update(array_merge($data, $_POST));

            $data = array();

            $data['title'] = $_POST['title'];
            $data['description'] = $_POST['description'];

            $FieldModel->deleteFrom($FieldModel->getTable())
                    ->where('table_name', $CalendarModel->getTable())
                    ->where('in_id', $_POST['id'])->execute();

            $FieldModel->saveI18n($_POST['id'], $data, $CalendarModel->getTable());

            $_SESSION['status'] = 5;
            Util::redirect(INSTALL_URL . "GzCalendar/index");
        } else {
            $id = $_GET['id'];

            Object::loadFiles('Model', array('CalendarGallery'));
            $CalendarGalleryModel = new CalendarGalleryModel();

            $arr = $CalendarModel->getI18n($id);

            if (count($arr) === 0) {
                $_SESSION['status'] = 8;
                Util::redirect(INSTALL_URL . "GzCalendar/index");
            }
            $this->tpl['arr'] = $arr;
            $this->tpl['gallery'] = $CalendarGalleryModel->getAll(array('calendar_id' => $id), 'date asc');
        }
    }

    //modified: add parameter
    function delete($id = '') {
        //modified: change related to parameter add
        $return_function = false;
        if ($id > 0) {
            $return_function = true;
        } else {
            $this->isAjax = true;
            $id = $_REQUEST['id'];
        }

        //modified: add logic
        if (!empty($id) && $id > 0) {
            //modified: add to also delete from price plan and calendar blocking
            Object::loadFiles('Model', array('Calendar', 'Field', 'Option', 'Price', 'Blocking', 'CalendarGallery'));
            $CalendarModel = new CalendarModel();
            $FieldModel = new FieldModel();
            $OptionModel = new OptionModel();
            $PriceModel = new PriceModel();
            $BlockingModel = new BlockingModel();
            $CalendarGalleryModel = new CalendarGalleryModel();

            $CalendarModel->deleteFrom($CalendarModel->getTable())
                    ->where('id', $id)->execute();

            $OptionModel->deleteFrom($OptionModel->getTable())
                    ->where('calendar_id', $id)->execute();

            $FieldModel->deleteFrom($FieldModel->getTable())
                    ->where('in_id', $id)->where('table_name', $CalendarModel->getTable())->execute();

            $PriceModel->deleteFrom($PriceModel->getTable())
                    ->where('calendar_id', $id)->execute();

            $BlockingModel->deleteFrom($BlockingModel->getTable())
                    ->where('calendar_id', $id)->execute();
            
            //modified: add, also delete from gallery
            $calendars_gallery_ids = $CalendarGalleryModel->from($CalendarGalleryModel->getTable())
                    ->where('calendar_id', $id)->fetchAll();
            if (count($calendars_gallery_ids) > 0) {
                foreach ($calendars_gallery_ids as $calendars_gallery_id) {
                    $this->deleteImage($calendars_gallery_id);
                }
            }

            $opts = array();

            $arr = $CalendarModel->getAll($opts, 'date asc');

        }
        //modified: change related to parameter add
        if ($return_function) {
            return true;
        } else {
            $this->index();
        }
    }

    function deleteSelected() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Calendar', 'Field', 'Option'));
        $CalendarModel = new CalendarModel();
        $FieldModel = new FieldModel();
        $OptionModel = new OptionModel();

        if (!empty($_POST['mark'])) {
            /* modified: delete to use one same function delete()
            $CalendarModel->deleteFrom($CalendarModel->getTable())
                    ->where('id', $_POST['mark'])->execute();

            $OptionModel->deleteFrom($OptionModel->getTable())
                    ->where('calendar_id', $_POST['mark'])->execute();

            $FieldModel->deleteFrom($FieldModel->getTable())
                    ->where('in_id', $_POST['mark'])->where('table_name', $FieldModel->getTable())->execute();
             */
            foreach ($_POST['mark'] as $calendar_id) {
                $this->delete($calendar_id);
            }
        }

        $this->index();
    }

    function uploadImage() {
        $this->isAjax = true;

        require_once APP_PATH . 'helpers/uploader/class.upload.php';

        $handle = new upload($_FILES['image']);

        if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

            $thumb_dest = INSTALL_PATH . UPLOAD_PATH . 'calendars/thumb';
            $medium_dest = INSTALL_PATH . UPLOAD_PATH . 'calendars/medium';
            $preview_dest = INSTALL_PATH . UPLOAD_PATH . 'calendars/preview';
            $original_dest = INSTALL_PATH . UPLOAD_PATH . 'calendars/original';

            $img_name = time();

            if ($handle->uploaded) {

                $handle->file_new_name_body = $img_name;
                $handle->image_resize = true;
                $handle->image_x = 600;
                $handle->image_ratio_y = true;
                $handle->process($medium_dest);

                if ($handle->processed) {
// $handle->clean();
                }

                $handle->file_new_name_body = $img_name;
                $handle->image_resize = true;
                $handle->image_x = 800;
                $handle->image_ratio_y = true;
                $handle->process($preview_dest);

                if ($handle->processed) {
// $handle->clean();
                }

                $handle->file_new_name_body = $img_name;
                $handle->image_resize = false;
                $handle->process($original_dest);

                if ($handle->processed) {
// $handle->clean();
                }

                $handle->file_new_name_body = $img_name;
                $handle->image_resize = true;
                $handle->image_x = 300;
                $handle->image_ratio_y = true;
                $handle->process($thumb_dest);
                if ($handle->processed) {

                    Object::loadFiles('Model', array('CalendarGallery'));
                    $CalendarGalleryModel = new CalendarGalleryModel();

                    $data = array();
                    $data['calendar_id'] = $_GET['id'];
                    $data['thumb'] = $handle->file_dst_name;
                    $data['preview'] = $handle->file_dst_name;
                    $data['original'] = $handle->file_dst_name;
                    $data['medium'] = $handle->file_dst_name;
                    $data['name'] = $img_name;

                    $CalendarGalleryModel->save($data);

                    $gallery = $CalendarGalleryModel->getAll(array('calendar_id' => $_GET['id']), 'date asc');

                    $this->tpl['gallery'] = $gallery;

                    $handle->clean();
                } else {
                    echo 'error : ' . $handle->error;
                }
            }
        }
    }

    function cropImage() {
        $id = $_GET['id'];
        Object::loadFiles('Model', array('CalendarGallery'));
        $CalendarGalleryModel = new CalendarGalleryModel();

        if (!empty($_POST['crop_img'])) {

            $img_arr = $CalendarGalleryModel->get($id);

            $filename = basename(INSTALL_PATH . UPLOAD_PATH . "calendars/" . $_POST['type'] . '/' . $img_arr[$_POST['type']]);
            $file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));

            $img_name = time();
            $dest = INSTALL_PATH . UPLOAD_PATH . "calendars/" . $_POST['type'] . '/' . $img_arr[$_POST['type']];
            $crop_dest = INSTALL_PATH . UPLOAD_PATH . "calendars/" . $_POST['type'] . '/' . $img_name . '.' . $file_ext;

            $x1 = $_POST["x"];
            $y1 = $_POST["y"];
            $w = $_POST["w"];
            $h = $_POST["h"];
//Scale the image to the thumb_width set above
            Util::resizeThumbnailImage($crop_dest, $dest, $w, $h, $x1, $y1);

            $data = array();
            $data['id'] = $id;
            $data[$_POST['type']] = $img_name . '.' . $file_ext;

            $CalendarGalleryModel->update($data);

            unlink($dest);
        }

        $this->tpl['arr'] = $CalendarGalleryModel->get($id);
    }

    //modified: add parameter
    function deleteImage($id = '') {
        //modified: change related to parameter add
        $return_function = false;
        if ($id > 0) {
            $return_function = true;
        } else {
            $this->isAjax = true;
            $id = $_REQUEST['id'];
        }

        Object::loadFiles('Model', array('CalendarGallery'));
        $CalendarGalleryModel = new CalendarGalleryModel();

        //modified: add logic
        if (!empty($id) && $id > 0) {
            //modified: comment
            //$id = $_REQUEST['id'];
            $img_arr = $CalendarGalleryModel->get($id);

            $dest = INSTALL_PATH . UPLOAD_PATH . "calendars/medium/" . $img_arr['medium'];
            if (is_file($dest)) {
                unlink($dest);
            }
            //modified: fix from .../thumb to .../thumb/
            $dest = INSTALL_PATH . UPLOAD_PATH . "calendars/thumb/" . $img_arr['thumb'];
            if (is_file($dest)) {
                unlink($dest);
            }
            //modified: fix from .../preview to .../preview/
            $dest = INSTALL_PATH . UPLOAD_PATH . "calendars/preview/" . $img_arr['preview'];
            if (is_file($dest)) {
                unlink($dest);
            }
            /* modified:
             * - fix from .../original to .../original/
             * - fix from $img_arr['preview'] to $img_arr['original']
             */
            $dest = INSTALL_PATH . UPLOAD_PATH . "calendars/original/" . $img_arr['original'];
            if (is_file($dest)) {
                unlink($dest);
            }

            $CalendarGalleryModel->deleteFrom($CalendarGalleryModel->getTable())
                    ->where('id', $id)->execute();
        }

        //modified: change related to parameter add
        if ($return_function) {
            return true;
        } else {
            $gallery = $CalendarGalleryModel->getAll(array('calendar_id' => $_REQUEST['calendar_id']), 'date asc');
            $this->tpl['gallery'] = $gallery;
        }
    }

    function block() {
        Object::loadFiles('Model', array('Blocking', 'Calendar'));
        $BlockingModel = new BlockingModel();
        $CalendarModel = new CalendarModel();

        $opts = array();

        $this->tpl['arr'] = $BlockingModel->getBlockings($opts);
        $this->tpl['calendars'] = $CalendarModel->getI18nAll();
    }

    function add_blocking() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Blocking', 'Calendar'));
        $BlockingModel = new BlockingModel();
        $CalendarModel = new CalendarModel();

        if (!empty($_POST['create_blocking'])) {

            $data = array();

            $_POST['date_range'] = str_replace(' - ', '|', $_POST['date_range']);
            $date = explode('|', $_POST['date_range']);

            if (!empty($date['0']) && !empty($date['1'])) {
                $data['from_date'] = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $date['0']);
                $data['to_date'] = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $date['1']);
            }
            $data['calendar_id'] = $_POST['calendar_id'];
            $BlockingModel->save($data);
        }

        $opts = array();

        $this->tpl['arr'] = $BlockingModel->getBlockings($opts);
        $this->tpl['caledanrs'] = $CalendarModel->getI18nAll();
    }

    function delete_block() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Blocking', 'Calendar'));
        $BlockingModel = new BlockingModel();
        $CalendarModel = new CalendarModel();

        $BlockingModel->deleteFrom($BlockingModel->getTable())
                ->where('id', $_POST['id'])->execute();

        $opts = array();

        $this->tpl['arr'] = $BlockingModel->getBlockings($opts);

        $this->tpl['caledanrs'] = $CalendarModel->getAll();
    }

    function edit_block() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Blocking', 'Calendar'));
        $BlockingModel = new BlockingModel();
        $CalendarModel = new CalendarModel();

        if (!empty($_REQUEST['eidt_blocking'])) {

            $_POST['date_range'] = str_replace(' - ', '|', $_POST['date_range']);
            $date = explode('|', $_POST['date_range']);

            if (!empty($date['0']) && !empty($date['1'])) {

                $data['from_date'] = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $date['0']);
                $data['to_date'] = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $date['1']);
            }

            $BlockingModel->update(array_merge($_POST, $data));
        }

        $opts = array();

        $this->tpl['arr'] = $BlockingModel->getBlockings($opts);
        $this->tpl['calendars'] = $CalendarModel->getAll();
    }

    function get_frm_edit_block() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Blocking', 'Calendar'));
        $BlockingModel = new BlockingModel();
        $CalendarModel = new CalendarModel();

        $opts = array();
        $opts['t1.id'] = $_POST['id'];

        $arr = $BlockingModel->get($_POST['id']);

        $this->tpl['block'] = $arr;
        $this->tpl['calendars'] = $CalendarModel->getI18nAll();
        $this->tpl['calendar_id_arr'][] = $this->tpl['block']['calendar_id'];
    }

    function deleteSelectedBlock() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Blocking', 'Calendar'));
        $BlockingModel = new BlockingModel();
        $CalendarModel = new CalendarModel();

        if (!empty($_POST['mark'])) {

            $BlockingModel->deleteFrom($BlockingModel->getTable())
                    ->where('id', $_POST['mark'])->execute();
        }

        $this->block();
    }

    function settings() {
        $opts = array();

        Object::loadFiles('Model', array('Option'));
        $OptionModel = new OptionModel();

        if (isset($_POST['update_option'])) {

            $_arr = $OptionModel->getAll();
            $options = array();

            foreach ($_arr as $key => $value) {
                $options[$value['key']] = $value;
            }

            foreach ($_POST as $key => $value) {

                if (array_key_exists($key, $options)) {
                    $sql_value = $OptionModel->escape($value, null, $options[$key]['type']);

                    $query = "UPDATE `" . $OptionModel->getTable() . "` SET `value` = '$sql_value' WHERE `key` = '$key' AND `calendar_id` = '" . $_GET['id'] . "' LIMIT 1";
                    $pdo = $OptionModel->getPdo();

                    $stmt = $pdo->prepare($query);

                    $stmt->execute();
                    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
            }

            $_SESSION['status'] = 29;
        }

        $opts = array();

        $opts['tab_id'] = 1;
        $opts['calendar_id'] = $_GET['id'];

        $this->tpl['general'] = $OptionModel->getAll($opts, 'order');

        $query = $OptionModel->from($OptionModel->getTable())
                ->where(array('`calendar_id` = ?' => $_GET['id']))
                ->where(array('`tab_id` = ?' => 2))
                ->where(array('`group`= ?' => 'options'))
                ->orderBy("`order`");

        $this->tpl['booking']['options'] = $query->fetchAll();

        $query = $OptionModel->from($OptionModel->getTable())
                ->where(array('`calendar_id` = ?' => $_GET['id']))
                ->where(array('`tab_id` = ?' => 2))
                ->where(array('`group` = ?' => 'booking_form'))
                ->orderBy("`order`");

        //echo $query->getQuery();
        $this->tpl['booking']['booking_form'] = $query->fetchAll();

        $query = $OptionModel->from($OptionModel->getTable())
                ->where(array('`calendar_id` = ?' => $_GET['id']))
                ->where(array('`tab_id` = ?' => 3))
                ->orderBy("`order`");

        $this->tpl['payment'] = $query->fetchAll();

        $query = $OptionModel->from($OptionModel->getTable())
                ->where(array('`calendar_id` = ?' => $_GET['id']))
                ->where(array('`tab_id` = ?' => 4))
                ->where(array('`group` = ?' => 'client'))
                ->orderBy("`order`");

        $this->tpl['emails']['client'] = $query->fetchAll();

        $query = $OptionModel->from($OptionModel->getTable())
                ->where(array('`calendar_id` = ?' => $_GET['id']))
                ->where(array('`tab_id` = ?' => 4))
                ->where(array('`group` = ?' => 'admin'))
                ->orderBy("`order`");

        $this->tpl['emails']['admin'] = $query->fetchAll();

        $query = $OptionModel->from($OptionModel->getTable())
                ->where(array('`calendar_id` = ?' => $_GET['id']))
                ->where(array('`tab_id` = ?' => 4))
                ->where(array('`group` = ?' => 'owner'))
                ->orderBy("`order`");

        $this->tpl['emails']['owner'] = $query->fetchAll();

        $query = $OptionModel->from($OptionModel->getTable())
                ->where(array('`calendar_id` = ?' => $_GET['id']))
                ->where(array('`tab_id` = ?' => 5))
                ->orderBy("`order`");

        $this->tpl['terms'] = $query->fetchAll();

        $query = $OptionModel->from($OptionModel->getTable())
                ->where(array('`calendar_id` = ?' => $_GET['id']))
                ->where(array('`tab_id` = ?' => 6))
                ->where(array('`group` = ?' => 'template'))
                ->orderBy("`order`");

        $this->tpl['invoice']['template'] = $query->fetchAll();

        $query = $OptionModel->from($OptionModel->getTable())
                ->where(array('`calendar_id` = ?' => $_GET['id']))
                ->where(array('`tab_id` = ?' => 6))
                ->where(array('`group` = ?' => 'company'))
                ->orderBy("`order`");

        $this->tpl['invoice']['company'] = $query->fetchAll();

        $query = $OptionModel->from($OptionModel->getTable())
                ->where(array('`calendar_id` = ?' => $_GET['id']))
                ->where(array('`tab_id` = ?' => 7))
                ->orderBy("`order`");

        $this->tpl['appearance']['options'] = $query->fetchAll();
    }

    function view() {
        $this->layout = 'empty';
    }

    function import() {
        if(!empty($_FILES['ics_file'])){
            
            Object::loadFiles('Model', array('Booking'));
            $BookingModel = new BookingModel();
        
            require_once APP_PATH . '/helpers/iCalReader/class.iCalReader.php';
            
            $filename = "booking_" . time() . $_FILES['ics_file']['name'];
            
            $path = INSTALL_PATH . 'iCal/' . $filename;

            if (move_uploaded_file($_FILES['ics_file']['tmp_name'], $path)) {
            
                $ical = new ical();
                
                $url = INSTALL_URL . 'iCal/' . $filename;
                $result = $ical->get_fcontent($url);

                $events = $ical->events();

                if (count($events)) {

                    foreach ($events as $event) {
                        if (!empty($event['DTSTART']) && !empty($event['DTEND'])) {

                            $data = array();
                            $data['date_from'] = $ical->iCalDateToUnixTimestamp($event['DTSTART']);
                            $data['date_to'] = $ical->iCalDateToUnixTimestamp($event['DTEND']);
                            $data['calendar_id'] = $_GET['id'];
                            $data['status'] = 'pending';
                            $data['booking_number'] = Util::incrementalHash(10);

                            if (!empty($event['DESCRIPTION_array'])) {
                                $arr = array();
                                $arr = explode('\n', $event['DESCRIPTION_array'][2]);
                                $data['phone'] = $arr[0];
                                $data['email'] = trim(str_replace("EMAIL:", "", $arr[1]));
                            }
                            if (!empty($event['SUMMARY']) && $event['SUMMARY'] != 'Not available') {
                                $data['first_name'] = $event['SUMMARY'];

                                $data['email'] = str_replace('EMAIL:', '', $data['email']);
                                $data['email'] = preg_replace('/EMAIL:/', '', $data['email']);
                                $data['email'] = trim($data['email']);

                                $id = $BookingModel->save($data);


                            }
                        }
                    }
                }
            }
        
            Util::redirect(INSTALL_URL . "GzBooking/index");
        }
    }
    
    function export(){
        $this->isAjax = true;
        
        Object::loadFiles('Model', array('Booking', 'Calendar', 'Blocking'));
        $BookingModel = new BookingModel();
        $BlockingModel = new BlockingModel();
        $CalendarModel = new CalendarModel();
        
        $calendar = $CalendarModel->getI18n($_GET['id']);
            
        $name = INSTALL_URL . 'ical/' . $_GET['id'] . '/export.ics';
        
        require_once APP_PATH . '/helpers/iCalReader/class.iCalReader.php';
        
        $ical = new ical();
        
        $opts = array();
        $opts['calendar_id'] = $_GET['id'];
        $bookings = $BookingModel->getAll($opts);

        $export_arr = array();
        foreach ($bookings as $key => $booking) {
            if (!empty($booking['date_from']) && !empty($booking['date_to'])) {
                $export_arr[$key]['description'] = "From " . date('d.m.Y', $booking['date_from']) . " to " . date('d.m.Y', $booking['date_to']) . "";
                $export_arr[$key]['summary'] = "From " . date('d.m.Y', $booking['date_from']) . " to " . date('d.m.Y', $booking['date_to']) . "";
                $export_arr[$key]['location'] = '';
                $export_arr[$key]['date_from'] = date('Y-m-d H:i', $booking['date_from']);
                $export_arr[$key]['date_to'] = date('Y-m-d H:i', $booking['date_to']);
                $export_arr[$key]['booking_number'] = $booking['booking_number'];
            }
        }
        $opts = array();
        $opts['calendar_id'] = $_GET['id'];
        $blokings = $BlockingModel->getAll($opts);

        foreach ($blokings as $key => $block) {
            if (!empty($block['from_date']) && !empty($block['to_date'])) {
                $export_arr[$key]['description'] = "LOCK";
                $export_arr[$key]['summary'] = "LOCK";
                $export_arr[$key]['location'] = '';
                $export_arr[$key]['date_from'] = date('Y-m-d H:i', $block['from_date']);
                $export_arr[$key]['date_to'] = date('Y-m-d H:i', $block['to_date']);
                $export_arr[$key]['booking_number'] = $block['booking_number'];
            }
        }
        
        $ical->export($export_arr, array(), $name, $calendar['i18n'][$this->tpl['default_language']['id']]['title']);
        //$ical->show();
        $ical->save($_GET['id']);
        
        Util::redirect($name);
    }

    //modified: add new
    function autoImportICSForBlocking()
    {
        echo 'Start at ' . date('d-m-Y H:i:s') . '<br/>';
        Object::loadFiles('Model', array('Calendar', 'Blocking', 'DrupalICal', 'DrupalFileManaged'));
        $CalendarModel = new CalendarModel();
        $calendar_villa_datas = $CalendarModel
                ->from($CalendarModel->getTable())
                ->where('villa_node_id > ?', 0)
                ->fetchAll();
        $blocked_added = array();
        if (is_array($calendar_villa_datas) && count($calendar_villa_datas) > 0) {
            $DrupalICalModel = new DrupalICalModel();
            $DrupalFileManagedModel = new DrupalFileManagedModel();
            $BlockingModel = new BlockingModel();
            //loop all calendar villa
            foreach ($calendar_villa_datas as $calendar_villa_data) {
                $ical_file_id = $DrupalICalModel
                        ->get($calendar_villa_data['villa_node_id'])['field_ical_fid'];
                if ($ical_file_id > 0) {
                    $ical_file_uri = $DrupalFileManagedModel->get($ical_file_id)['uri'];
                    $tmp = explode('/', $ical_file_uri);
                    end($tmp);
                    $ics_filename = $tmp[key($tmp)];
                    if ($ics_filename !== '') {
                        $blocked_added[$ics_filename] = array();
                        $events = $this->icalGetEventsDate($ics_filename);
                        $blocked_added[$ics_filename][$calendar_villa_data['title']] = 0;
                        if (count($events) > 0) {
                            foreach ($events as $event) {
                                if ($BlockingModel->from($BlockingModel->getTable())
                                        ->where(array(
                                            'calendar_id' => $calendar_villa_data['id'],
                                            'from_date' => $event['date_from'],
                                            'to_date' => $event['date_to'],
                                        ))->count() == 0) {
                                    $data_blocking = array(
                                        'from_date' => $event['date_from'],
                                        'to_date' => $event['date_to'],
                                        'calendar_id' => $calendar_villa_data['id'],
                                    );
                                    $BlockingModel->save($data_blocking);
                                    $blocked_added[$ics_filename][$calendar_villa_data['title']]++;
                                }
                            }
                        }
                    }
                }
            }
        }
        if (count($blocked_added) > 0) {
            echo '<h3>ICS Import for Blocking Date Status:</h3>';
            foreach ($blocked_added as $ics_filename => $dts) {
                echo "<strong>File: $ics_filename</strong><br/>";
                foreach ($dts as $villa_title => $total) {
                    echo "&bull;&nbsp;$villa_title: $total event(s) added!<br/>";
                }
            }
        } else {
            echo 'No blocking date found could be added!';
        }
        echo '<br/>Done at ' . date('d-m-Y H:i:s');
        exit();
    }
    
    //modified: add new
    function icalGetEventsDate($ical_filename_drupal)
    {
        //$ical_filename_drupal = '4mu1aaxodluwskipyvzmp8edaspjqdwj.ics';
        require_once APP_PATH . '/helpers/iCalReader/class.iCalReader.php';
        $result = array();
        $url_file_ical = DRUPAL_URL . '/sites/default/files/ical/' . $ical_filename_drupal;
        $file_headers = @get_headers($url_file_ical);
        if ($file_headers[0] !== 'HTTP/1.0 404 Not Found'){
            $ical = new ical();
            $ics_data = $ical->get_fcontent($url_file_ical);
            if ($ics_data !== false) {
                $events = $ical->events();
                if (is_array($events) && count($events) > 0) {
                    $total_events = count($events);
                    $ev = array();
                    $n_event = 0;
                    foreach ($events as $event) {
                        $n_event++;
                        if (!empty($event['DTSTART']) && !empty($event['DTEND'])) {
                            $dtstart_timestamp = $ical->iCalDateToUnixTimestamp($event['DTSTART']);
                            $dtend_timestamp = $ical->iCalDateToUnixTimestamp($event['DTEND']);
                            if (count($ev) == 0) {
                                $ev['date_from'] = $dtstart_timestamp;
                                $ev['date_to'] = $dtend_timestamp;
                            } elseif ($ev['date_to'] == $dtstart_timestamp) {
                                $ev['date_to'] = $dtend_timestamp;
                            } elseif ($ev['date_to'] !== $dtstart_timestamp) {
                                $result[] = $ev;
                                $ev['date_from'] = $dtstart_timestamp;
                                $ev['date_to'] = $dtend_timestamp;
                            }
                            if ($total_events == 1 || $n_event == $total_events) {
                                $result[] = $ev;
                                $ev = array();
                            } else {
                                continue;
                            }
                            //$result[] = array('date_from' => $dtstart_timestamp, 'date_to' => $dtend_timestamp);
                        }
                    }
                }
            }
        }
        return $result;
    }
    
    /**
     * modified: added new
     * Get Exchange Rate from API to be saved on flat file
     */
    function refreshExchangeRate()
    {
        $result = array();
        $currency_from = 'USD';
        $currency_tos = array(
            'AUD',
            'CAD',
            'CZK',
            'DKK',
            'EUR',
            'HKD',
            'HUF',
            'IDR',
            'ILS',
            'JPY',
            'MXN',
            'NOK',
            'NZD',
            'PHP',
            'PLN',
            'GBP',
            'SGD',
            'SEK',
            'CHF',
            'TWD',
            'THB'
            );
        foreach ($currency_tos as $currency_to) {
            $url_target = 'http://query.yahooapis.com/v1/public/yql'
                . '?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20'
                . '%28%22' . $currency_from . $currency_to . '%22%29'
                . '&format=json&env=store://datatables.org/alltableswithkeys';
            $json = file_get_contents($url_target);
            //$json = '{"query":{"count":1,"created":"2016-12-28T04:20:49Z","lang":"en-US","results":{"rate":{"id":"USDIDR","Name":"USD/IDR","Rate":"13448.0000","Date":"12/28/2016","Time":"2:41am","Ask":"13548.0000","Bid":"13448.0000"}}}}';
            $decoded = json_decode($json);
            if (is_object($decoded->query->results->rate)) {
                $result["$currency_from$currency_to"] = (float)$decoded->query->results->rate->Rate;
            }
        }
        if (count($result) > 0) {
            $fc  = '<?php' . "\n";
            $fc .= '$exchange_rate = array();' . "\n";
            $fc .= '$exchange_rate["USDUSD"] = 1;' . "\n";
            foreach($result as $ckey => $cval) {
                $fc .= '$exchange_rate[' . "'$ckey'" . '] = ' . "'$cval';\n";
            }
            $fc .= '?>';
            
            $exchange_rate_filename = CONFIG_PATH . 'exchange.rate.php';
            if (!file_exists($exchange_rate_filename)) {
                $handle = fopen($exchange_rate_filename, 'w');
                fclose($handle);
            }
            file_put_contents($exchange_rate_filename, $fc);
        }
        echo "Done";
        exit;
    }
    
    function testSendMail()
    {
        require_once APP_PATH . '/helpers/PHPMailer_5.2.4/class.phpmailer.php';
        
        /*
        //PHPMailer Object
        $mail = new PHPMailer;

        $mail->IsSMTP();
        $mail->Host = "mail.individualbali.com";
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true; 
        $mail->Username = 'admin@individualbali.com';
        $mail->Password = 'Rian&Dewa';

        //From email address and name
        $mail->From = "admin@individualbali.com";
        $mail->FromName = "Admin Individual Bali Hospitality";

        //To address and name
        //$mail->addAddress("recepient1@example.com", "Recepient Name");
        $mail->addAddress("swaciptanto@gmail.com", "Dewa Sudarmoko Aji"); //Recipient name is optional

        //Address to which recipient will reply
        //$mail->addReplyTo("reply@yourdomain.com", "Reply");

        //CC and BCC
        //$mail->addCC("cc@example.com");
        //$mail->addBCC("bcc@example.com");

        //Send HTML or Plain Text email
        $mail->isHTML(true);

        $mail->Subject = "Subject Text";
        $mail->Body = "<i>Mail body in HTML</i>";
        $mail->AltBody = "This is the plain text version of the email content";
         * 
         */
        
        $mail = new PHPMailer(true); //New instance, with exceptions enabled
        $mail->CharSet = "UTF-8";
        //$mail->IsSendmail();  // tell the class to use Sendmail
        //modified: use SMTP
        $mail->IsSMTP();
        $mail->Host = "mail.individualbali.com";
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->Username = 'admin@individualbali.com';
        $mail->Password = 'Rian&Dewa';
        $mail->AddReplyTo('admin@individualbali.com', "Admin");
        $mail->From = 'admin@individualbali.com';
        $mail->FromName = 'admin@individualbali.com';
        $to = $to;
        $mail->AddAddress($to);
        $mail->Subject = "Test Send Mail Using SMTP";
        $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
        $mail->WordWrap = 80; // set word wrap
        $mail->MsgHTML("Test Message");
        $mail->IsHTML(true); // send as HTML

        if(!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message has been sent successfully";
        }
        die("Done");
    }
}

?>