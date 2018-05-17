<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

$msgkey = JSJOBSincluder::getJSModel('coverletter')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(jsjobs::$_path . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    $msg = isset(jsjobs::$_data[0]) ? __('Edit', 'js-jobs') : __('Add', 'js-jobs');
    ?>
    <div id="jsjobs-wrapper">
        <div class="page_heading"><?php echo $msg.' '. __("Cover Letter", 'js-jobs'); ?></div>
        <form class="js-ticket-form" id="coverletter_form" method="post" action="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'coverletter', 'task'=>'savecoverletter')); ?>">
            <div class="js-col-md-12 js-form-wrapper">
                <div class="js-col-md-12 js-form-title"><?php echo __('Title', 'js-jobs'); ?>&nbsp;<font color="red">*</font></div>
                <div class="js-col-md-12 js-form-value"><?php echo JSJOBSformfield::text('title', isset(jsjobs::$_data[0]->title) ? jsjobs::$_data[0]->title : '', array('class' => 'inputbox', 'data-validation' => 'required')) ?></div>
            </div>
            <div class="js-col-md-12 js-form-wrapper">
                <div class="js-col-md-12 js-form-title"><?php echo __('Description', 'js-jobs'); ?></div>
                <div class="js-col-md-12 js-form-value" id="cover-letter-desc"><?php echo wp_editor(isset(jsjobs::$_data[0]->description) ? jsjobs::$_data[0]->description : '', 'description', array('media_buttons' => false)); ?></div>
            </div>
            <?php echo JSJOBSformfield::hidden('id', isset(jsjobs::$_data[0]->id) ? jsjobs::$_data[0]->id : '' ); ?>
            <?php echo JSJOBSformfield::hidden('uid', JSJOBSincluder::getObjectClass('user')->uid()); ?>
            <?php echo JSJOBSformfield::hidden('created', isset(jsjobs::$_data[0]->created) ? jsjobs::$_data[0]->created : date('Y-m-d H:i:s')); ?>
            <?php echo JSJOBSformfield::hidden('action', 'coverletter_savecoverletter'); ?>
            <?php echo JSJOBSformfield::hidden('jsjobspageid', get_the_ID()); ?>
            <?php echo JSJOBSformfield::hidden('form_request', 'jsjobs'); ?>
            <div class="js-col-md-12 js-form-button" id="save-button">			    	
                <?php
                    echo JSJOBSformfield::submitbutton('save', __('Save','js-jobs') .' '. __('Cover Letter', 'js-jobs'), array('class' => 'button'));
                ?>
            </div>
        </form>
    </div>
<?php 
}else{
    echo jsjobs::$_error_flag_message;
}
?>