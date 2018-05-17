<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSJobController {

    private $_msgkey;

    function __construct() {
        //echo '<pre>';print_r(wp_debug_backtrace_summary());die();
        //echo '<pre>';print_r( debug_backtrace());die();
        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('job')->getMessagekey();  

    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'jobs');
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        if (self::canaddfile()) {
            $empflag  = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('disable_employer');
            $string = "'jscontrolpanel','emcontrolpanel','visitor'" ;
            $config_array = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigForMultiple($string);
            switch ($layout) {
                case 'myjobs':
                    if (JSJOBSincluder::getObjectClass('user')->isemployer() && $empflag == 1) {
                        JSJOBSincluder::getJSModel('job')->getMyJobs($uid);
                    } else {
                        if (JSJOBSincluder::getObjectClass('user')->isjobseeker()) {
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(2,null,null,1);
                            jsjobs::$_error_flag_message_for=2; // user is jobseeker
                        } elseif (JSJOBSincluder::getObjectClass('user')->isguest()) {
                            $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('job', $layout, 1);
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
                        jsjobs::$_error_flag = true;
                        if(isset($link) && isset($linktext)){
                            jsjobs::$_error_flag_message_for_link = $link;               
                            jsjobs::$_error_flag_message_for_link_text = $linktext;              
                        }
                    }
                    break;
                case 'jobs':
                case 'newestjobs':
                    $flag = true;
                    $search = JSJOBSrequest::getVar('issearchform', 'post');
                    $companyid = JSJOBSrequest::getVar('companyid', 'get');
                    $jobtypeid = JSJOBSrequest::getVar('jobtype', 'get');
                    $categoryid = JSJOBSrequest::getVar('category', 'get');
                    $jsjobsid = JSJOBSrequest::getVar('jsjobsid', 'get');
                    $jsjobsid = JSJOBSincluder::getJSModel('common')->parseID($jsjobsid);                    
                    if ($categoryid != null) {
                        if(JSJOBSincluder::getObjectClass('user')->isguest() && $config_array['visitorview_js_jobcat'] != 1){
                            $flag = 2;
                        }
                        if(!JSJOBSincluder::getObjectClass('user')->isJSJobsUser() && $config_array['visitorview_js_jobcat'] != 1){
                            $flag = 3;
                        }
                    }elseif(JSJOBSincluder::getObjectClass('user')->isguest() && $config_array['visitorview_js_newestjobs'] != 1) {
                        $flag = 2;
                    }elseif(!JSJOBSincluder::getObjectClass('user')->isJSJobsUser() && $config_array['visitorview_js_newestjobs'] != 1) {
                        $flag = 3;
                    } elseif (JSJOBSincluder::getObjectClass('user')->isguest() && $config_array['visitorview_js_jobsearchresult'] != 1 && $search != null) {
                        $flag = 2;
                    } elseif (!JSJOBSincluder::getObjectClass('user')->isJSJobsUser() && $config_array['visitorview_js_jobsearchresult'] != 1 && $search != null) {
                        $flag = 3;
                    }
                    if ($flag === true) {
                        $vars = JSJOBSincluder::getJSModel('job')->getjobsvar();
                        JSJOBSincluder::getJSModel('job')->getJobs($vars);
                        jsjobs::$_data['vars'] = $vars;
                        $issearchform = JSJOBSrequest::getVar('issearchform', 'post', null);
                        if ($issearchform != null) {
                            jsjobs::$_data['issearchform'] = $issearchform;
                        }
                    }elseif($flag === 2){
                        $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('job', $layout, 1);
                        $linktext = __('Login','js-jobs');
                        jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                        jsjobs::$_error_flag_message_for=1; // user is guest
                        jsjobs::$_error_flag_message_register_for=1; // register as jobseeker
                        jsjobs::$_error_flag = true;
                    }elseif($flag === 3){
                        $link = jsjobs::makeUrl(array('jsjobsme'=>'common', 'jsjobslt'=>'newinjsjobs'));
                        $linktext = __('Select role','js-jobs');
                        jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(9 , $link , $linktext,1);
                        jsjobs::$_error_flag_message_for=9; 
                        jsjobs::$_error_flag = true;
                    }elseif($flag === 4){
                        jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(2 , null , null,1);
                        jsjobs::$_error_flag_message_for=2; 
                        jsjobs::$_error_flag = true;
                    }
                    if(isset($link) && isset($linktext)){
                        jsjobs::$_error_flag_message_for_link = $link;               
                        jsjobs::$_error_flag_message_for_link_text = $linktext;              
                    }
                    $layout = 'jobs';

                    break;
                case 'viewjob':
                    $jobid = JSJOBSrequest::getVar('jsjobsid');
                    $jobid = JSJOBSincluder::getJSModel('common')->parseID($jobid);

                    $expiryflag = JSJOBSincluder::getJSModel('job')->getJobsExpiryStatus($jobid);
                    if (JSJOBSincluder::getObjectClass('user')->isemployer()) {
                        if (JSJOBSincluder::getJSModel('job')->getIfJobOwner($jobid)) {
                            $expiryflag = true;
                        }
                    }
                    if (JSJOBSincluder::getObjectClass('user')->isguest() && $config_array['visitorview_emp_viewjob'] != 1) {
                        $linktext = __('Login','js-jobs');
                        $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('job', $layout, 1);
                        jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                        jsjobs::$_error_flag = true;
                        jsjobs::$_error_flag_message_for=1; 
                        jsjobs::$_error_flag_message_register_for=1; 
                    } elseif ($expiryflag == false) {
                        jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(6,null,null,1);
                        jsjobs::$_error_flag_message_for=6; 
                        jsjobs::$_error_flag = true;
                    } else {
                        JSJOBSincluder::getJSModel('job')->getJobbyIdForView($jobid);
                    }
                    if(isset($link) && isset($linktext)){
                        jsjobs::$_error_flag_message_for_link=$link;
                        jsjobs::$_error_flag_message_for_link_text=$linktext;
                    }
                    break;
                case 'jobsbycategories':
                    if ((JSJOBSincluder::getObjectClass('user')->isjobseeker()) || ($config_array['visitorview_js_jobcat'] == 1)) {
                        JSJOBSincluder::getJSModel('job')->getJobsByCategories();
                    } else {
                        if (JSJOBSincluder::getObjectClass('user')->isemployer()) {
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(3,null,null,1);
                            jsjobs::$_error_flag_message_for=3; 
                        } elseif (JSJOBSincluder::getObjectClass('user')->isguest()) {
                            $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('job', $layout, 1);
                            $linktext = __('Login','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=1; 
                            jsjobs::$_error_flag_message_register_for=1; 
                        } elseif (!JSJOBSincluder::getObjectClass('user')->isJSJobsUser()) {
                            $link = jsjobs::makeUrl(array('jsjobsme'=>'common', 'jsjobslt'=>'newinjsjobs'));
                            $linktext = __('Select role','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(9 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=9; 
                        }
                        jsjobs::$_error_flag = true;
                        if(isset($link) && isset($linktext)){
                            jsjobs::$_error_flag_message_for_link=$link;
                            jsjobs::$_error_flag_message_for_link_text=$linktext;
                        }
                    }
                    break;
                case 'jobsbytypes':
					JSJOBSincluder::getJSModel('job')->getJobsByTypes();
                    break;
                case 'admin_jobs':
                    JSJOBSincluder::getJSModel('job')->getAllJobs();
                    break;
                case 'addjob':
                case 'admin_formjob':
                    if (is_admin() || (JSJOBSincluder::getObjectClass('user')->isemployer() && $empflag == 1)) {
                        $id = JSJOBSrequest::getVar('jsjobsid');
                        if($id == ''){
                            $check = true;
                        }else{
                            if(!is_admin()){
                                $check = JSJOBSincluder::getJSModel('job')->getIfJobOwner($id);// owner check
                            }
                        }
                        if (is_admin() || $check == true) {
                            JSJOBSincluder::getJSModel('job')->getJobbyId($id);
                        }else{
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(10);
                            jsjobs::$_error_flag_message_for=10; //credit not enough to perform this action 
                            jsjobs::$_error_flag = true;
                        }                        
			JSJOBSincluder::getJSModel('job')->getJobbyId($id);
                        if (isset(jsjobs::$_data[0])) {
                            jsjobs::$_data[7] = jsjobs::$_data[0]; //job data
                        }
                        jsjobs::$_data[8] = jsjobs::$_data[2]; //job fields ordering
                    } else {
                        if (JSJOBSincluder::getObjectClass('user')->isjobseeker()) {
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(2,null,null,1);
                            jsjobs::$_error_flag_message_for=2;
                        } elseif (JSJOBSincluder::getObjectClass('user')->isguest()) {
                            $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('job', $layout, 1);
                            $linktext = __('Login','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=1;
                            jsjobs::$_error_flag_message_register_for=1; 
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
                case 'admin_jobqueue':
                    JSJOBSincluder::getJSModel('job')->getAllUnapprovedJobs();
                    break;
                case 'admin_job_searchresult':
                    JSJOBSincluder::getJSModel('job')->getJobSearch();
                    break;
                case 'admin_jobsearch':
                    JSJOBSincluder::getJSModel('job')->getSearchOptions();
                    break;
                case 'admin_view_job':
                    $id = JSJOBSrequest::getVar('jsjobsid');
                    JSJOBSincluder::getJSModel('job')->getJobbyIdForView($jobid);
                    break;
            }

            if ($empflag == 0) {
                JSJOBSLayout::setMessageFor(5);
                jsjobs::$_error_flag_message_for=5;
                jsjobs::$_error_flag = true;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'job');
            $module = str_replace('jsjobs_', '', $module);
            JSJOBSincluder::include_file($layout, $module);
        }
    }

    function approveQueueJob() {
        $id = JSJOBSrequest::getVar('id');
        $result = JSJOBSincluder::getJSModel('job')->approveQueueJobModel($id);
        $msg = JSJOBSMessages::getMessage($result, 'job');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jsjobs_job&jsjobslt=jobqueue");
        wp_redirect($url);
        die();
    }

    function rejectQueueJob() {
        $id = JSJOBSrequest::getVar('id');
        $result = JSJOBSincluder::getJSModel('job')->rejectQueueJobModel($id);
        $msg = JSJOBSMessages::getMessage($result, 'job');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jsjobs_job&jsjobslt=jobqueue");

        wp_redirect($url);
        die();
    }

    function approveQueueAllJobs() {
        $id = JSJOBSrequest::getVar('id');
        $alltype = JSJOBSrequest::getVar('objid');
        $result = JSJOBSincluder::getJSModel('job')->approveQueueAllJobsModel($id, $alltype);
        $msg = JSJOBSMessages::getMessage($result, 'job');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jsjobs_job&jsjobslt=jobqueue");

        wp_redirect($url);
        die();
    }

    function rejectQueueAllJobs() {
        $id = JSJOBSrequest::getVar('id');
        $alltype = JSJOBSrequest::getVar('objid');
        $result = JSJOBSincluder::getJSModel('job')->rejectQueueAllJobsModel($id, $alltype);
        $msg = JSJOBSMessages::getMessage($result, 'job');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jsjobs_job&jsjobslt=jobqueue");
        wp_redirect($url);
    }

    function canaddfile() {
        if (isset($_POST['form_request']) && $_POST['form_request'] == 'jsjobs')
            return false;
        elseif (isset($_GET['action']) && $_GET['action'] == 'jsjobtask')
            return false;
        else
            return true;
    }

    function savejob() {
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('job')->storeJob($data);
        $adminjoblayout = (isset($_POST['isqueue']) && $_POST['isqueue'] == 1) ? 'jobqueue' : 'jobs';
        if ($result == SAVED) {
            if (is_admin()) {
                $url = admin_url("admin.php?page=jsjobs_job&jsjobslt=".$adminjoblayout);
            } else {
                $url = jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs'));
            }
            if(JSJOBSincluder::getObjectClass('user')->isguest()){
                $pageid = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('visitor_add_job_redirect_page');
                $url = get_the_permalink($pageid);
            }
        } else {
            if (is_admin()) {
                $url = admin_url("admin.php?page=jsjobs_job&jsjobslt=formjob");
            } else {
                $url = jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'addjob'));
            }
        }

        $msg = JSJOBSMessages::getMessage($result, 'job');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }


    function remove() {
        $id = JSJOBSrequest::getVar('jsjobs-cb');
        $data = JSJOBSrequest::get('post');
        if (isset($data['callfrom']) AND $data['callfrom'] == null) {
            $data['callfrom'] = $callfrom = JSJOBSrequest::getVar('callfrom');
        }
        $result = JSJOBSincluder::getJSModel('job')->deleteJobs($id);
        $msg = JSJOBSMessages::getMessage($result, 'job');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        if (is_admin()) {
            if (isset($data['callfrom']) AND $data['callfrom'] == 2) {
                $url = admin_url("admin.php?page=jsjobs_job&jsjobslt=jobqueue");
            }else{
                $url = admin_url("admin.php?page=jsjobs_job&jsjobslt=jobs");
            }
		} else {
            $url = jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs'));
        }
        wp_redirect($url);
        die();
    }

    function jobenforcedelete() {
        $jobid = JSJOBSrequest::getVar('jobid');
        $callfrom = JSJOBSrequest::getVar('callfrom');
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        $resultforsendmail = JSJOBSincluder::getJSModel('job')->getJobInfoForEmail($jobid);
        $_SESSION['jobtitle'] = $resultforsendmail->jobtitle;
        $_SESSION['companyname'] = $resultforsendmail->companyname;
        $_SESSION['user'] = $resultforsendmail->user;
        $_SESSION['useremail'] = $resultforsendmail->useremail;
        $result = JSJOBSincluder::getJSModel('job')->jobEnforceDelete($jobid, $uid);

        $msg = JSJOBSMessages::getMessage($result, 'job');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        if ($callfrom == 1) {
            $url = admin_url("admin.php?page=jsjobs_job&jsjobslt=jobs");
        } else {
            $url = admin_url("admin.php?page=jsjobs_job&jsjobslt=jobqueue");
        }
        if ($result == DELETED) {
            JSJOBSincluder::getJSModel('emailtemplate')->sendMail(2, 2, $jobid); // 2 for job,2 for DELETE job
        }
        wp_redirect($url);
        die();
    }

}

$JSJOBSJobController = new JSJOBSJobController();
?>