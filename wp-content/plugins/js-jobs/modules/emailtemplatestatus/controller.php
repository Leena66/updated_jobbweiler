<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSemailtemplatestatusController {

    function __construct() {

        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'emailtemplatestatus');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_emailtemplatestatus':
                    JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatusData();
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'emailtemplatestatus');
            $module = str_replace('jsjobs_', '', $module);
            JSJOBSincluder::include_file($layout, $module);
        }
    }

    function sendEmail() {
        $id = JSJOBSrequest::getVar('jsjobsid');
        $action = JSJOBSrequest::getVar('actionfor');
        JSJOBSincluder::getJSModel('emailtemplatestatus')->sendEmailModel($id, $action); //  for send email
        $url = admin_url("admin.php?page=jsjobs_emailtemplatestatus");
        wp_redirect($url);
        die();
    }

    function noSendEmail() {
        $id = JSJOBSrequest::getVar('jsjobsid');
        $action = JSJOBSrequest::getVar('actionfor');
        JSJOBSincluder::getJSModel('emailtemplatestatus')->noSendEmailModel($id, $action); //  for notsendemail
        $url = admin_url("admin.php?page=jsjobs_emailtemplatestatus");
        wp_redirect($url);
        die();
    }

    function canaddfile() {
        if (isset($_POST['form_request']) && $_POST['form_request'] == 'jsjobs')
            return false;
        elseif (isset($_GET['action']) && $_GET['action'] == 'jsjobtask')
            return false;
        else
            return true;
    }

}

$JSJOBSEmailtemplatestatusController = new JSJOBSEmailtemplatestatusController();
?>
