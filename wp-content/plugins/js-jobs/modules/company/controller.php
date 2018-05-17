<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSCompanyController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('company')->getMessagekey();        
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'companies');
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        if (self::canaddfile()) {
            $empflag  = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('disable_employer');
            $string = "'jscontrolpanel','emcontrolpanel','visitor'" ;
            $config_array = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigForMultiple($string);
            switch ($layout) {
                case 'mycompanies':
                    if (JSJOBSincluder::getObjectClass('user')->isemployer() && $empflag == 1) {
                        JSJOBSincluder::getJSModel('company')->getMyCompanies($uid);
                    } else {
                        if (JSJOBSincluder::getObjectClass('user')->isjobseeker()) {
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(2,null,null,1);
                            jsjobs::$_error_flag_message_for=2; // user is jobseeker
                        } elseif (JSJOBSincluder::getObjectClass('user')->isguest()) {
                            $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('company', $layout, 1);
                            $linktext = __('Login','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=1; // user is guest
                            jsjobs::$_error_flag_message_register_for=2; // register as employer
                        } elseif (!JSJOBSincluder::getObjectClass('user')->isJSJobsUser()) {
                            $link = jsjobs::makeUrl(array('jsjobsme'=>'common', 'jsjobslt'=>'newinjsjobs'));
                            $linktext = __('Select role','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(9 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=9; // role is not select
                        }
                        if(isset($link) && isset($linktext)){
                            jsjobs::$_error_flag_message_for_link=$link;
                            jsjobs::$_error_flag_message_for_link_text=$linktext;
                        }
                        jsjobs::$_error_flag = true;
                    }
                    break;
                case 'companies':
                case 'admin_companies':
                        JSJOBSincluder::getJSModel('company')->getAllCompanies();
                    break;
                case 'admin_companiesqueue':
                    JSJOBSincluder::getJSModel('company')->getAllUnapprovedCompanies();
                    break;
                case 'addcompany':
                case 'admin_formcompany':
                    if (is_admin() || (JSJOBSincluder::getObjectClass('user')->isemployer() && $empflag == 1)) {
                        $id = JSJOBSrequest::getVar('jsjobsid');
                        if($id == ''){
                            $check = true;
                        }else{
                            if(!is_admin()){
                                $check = JSJOBSincluder::getJSModel('company')->getIfCompanyOwner($id);// so only owner can edit company
                            }
                        }
                        if (is_admin() || $check == true) {
                            JSJOBSincluder::getJSModel('company')->getCompanybyId($id);
                        }else{
                            jsjobs::$_error_flag = true;
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(10);
                            jsjobs::$_error_flag_message_for=10; 
                        }
                    } else {
                        if (JSJOBSincluder::getObjectClass('user')->isjobseeker()) {
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(2,null,null,1);
                            jsjobs::$_error_flag_message_for=2; 
                        } elseif (JSJOBSincluder::getObjectClass('user')->isguest()) {
                            $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('company', $layout, 1);
                            $linktext = __('Login','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=1; 
                            jsjobs::$_error_flag_message_register_for=2; 
                        } elseif (!JSJOBSincluder::getObjectClass('user')->isJSJobsUser()) {
                            $link = jsjobs::makeUrl(array('jsjobsme'=>'common', 'jsjobslt'=>'newinjsjobs'));
                            $linktext = __('Select role','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(9 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=9; 
                        }
                        jsjobs::$_error_flag = true;
                    }
                    if(isset($link) && isset($linktext)){
                        jsjobs::$_error_flag_message_for_link=$link;
                        jsjobs::$_error_flag_message_for_link_text=$linktext;
                    }
                    break;
                case 'viewcompany':
                case 'admin_view_company':
                    if (JSJOBSincluder::getObjectClass('user')->isguest() && $config_array['visitorview_emp_viewcompany'] != 1) {
                        $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('company', $layout, 1);
                        $linktext = __('Login','js-jobs');
                        jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                        jsjobs::$_error_flag_message_for=1; 
                        jsjobs::$_error_flag_message_register_for=2; 
                        jsjobs::$_error_flag = true;
                    } elseif (!JSJOBSincluder::getObjectClass('user')->isJSJobsUser() && $config_array['visitorview_emp_viewcompany'] != 1) {
                        $link = jsjobs::makeUrl(array('jsjobsme'=>'common', 'jsjobslt'=>'newinjsjobs'));
                        $linktext = __('Select role','js-jobs');
                        jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(9 , $link , $linktext,1);
                        jsjobs::$_error_flag_message_for=9; 
                        jsjobs::$_error_flag_message_register_for=2; 
                        jsjobs::$_error_flag = true;
                    } else {
                        $id = JSJOBSrequest::getVar('jsjobsid');
                        $id = JSJOBSincluder::getJSModel('common')->parseID($id);
                        $expiryflag = JSJOBSincluder::getJSModel('company')->getCompanyExpiryStatus($id);
                        if (JSJOBSincluder::getObjectClass('user')->isemployer()) {
                            if (JSJOBSincluder::getJSModel('company')->getIfCompanyOwner($id)) {
                                $expiryflag = true;
                            }
                        }
                        if ($expiryflag == false) {
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(6,null,null,1);
                            jsjobs::$_error_flag_message_for=6; 
                            jsjobs::$_error_flag = true;
                        } else {
                            JSJOBSincluder::getJSModel('company')->getCompanybyIdForView($id);
                        }
                    }
                    if(isset($link) && isset($linktext)){
                        jsjobs::$_error_flag_message_for_link=$link;
                        jsjobs::$_error_flag_message_for_link_text=$linktext;
                    }
                    break;
            }
            if ($empflag == 0) {
                JSJOBSLayout::setMessageFor(5);
                jsjobs::$_error_flag_message_for=5;
                jsjobs::$_error_flag = true;
            }

            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'company');
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

    function approveQueueCompany() {
        $id = JSJOBSrequest::getVar('id');
        $result = JSJOBSincluder::getJSModel('company')->approveQueueCompanyModel($id);
        $msg = JSJOBSMessages::getMessage($result, 'company');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jsjobs_company&jsjobslt=companiesqueue");
        wp_redirect($url);
        die();
    }

    function rejectQueueCompany() {
        $id = JSJOBSrequest::getVar('id');
        $result = JSJOBSincluder::getJSModel('company')->rejectQueueCompanyModel($id);
        $msg = JSJOBSMessages::getMessage($result, 'company');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jsjobs_company&jsjobslt=companiesqueue");

        wp_redirect($url);
        die();
    }

    function approveQueueAllCompanies() {
        $id = JSJOBSrequest::getVar('id');
        $alltype = JSJOBSrequest::getVar('objid');
        $result = JSJOBSincluder::getJSModel('company')->approveQueueAllCompaniesModel($id, $alltype);
        $msg = JSJOBSMessages::getMessage($result, 'company');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jsjobs_company&jsjobslt=companiesqueue");
        wp_redirect($url);
        die();
    }

    function rejectQueueAllCompanies() {
        $id = JSJOBSrequest::getVar('id');
        $alltype = JSJOBSrequest::getVar('objid');
        $result = JSJOBSincluder::getJSModel('company')->rejectQueueAllCompaniesModel($id, $alltype);
        $msg = JSJOBSMessages::getMessage($result, 'company');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jsjobs_company&jsjobslt=companiesqueue");
        wp_redirect($url);
        die();
    }

    function savecompany() {
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('company')->storeCompany($data);
        $admincompanylayout = (isset($_POST['isqueue']) && $_POST['isqueue'] == 1) ? 'companiesqueue' : 'companies';
        if ($result == SAVED) {
            if (is_admin()) {
                $url = admin_url("admin.php?page=jsjobs_company&jsjobslt=".$admincompanylayout);
            } else {
                $url = jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'mycompanies'));
            }
        } else {
            if (is_admin()) {
                $url = admin_url("admin.php?page=jsjobs_company&jsjobslt=formcompany");
            } else {
                $url = jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'addcompany'));
            }
        }
        $msg = JSJOBSMessages::getMessage($result, 'company');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

    function enforcedelete() {
        $companyid = JSJOBSrequest::getVar('id');
        $callfrom = JSJOBSrequest::getVar('callfrom');

        $result = JSJOBSincluder::getJSModel('company')->companyEnforceDeletes($companyid);
        $msg = JSJOBSMessages::getMessage($result, 'company');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        if ($callfrom == 1) {
            $url = admin_url("admin.php?page=jsjobs_company&jsjobslt=companies");
        } else {
            $url = admin_url("admin.php?page=jsjobs_company&jsjobslt=companiesqueue");
        }
        wp_redirect($url);
        die();
    }

    function remove() {
        $data = JSJOBSrequest::get('post');
        if (!isset($data['callfrom']) || $data['callfrom'] == null) {
            $data['callfrom'] = $callfrom = JSJOBSrequest::getVar('callfrom');
        }
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('company')->deleteCompanies($ids);
        $msg = JSJOBSMessages::getMessage($result, 'company');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        if (is_admin()) {
            if ($data['callfrom'] == 1) {
                $url = admin_url("admin.php?page=jsjobs_company&jsjobslt=companies");
            } elseif ($data['callfrom'] == 2) {
                $url = admin_url("admin.php?page=jsjobs_company&jsjobslt=companiesqueue");
            }
        } else {
            $url = jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'mycompanies','jsjobspageid'=>jsjobs::getPageid()));
        }
        wp_redirect($url);
        die();
    }

    function addviewcontactdetail() {
        $id = JSJOBSrequest::getVar('companyid');
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        JSJOBSincluder::getJSModel('company')->addViewContactDetail($id, $uid);
        $url = jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$id,'jsjobspageid'=>jsjobs::getPageid()));
        wp_redirect($url);
        die();
    }

}

$JSJOBSCompanyController = new JSJOBSCompanyController();
?>