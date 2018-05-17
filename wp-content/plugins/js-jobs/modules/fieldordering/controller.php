<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSfieldorderingController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('fieldordering')->getMessagekey();        
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'fieldsordering');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_fieldsordering':
                    $fieldfor = JSJOBSrequest::getVar('ff');
                    jsjobs::$_data['fieldfor'] = $fieldfor;
                    JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrdering($fieldfor);
                    break;
                case 'admin_searchfields':
                    $fieldfor = JSJOBSrequest::getVar('fieldfor','',2);
                    jsjobs::$_data['fieldfor'] = $fieldfor;
                    JSJOBSincluder::getJSModel('fieldordering')->getSearchFieldsOrdering($fieldfor);
                    break;

                case 'admin_formuserfield':
                    $id = JSJOBSrequest::getVar('jsjobsid');
                    $fieldfor = JSJOBSrequest::getVar('ff');
                    if (empty($fieldfor)){
                        $fieldfor = jsjobs::$_data['fieldfor'];
                    }else{
                        jsjobs::$_data['fieldfor'] = $fieldfor;
                    }
                    jsjobs::$_data[0]['fieldfor'] = $fieldfor;
                    JSJOBSincluder::getJSModel('fieldordering')->getUserFieldbyId($id, $fieldfor);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'fieldordering');
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

    function fieldrequired() {
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $fieldfor = JSJOBSrequest::getVar('ff');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('fieldordering')->fieldsRequiredOrNot($ids, 1); // required
        $msg = JSJOBSMessages::getMessage($result, 'fieldordering');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url('admin.php?page=jsjobs_fieldordering&jsjobslt=fieldsordering&ff=' . $fieldfor);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function fieldnotrequired() {
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $fieldfor = JSJOBSrequest::getVar('ff');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('fieldordering')->fieldsRequiredOrNot($ids, 0); // notrequired
        $msg = JSJOBSMessages::getMessage($result, 'fieldordering');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url('admin.php?page=jsjobs_fieldordering&jsjobslt=fieldsordering&ff=' . $fieldfor);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function fieldpublished() {
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $fieldfor = JSJOBSrequest::getVar('ff');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('fieldordering')->fieldsPublishedOrNot($ids, 1);
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url('admin.php?page=jsjobs_fieldordering&jsjobslt=fieldsordering&ff=' . $fieldfor);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function fieldunpublished() {
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $fieldfor = JSJOBSrequest::getVar('ff');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('fieldordering')->fieldsPublishedOrNot($ids, 0);
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url('admin.php?page=jsjobs_fieldordering&jsjobslt=fieldsordering&ff=' . $fieldfor);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function visitorfieldpublished() {
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $fieldfor = JSJOBSrequest::getVar('ff');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('fieldordering')->visitorFieldsPublishedOrNot($ids, 1);
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url('admin.php?page=jsjobs_fieldordering&jsjobslt=fieldsordering&ff=' . $fieldfor);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function visitorfieldunpublished() {
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $fieldfor = JSJOBSrequest::getVar('ff');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('fieldordering')->visitorFieldsPublishedOrNot($ids, 0);
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url('admin.php?page=jsjobs_fieldordering&jsjobslt=fieldsordering&ff=' . $fieldfor);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function fieldorderingup() {
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $fieldfor = JSJOBSrequest::getVar('ff');
        $id = JSJOBSrequest::getVar('fieldid');
        $result = JSJOBSincluder::getJSModel('fieldordering')->fieldOrderingUp($id);
        $msg = JSJOBSMessages::getMessage($result, 'fieldordering');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url('admin.php?page=jsjobs_fieldordering&jsjobslt=fieldsordering&ff=' . $fieldfor);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function fieldorderingdown() {
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $fieldfor = JSJOBSrequest::getVar('ff');
        $id = JSJOBSrequest::getVar('fieldid');
        $result = JSJOBSincluder::getJSModel('fieldordering')->fieldOrderingDown($id);
        $msg = JSJOBSMessages::getMessage($result, 'fieldordering');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url('admin.php?page=jsjobs_fieldordering&jsjobslt=fieldsordering&ff=' . $fieldfor);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function saveuserfield() {
        $data = JSJOBSrequest::get('post');
        $fieldfor = JSJOBSrequest::getVar('fieldfor');
        if($fieldfor == ''){
            $fieldfor = $data['fieldfor'];
        }
        $result = JSJOBSincluder::getJSModel('fieldordering')->storeUserField($data);
        if ($result === SAVE_ERROR || $result === false) {
            $url = admin_url("admin.php?page=jsjobs_fieldordering&jsjobslt=formuserfield&ff=" . $fieldfor);
        } else
            $url = admin_url("admin.php?page=jsjobs_fieldordering&ff=" . $fieldfor);
        $msg = JSJOBSMessages::getMessage($result, 'customfield');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

    function savesearchfieldordering() {
        $data = JSJOBSrequest::get('post');
        $fieldfor = JSJOBSrequest::getVar('fieldfor');
        if($fieldfor == ''){
            $fieldfor = $data['fieldfor'];
        }
        $result = JSJOBSincluder::getJSModel('fieldordering')->storeSearchFieldOrdering($data);
        $url = admin_url("admin.php?page=jsjobs_fieldordering&jsjobslt=searchfields&fieldfor=" . $fieldfor);
        $msg = JSJOBSMessages::getMessage($result, 'customfield');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

    function savesearchfieldorderingFromForm() {
        $data = JSJOBSrequest::get('post');
        $fieldfor = JSJOBSrequest::getVar('fieldfor');
        if($fieldfor == ''){
            $fieldfor = $data['fieldfor'];
        }
        $result = JSJOBSincluder::getJSModel('fieldordering')->storeSearchFieldOrderingByForm($data);
        $url = admin_url("admin.php?page=jsjobs_fieldordering&jsjobslt=searchfields&fieldfor=" . $fieldfor);
        $msg = JSJOBSMessages::getMessage($result, 'customfield');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

    function remove() {
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $id = JSJOBSrequest::getVar('fieldid');
        $ff = JSJOBSrequest::getVar('ff');
        $result = JSJOBSincluder::getJSModel('fieldordering')->deleteUserField($id);
        $msg = JSJOBSMessages::getMessage($result, 'fieldordering');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jsjobs_fieldordering&ff=".$ff);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }   

}

$JSJOBSfieldorderingController = new JSJOBSfieldorderingController();
?>