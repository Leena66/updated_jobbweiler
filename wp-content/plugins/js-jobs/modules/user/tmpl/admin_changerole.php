<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
$role = jsjobs::$_data[0];
?>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <span class="js-admin-title">
        <a href="<?php echo admin_url('admin.php?page=jsjobs_user'); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Change Role', 'js-jobs'); ?>
    </span>
    <form id="jsjobs-form" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_user&task=saveuserrole"); ?>">
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Name', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-5 js-col-md-5 no-padding"><?php echo $role->first_name . ' ' . $role->last_name; ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Username', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-5 js-col-md-5 no-padding"><?php echo $role->user_login; ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Group', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-5 js-col-md-5 no-padding"><?php echo JSJOBSincluder::getJSModel('user')->getWPRoleNameById($role->wpuid); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('ID', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-5 js-col-md-5 no-padding"><?php echo $role->id; ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Role', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-5 js-col-md-5 no-padding"><?php echo JSJOBSformfield::select('roleid', JSJOBSincluder::getJSModel('common')->getRolesForCombo(), isset(jsjobs::$_data[0]->roleid) ? jsjobs::$_data[0]->roleid : '', '', array('class' => 'inputbox')); ?></div>
        </div>
        <?php
        if ($role) {
            if (($role->dated == '0000-00-00 00:00:00') || ($role->dated == ''))
                $curdate = date_i18n('Y-m-d H:i:s');
            else
                $curdate = $role->dated;
        }else {
            $curdate = date_i18n('Y-m-d H:i:s');
        }
        ?>
        <?php echo JSJOBSformfield::hidden('id', $role->id); ?>
        <?php echo JSJOBSformfield::hidden('created', $curdate); ?>
        <?php echo JSJOBSformfield::hidden('action', 'user_saveuserrole'); ?>
        <?php echo JSJOBSformfield::hidden('form_request', 'jsjobs'); ?>
        <div class="js-submit-container js-col-lg-8 js-col-md-8 js-col-md-offset-2 js-col-md-offset-2">
            <a id="form-cancel-button" href="<?php echo admin_url('admin.php?page=jsjobs_user'); ?>" ><?php echo __('Cancel', 'js-jobs'); ?></a>
            <?php echo JSJOBSformfield::submitbutton('save', __('Change Role', 'js-jobs'), array('class' => 'button')); ?>
        </div>
    </form>
</div>
</div>