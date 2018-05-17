<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
$msgkey = JSJOBSincluder::getJSModel('departments')->getMessagekey();

JSJOBSMessages::getLayoutMessage($msgkey);
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(jsjobs::$_path . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    ?>
    <div id="jsjobs-wrapper">
        <div class="page_heading"><?php echo __('View Department', 'js-jobs'); ?></div>
        <div id="department-name">
            <span class="view-department-title" ><?php echo __('Name', 'js-jobs').': '; ?></span><span class="wrapper-text" ><?php echo jsjobs::$_data[0]->name ?></span>
        </div>
        <div id="department-company">
            <span class="view-department-title" ><?php echo __('Company', 'js-jobs').': '; ?></span><span class="wrapper-text" ><?php echo jsjobs::$_data[0]->companyname ?></span>
        </div>
        <div id="department-disc">
            <span class="view-department-title" ><?php echo __('Description', 'js-jobs').': '; ?></span><span class="wrapper-text1" ><?php echo jsjobs::$_data[0]->description ?></span>
        </div>    
    </div>
<?php 
}else{
    echo jsjobs::$_error_flag_message;
}
?>
