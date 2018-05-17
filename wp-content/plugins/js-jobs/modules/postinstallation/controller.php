<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSpostinstallationController {

    function __construct() {

        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'stepone');
        if($this->canaddfile()){
            switch ($layout) {
                case 'admin_stepone':
                    JSJOBSincluder::getJSModel('postinstallation')->getConfigurationValues();
                break;
                case 'admin_steptwo':
                    JSJOBSincluder::getJSModel('postinstallation')->getConfigurationValues();
                break;
                case 'admin_stepthree':
                    JSJOBSincluder::getJSModel('postinstallation')->getConfigurationValues();
                break;
                case 'admin_themedemodata':
                    jsjobs::$_data['flag'] = JSJOBSrequest::getVar('flag');
                break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'postinstallation');
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

    function save(){
        $data = JSJOBSrequest::get('post');
        $url = admin_url("admin.php?page=jsjobs_postinstallation&jsjobslt=steptwo");
        $result = JSJOBSincluder::getJSModel('postinstallation')->storeconfigurations($data);
        if($data['step'] == 2){
            $url = admin_url("admin.php?page=jsjobs_postinstallation&jsjobslt=stepthree");
        }
        if($data['step'] == 3){
            $url = admin_url("admin.php?page=jsjobs_postinstallation&jsjobslt=stepfour");
        }
        wp_redirect($url);
        exit();
    }

    function savesampledata(){
        $data = JSJOBSrequest::get('post');
        $sampledata = $data['sampledata'];
        $temp_data = 0;
        if(isset($data['temp_data'])){
            $temp_data = 1;
            $jsmenu = 0;
            $empmenu = 0;
        }else{
            $jsmenu = $data['jsmenu'];
            $empmenu = $data['empmenu'];
        }
        $url = admin_url("admin.php?page=jsjobs");
        $result = JSJOBSincluder::getJSModel('postinstallation')->installSampleData($sampledata,$jsmenu,$empmenu,$temp_data);
        wp_redirect($url);
        exit();
    }

    function savetemplatesampledata(){
        $flag = JSJOBSrequest::getVar('flag');
        $result = JSJOBSincluder::getJSModel('postinstallation')->installSampleDataTemplate($flag);
        $url = admin_url("admin.php?page=jsjobs_postinstallation&jsjobslt=themedemodata&flag=".$result);
        wp_redirect($url);
        exit();
    }
}
$JSJOBSpostinstallationController = new JSJOBSpostinstallationController();
?>
