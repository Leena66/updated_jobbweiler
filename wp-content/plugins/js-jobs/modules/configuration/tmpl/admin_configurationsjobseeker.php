<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jquery-ui-tabs');
$yesno = array((object) array('id' => 1, 'text' => __('Yes', 'js-jobs')), (object) array('id' => 0, 'text' => __('No', 'js-jobs')));
$showhide = array((object) array('id' => 1, 'text' => __('Show', 'js-jobs')), (object) array('id' => 0, 'text' => __('Hide', 'js-jobs')));
$applybutton = array((object) array('id' => 1, 'text' => __('Enable')), (object) array('id' => 2, 'text' => __('Disable')));
$msgkey = JSJOBSincluder::getJSModel('configuration')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
$theme = wp_get_theme();
$theme_chk = 0;
if($theme == 'Job Manager'){
    $theme_chk = 1;
}
?>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <span class="js-admin-title">
        <a href="<?php echo admin_url('admin.php?page=jsjobs'); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Job Seeker Configuration', 'js-jobs'); ?>
    </span>
    <form id="jsjobs-form" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_configuration&task=saveconfiguration") ?>">
        <div id="tabs" class="tabs">
            <ul>
                <li><a href="#js_generalsetting"><?php echo __('General Settings', 'js-jobs'); ?></a></li>
                <li><a href="#js_resume_setting"><?php echo __('Resume Settings', 'js-jobs'); ?></a></li>
                <li><a href="#js_visitor"><?php echo __('Visitors', 'js-jobs'); ?></a></li>
                <li><a href="#js_jobsearch"><?php echo __('Job Search', 'js-jobs'); ?></a></li>
                <li><a href="#js_memberlinks"><?php echo __('Members Links', 'js-jobs'); ?></a></li>
                <li><a href="#js_visitorlinks"><?php echo __('Visitors Links', 'js-jobs'); ?></a></li>
                <li><a href="#email"><?php echo __('Email Alert', 'js-jobs'); ?></a></li>
            </ul>
            <div class="tabInner">
                <div id="js_generalsetting">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('General Settings', 'js-jobs'); ?></h3>
                    <div class="left">
                    <?php /*
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Enable gold resume', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('system_have_gold_resume', $yesno, jsjobs::$_data[0]['system_have_gold_resume']); ?></div>
                            <div><small><?php echo __('Gold resume are allowed in plugin', 'js-jobs'); ?></small></div>
                        </div>
                        */?>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Enable featured resume', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('system_have_featured_resume', $yesno, jsjobs::$_data[0]['system_have_featured_resume']); ?></div>
                            <div><small><?php echo __('Featured resume are allowed in plugin', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Show company contact detail', 'js-jobs').' <small>( '.__('effect on credits system').' )</small>'; ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('company_contact_detail', $yesno, jsjobs::$_data[0]['company_contact_detail']); ?></div>
                            <div><small><?php echo __('If no then credits will be taken to view contact detail', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Show apply button', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('showapplybutton', $yesno, jsjobs::$_data[0]['showapplybutton']); ?></div>
                            <div><small><?php echo __('Controls the visibility of apply now button in plugin', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Apply now redirect link', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::text('applybuttonredirecturl', jsjobs::$_data[0]['applybuttonredirecturl'], array('class' => 'inputbox')); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('Click on Apply Now button will be redirect to given url', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Show applied resume status', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('show_applied_resume_status', $yesno, jsjobs::$_data[0]['show_applied_resume_status']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Show count in jobs by categories page', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('categories_numberofjobs', $yesno, jsjobs::$_data[0]['categories_numberofjobs']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Show count in jobs by types page', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('jobtype_numberofjobs', $yesno, jsjobs::$_data[0]['jobtype_numberofjobs']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Job seeker Registration redirect page ', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('register_jobseeker_redirect_page', JSJOBSincluder::getJSModel('postinstallation')->getPageList(), jsjobs::$_data[0]['register_jobseeker_redirect_page']); ?></div>
                            <div><small><?php echo __('whenever anyone registers as job seeker, he will be redirected to this page', 'js-jobs'); ?></small></div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Resume','js-jobs') .' '. __('auto approve', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('empautoapprove', $yesno, jsjobs::$_data[0]['empautoapprove']); ?></div>
                        </div> 
                        <?php /*
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Gold','js-jobs') .' '. __('resume','js-jobs') .' '. __('auto approve', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('goldresume_autoapprove', $yesno, jsjobs::$_data[0]['goldresume_autoapprove']); ?></div>
                        </div>
                        */ ?>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Featured','js-jobs') .' '. __('resume','js-jobs') .' '. __('auto approve', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('featuredresume_autoapprove', $yesno, jsjobs::$_data[0]['featuredresume_autoapprove']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Job alert for visitor', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('overwrite_jobalert_settings', $yesno, jsjobs::$_data[0]['overwrite_jobalert_settings']); ?> </div>
                        </div> 
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Job short list', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('allow_jobshortlist', $yesno, jsjobs::$_data[0]['allow_jobshortlist']); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('Job short list setting effects on jobs listing page', 'js-jobs'); ?></small></div>
                        </div> 
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Job alert','js-jobs') .' '. __('auto approve', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('jobalert_auto_approve', $yesno, jsjobs::$_data[0]['jobalert_auto_approve']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Tell a friend', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('allow_tellafriend', $yesno, jsjobs::$_data[0]['allow_tellafriend']); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('Tell a friend setting effects on jobs listing page', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Show login logout button', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('jobsloginlogout', $yesno, jsjobs::$_data[0]['jobsloginlogout']); ?> </div>
                            <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('Show login logout button in job seeker control panel', 'js-jobs'); ?></small></div>
                        </div>
                    </div>
                </div>
                <div id="js_resume_setting">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Resume Settings', 'js-jobs'); ?></h3>
                    <div class="js-job-configuration-table">
                        <div class="left">
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Document file extensions', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('document_file_type', jsjobs::$_data[0]['document_file_type'], array('class' => 'inputbox')); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Document file extensions allowed', 'js-jobs'); ?>, <?php echo __('Must be comma separated', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Resume file maximum size', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::text('document_file_size', jsjobs::$_data[0]['document_file_size'], array('class' => 'inputbox not-full-width', 'data-validation' => 'number')); ?>&nbsp KB</div>
                                <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('System will not upload if resume file size exceeds than given size', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Number of files for resume', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::text('document_max_files', jsjobs::$_data[0]['document_max_files'], array('class' => 'inputbox', 'data-validation' => 'number')); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('Maximum number of files that job seeker can upload in resume', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Resume photo maximum size ', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::text('resume_photofilesize', jsjobs::$_data[0]['resume_photofilesize'], array('class' => 'inputbox not-full-width', 'data-validation' => 'number')); ?> &nbsp;KB</div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Number of employers allowed', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::text('max_resume_employers', jsjobs::$_data[0]['max_resume_employers'], array('class' => 'inputbox', 'data-validation' => 'number')); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('Maximum number of employers allowed in resume', 'js-jobs'); ?></small></div>
                            </div>
                        </div>          
                        <div class="right">
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Number of institutes allowed', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::text('max_resume_institutes', jsjobs::$_data[0]['max_resume_institutes'], array('class' => 'inputbox', 'data-validation' => 'number')); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('Maximum number of institutes allowed in resume', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Number of languages allowed', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::text('max_resume_languages', jsjobs::$_data[0]['max_resume_languages'], array('class' => 'inputbox', 'data-validation' => 'number')); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('Maximum number of languages allowed in resume', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Number of references allowed', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::text('max_resume_references', jsjobs::$_data[0]['max_resume_references'], array('class' => 'inputbox', 'data-validation' => 'number')); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('Maximum number of references allowed in resume', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Number of addresses allowed', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::text('max_resume_addresses', jsjobs::$_data[0]['max_resume_addresses'], array('class' => 'inputbox', 'data-validation' => 'number')); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('Maximum number of addresses allowed in resume', 'js-jobs'); ?></small></div>
                            </div>
                        </div>      
                    </div>
                </div>
                <div id="js_visitor">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Job Seeker', 'js-jobs'); ?></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Visitor can apply to job', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('visitor_can_apply_to_job', $yesno, jsjobs::$_data[0]['visitor_can_apply_to_job']); ?></div>
                        </div> 
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Visitor can add resume', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('visitor_can_add_resume', $yesno, jsjobs::$_data[0]['visitor_can_add_resume']); ?></div>
                        </div> 
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Show login message to visitor', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('visitor_show_login_message', $yesno, jsjobs::$_data[0]['visitor_show_login_message']); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('Show login option to visitor on job apply', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Visitor post resume redirect page ', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('visitor_add_resume_redirect_page', JSJOBSincluder::getJSModel('postinstallation')->getPageList(), jsjobs::$_data[0]['visitor_add_resume_redirect_page']); ?></div>
                            <div><small><?php echo __('whenever any visitor posts a resume, he will be redirected to this page', 'js-jobs'); ?></small></div>
                        </div>
                    </div> 
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Show captcha on resume form', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('resume_captcha', $yesno, jsjobs::$_data[0]['resume_captcha']); ?><div><small><?php echo __('Show captcha on visitor form resume', 'js-jobs'); ?></small></div></div>
                        </div> 
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Show captcha on Job alert form', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('job_alert_captcha', $yesno, jsjobs::$_data[0]['job_alert_captcha']); ?><br clear="all"/><div><small><?php echo __('Show captcha visitor job alert form', 'js-jobs'); ?></small></div></div>
                        </div> 
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Show captcha on tell a friend popup', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('tell_a_friend_captcha', $yesno, jsjobs::$_data[0]['tell_a_friend_captcha']); ?><br clear="all"/><div><small><?php echo __('Show captcha on visitor tell a friend popup', 'js-jobs'); ?></small></div></div>
                        </div>
                    </div>
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Visitors Can View Job seeker', 'js-jobs'); ?></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Control Panel', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('visitorview_js_controlpanel', $showhide, jsjobs::$_data[0]['visitorview_js_controlpanel']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('View company', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('visitorview_emp_viewcompany', $showhide, jsjobs::$_data[0]['visitorview_emp_viewcompany']); ?></div>
                        </div> 
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('View Job', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('visitorview_emp_viewjob', $showhide, jsjobs::$_data[0]['visitorview_emp_viewjob']); ?></div>
                        </div> 
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Jobs By Categories', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('visitorview_js_jobcat', $showhide, jsjobs::$_data[0]['visitorview_js_jobcat']); ?></div>
                        </div> 
                    </div>
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Newest jobs', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('visitorview_js_newestjobs', $showhide, jsjobs::$_data[0]['visitorview_js_newestjobs']); ?></div>
                        </div> 
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Search job', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('visitorview_js_jobsearch', $showhide, jsjobs::$_data[0]['visitorview_js_jobsearch']); ?></div>
                        </div> 
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Job search result', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('visitorview_js_jobsearchresult', $showhide, jsjobs::$_data[0]['visitorview_js_jobsearchresult']); ?></div>
                        </div> 
                    </div>
                
                </div>
                <div id="js_jobsearch">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Search Job Settings', 'js-jobs'); ?></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Allow save search', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('search_job_showsave', $yesno, jsjobs::$_data[0]['search_job_showsave']); ?></div>
                            <div><small><?php echo __('User can save search criteria', 'js-jobs'); ?></small></div>
                        </div> 
                    </div>
                </div>
                <div id="js_memberlinks">
                    <?php if($theme_chk == 0){ ?>     
                        <h3 class="js-job-configuration-heading-main"><?php echo __('Job Seeker Top Menu Links','js-jobs'); ?></h3>
                        <div class="left">
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Control Panel', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('tmenu_jscontrolpanel', $showhide, jsjobs::$_data[0]['tmenu_jscontrolpanel']); ?></div>
                            </div> 
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Jobs By Categories', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('tmenu_jsjobcategory', $showhide, jsjobs::$_data[0]['tmenu_jsjobcategory']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Newest Jobs', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('tmenu_jsnewestjob', $showhide, jsjobs::$_data[0]['tmenu_jsnewestjob']); ?></div>
                            </div>
                        </div>
                        <div class="right"> 
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Resumes', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('tmenu_jsmyresume', $showhide, jsjobs::$_data[0]['tmenu_jsmyresume']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Search Job', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('tmenu_jssearchjob', $showhide, jsjobs::$_data[0]['tmenu_jssearchjob']); ?></div>
                            </div>
                        </div>
                    <?php }else{ ?>
                            <h3 class="js-job-configuration-heading-main"><?php echo __('Job Seeker Dashboard','js-jobs'); ?></h3>
                            <div class="left">
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Jobs Graph', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('temp_jobseeker_dashboard_jobs_graph', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_jobs_graph']); ?></div>
                                </div> 
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Useful Links', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('temp_jobseeker_dashboard_useful_links', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_useful_links']); ?></div>
                                </div>
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Applied jobs', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('temp_jobseeker_dashboard_apllied_jobs', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_apllied_jobs']); ?></div>
                                </div>
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Shortlisted Jobs', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('temp_jobseeker_dashboard_shortlisted_jobs', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_shortlisted_jobs']); ?></div>
                                </div>
                            </div>
                            <div class="right"> 
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Credits Log', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('temp_jobseeker_dashboard_credits_log', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_credits_log']); ?></div>
                                </div>
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Purchase History', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('temp_jobseeker_dashboard_purchase_history', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_purchase_history']); ?></div>
                                </div>
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Newest Jobs', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('temp_jobseeker_dashboard_newest_jobs', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_newest_jobs']); ?></div>
                                </div>
                            </div>
                    <?php } ?>
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Job Seeker Control Panel Links','js-jobs'); ?></h3>
                    <div class="left">
                        <?php if($theme_chk == 0){ ?>     
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Active Jobs Graph', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('jsactivejobs_graph', $showhide, jsjobs::$_data[0]['jsactivejobs_graph']); ?></div>
                            </div>
                        <?php } ?>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('User Notifications', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('js_cpnotification', $showhide, jsjobs::$_data[0]['js_cpnotification']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('User Messages', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('js_cpmessage', $showhide, jsjobs::$_data[0]['js_cpmessage']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Applied Resumes Box', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('jsappliedresume_box', $showhide, jsjobs::$_data[0]['jsappliedresume_box']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Resumes', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('myresumes', $showhide, jsjobs::$_data[0]['myresumes']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Add','js-jobs') .' '. __('Resume', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('formresume', $showhide, jsjobs::$_data[0]['formresume']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Cover Letters', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('mycoverletters', $showhide, jsjobs::$_data[0]['mycoverletters']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Add','js-jobs') .' '. __('Cover Letter', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('formcoverletter', $showhide, jsjobs::$_data[0]['formcoverletter']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('All Companies', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('listallcompanies', $showhide, jsjobs::$_data[0]['listallcompanies']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">   
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Jobs By Categories', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('jobcat', $showhide, jsjobs::$_data[0]['jobcat']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">  
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Newest Jobs', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('listnewestjobs', $showhide, jsjobs::$_data[0]['listnewestjobs']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">    
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Jobs By Types', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('listjobbytype', $showhide, jsjobs::$_data[0]['listjobbytype']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">  
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Credits', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('jscredits', $showhide, jsjobs::$_data[0]['jscredits']); ?></div>
                        </div>
                    </div>
                    <div class="right">
                        <?php if($theme_chk == 0){ ?>     
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Newest Jobs Box', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('jssuggestedjobs_box', $showhide, jsjobs::$_data[0]['jssuggestedjobs_box']); ?></div>
                            </div>
                        <?php } ?>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Purchase History', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('jspurchasehistory', $showhide, jsjobs::$_data[0]['jspurchasehistory']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row"> 
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Search Job', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('jobsearch', $showhide, jsjobs::$_data[0]['jobsearch']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row"> 
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Saved Searches', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('my_jobsearches', $showhide, jsjobs::$_data[0]['my_jobsearches']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Job Alert', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('jobalertsetting', $showhide, jsjobs::$_data[0]['jobalertsetting']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row"> 
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Messages', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('jsmessages', $showhide, jsjobs::$_data[0]['jsmessages']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Jobs RSS', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('jsjob_rss', $showhide, jsjobs::$_data[0]['jsjob_rss']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Register', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('jsregister', $showhide, jsjobs::$_data[0]['jsregister']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">  
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Short Listed Jobs', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('listjobshortlist', $showhide, jsjobs::$_data[0]['listjobshortlist']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Credits Log', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('jscreditlog', $showhide, jsjobs::$_data[0]['jscreditlog']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Rate List', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('jsratelist', $showhide, jsjobs::$_data[0]['jsratelist']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row"> 
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Applied Jobs', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('myappliedjobs', $showhide, jsjobs::$_data[0]['myappliedjobs']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Stats', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('jsmystats', $showhide, jsjobs::$_data[0]['jsmystats']); ?></div>
                        </div>
                    </div>
                </div>
                <div id="js_visitorlinks">
                    <?php if($theme_chk == 0){ ?>     
                        <h3 class="js-job-configuration-heading-main"><?php echo __('Job Seeker Top Menu Links','js-jobs'); ?></h3>
                        <div class="left">
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Control Panel', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('tmenu_vis_jscontrolpanel', $showhide, jsjobs::$_data[0]['tmenu_vis_jscontrolpanel']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row"> 
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Jobs By Categories', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('tmenu_vis_jsjobcategory', $showhide, jsjobs::$_data[0]['tmenu_vis_jsjobcategory']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Search Job', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('tmenu_vis_jssearchjob', $showhide, jsjobs::$_data[0]['tmenu_vis_jssearchjob']); ?></div>
                            </div>
                        </div>
                        <div class="right">
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row"> 
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Newest Jobs', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('tmenu_vis_jsnewestjob', $showhide, jsjobs::$_data[0]['tmenu_vis_jsnewestjob']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Resumes', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('tmenu_vis_jsmyresume', $showhide, jsjobs::$_data[0]['tmenu_vis_jsmyresume']); ?></div>
                            </div>
                        </div>
                    <?php }else{ ?>
                            <h3 class="js-job-configuration-heading-main"><?php echo __('Job Seeker Dashboard','js-jobs'); ?></h3>
                            <div class="left">
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Jobs Graph', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_temp_jobseeker_dashboard_jobs_graph', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_jobs_graph']); ?></div>
                                </div> 
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Useful Links', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_temp_jobseeker_dashboard_useful_links', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_useful_links']); ?></div>
                                </div>
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Applied jobs', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_temp_jobseeker_dashboard_apllied_jobs', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_apllied_jobs']); ?></div>
                                </div>
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Shortlisted Jobs', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_temp_jobseeker_dashboard_shortlisted_jobs', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_shortlisted_jobs']); ?></div>
                                </div>
                            </div>
                            <div class="right"> 
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Credits Log', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_temp_jobseeker_dashboard_credits_log', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_credits_log']); ?></div>
                                </div>
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Purchase History', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_temp_jobseeker_dashboard_purchase_history', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_purchase_history']); ?></div>
                                </div>
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Newest Jobs', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_temp_jobseeker_dashboard_newest_jobs', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_newest_jobs']); ?></div>
                                </div>
                            </div>
                    <?php } ?>
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Job Seeker Control Panel Links','js-jobs'); ?></h3>
                    <div class="left">

                        <?php if($theme_chk == 0){ ?>     
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Active Jobs Graph', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_jsactivejobs_graph', $showhide, jsjobs::$_data[0]['vis_jsactivejobs_graph']); ?></div>
                            </div>
                        <?php } ?>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Applied Resumes Box', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_jsappliedresume_box', $showhide, jsjobs::$_data[0]['vis_jsappliedresume_box']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Resumes', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_jsmyresumes', $showhide, jsjobs::$_data[0]['vis_jsmyresumes']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Add','js-jobs') .' '. __('Resume', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_jsformresume', $showhide, jsjobs::$_data[0]['vis_jsformresume']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Cover Letters', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_jsmycoverletters', $showhide, jsjobs::$_data[0]['vis_jsmycoverletters']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Add','js-jobs') .' '. __('Cover Letter', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_jsformcoverletter', $showhide, jsjobs::$_data[0]['vis_jsformcoverletter']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('All Companies', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_jslistallcompanies', $showhide, jsjobs::$_data[0]['vis_jslistallcompanies']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row"> 
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Jobs By Categories', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_jsjobcat', $showhide, jsjobs::$_data[0]['vis_jsjobcat']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">  
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Newest Jobs', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_jslistnewestjobs', $showhide, jsjobs::$_data[0]['vis_jslistnewestjobs']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">   
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Jobs By Types', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_jslistjobbytype', $showhide, jsjobs::$_data[0]['vis_jslistjobbytype']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">  
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Applied Jobs', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_jsmyappliedjobs', $showhide, jsjobs::$_data[0]['vis_jsmyappliedjobs']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">  
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Credits', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_jscredits', $showhide, jsjobs::$_data[0]['vis_jscredits']); ?></div>
                        </div>
                    </div>
                    <div class="right">
                        <?php if($theme_chk == 0){ ?>     
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Suggested Jobs Box', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                               <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_jssuggestedjobs_box', $showhide, jsjobs::$_data[0]['vis_jssuggestedjobs_box']); ?></div>
                            </div>
                        <?php } ?>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Credits Log', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_jscreditlog', $showhide, jsjobs::$_data[0]['vis_jscreditlog']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Purchase History', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_jspurchasehistory', $showhide, jsjobs::$_data[0]['vis_jspurchasehistory']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row"> 
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Search Job', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_jsjobsearch', $showhide, jsjobs::$_data[0]['vis_jsjobsearch']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">    
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Saved Searches', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_jsmy_jobsearches', $showhide, jsjobs::$_data[0]['vis_jsmy_jobsearches']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Job Alert', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_jsjobalertsetting', $showhide, jsjobs::$_data[0]['vis_jsjobalertsetting']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">   
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Messages', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_jsmessages', $showhide, jsjobs::$_data[0]['vis_jsmessages']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Jobs RSS', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_job_rss', $showhide, jsjobs::$_data[0]['vis_job_rss']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Rate List', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_jsratelist', $showhide, jsjobs::$_data[0]['vis_jsratelist']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Register', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_jsregister', $showhide, jsjobs::$_data[0]['vis_jsregister']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Stats', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_jsmystats', $showhide, jsjobs::$_data[0]['vis_jsmystats']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Short Listed Jobs', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_jslistjobshortlist', $showhide, jsjobs::$_data[0]['vis_jslistjobshortlist']); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-desc"><small></small></div>
                        </div>
                    </div>
                </div>
                <div id="email">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Applied Resume Alert', 'js-jobs'); ?></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Applied resume notification', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('jobseeker_resume_applied_status', $yesno, jsjobs::$_data[0]['jobseeker_resume_applied_status']); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('Applied resume status change mail to jobseeker', 'js-jobs'); ?></small></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo JSJOBSformfield::hidden('isgeneralbuttonsubmit', 0); ?>
        <?php echo JSJOBSformfield::hidden('jsjobslt', 'configurationsjobseeker'); ?>
        <?php echo JSJOBSformfield::hidden('action', 'configuration_saveconfiguration'); ?>
        <?php echo JSJOBSformfield::hidden('form_request', 'jsjobs'); ?>
        <div class="js-form-button">
            <?php echo JSJOBSformfield::submitbutton('save', __('Save','js-jobs') .' '. __('Configuration', 'js-jobs'), array('class' => 'button')); ?>
        </div>    
        <div class="js-form-button">
            <font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font>
            <?php echo __('Pro Version Only', 'js-jobs');?>
        </div>        
    </form>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            var value = jQuery("#showapplybutton").val();
            var divsrc = "div#showhideapplybutton";
            if (value == 2) {
                jQuery(divsrc).slideDown("slow");
            }
        });
        function showhideapplybutton(src, value) {
            var divsrc = "div#" + src;
            if (value == 2) {
                jQuery(divsrc).slideDown("slow");
            } else if (value == 1) {
                jQuery(divsrc).slideUp("slow");
                jQuery(divsrc).hide();
            }
            return true;
        }

        jQuery(document).ready(function () {
            jQuery("#tabs").tabs();
        });
    </script>
</div>
</div>
