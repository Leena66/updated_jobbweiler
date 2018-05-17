<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSslugController {
    private $_msgkey;
    function __construct() {
        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('slug')->getMessagekey();        
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'slug');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_slug':
                    JSJOBSincluder::getJSModel('slug')->getSlug();
                    break;
            }
            $module = 'page';
            $module = JSJOBSrequest::getVar($module, null, 'slug');
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

    function saveSlug() {
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('slug')->storeSlug($data);
        if($data['pagenum'] > 0){
            $url = admin_url("admin.php?page=jsjobs_slug&pagenum=".$data['pagenum']);
        }else{
            $url = admin_url("admin.php?page=jsjobs_slug");
        }

        $msg = JSJOBSMessages::getMessage($result, 'slug');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        exit;
    }

    function saveprefix() {
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('slug')->savePrefix($data);
        $url = admin_url("admin.php?page=jsjobs_slug");
        $msg = JSJOBSMessages::getMessage($result, 'prefix');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        exit;
    }

    function savehomeprefix() {
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('slug')->saveHomePrefix($data);
        $url = admin_url("admin.php?page=jsjobs_slug");
        $msg = JSJOBSMessages::getMessage($result, 'prefix');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        exit;
    }

    function resetallslugs() {
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('slug')->resetAllSlugs();
        $url = admin_url("admin.php?page=jsjobs_slug");
        $msg = JSJOBSMessages::getMessage($result, 'slug');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        exit;
    }
}

$JSJOBSslugController = new JSJOBSslugController();
?>
