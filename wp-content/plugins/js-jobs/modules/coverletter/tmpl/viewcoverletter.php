<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

$msgkey = JSJOBSincluder::getJSModel('coverletter')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(jsjobs::$_path . 'includes/header.php');
if (jsjobs::$_error_flag == null) { ?>
    <div id="jsjobs-wrapper">
        <div class="page_heading">
            <?php echo __('Cover Letter', 'js-jobs'); ?>
        </div>
        <div id="cover-letter-wrapper-title">
            <span class="cover-letter-title" ><?php echo __('Name', 'js-jobs').': '; ?></span><span class="wrapper-text" ><?php echo jsjobs::$_data[0]->title; ?></span>
        </div>
        <div id="cover-letter-wrapper-disc">
            <span class="cover-letter-title" ><?php echo __('Description', 'js-jobs').': '; ?></span><span class="wrapper-text1" ><?php echo jsjobs::$_data[0]->description; ?></span>
        </div>    
    </div>
<?php 
}else{
    echo jsjobs::$_error_flag_message;
}
?>
