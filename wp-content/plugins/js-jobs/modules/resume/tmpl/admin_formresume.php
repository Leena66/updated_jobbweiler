<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
    include_once jsjobs::$_path.'includes/css/style_color.php';
wp_enqueue_style('jsjob-style', jsjobs::$_pluginpath . 'includes/css/style.css');
wp_enqueue_style('jsjob-style-mobile', jsjobs::$_pluginpath . 'includes/css/style_mobile.css',array(),'','(max-width: 480px)');;
wp_enqueue_style('jsjob-jobseeker-style', jsjobs::$_pluginpath . 'includes/css/jobseekercp.css');
wp_enqueue_style('jsjob-employer-style', jsjobs::$_pluginpath . 'includes/css/employercp.css');
if (is_rtl()) {
    wp_register_style('jsjob-style-rtl', jsjobs::$_pluginpath . 'includes/css/stylertl.css');
    wp_enqueue_style('jsjob-style-rtl');
}

$_SESSION['jsjobsresumeeditadmin'] = 1;
?>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <span class="js-admin-title">
        <span class="heading">
            <a href="<?php echo admin_url('admin.php?page=jsjobs_resume'); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/back-icon.png" /></a>
            <span class="text-heading"><?php echo __('Resume', 'js-jobs') ?></span>
        </span>
    </span>
    <?php
    require_once(jsjobs::$_path . 'modules/resume/tmpl/addresume.inc.php');
    require_once(jsjobs::$_path . 'modules/resume/tmpl/addresume.php');
    ?>
    </div>
</div>