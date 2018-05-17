<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $.validate();
    });
</script>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <?php $status = array((object) array('id' => 0, 'text' => __('Pending', 'js-jobs')), (object) array('id' => 1, 'text' => __('Approve', 'js-jobs')), (object) array('id' => -1, 'text' => __('Reject'))); ?>
    <span class="js-admin-title">
        <a href="<?php echo admin_url('admin.php?page=jsjobs_departments'); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/back-icon.png" /></a>
        <?php
        $heading = isset(jsjobs::$_data[0]) ? __('Edit', 'js-jobs') : __('Add New', 'js-jobs');
        echo $heading . '&nbsp' . __('Department', 'js-jobs');
        ?>
    </span>
    <form id="department_form" class="jsjobs-form" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_departments&task=savedepartment"); ?>">
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Company', 'js-jobs'); ?><font class="required-notifier">*</font></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo JSJOBSformfield::select('companyid', JSJOBSincluder::getJSModel('company')->getCompaniesForCombo(), isset(jsjobs::$_data[0]->companyid) ? jsjobs::$_data[0]->companyid : '', __('Select','js-jobs') .'&nbsp;'. __('Company', 'js-jobs'), array('class' => 'inputbox one', 'data-validation' => 'required')); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Department Name', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo JSJOBSformfield::text('name', isset(jsjobs::$_data[0]->name) ? jsjobs::$_data[0]->name : '', array('class' => 'inputbox one', 'data-validation' => 'required')); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Description', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_editor(isset(jsjobs::$_data[0]->description) ? jsjobs::$_data[0]->description : '', 'description', array('media_buttons' => false)); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin status-field-on-form">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Status', 'js-jobs'); ?><font class="required-notifier">*</font></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo JSJOBSformfield::select('status', $status, isset(jsjobs::$_data[0]->status) ? jsjobs::$_data[0]->status : 1, __('Select Status', 'js-jobs'), array('class' => 'inputbox one', 'data-validation' => 'required')); ?></div>
        </div>
        <?php echo JSJOBSformfield::hidden('isqueue', isset($_GET['isqueue']) ? 1 : 0); ?>
        <?php echo JSJOBSformfield::hidden('id', isset(jsjobs::$_data[0]->id) ? jsjobs::$_data[0]->id : ''); ?>
        <?php echo JSJOBSformfield::hidden('action', 'department_savedepartment'); ?>
        <?php echo JSJOBSformfield::hidden('form_request', 'jsjobs'); ?>
        <?php echo JSJOBSformfield::hidden('isadmin', '1'); ?>
        <?php echo JSJOBSformfield::hidden('payment', ''); ?>
        <?php echo JSJOBSformfield::hidden('creditid', ''); ?>
        <?php echo JSJOBSformfield::hidden('tast', 'savedepartment'); ?>
        <?php echo JSJOBSformfield::hidden('uid', ''); ?>
        <div class="js-submit-container js-col-lg-8 js-col-md-8 js-col-md-offset-2 js-col-md-offset-2">
            <a id="form-cancel-button" href="<?php echo admin_url('admin.php?page=jsjobs_departments'); ?>" ><?php echo __('Cancel', 'js-jobs'); ?></a>
            <?php
               echo JSJOBSformfield::submitbutton('save', __('Save','js-jobs') .' '. __('Department', 'js-jobs'), array('class' => 'button')); 
            ?>
        </div>
    </form>
</div>
</div>