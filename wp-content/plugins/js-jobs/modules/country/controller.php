<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBScountryController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('country')->getMessagekey();        
    }

    function handleRequest() {

        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'countries');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_countries':
                    JSJOBSincluder::getJSModel('country')->getAllCountries();
                    break;
                case 'admin_formcountry':
                    $id = JSJOBSrequest::getVar('jsjobsid');
                    JSJOBSincluder::getJSModel('country')->getCountrybyId($id);
                    break;
            }

            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'countries');
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
        $result = JSJOBSincluder::getJSModel('country')->deleteCountries($ids);
        $msg = JSJOBSMessages::getMessage($result, 'country');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jsjobs_country&jsjobslt=countries");
        wp_redirect($url);
        die();
    }

    function publish() {
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('country')->publishUnpublish($ids, 1); //  for publish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jsjobs_country&jsjobslt=countries");
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function unpublish() {
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('country')->publishUnpublish($ids, 0); //  for unpublish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jsjobs_country&jsjobslt=countries");
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function savecountry() {

        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('country')->storeCountry($data);
        $url = admin_url("admin.php?page=jsjobs_country&jsjobslt=countries");
        $msg = JSJOBSMessages::getMessage($result, 'country');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

}

$JSJOBScountry = new JSJOBScountryController();
?>