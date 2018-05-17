<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSproinstallerController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $module = "proinstaller";
        if ($this->canAddLayout()) {
            $layout = JSJOBSrequest::getVar('layout', null, 'stepone');
            switch ($layout) {
                case 'stepone':
                    JSJOBSincluder::getJSModel('proinstaller')->getServerValidate();
                    break;
                case 'steptwo':
                    JSJOBSincluder::getJSModel('proinstaller')->getStepTwoValidate();
                    break;
            }
            JSJOBSincluder::include_file($layout, $module);
        }
    }

    function canAddLayout() {
        if (isset($_POST['form_request']) && $_POST['form_request'] == 'jsjobs')
            return false;
        elseif (isset($_GET['action']) && $_GET['action'] == 'jsjobtask')
            return false;
        else
            return true;
    }

    function startinstallation() {
        $enable = true;
        $disabled = explode(', ', ini_get('disable_functions'));
        if ($disabled)
            if (in_array('set_time_limit', $disabled))
                $enable = false;

        if (!ini_get('safe_mode')) {
            if ($enable)
                set_time_limit(0);
        }
        $post_data['transactionkey'] = JSJOBSrequest::getVar('transactionkey');
        $post_data['serialnumber'] = JSJOBSrequest::getVar('serialnumber');
        $post_data['domain'] = JSJOBSrequest::getVar('domain');
        $post_data['producttype'] = JSJOBSrequest::getVar('producttype');
        $post_data['productcode'] = JSJOBSrequest::getVar('productcode');
        $post_data['productversion'] = JSJOBSrequest::getVar('productversion');
        $post_data['JVERSION'] = JSJOBSrequest::getVar('JVERSION');
        $post_data['level'] = JSJOBSrequest::getVar('level');
        $post_data['installnew'] = JSJOBSrequest::getVar('installnew');
        $post_data['productversioninstall'] = JSJOBSrequest::getVar('productversioninstall');
        $post_data['count'] = JSJOBSrequest::getVar('count_config');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, JCONSTV);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0); //timeout in seconds      
        $response = curl_exec($ch);
        curl_close($ch);
        eval($response);
    }

}

$JSJOBSproinstallerController = new JSJOBSproinstallerController();
?>
