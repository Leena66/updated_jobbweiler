<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSajax {

    function __construct() {
        add_action("wp_ajax_jsjobs_ajax", array($this, "ajaxhandler")); // when user is login
        add_action("wp_ajax_nopriv_jsjobs_ajax", array($this, "ajaxhandler")); // when user is not login
    }

    function ajaxhandler() {
        $module = JSJOBSrequest::getVar('jsjobsme');
        $task = JSJOBSrequest::getVar('task');
        $result = JSJOBSincluder::getJSModel($module)->$task();
        echo $result;
        die();
    }



}

$jsajax = new JSJOBSajax();
?>
