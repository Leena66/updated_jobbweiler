<?php

/**
 * @package JS Jobs Manager
 * @version 1.0.6
 */
/*
  Plugin Name: JS Jobs Manager
  Plugin URI: http://www.joomsky.com
  Description: JS Job Manager is Word Press best job board plugin. It is easy to use and highly configurable. It fully accommodates job seekers and employers.
  Author: JoomSky
  Version: 1.0.6
  Author URI: http://www.joomsky.com
 */

if (!defined('ABSPATH'))
    die('Restricted Access');

class jsjobs {

    public static $_path;
    public static $_pluginpath;
    public static $_data; /* data[0] for list , data[1] for total paginition ,data[2] fieldsorderring , data[3] userfield for form , data[4] for reply , data[5] for ticket history  , data[6] for internal notes  , data[7] for ban email  , data['ticket_attachment'] for attachment */
    public static $_pageid;
    public static $_db;
    public static $_configuration;
    public static $_sorton;
    public static $_sortorder;
    public static $_ordering;
    public static $_sortlinks;
    public static $_msg;
    public static $_error_flag;
    public static $_error_flag_message;
    public static $_currentversion;
    public static $_error_flag_message_for;
    public static $_error_flag_message_for_link;
    public static $_error_flag_message_for_link_text;
    public static $_error_flag_message_register_for;
    public static $theme_chk;

    function __construct() {
        self::includes();
        //  self::registeractions();
        self::$_path = plugin_dir_path(__FILE__);
        self::$_pluginpath = plugins_url('/', __FILE__);
        self::$_data = array();
        self::$_error_flag = null;
        self::$_error_flag_message = null;
        self::$_currentversion = '106';
        global $wpdb;
        self::$_db = $wpdb;
        JSJOBSincluder::getJSModel('configuration')->getConfiguration();
        register_activation_hook(__FILE__, array($this, 'jsjobs_activate'));
        register_deactivation_hook(__FILE__, array($this, 'jsjobs_deactivate'));
        add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
        add_action('template_redirect', array($this, 'printResume'), 5); // Only for the print resume in wordpress
        add_action('template_redirect', array($this, 'pdf'), 5); // Only for the pdf in wordpress
        add_action('admin_init', array($this, 'jsjobs_activation_redirect'));//for post installation screens
        add_action('jsjobs_cronjobs_action', array($this,'jsjobs_cronjobs'));
        $theme_chk = 0;
        $theme = wp_get_theme();
        if($theme == 'Job Manager'){
            $theme_chk = 1;
        }
        self::$theme_chk = $theme_chk;
    }

    function jsjobs_activation_redirect(){
        if (get_option('jsjobs_do_activation_redirect') == true) {
            update_option('jsjobs_do_activation_redirect',false);
            exit(wp_redirect(admin_url('admin.php?page=jsjobs_postinstallation&jsjobslt=stepone')));
        }        
    }

    function printResume() {
        $printResume =JSJOBSrequest::getVar("jsjobslt");
        if ($printResume == 'printresume') {
            $resumeid = JSJOBSrequest::getVar('jsjobsid');
            $issocial = JSJOBSrequest::getVar('issocial');
            if ($issocial == 1) {
                jsjobs::$_data['socialprofileid'] = $resumeid;
                jsjobs::$_data['socialprofile'] = true;
            } else {
                JSJOBSincluder::getJSModel('resume')->getResumebyId($resumeid);
            }
            jsjobs::addStyleSheets();
            JSJOBSincluder::include_file('viewresume', 'resume');
            exit();
        }
    }

    function pdf() {
        $pdf =JSJOBSrequest::getVar("jsjobslt");
        if ($pdf == 'pdf') {
            $resumeid = JSJOBSrequest::getVar('jsjobsid');
            if (!$resumeid) {
                $profileid = JSJOBSrequest::getVar('jsscid');
                jsjobs::$_data['socialprofilepdf'] = true;
            } else {
                JSJOBSincluder::getJSModel('resume')->getResumebyId($resumeid);
            }
            JSJOBSincluder::include_file('pdf', 'resume');
            exit();
        }
    }

    function jsjobs_activate() {
        include_once 'includes/activation.php';
        JSJOBSactivation::jsjobs_activate();
		add_option('jsjobs_do_activation_redirect', true);
    }

    function jsjobs_deactivate() {
        include_once 'includes/deactivation.php';
        JSJOBSdeactivation::jsjobs_deactivate();
    }

    /*
     * Include the required files
     */

    function includes() {
        if (is_admin()) {
            include_once 'includes/jsjobsadmin.php';
        }
        include_once 'includes/jsjobs-hooks.php';
        include_once 'includes/captcha.php';
        include_once 'includes/recaptchalib.php';
        include_once 'includes/layout.php';
        include_once 'includes/pagination.php';
        include_once 'includes/includer.php';
        include_once 'includes/formfield.php';
        include_once 'includes/request.php';
        include_once 'includes/formhandler.php';
        include_once 'includes/ajax.php';
        require_once 'includes/constants.php';
        require_once 'includes/messages.php';
        require_once 'includes/jsjobsdb.php';
        include_once 'includes/shortcodes.php';
        include_once 'includes/paramregister.php';
        include_once 'includes/breadcrumbs.php';
        include_once 'includes/dashboardapi.php';
        // Widgets
        include_once 'includes/widgets/searchjobs.php';
    }

    /*
     * Localization
     */

    public function load_plugin_textdomain() {
        load_plugin_textdomain('js-jobs', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /*
     * function for the Style Sheets
     */

    static function addStyleSheets() {
        wp_enqueue_style('jsjob-bootstrap', jsjobs::$_pluginpath . 'includes/css/bootstrap.min.css');
        wp_enqueue_style('jsjob-tokeninput', jsjobs::$_pluginpath . 'includes/css/tokeninput.css');
        wp_enqueue_script('jsjob-commonjs', jsjobs::$_pluginpath . 'includes/js/common.js');
        wp_localize_script('jsjob-commonjs', 'common', array('ajaxurl' => admin_url('admin-ajax.php'),'insufficient_credits' => __('You have insufficient credits, you can not perform this action','js-jobs')));
        wp_enqueue_script('jsjob-formvalidator', jsjobs::$_pluginpath . 'includes/js/jquery.form-validator.js');
        if(jsjobs::$theme_chk == 0 || is_admin()){
            wp_enqueue_script('jsjob-tokeninput', jsjobs::$_pluginpath . 'includes/js/jquery.tokeninput.js');
        }
        wp_enqueue_script('jsjob-chosen-js', jsjobs::$_pluginpath . 'includes/js/chosen/chosen.jquery.min.js');
    }

    /*
     * function to get the pageid from the wpoptions
     */

    public static function getPageid() {
        if(jsjobs::$_pageid != ''){
            return jsjobs::$_pageid;
        }else{
            $pageid = JSJOBSrequest::getVar('page_id','GET');
            if($pageid){
                return $pageid;
            }else{ // in case of categories popup
                $module = JSJOBSrequest::getVar('jsjobsme');
                if($module == 'category'){
                    $pageid = JSJOBSrequest::getVar('page_id','POST');
                    if($pageid)
                        return $pageid;
                }
            }
            $id = 0;
            $pageid = jsjobs::$_db->get_var("SELECT configvalue FROM `".jsjobs::$_db->prefix."js_job_config` WHERE configname = 'default_pageid'");
            if ($pageid)
                $id = $pageid;
            return $id;
        }
    }

    public static function setPageID($id) {
        jsjobs::$_pageid = $id;
    }

    /*
     * function to parse the spaces in given string
     */

    public static function parseSpaces($string) {
        return str_replace('%20', ' ', $string);
    }

    public static function tagfillin($string) {
        return str_replace(' ', '_', $string);
    }
    
    public static function tagfillout($string) {
        return str_replace('_', ' ', $string);
    }
    static function makeUrl($args = array()){
        global $wp_rewrite;

        $pageid = JSJOBSrequest::getVar('jsjobspageid');

        if(is_numeric($pageid)){
            $permalink = get_the_permalink($pageid);
        }else{
            if(isset($args['jsjobspageid']) && is_numeric($args['jsjobspageid'])){
                $permalink = get_the_permalink($args['jsjobspageid']);
            }else{
                $permalink = get_the_permalink();
            }
        }
        if (!$wp_rewrite->using_permalinks()){
            if(!strstr($permalink, 'page_id') && !strstr($permalink, '?p=')) {
                $page['page_id'] = get_option('page_on_front');
                $args = $page + $args;
            }
            $redirect_url = add_query_arg($args,$permalink);
            return $redirect_url;
        }

        if(isset($args['jsjobsme']) && isset($args['jsjobslt'])){
            // Get the original query parts
            $redirect = @parse_url($permalink);
            if (!isset($redirect['query']))
                $redirect['query'] = '';

            if(strstr($permalink, '?')){ // if variable exist
                $redirect_array = explode('?', $permalink);
                $_redirect = $redirect_array[0];
            }else{
                $_redirect = $permalink;
            }

            if($_redirect[strlen($_redirect) - 1] == '/'){
                $_redirect = substr($_redirect, 0, strlen($_redirect) - 1);
            }
            // If is layout
            $changename = false;
            if(file_exists(WP_PLUGIN_DIR.'/js-vehicle-manager/js-vehicle-manager.php')){
                $changename = true;
            }
            if(file_exists(WP_PLUGIN_DIR.'/js-support-ticket/js-support-ticket.php')){
                $changename = true;
            }

            if (isset($args['jsjobslt'])) {
                $layout = '';
                ///echo $args['jsjobslt'].'-';
                $layout = JSJOBSincluder::getJSModel('slug')->getSlugFromFileName($args['jsjobslt'],$args['jsjobsme']);
                /*
                echo "</br>";
                echo $layout;
                switch ($args['jsjobslt']) {
                    case 'newinjsjobs':$layout = 'new-in-jsjobs';break;
                    case 'login':$layout = 'jsjobs-login';break;
                    case 'controlpanel':
                        if($args['jsjobsme'] == 'jobseeker'){
                            $layout = 'jobseeker-control-panel';                            
                        }elseif($args['jsjobsme'] == 'employer'){
                            $layout = 'employer-control-panel';
                        }
                    break;
                    case 'mystats':
                        if($args['jsjobsme'] == 'jobseeker'){
                            $layout = 'jobseeker-my-stats';                            
                        }elseif($args['jsjobsme'] == 'employer'){
                            $layout = 'employer-my-stats';
                        }
                    break;
                    case 'resumes':$layout = 'resumes';break;
                    case 'jobs':$layout = 'jobs';break;
                    case 'mycompanies':$layout = 'my-companies';break;
                    case 'addcompany':$layout = 'add-company';break;
                    case 'myjobs':$layout = 'my-jobs';break;
                    case 'addjob':$layout = 'add-job';break;
                    case 'mydepartments':$layout = 'my-departments';break;
                    case 'adddepartment':$layout = 'add-department';break;
                    case 'viewdepartment':$layout = 'department';break;
                    case 'viewcoverletter':$layout = 'cover-letter';break;
                    case 'viewcompany':$layout = 'company';break;
                    case 'viewresume':$layout = 'resume';break;
                    case 'viewjob':$layout = 'job';break;
                    case 'myfolders':$layout = 'my-folders';break;
                    case 'addfolder':$layout = 'add-folder';break;
                    case 'viewfolder':$layout = 'folder';break;
                    case 'folderresume':$layout = 'folder-resumes';break;
                    case 'jobseekermessages':$layout = 'jobseeker-messages';break;
                    case 'employermessages':$layout = 'employer-messages';break;
                    case 'sendmessage':$layout = 'message';break;
                    case 'jobmessages':$layout = 'job-messages';break;
                    case 'jobsbytypes':$layout = 'job-types';break;
                    case 'messages':$layout = 'messages';break;
                    case 'resumesearch':$layout = 'resume-search';break;
                    case 'resumesavesearch':$layout = 'resume-save-searches';break;
                    case 'resumebycategory':$layout = 'resume-categories';break;
                    case 'resumerss':$layout = 'resume-rss';break;
                    case 'employercredits':$layout = 'employer-credits';break;
                    case 'jobseekercredits':$layout = 'jobseeker-credits';break;
                    case 'employerpurchasehistory':$layout = 'employer-purchase-history';break;
                    case 'employermystats':$layout = 'employer-my-stats';break;
                    case 'jobseekerstats':$layout = 'jobseker-my-stats';break;
                    case 'regemployer':$layout = 'employer-register';break;
                    case 'regjobseeker':$layout = 'jobseeker-register';break;
                    case 'userregister':$layout = 'user-register';break;
                    case 'addresume':$layout = 'add-resume';break;
                    case 'myresumes':$layout = 'my-resumes';break;
                    case 'addcoverletter':$layout = 'add-cover-letter';break;
                    case 'companies':$layout = 'companies';break;
                    case 'myappliedjobs':$layout = 'my-applied-jobs';break;
                    case 'jobappliedresume':$layout = 'job-applied-resume';break;
                    case 'mycoverletters':$layout = 'my-cover-letters';break;
                    case 'jobsearch':$layout = 'job-search';break;
                    case 'jobsavesearch':$layout = 'job-save-searches';break;
                    case 'jobalert':$layout = 'job-alert';break;
                    case 'jobrss':$layout = 'job-rss';break;
                    case 'shortlistedjobs':$layout = 'shortlisted-jobs';break;
                    case 'jobseekerpurchasehistory':$layout = 'jobseeker-purchase-history';break;
                    case 'ratelistjobseeker':$layout = 'jobseeker-rate-list';break;
                    case 'ratelistemployer':$layout = 'employer-rate-list';break;
                    case 'jobseekercreditslog':$layout = 'jobseeker-credits-log';break;
                    case 'employercreditslog':$layout = 'employer-credits-log';break;
                    case 'jobsbycategories':$layout = 'job-categories';break;
                    case 'newestjobs':$layout = 'newest-jobs';break;
                    case 'jobsbytypes':$layout = 'job-by-types';break;
                    case 'pdf':$layout = 'resume-pdf';break;
                    case 'printresume':$layout = 'resume-print';break;
                    default:
                        $layout = $args['jsjobslt'];
                    break;
                }
                */
                global $wp_rewrite;
                $slug_prefix = JSJOBSincluder::getJSModel('configuration')->getConfigValue('home_slug_prefix');
                if($_redirect == site_url()){
                    $layout = $slug_prefix.$layout;
                }
                /*
                else{
                    if($_redirect == site_url()){
                        $layout = $slug_prefix.$layout;
                    }else{
                        $rules = json_encode($wp_rewrite->rules);
                        if(strpos($rules,$slug_prefix.$layout) !== false){
                            $layout = $slug_prefix. $layout;
                        }
                    }
                }
                */
                $_redirect .= '/' . $layout;                
            }
            // If is jobid
            if (isset($args['jobid'])) {
                $_redirect .= '/' . $args['jobid'];
            }
            // If is list
            if (isset($args['list'])) {
                $_redirect .= '/' . $args['list'];
            }
            // If is jsjobs_id
            if (isset($args['jsjobsid'])) {
                $jsjobs_id = $args['jsjobsid'];
                //$layout = str_replace('jm-', '', $layout);
                if($args['jsjobslt'] == 'viewjob'){
                    $job_seo = JSJOBSincluder::getJSModel('configuration')->getConfigValue('job_seo');
                    if(!empty($job_seo)){
                        $job_seo = JSJOBSincluder::getJSModel('job')->makeJobSeo($job_seo , $jsjobs_id);
                        if($job_seo != ''){
                            $id = JSJOBSincluder::getJSModel('common')->parseID($jsjobs_id);
                            $jsjobs_id = $job_seo.'-'.$id;
                        }
                    }        
                }elseif($args['jsjobslt'] == 'viewcompany'){
                    $company_seo = JSJOBSincluder::getJSModel('configuration')->getConfigValue('company_seo');
                    if(!empty($company_seo)){
                        $company_seo = JSJOBSincluder::getJSModel('company')->makeCompanySeo($company_seo , $jsjobs_id);
                        if($company_seo != ''){
                            $id = JSJOBSincluder::getJSModel('common')->parseID($jsjobs_id);
                            $jsjobs_id = $company_seo.'-'.$id;
                        }
                    }
                }elseif($args['jsjobslt'] == 'viewresume'){
                    $resume_seo = JSJOBSincluder::getJSModel('configuration')->getConfigValue('resume_seo');
                    if(!empty($resume_seo)){
                        $resume_seo = JSJOBSincluder::getJSModel('resume')->makeResumeSeo($resume_seo , $jsjobs_id);
                        if($resume_seo != ''){
                            $id = JSJOBSincluder::getJSModel('common')->parseID($jsjobs_id);
                            $jsjobs_id = $resume_seo.'-'.$id;
                        }
                    }            
                }

                $_redirect .= '/' . $jsjobs_id;
            }

            // If is ta
            if (isset($args['ta'])) {
                $_redirect .= '/' . $args['ta'];
            }
            // If is ta
            if (isset($args['viewtype'])) { // resume list or grid view 
                $_redirect .= '/vt-' . $args['viewtype'];
            }
            // If is jsscid
            if (isset($args['jsscid'])) {
                $_redirect .= '/sc-' . $args['jsscid'];
            }
            // If is category
            if (isset($args['category'])) {
                $category = $args['category'];
                $array = explode('-', $category);
                $count = count($array);
                $id = $array[$count - 1];
                unset($array[$count - 1]);
                $string = implode("-", $array);
                $finalstring = $string . '_10' . $id;
                $_redirect .= '/' . $finalstring;
            }
            // If is tags
            if (isset($args['tags'])) {
                $tags = $args['tags'];
                $finalstring = 'tags' . '_' . $tags;
                $_redirect .= '/' . $finalstring;
            }
            // If is jobtype
            if (isset($args['jobtype'])) {
                $jobtype = $args['jobtype'];
                $array = explode('-', $jobtype);
                $count = count($array);
                $id = $array[$count - 1];
                unset($array[$count - 1]);
                $string = implode("-", $array);
                $finalstring = $string . '_11' . $id;
                $_redirect .= '/' . $finalstring;
            }
            // If is company
            if (isset($args['company'])) {
                $company = $args['company'];
                $array = explode('-', $company);
                $count = count($array);
                $id = $array[$count - 1];
                unset($array[$count - 1]);
                $string = implode("-", $array);
                $finalstring = $string . '_12' . $id;
                $_redirect .='/' . $finalstring;
            }
            // If is search
            if (isset($args['search'])) {
                $search = $args['search'];
                $array = explode('-', $search);
                $count = count($array);
                $id = $array[$count - 1];
                unset($array[$count - 1]);
                $string = implode("-", $array);
                $finalstring = $string . '_13' . $id;
                $_redirect .='/' . $finalstring;
            }
            // If is city
            if (isset($args['city'])) {
                $alias = JSJOBSincluder::getJSModel('city')->getCityNamebyId($args['city']);
                $alias = JSJOBSincluder::getJSModel('common')->removeSpecialCharacter($alias);
                $_redirect .= '/'.$alias.'_14' . $args['city'];
            }

            // If is sortby
            if (isset($args['sortby'])) {
                //$_redirect .= '/sortby-' . $args['sortby'];
                $_redirect .= '/' . $args['sortby'];
            }
            // login redirect
            if (isset($args['jsjobsredirecturl'])) {
                //$_redirect .= '/sortby-' . $args['sortby'];
                $_redirect .= '/' . $args['jsjobsredirecturl'];
            }

            return $_redirect;
        }else{ // incase of form
            $redirect_url = add_query_arg($args,$permalink);
            return $redirect_url;
        }
        
    }

    static function bjencode($array){
        return base64_encode(json_encode($array));
    }

    static function bjdecode($array){
        return json_decode(base64_decode($array));
    }

}

$jsjobs = new jsjobs();
// lost your password link hook
add_action( 'login_form_middle', 'jsjobaddLostPasswordLink' );
function jsjobaddLostPasswordLink() {
   return '<a class="jsjb-jm-login-page-register-link" href="'.site_url().'/wp-login.php?action=lostpassword">'. __('Lost your password','js-jobs') .'?</a>';
}

add_action('init', 'jsjobs_custom_init_session', 1);

function jsjobs_custom_init_session() {
    if (!session_id())
        session_start();
    if(isset($_SESSION['jsjobs_apply_visitor'])){
        $layout = JSJOBSrequest::getVar('jsjobslt');
        if($layout != null && $layout != 'addresume'){ // reset the session id
            unset($_SESSION['jsjobs_apply_visitor']);
        }
    }
    if(isset($_SESSION['wp-jsjobs']) && isset($_SESSION['wp-jsjobs']['resumeid'])){
       $layout = JSJOBSrequest::getVar('jsjobslt');
       if($layout != null && $layout != 'addresume'){ // reset the session id
           unset($_SESSION['wp-jsjobs']);
       }
    }
}

function jsjobs_register_plugin_styles(){
    include_once 'includes/css/style_color.php';
    wp_enqueue_style('jsjob-jobseeker-style', jsjobs::$_pluginpath . 'includes/css/jobseekercp.css');
    wp_enqueue_style('jsjob-employer-style', jsjobs::$_pluginpath . 'includes/css/employercp.css');
    wp_enqueue_style('jsjob-style', jsjobs::$_pluginpath . 'includes/css/style.css');
    wp_enqueue_style('jsjob-style-tablet', jsjobs::$_pluginpath . 'includes/css/style_tablet.css',array(),'','(min-width: 481px) and (max-width: 780px)');
    wp_enqueue_style('jsjob-style-mobile-landscape', jsjobs::$_pluginpath . 'includes/css/style_mobile_landscape.css',array(),'','(min-width: 481px) and (max-width: 650px)');
    wp_enqueue_style('jsjob-style-mobile', jsjobs::$_pluginpath . 'includes/css/style_mobile.css',array(),'','(max-width: 480px)');
    wp_enqueue_style('jsjob-chosen-style', jsjobs::$_pluginpath . 'includes/js/chosen/chosen.min.css');
    if (is_rtl()) {
        wp_register_style('jsjob-style-rtl', jsjobs::$_pluginpath . 'includes/css/stylertl.css');
        wp_enqueue_style('jsjob-style-rtl');
    }

}

add_action( 'wp_enqueue_scripts', 'jsjobs_register_plugin_styles' );

function jsjobs_admin_register_plugin_styles() {
    wp_enqueue_style('jsjob-admin-desktop-css', jsjobs::$_pluginpath . 'includes/css/jsjobsadmin_desktop.css',array(),'','all');
    wp_enqueue_style('jsjob-admin-mobile-css', jsjobs::$_pluginpath . 'includes/css/jsjobsadmin_mobile.css',array(),'','(max-width: 480px)');
    wp_enqueue_style('jsjob-admin-mobile-landscape-css', jsjobs::$_pluginpath . 'includes/css/jsjobsadmin_mobile_landscape.css',array(),'','(min-width: 481px) and (max-width: 660px)');
    wp_enqueue_style('jsjob-admin-tablet-css', jsjobs::$_pluginpath . 'includes/css/jsjobsadmin_tablet.css',array(),'','(min-width: 481px) and (max-width: 780px)');
    if (is_rtl()) {
        wp_register_style('jsjob-admincss-rtl', jsjobs::$_pluginpath . 'includes/css/admincssrtl.css');
        wp_enqueue_style('jsjob-admincss-rtl');
    }
}
add_action( 'admin_enqueue_scripts', 'jsjobs_admin_register_plugin_styles' );

?>
