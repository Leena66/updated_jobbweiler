<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSCityController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('city')->getMessagekey();        
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'cities');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_cities':
                    $countryid = JSJOBSrequest::getVar('countryid');
                    $stateid = JSJOBSrequest::getVar('stateid');

                    $_SESSION["countryid"] = $countryid;
                    $_SESSION["stateid"] = $stateid;
                    JSJOBSincluder::getJSModel('city')->getAllStatesCities($countryid, $stateid);
                    break;
                case 'admin_formcity':
                    $id = JSJOBSrequest::getVar('jsjobsid');
                    JSJOBSincluder::getJSModel('city')->getCitybyId($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'cities');
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

    function getaddressdatabycityname() {
        $cityname = JSJOBSrequest::getVar('q');
        $result = JSJOBSincluder::getJSModel('city')->getAddressDataByCityName($cityname);
        $json_response = json_encode($result);
        echo $json_response;
        exit();
    }

    function removecity() {
        $countryid = $_SESSION["countryid"];
        $stateid = $_SESSION["stateid"];

        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('city')->deleteCities($ids);
        $msg = JSJOBSMessages::getMessage($result, 'city');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jsjobs_city&jsjobslt=cities&countryid=" . $countryid . "&stateid=" . $stateid);
        wp_redirect($url);
        die();
    }

    function publish() {
        $countryid = $_SESSION["countryid"];
        $stateid = $_SESSION["stateid"];

        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('city')->publishUnpublish($ids, 1); //  for publish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jsjobs_city&jsjobslt=cities&countryid=" . $countryid . "&stateid=" . $stateid);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function unpublish() {
        $countryid = $_SESSION["countryid"];
        $stateid = $_SESSION["stateid"];

        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('city')->publishUnpublish($ids, 0); //  for unpublish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jsjobs_city&jsjobslt=cities&countryid=" . $countryid . "&stateid=" . $stateid);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function savecity() {
        $countryid = $_SESSION["countryid"];
        $stateid = $_SESSION["stateid"];
        $url = admin_url("admin.php?page=jsjobs_city&jsjobslt=cities&countryid=" . $countryid . "&stateid=" . $stateid);

        $data = JSJOBSrequest::get('post');
        if ($data['stateid'])
            $stateid = $data['stateid'];
        $result = JSJOBSincluder::getJSModel('city')->storeCity($data, $countryid, $stateid);
        $msg = JSJOBSMessages::getMessage($result, 'city');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

}

$JSJOBSCityController = new JSJOBSCityController();
?>