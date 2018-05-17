<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSJobapplyController {

    private $_msgkey;

    function __construct() {

        self::handleRequest();
        
        $this->_msgkey = JSJOBSincluder::getJSModel('jobapply')->getMessagekey();        
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'appliedresumes');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_appliedresumes':
                    JSJOBSincluder::getJSModel('jobapply')->getAppliedResume();
                    break;
                case 'myappliedjobs':
                    $conflag = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('myappliedjobs');
                    if (JSJOBSincluder::getObjectClass('user')->isjobseeker()) {
                        $uid = JSJOBSincluder::getObjectClass('user')->uid();
                        JSJOBSincluder::getJSModel('jobapply')->getMyAppliedJobs($uid);
                    } else {
                        if (JSJOBSincluder::getObjectClass('user')->isemployer()) {
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(3,null,null,1);
                            jsjobs::$_error_flag_message_for=3;
                        } elseif (JSJOBSincluder::getObjectClass('user')->isguest()) {
                            $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('jobapply', $layout, 1);
                            $linktext = __('Login','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=1;
                            jsjobs::$_error_flag_message_register_for=1; // register as jobseeker
                        } elseif (!JSJOBSincluder::getObjectClass('user')->isJSJobsUser()) {
                            $link = jsjobs::makeUrl(array('jsjobsme'=>'common', 'jsjobslt'=>'newinjsjobs'));
                            $linktext = __('Select role','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(9 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=9;
                        }
                        jsjobs::$_error_flag = true;
                        if(isset($link) && isset($linktext)){
                            jsjobs::$_error_flag_message_for_link = $link;               
                            jsjobs::$_error_flag_message_for_link_text = $linktext;              
                        }
                    }
                    break;
                case 'jobappliedresume':
                case 'admin_jobappliedresume':
                    if (JSJOBSincluder::getObjectClass('user')->isemployer() || is_admin()) {
                        $uid = JSJOBSincluder::getObjectClass('user')->uid();
                        $jobid = JSJOBSrequest::getVar('jobid');
                        $tab_action = JSJOBSrequest::getVar('ta', null, 1);
                        JSJOBSincluder::getJSModel('jobapply')->getJobAppliedResume($tab_action, $jobid, $uid);
                        jsjobs::$_data['jobid'] = $jobid;
                    } else {
                        if (JSJOBSincluder::getObjectClass('user')->isjobseeker()) {
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(2,null,null,1);
                            jsjobs::$_error_flag_message_for=2;                            
                        } elseif (JSJOBSincluder::getObjectClass('user')->isguest()) {
                            $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('jobapply', $layout, 1);
                            $linktext = __('Login','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=1;
                            jsjobs::$_error_flag_message_register_for=2; // register as employer 
                        } elseif (!JSJOBSincluder::getObjectClass('user')->isJSJobsUser()) {
                            $link = jsjobs::makeUrl(array('jsjobsme'=>'common', 'jsjobslt'=>'newinjsjobs'));
                            $linktext = __('Select role','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(9 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=9;
                        }
                        if(isset($link) && isset($linktext)){
                            jsjobs::$_error_flag_message_for_link = $link;               
                            jsjobs::$_error_flag_message_for_link_text = $linktext;              
                        }
                        jsjobs::$_error_flag = true;
                    }
                    break;
                case 'admin_jobshortlists':
                    $jobid = JSJOBSrequest::getVar('oi');
                    JSJOBSincluder::getJSModel('jobapply')->getJobAppliedResume($jobid);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'jobapply');
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

    function appliedresumetabactions() {
        $data = JSJOBSrequest::get('post');
        if ($data['tab_action'] == 6)
            $needle_array = json_encode($data);
        $_SESSION['jsjobappliedresumefilter'] = $needle_array;
        $url = admin_url("admin.php?page=jsjobs_jobapply&jsjobslt=jobappliedresume&jobid=" . $data["jobid"] . "&ta=" . $data["tab_action"]);
        wp_redirect($url);
        die();
    }

    function actionresume() { //save shortlist candidate
        $data = JSJOBSrequest::get('post');
        $url = admin_url("admin.php?page=jsjobs_jobapply&jsjobslt=jobappliedresume&oi=" . $data['jobid']);
        wp_redirect($url);
        die();
    }

    function jobapplyasvisitor() {
        $jobid = JSJOBSrequest::getVar('jsjobsid-jobid');
        if (!is_numeric($jobid)) { // redirect to jobs page if id is not numeric
            $url = jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'jobs'));
        } else {
            $_SESSION['jsjobs_apply_visitor'] = $jobid;
            $url = jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'addresume'));
        }
        wp_redirect($url);
        die();
    }

    function jobapplysocial() {
        $media = JSJOBSrequest::getVar('media');
        $result = false;
        switch ($media) {
            case 'facebook':
                $result = JSJOBSincluder::getObjectClass('facebook')->applywithfacebook();
                break;
            case 'linkedin':
                $result = JSJOBSincluder::getObjectClass('linkedin')->applywithlinkedin();
                break;
            case 'xing':
                $result = JSJOBSincluder::getObjectClass('xing')->applywithxing();
                break;
        }
        if ($result == false) {
            JSJOBSMessages::setLayoutMessage(__('Error applying on job', 'js-jobs'), 'error',$this->_msgkey);
        } else {
            JSJOBSMessages::setLayoutMessage(__('Job has been applied', 'js-jobs'), 'updated',$this->_msgkey);
        }
        $url = JSJOBSrequest::getVar('jsnext');
        wp_redirect($url);
        exit;
    }

}

$JSJOBSJobapplyController = new JSJOBSJobapplyController();
?>
