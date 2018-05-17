<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSStateController {

    private $_msgkey;

    function __construct() {

        self::handleRequest();

        $this->_msgkey = JSJOBSincluder::getJSModel('state')->getMessagekey();        
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'states');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_states':
                    $countryid = JSJOBSrequest::getVar('countryid');
                    if (!$countryid)
                        $countryid = $_SESSION["countryid"];
                    $_SESSION["countryid"] = $countryid;

                    JSJOBSincluder::getJSModel('state')->getAllCountryStates($countryid);
                    break;
                case 'admin_formstate':
                    $id = JSJOBSrequest::getVar('jsjobsid');
                    JSJOBSincluder::getJSModel('state')->getStatebyId($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'states');
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
        $countryid = $_SESSION["countryid"];

        $result = JSJOBSincluder::getJSModel('state')->deleteStates($ids);
        $msg = JSJOBSMessages::getMessage($result, 'state');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jsjobs_state&jsjobslt=states&countryid=" . $countryid);
        wp_redirect($url);
        die();
    }

    function publish() {
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $countryid = $_SESSION["countryid"];
        $result = JSJOBSincluder::getJSModel('state')->publishUnpublish($ids, 1); //  for publish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jsjobs_state&jsjobslt=states&countryid=" . $countryid);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function unpublish() {
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $countryid = $_SESSION["countryid"];
        $result = JSJOBSincluder::getJSModel('state')->publishUnpublish($ids, 0); //  for unpublish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jsjobs_state&jsjobslt=states&countryid=" . $countryid);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function savestate() {
        $data = JSJOBSrequest::get('post');
        $countryid = $_SESSION["countryid"];
        $result = JSJOBSincluder::getJSModel('state')->storeState($data, $countryid);
        $url = admin_url("admin.php?page=jsjobs_state&jsjobslt=states&countryid=" . $countryid);
        $msg = JSJOBSMessages::getMessage($result, 'state');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

}

$JSJOBSStateController = new JSJOBSStateController();
?>