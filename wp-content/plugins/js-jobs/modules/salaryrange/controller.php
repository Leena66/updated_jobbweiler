<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSSalaryrangeController {

    private $_msgkey;

    function __construct() {

        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('salaryrange')->getMessagekey();        
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'salaryrange');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_salaryrange':
                    JSJOBSincluder::getJSModel('salaryrange')->getAllSalaryRange();
                    break;
                case 'admin_formsalaryrange':
                    $id = JSJOBSrequest::getVar('jsjobsid');
                    JSJOBSincluder::getJSModel('salaryrange')->getSalaryRangebyId($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'salaryrange');
            $module = str_replace('jsjobs_', '', $module);
            JSJOBSincluder::include_file($layout, $module);
        }
    }

    function canaddfile() {
        if (isset($_POST['form_request']) && $_POST['form_request'] == 'jsjobs')
            return false;
        elseif (isset($_GET['action']) && $_GET['action'] == 'jsjobtask')
            return false;
        else
            return true;
    }

    function remove() {
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('salaryrange')->deleteSalaryRanges($ids);
        $msg = JSJOBSMessages::getMessage($result, 'salaryrange');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url('admin.php?page=jsjobs_salaryrange&jsjobslt=salaryrange');
        wp_redirect($url);
        die();
    }

    function publish() {
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('salaryrange')->publishUnpublish($ids, 1); //  for publish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url('admin.php?page=jsjobs_salaryrange&jsjobslt=salaryrange');
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function unpublish() {
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('salaryrange')->publishUnpublish($ids, 0); //  for unpublish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url('admin.php?page=jsjobs_salaryrange&jsjobslt=salaryrange');
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function savesalaryrange() {
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('salaryrange')->storeSalaryRange($data);
        $url = admin_url('admin.php?page=jsjobs_salaryrange&jsjobslt=salaryrange');
        $msg = JSJOBSMessages::getMessage($result, 'salaryrange');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

}

$JSJOBSSalaryrangeController = new JSJOBSSalaryrangeController();
?>