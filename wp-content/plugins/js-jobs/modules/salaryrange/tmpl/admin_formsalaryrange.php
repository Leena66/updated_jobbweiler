<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $.validate();
    });
    function validate_ranges() {
        var rangeend = jQuery("#rangeend").val();
        var rvalue = parseInt(rangeend);
        var value = document.getElementById("rangestart").value;
        if (value > rvalue) {
            event.preventDefault();
            alert("<?php echo __('Range start can not be greater than range end', 'js-jobs'); ?>");
        }
        return true;
    }
</script>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <span class="js-admin-title">
        <a href="<?php echo admin_url('admin.php?page=jsjobs_salaryrange'); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/back-icon.png" /></a>
        <?php
        $heading = isset(jsjobs::$_data[0]) ? __('Edit', 'js-jobs') : __('Add New', 'js-jobs');
        echo $heading . '&nbsp' . __('Salary Range', 'js-jobs');
        ?>
    </span>
    <form id="jsjobs-form" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_salaryrange&task=savesalaryrange"); ?>">
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Salary Range Start', 'js-jobs'); ?><font class="required-notifier">*</font></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo JSJOBSformfield::text('rangestart', isset(jsjobs::$_data[0]->rangestart) ? jsjobs::$_data[0]->rangestart : '', array('class' => 'inputbox one', 'data-validation' => 'required')) ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Salary Range End', 'js-jobs'); ?><font class="required-notifier">*</font></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo JSJOBSformfield::text('rangeend', isset(jsjobs::$_data[0]->rangeend) ? jsjobs::$_data[0]->rangeend : '', array('class' => 'inputbox one', 'data-validation' => 'required')) ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Published', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo JSJOBSformfield::radiobutton('status', array('1' => __('Yes', 'js-jobs'), '0' => __('No', 'js-jobs')), isset(jsjobs::$_data[0]->status) ? jsjobs::$_data[0]->status : 1, array('class' => 'radiobutton')); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Default', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo JSJOBSformfield::radiobutton('isdefault', array('1' => __('Yes', 'js-jobs'), '0' => __('No', 'js-jobs')), isset(jsjobs::$_data[0]->isdefault) ? jsjobs::$_data[0]->isdefault : 0, array('class' => 'radiobutton')); ?></div>
        </div>
        <?php echo JSJOBSformfield::hidden('id', isset(jsjobs::$_data[0]->id) ? jsjobs::$_data[0]->id : ''); ?>
        <?php echo JSJOBSformfield::hidden('ordering', isset(jsjobs::$_data[0]->ordering) ? jsjobs::$_data[0]->ordering : '' ); ?>
        <?php echo JSJOBSformfield::hidden('action', 'salaryrange_savesalaryrange'); ?>
        <?php echo JSJOBSformfield::hidden('jsjobs_isdefault', isset(jsjobs::$_data[0]->isdefault) ? jsjobs::$_data[0]->isdefault : ''); ?>
<?php echo JSJOBSformfield::hidden('form_request', 'jsjobs'); ?>
        <div class="js-submit-container js-col-lg-8 js-col-md-8 js-col-md-offset-2 js-col-md-offset-2">
            <a id="form-cancel-button" href="<?php echo admin_url('admin.php?page=jsjobs_salaryrange'); ?>" ><?php echo __('Cancel', 'js-jobs'); ?></a>
<?php echo JSJOBSformfield::submitbutton('save', __('Save','js-jobs') .' '. __('Salary Range', 'js-jobs'), array('class' => 'button', 'onclick' => 'return validate_ranges(event);')); ?>
        </div>
    </form>
</div>
</div>
