<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
jsjobs::$_data['resumeid'] = isset(jsjobs::$_data['resumeid']) ? jsjobs::$_data['resumeid'] : '';

echo JSJOBSformfield::hidden('resume_temp', jsjobs::$_data['resumeid']);
$msgkey = JSJOBSincluder::getJSModel('resume')->getMessagekey();

    
    if(! is_admin()){
        JSJOBSbreadcrumbs::getBreadcrumbs();
        include_once(jsjobs::$_path . 'includes/header.php');        
    }
JSJOBSMessages::getLayoutMessage($msgkey);
if (jsjobs::$_error_flag == null) {
    ?>
    <div id="resume-wating" class="loading"></div>
    <div id="black_wrapper_jobapply" style="display:none;"></div>
    <div id="warn-message" style="display: none;">
        <span class="close-warnmessage"><img src="<?php echo jsjobs::$_pluginpath; ?>/includes/images/close-icon.png" /></span>
        <img src="<?php echo jsjobs::$_pluginpath; ?>/includes/images/warning-icon.png" />
        <span class="text"></span>
    </div>
    <div id="resume-files-popup-wrapper" style="display:none;">
        <span class="close-resume-files"><?php echo __('Resume Files', 'js-jobs'); ?><img src="<?php echo jsjobs::$_pluginpath; ?>/includes/images/popup-close.png" /></span>
        <div class="resumepopupsectionwrapper">
            <span class="clickablefiles"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/resume/select-file.png"/><?php echo __('Select files', 'js-jobs'); ?></span>
            <span class="headingpopup"><?php echo __('Selected files', 'js-jobs'); ?></span>
            <span id="resume-files-selected"><?php echo __('No file selected', 'js-jobs'); ?></span>
            <div class="resume-filepopup-lowersection-wrapper">
                <div class="allowedfiles"><?php echo __('Files allowed', 'js-jobs') . '&nbsp;(&nbsp;' . JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('document_max_files') . '&nbsp;)'; ?></div>
                <div class="allowedextension">(&nbsp;<?php echo JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('document_file_type'); ?>&nbsp;)</div>
                <div class="allowedsize"><?php echo __('Maximum file size', 'js-jobs') . '&nbsp;(&nbsp;' . JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('document_file_size') . '&nbsp;KB&nbsp;)'; ?></div>
            </div>
        </div>
    </div>
    <div id="jsjobs-wrapper" <?php if (isset($_SESSION['jsjobs_apply_visitor'])) echo 'style="padding-bottom:63px;"'; ?>>
        <?php $msg = isset(jsjobs::$_data[0]) ? __('Edit', 'js-jobs') : __('Add New', 'js-jobs'); ?>
        <div class="page_heading"><?php echo $msg . '&nbsp;' . __("Resume", 'js-jobs'); ?></div>
        <?php
        $resumelayout = JSJOBSincluder::getObjectClass('resumeformlayout');
        //var_dump(jsjobs::$_data[2]);
        $resumelayout->printResume();
        /*if (isset($_SESSION['jsjobs_apply_visitor'])) {
            echo  '<div class="js-jobs-resume-apply-now-visitor" style="position:absolute; top: 100%;width:100%;z-index:9999;">
                        <div class="js-jobs-resume-apply-now-text">'.__('Please save your resume first then press apply now button','js-jobs').'</div>
                        <div class="js-jobs-resume-apply-now-button">
                            <input id="jsjobs-cancel-btn" type="button" onclick="cancelJobApplyVisitor();" link="javascript:void(0);" value="'.__('Cancel','js-jobs').'" />
                            <input id="jsjobs-login-btn" type="button" onclick="JobApplyVisitor();" link="javascript:void(0);" value="'.__('Apply Now','js-jobs').'" />
                        </div>
                    </div>';

        }*/
        ?>
    </div>
    <div id="ajax-loader" style="display:none"><img src="<?php echo jsjobs::$_pluginpath; ?>/includes/images/loading.gif"></div>
<?php 
}else{
    echo jsjobs::$_error_flag_message;
}
?>