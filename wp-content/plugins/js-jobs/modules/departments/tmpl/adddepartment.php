<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

$msgkey = JSJOBSincluder::getJSModel('departments')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(jsjobs::$_path . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
        $msg = isset(jsjobs::$_data[0]) ? __('Edit', 'js-jobs') : __('Add New', 'js-jobs');
        ?>
        <div id="jsjobs-wrapper">
            <div class="page_heading"><?php echo $msg .'&nbsp;'. __("Department", 'js-jobs'); ?></div>
            <form class="js-ticket-form" id="department_form" method="post" action="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'departments', 'task'=>'savedepartment')); ?>">
            <div class="js-col-md-12 js-form-wrapper">
                <div class="js-col-md-12 js-form-title"><?php echo __('Company', 'js-jobs'); ?>&nbsp;<font color="red">*</font></div>
                <div class="js-col-md-12 js-form-value"><?php echo JSJOBSformfield::select('companyid', JSJOBSincluder::getJSModel('company')->getUserCompaniesForCombo(), isset(jsjobs::$_data[0]->companyid) ? jsjobs::$_data[0]->companyid : '', '', array('class' => 'inputbox', 'data-validation' => 'required')); ?></div>
            </div>
            <div class="js-col-md-12 js-form-wrapper">
                <div class="js-col-md-12 js-form-title"><?php echo __('Department Name', 'js-jobs'); ?>&nbsp;<font color="red">*</font></div>
                <div class="js-col-md-12 js-form-value"><?php echo JSJOBSformfield::text('name', isset(jsjobs::$_data[0]->name) ? jsjobs::$_data[0]->name : '', array('class' => 'inputbox', 'data-validation' => 'required')) ?></div>
            </div>
            <div class="js-col-md-12 js-form-wrapper">
                <div class="js-col-md-12 js-form-title"><?php echo __('Description', 'js-jobs'); ?></div>
                <div class="js-col-md-12 js-form-value"><?php echo wp_editor(isset(jsjobs::$_data[0]->description) ? jsjobs::$_data[0]->description : '', 'description', array('media_buttons' => false)); ?></div>
            </div>
            <?php echo JSJOBSformfield::hidden('id', isset(jsjobs::$_data[0]->id) ? jsjobs::$_data[0]->id : '' ); ?>
            <?php echo JSJOBSformfield::hidden('creditid', 0); ?>
            <?php echo JSJOBSformfield::hidden('uid', JSJOBSincluder::getObjectClass('user')->uid()); ?>
            <?php echo JSJOBSformfield::hidden('created', isset(jsjobs::$_data[0]->created) ? jsjobs::$_data[0]->created : date('Y-m-d H:i:s')); ?>
            <?php echo JSJOBSformfield::hidden('action', 'coverletter_savecoverletter'); ?>
            <?php echo JSJOBSformfield::hidden('jsjobspageid', get_the_ID()); ?>
            <?php echo JSJOBSformfield::hidden('form_request', 'jsjobs'); ?>
            <div class="js-col-md-12 js-form-button" id="save-button">			    	
                <?php
                    echo JSJOBSformfield::submitbutton('save', __('Save','js-jobs') .' '. __('Department', 'js-jobs'), array('class' => 'button'));
                ?>
            </div>
        </form>
    </div>
<?php 
}else{
    echo jsjobs::$_error_flag_message;
}
?>