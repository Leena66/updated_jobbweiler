<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSJobstatusController {

    private $_msgkey;

    function __construct() {

        self::handleRequest();
        
        $this->_msgkey = JSJOBSincluder::getJSModel('jobstatus')->getMessagekey();        
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'jobstatus');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_jobstatus':
                    JSJOBSincluder::getJSModel('jobstatus')->getAllJobStatus();
                    break;
                case 'admin_formjobstatus':
                    $id = JSJOBSrequest::getVar('jsjobsid');
                    JSJOBSincluder::getJSModel('jobstatus')->getJobStatusbyId($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'jobstatus');
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

    function savejobstatus() {
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('jobstatus')->storeJobStatus($data);
        $url = admin_url('admin.php?page=jsjobs_jobstatus&jsjobslt=jobstatus');
        $link = 'index.php?option=com_jsjobs&c=jobstatus&view=jobstatus&jsjobslt=jobstatus';
        $msg = JSJOBSMessages::getMessage($result, 'jobstatus');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

    function remove() {
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('jobstatus')->deleteJobsStatus($ids);
        $msg = JSJOBSMessages::getMessage($result, 'jobstatus');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jsjobs_jobstatus&jsjobslt=jobstatus");
        wp_redirect($url);
        die();
    }

    function publish() {
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('jobstatus')->publishUnpublish($ids, 1); //  for publish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jsjobs_jobstatus&jsjobslt=jobstatus");
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function unpublish() {
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('jobstatus')->publishUnpublish($ids, 0); //  for unpublish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jsjobs_jobstatus&jsjobslt=jobstatus");
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

}

$JSJOBSJobstatusController = new JSJOBSJobstatusController();
?>