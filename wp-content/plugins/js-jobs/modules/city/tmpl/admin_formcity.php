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
        <a href="<?php echo admin_url('admin.php?page=jsjobs_city&countryid='.$_SESSION["countryid"]); ?>&stateid=<?php echo $_SESSION["stateid"]; ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/back-icon.png" /></a>
        <?php
        $heading = isset(jsjobs::$_data[0]) ? __('Edit', 'js-jobs') : __('Add New', 'js-jobs');
        echo $heading . '&nbsp' . __('City', 'js-jobs');
        ?>
    </span>
    <?php
    $countryid = $_SESSION["countryid"];
    $stateid = $_SESSION["stateid"];
    ?>
    <form id="jsjobs-form" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_city&task=savecity&action=jsjobtask"); ?>">
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('State', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo JSJOBSformfield::select('stateid', JSJOBSincluder::getJSModel('state')->getStatesForCombo(isset(jsjobs::$_data[0]) ? jsjobs::$_data[0]->countryid : $countryid ), isset(jsjobs::$_data[0]) ? jsjobs::$_data[0]->stateid : $stateid, __('Select','js-jobs') .'&nbsp;'. __('State', 'js-jobs'), array('class' => 'inputbox one')); ?></div>
        </div>
        
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('City', 'js-jobs'); ?><font class="required-notifier">*</font></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo JSJOBSformfield::text('name', isset(jsjobs::$_data[0]->name) ? __(jsjobs::$_data[0]->name,'js-jobs') : '', array('class' => 'inputbox one', 'data-validation' => 'required')) ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Latitude', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo JSJOBSformfield::text('latitude', isset(jsjobs::$_data[0]->latitude) ? __(jsjobs::$_data[0]->latitude,'js-jobs') : '', array('class' => 'inputbox one')) ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Longitude', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo JSJOBSformfield::text('longitude', isset(jsjobs::$_data[0]->longitude) ? __(jsjobs::$_data[0]->longitude,'js-jobs') : '', array('class' => 'inputbox one')) ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Published', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo JSJOBSformfield::radiobutton('enabled', array('1' => __('Yes', 'js-jobs'), '0' => __('No', 'js-jobs')), isset(jsjobs::$_data[0]->enabled) ? jsjobs::$_data[0]->enabled : 1, array('class' => 'radiobutton')); ?></div>
        </div>
        <?php echo JSJOBSformfield::hidden('id', isset(jsjobs::$_data[0]->id) ? jsjobs::$_data[0]->id : ''); ?>
        <?php
        if (isset(jsjobs::$_data[0]->id) AND ( jsjobs::$_data[0]->id != 0)) {
            echo JSJOBSformfield::hidden('isedit', 1);
        }
        ?>
        <?php echo JSJOBSformfield::hidden('action', 'city_savecity'); ?>
        <?php echo JSJOBSformfield::hidden('form_request', 'js-jobs'); ?>
        <div class="js-submit-container js-col-lg-8 js-col-md-8 js-col-md-offset-2 js-col-md-offset-2">
            <a id="form-cancel-button" href="<?php echo admin_url('admin.php?page=jsjobs_city&countryid='.$_SESSION["countryid"]); ?>&stateid=<?php echo $_SESSION["stateid"]; ?>" ><?php echo __('Cancel', 'js-jobs'); ?></a>
            <?php echo JSJOBSformfield::submitbutton('save', __('Save','js-jobs') .' '. __('City', 'js-jobs'), array('class' => 'button')); ?>
        </div>
    </form>
</div>
</div>


