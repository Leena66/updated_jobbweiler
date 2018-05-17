<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSCommonController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('common')->getMessagekey();        
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'newinjsjobs');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'newinjsjobs':
                    if(JSJOBSincluder::getObjectClass('user')->isguest()){
                        $link = get_permalink();
                        $linktext = __('Login','js-jobs');
                        jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                        jsjobs::$_error_flag = true;
                    }
                break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'common');
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

    function makedefault() {
        $id = JSJOBSrequest::getVar('id');
        $for = JSJOBSrequest::getVar('for'); // table name
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $result = JSJOBSincluder::getJSModel('common')->setDefaultForDefaultTable($id, $for);
        $object = $this->getpageandlayoutname($for);
        $msg = JSJOBSMessages::getMessage($result, $object['page']);
        $url = admin_url("admin.php?page=jsjobs_" . $object['page'] . "&jsjobslt=" . $object['jsjobslt']);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

    function defaultorderingup() {
        $id = JSJOBSrequest::getVar('id');
        $for = JSJOBSrequest::getVar('for'); //table name
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $result = JSJOBSincluder::getJSModel('common')->setOrderingUpForDefaultTable($id, $for);
        $object = $this->getpageandlayoutname($for);
        $msg = JSJOBSMessages::getMessage($result, $object['page']);
        $url = admin_url("admin.php?page=jsjobs_" . $object['page'] . "&jsjobslt=" . $object['jsjobslt']);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

    function defaultorderingdown() {
        $id = JSJOBSrequest::getVar('id');
        $for = JSJOBSrequest::getVar('for'); // table name
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $result = JSJOBSincluder::getJSModel('common')->setOrderingDownForDefaultTable($id, $for);
        $object = $this->getpageandlayoutname($for);
        $msg = JSJOBSMessages::getMessage($result, $object['page']);
        $url = admin_url("admin.php?page=jsjobs_" . $object['page'] . "&jsjobslt=" . $object['jsjobslt']);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

    function getpageandlayoutname($for) { // for tablename
        switch ($for) {
            case 'jobtypes' : $object['page'] = "jobtype";
                $object['jsjobslt'] = "jobtypes";
                break;
            case 'shifts' : $object['page'] = "shift";
                $object['jsjobslt'] = "shifts";
                break;
            case 'ages' : $object['page'] = "age";
                $object['jsjobslt'] = "ages";
                break;
            case 'careerlevels' : $object['page'] = "careerlevel";
                $object['jsjobslt'] = "careerlevels";
                break;
            case 'salaryrangetypes' : $object['page'] = "salaryrangetype";
                $object['jsjobslt'] = "salaryrangetype";
                break;
            case 'currencies' : $object['page'] = "currency";
                $object['jsjobslt'] = "currency";
                break;
            case 'experiences' : $object['page'] = "experience";
                $object['jsjobslt'] = "experience";
                break;
            case 'heighesteducation' : $object['page'] = "highesteducation";
                $object['jsjobslt'] = "highesteducations";
                break;
            case 'categories' : $object['page'] = "category";
                $object['jsjobslt'] = "categories";
                break;
            case 'subcategories' :
                $object['page'] = "subcategory";
                $categoryid = $_SESSION['sub_categoryid'];
                $object['jsjobslt'] = "subcategories&categoryid=" . $categoryid;
                break;
            default : $object['page'] = $object['jsjobslt'] = $for;
                break;
        }
        return $object;
    }

    function savenewinjsjobs() {
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('common')->saveNewInJSJobs($data);
        if ($data['desired_module'] == 'common' && $data['desired_layout'] == 'newinjsjobs') {
            if (JSJOBSincluder::getObjectClass('user')->isjobseeker()) {
                $data['desired_module'] = 'job seeker';
            } else {
                $data['desired_module'] = 'employer';
            }
            $data['desired_layout'] = 'controlpanel';
        }
        $url = jsjobs::makeUrl(array('jsjobsme'=>$data['desired_module'], 'jsjobslt'=>$data['desired_layout']));
        $msg = JSJOBSMessages::getMessage($result, 'userrole');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

}

$JSJOBSCommonController = new JSJOBSCommonController;
?>