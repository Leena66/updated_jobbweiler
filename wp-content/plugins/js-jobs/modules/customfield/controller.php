<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSCustomfieldController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('customfield')->getMessagekey();        
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'userfields');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_userfields':
                    $fieldfor = JSJOBSrequest::getVar('ff');
                    $_SESSION['ff'] = $fieldfor;
                    JSJOBSincluder::getJSModel('customfield')->getUserFields($fieldfor);
                    jsjobs::$_data['fieldfor'] = $fieldfor;
                    break;
                case 'admin_formuserfield':
                    $id = JSJOBSrequest::getVar('jsjobsid');
                    $fieldfor = JSJOBSrequest::getVar('fieldfor');
                    if (empty($fieldfor))
                        $fieldfor = JSJOBSrequest::getVar('ff');
                    if (empty($fieldfor))
                        $fieldfor = $_SESSION['ff'];

                    JSJOBSincluder::getJSModel('fieldordering')->getUserFieldbyId($id, $fieldfor);
                    if ($fieldfor == 3)
                        JSJOBSincluder::getJSModel('fieldordering')->getResumeSections($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'userfields');
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
        $fieldfor = $_SESSION['ff'];
        $result = JSJOBSincluder::getJSModel('customfield')->deleteUserFields($ids);
        $msg = JSJOBSMessages::getMessage($result, 'customfield');
        $url = admin_url("admin.php?page=jsjobs_customfield&jsjobslt=userfields&ff=" . $fieldfor);
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

}

$JSJOBSCustomfieldController = new JSJOBSCustomfieldController();
?>
