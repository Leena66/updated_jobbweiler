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
    <span class="js-admin-title">
        <a href="<?php echo admin_url('admin.php?page=jsjobs_coverletter'); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/back-icon.png" /></a>
        <?php $msg = __('Edit','js-jobs'); ?>
        <?php echo $msg . '&nbsp;' . __('Cover letter', 'js-jobs'); ?>
    </span>
    <form id="jsjobs-form" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_coverletter&task=savecoverletter"); ?>">
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Title', 'js-jobs'); ?><font class="required-notifier">*</font></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo JSJOBSformfield::text('title', isset(jsjobs::$_data[0]->title) ? jsjobs::$_data[0]->title : '', array('class' => 'inputbox one', 'data-validation' => 'required')) ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Description', 'js-jobs'); ?><font class="required-notifier">*</font></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_editor(isset(jsjobs::$_data[0]->description) ? jsjobs::$_data[0]->description : '', 'description', array('media_buttons' => false)); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Published', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo JSJOBSformfield::radiobutton('status', array('1' => __('Yes', 'js-jobs'), '0' => __('No', 'js-jobs')), isset(jsjobs::$_data[0]->status) ? jsjobs::$_data[0]->status : 1, array('class' => 'radiobutton')); ?></div>
        </div>
        <?php echo JSJOBSformfield::hidden('id', isset(jsjobs::$_data[0]->id) ? jsjobs::$_data[0]->id : ''); ?>
        <?php echo JSJOBSformfield::hidden('action', 'coverletter_savecoverletter'); ?>
        <?php echo JSJOBSformfield::hidden('form_request', 'jsjobs'); ?>
        <div class="js-submit-container js-col-lg-8 js-col-md-8 js-col-md-offset-2 js-col-md-offset-2">
            <a id="form-cancel-button" href="<?php echo admin_url('admin.php?page=jsjobs_coverletter'); ?>" ><?php echo __('Cancel', 'js-jobs'); ?></a>
            <?php echo JSJOBSformfield::submitbutton('save', __('Save','js-jobs') .' '. __('Cover letter', 'js-jobs'), array('class' => 'button')); ?>
        </div>
    </form>
</div>
</div>