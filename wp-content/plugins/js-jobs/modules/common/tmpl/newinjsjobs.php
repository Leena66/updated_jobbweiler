<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
    $msgkey = JSJOBSincluder::getJSModel('common')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey);
    JSJOBSbreadcrumbs::getBreadcrumbs();
    include_once(jsjobs::$_path . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    $module = JSJOBSrequest::getVar('jsjobsme');
    $layout = JSJOBSrequest::getVar('jsjobslt');
    $currentuser = get_userdata(get_current_user_id());
    $uid = $currentuser->ID;

    $title = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('title');

    ?>
    <div id="jsjobs-wrapper">
        <div class="page_heading"><?php echo __( $title , 'js-jobs'); ?></div>
        <form class="js-ticket-form" id="coverletter_form" method="post" action="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'common', 'task'=>'savenewinjsjobs')); ?>">
            <div class="js-form-wrapper-newlogin">
                <div class="js-imagearea">
                    <div class="js-img">
                        <img id="jsjobslogin" src="<?php echo jsjobs::$_pluginpath;?>/includes/images/man-icon.png">
                    </div>
                </div>
                <div class="js-dataarea">                
                    <div class="js-col-md-12 js-form-heading"><?php echo __('Are you new in', 'js-jobs').' '.__( $title,'js-jobs'); ?></div>
                    <div class="js-col-md-12 js-form-title"><?php echo __('Please select your role', 'js-jobs'); ?>&nbsp;<font color="red">*</font></div>
                    <div class="js-col-md-12 js-form-value">
                        <?php echo JSJOBSformfield::select('roleid', JSJOBSincluder::getJSModel('common')->getRolesForCombo(''), '', __('Select Role'), array('class' => 'inputbox', 'data-validation' => 'required')); ?>
                    </div>
                    <?php echo JSJOBSformfield::hidden('desired_module', $module); ?>
                    <?php echo JSJOBSformfield::hidden('desired_layout', $layout); ?>
                    <?php echo JSJOBSformfield::hidden('id', ''); ?>
                    <?php echo JSJOBSformfield::hidden('uid', $uid); ?>
                    <?php echo JSJOBSformfield::hidden('action', 'common_savenewinjsjobs'); ?>
                    <?php echo JSJOBSformfield::hidden('jsjobspageid', get_the_ID()); ?>
                    <?php echo JSJOBSformfield::hidden('form_request', 'jsjobs'); ?>
                    <?php echo JSJOBSformfield::submitbutton('save', __('Submit', 'js-jobs'), array('class' => 'button jsjobs-newsubmit')); ?>
                </div>

            </div>
        </form>
    </div>
<?php 
}else{
    echo jsjobs::$_error_flag_message;
}
?>
