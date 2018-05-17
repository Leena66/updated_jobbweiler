<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jquery-ui-tabs');

$yesno = array((object) array('id' => 1, 'text' => __('Yes', 'js-jobs')), (object) array('id' => 0, 'text' => __('No', 'js-jobs')));
$yesnosectino = array((object) array('id' => 1, 'text' => __('Only section that have value', 'js-jobs')), (object) array('id' => 0, 'text' => __('All sections', 'js-jobs')));
$showhide = array((object) array('id' => 1, 'text' => __('Show', 'js-jobs')), (object) array('id' => 0, 'text' => __('Hide', 'js-jobs')));
$resumealert = array((object) array('id' => '', 'text' => __('Select Option')), (object) array('id' => 1, 'text' => __('All Fields')), (object) array('id' => 2, 'text' => __('Only filled fields', 'js-jobs')));
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
        <?php echo __('Employer Configuration', 'js-jobs'); ?>
    </span>
    <form id="jsjobs-form" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_configuration&task=saveconfiguration") ?>">
        <div id="tabs" class="tabs">
            <ul>
                <li><a href="#emp_generalsetting"><?php echo __('General Settings', 'js-jobs'); ?></a></li>
                <li><a href="#emp_visitor"><?php echo __('Visitors', 'js-jobs'); ?></a></li>
                <li><a href="#emp_listresume"><?php echo __('Search Resume', 'js-jobs'); ?></a></li>
                <li><a href="#emp_company"><?php echo __('Company', 'js-jobs'); ?></a></li>
                <li><a href="#emp_memberlinks"><?php echo __('Members Links', 'js-jobs'); ?></a></li>
                <li><a href="#emp_visitorlinks"><?php echo __('Visitor Links', 'js-jobs'); ?></a></li>
                <li><a href="#email"><?php echo __('Email', 'js-jobs'); ?></a></li>
            </ul>
            <div class="tabInner">
                <div id="emp_generalsetting">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('General Settings', 'js-jobs'); ?></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Enable Employer Area', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('disable_employer', $yesno, jsjobs::$_data[0]['disable_employer']); ?></div>
                            <div><small><?php echo __('If no then front end employer area is not accessable', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Allow user to register as employer', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('showemployerlink', $yesno, jsjobs::$_data[0]['showemployerlink']); ?></div>
                            <div><small><?php echo __('effects on user registration', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Employer can view job seeker area', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('employerview_js_controlpanel', $yesno, jsjobs::$_data[0]['employerview_js_controlpanel']); ?> </div>
                        </div>
                        <?php /*
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Enable','js-jobs') .' '. __('gold','js-jobs') .' '. __('company', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('system_have_gold_company', $yesno, jsjobs::$_data[0]['system_have_gold_company']); ?></div>
                            <div><small><?php echo __('Gold companies are allowed in plugin', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Enable','js-jobs') .' '. __('gold','js-jobs') .' '. __('job', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('system_have_gold_job', $yesno, jsjobs::$_data[0]['system_have_gold_job']); ?></div>
                            <div><small><?php echo __('Gold jobs are allowed in plugin', 'js-jobs'); ?></small></div>
                        </div>
                        */ ?>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Enable','js-jobs') .' '. __('featured','js-jobs') .' '. __('company', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('system_have_featured_company', $yesno, jsjobs::$_data[0]['system_have_featured_company']); ?></div>
                            <div><small><?php echo __('Featured companies are allowed in plugin', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Enable','js-jobs') .' '. __('featured','js-jobs') .' '. __('job', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('system_have_featured_job', $yesno, jsjobs::$_data[0]['system_have_featured_job']); ?></div>
                            <div><small><?php echo __('Featured jobs are allowed in plugin', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Company logo maximum size', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::text('company_logofilezize', jsjobs::$_data[0]['company_logofilezize'], array('class' => 'inputbox not-full-width', 'data-validation' => 'number')); ?> &nbsp;&nbsp;KB</div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Show resume contact detail', 'js-jobs').' <small>( '.__('effect on credits system').' )</small>'; ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('resume_contact_detail', $yesno, jsjobs::$_data[0]['resume_contact_detail']); ?></div>
                            <div><small><?php echo __('If no then credits will be taken to view contact detail', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Employer Registration Redirect Page ', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('register_employer_redirect_page', JSJOBSincluder::getJSModel('postinstallation')->getPageList(), jsjobs::$_data[0]['register_employer_redirect_page']); ?></div>
                            <div><small><?php echo __('whenever anyone registers as employer he will be redirected to this page', 'js-jobs'); ?></small></div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Company','js-jobs') .' '. __('auto approve', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('companyautoapprove', $yesno, jsjobs::$_data[0]['companyautoapprove']); ?></div>
                        </div>
                        <?php /*
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Gold','js-jobs') .' '. __('company','js-jobs') .' '. __('auto approve', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('goldcompany_autoapprove', $yesno, jsjobs::$_data[0]['goldcompany_autoapprove']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Gold','js-jobs') .' '. __('job','js-jobs') .' '. __('auto approve', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('goldjob_autoapprove', $yesno, jsjobs::$_data[0]['goldjob_autoapprove']); ?> </div>
                        </div>
                        */?>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Featured','js-jobs') .' '. __('company','js-jobs') .' '. __('auto approve', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('featuredcompany_autoapprove', $yesno, jsjobs::$_data[0]['featuredcompany_autoapprove']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Job','js-jobs') .' '. __('auto approve', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('jobautoapprove', $yesno, jsjobs::$_data[0]['jobautoapprove']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Featured','js-jobs') .' '. __('job','js-jobs') .' '. __('auto approve', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('featuredjob_autoapprove', $yesno, jsjobs::$_data[0]['featuredjob_autoapprove']); ?> </div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Department','js-jobs') .' '. __('auto approve', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('department_auto_approve', $yesno, jsjobs::$_data[0]['department_auto_approve']); ?> </div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Folder','js-jobs') .' '. __('auto approve', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('folder_auto_approve', $yesno, jsjobs::$_data[0]['folder_auto_approve']); ?> </div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Show login logout button', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('emploginlogout', $yesno, jsjobs::$_data[0]['emploginlogout']); ?><div><small><?php echo __('Show login/logout button on employer control panel', 'js-jobs'); ?></small></div></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Show count in resume categories', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('categories_numberofresumes', $yesno, jsjobs::$_data[0]['categories_numberofresumes']); ?></div>
                        </div>
                    </div>
                </div>
                <div id="emp_visitor">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Job Posting Options', 'js-jobs'); ?></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Visitor can post job', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('visitor_can_post_job', $yesno, jsjobs::$_data[0]['visitor_can_post_job']); ?><div><small><?php echo __('Visitor can post a job', 'js-jobs'); ?></small></div></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Allow edit job', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('visitor_can_edit_job', $yesno, jsjobs::$_data[0]['visitor_can_edit_job']); ?><div><small><?php echo __('Visitor can edit his posted job', 'js-jobs'); ?></small></div></div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Show captcha', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('job_captcha', $yesno, jsjobs::$_data[0]['job_captcha']); ?><div><small><?php echo __('Show captcha on visitor form job', 'js-jobs'); ?></small></div></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Visitor post job redirect page ', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('visitor_add_job_redirect_page', JSJOBSincluder::getJSModel('postinstallation')->getPageList(), jsjobs::$_data[0]['visitor_add_job_redirect_page']); ?></div>
                            <div><small><?php echo __('whenever any visitor posts a job, he will be redirected to this page', 'js-jobs'); ?></small></div>
                        </div>
                    </div>
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Visitors Can View Employer', 'js-jobs'); ?></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Resume Search', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('visitorview_emp_resumesearch', $showhide, jsjobs::$_data[0]['visitorview_emp_resumesearch']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('View Resume', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('visitorview_emp_viewresume', $showhide, jsjobs::$_data[0]['visitorview_emp_viewresume']); ?></div>
                        </div> 
                    </div>
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Resume Categories', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('visitorview_emp_resumecat', $showhide, jsjobs::$_data[0]['visitorview_emp_resumecat']); ?></div>
                        </div> 
                    </div>
                </div>
                <div id="emp_listresume">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Search Resume Settings', 'js-jobs'); ?></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Allow save search', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('search_resume_showsave', $yesno, jsjobs::$_data[0]['search_resume_showsave']); ?></div>
                            <div><small><?php echo __('User can save search criteria', 'js-jobs'); ?></small></div>
                        </div>
                    </div>
                </div>
                <div id="emp_company">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Company Settings', 'js-jobs'); ?></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Company','js-jobs') .' '. __('Name', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('comp_name', $showhide, jsjobs::$_data[0]['comp_name']); ?></div>
                            <div><small><?php echo __('Effects on jobs listing and view company page', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Company','js-jobs') .' '. __('Email address', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('comp_email_address', $showhide, jsjobs::$_data[0]['comp_email_address']); ?></div>
                            <div><small><?php echo __('Effects on view company page', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">                        
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('City', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('comp_city', $showhide, jsjobs::$_data[0]['comp_city']); ?></div>
                            <div><small><?php echo __('Effects on company listing and view company page', 'js-jobs'); ?></small></div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Company URL', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('comp_show_url', $showhide, jsjobs::$_data[0]['comp_show_url']); ?></div>
                            <div><small><?php echo __('Effects on view company page', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Zip code', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('comp_zipcode', $showhide, jsjobs::$_data[0]['comp_zipcode']); ?></div>
                            <div><small><?php echo __('Effects on view company page', 'js-jobs'); ?></small></div>
                        </div>
                    </div>
                </div>
                <div id="emp_memberlinks">
                    <?php if($theme_chk == 0){ ?>     
                        <h3 class="js-job-configuration-heading-main"><?php echo __('Employer Top Menu Links', 'js-jobs'); ?></h3>
                        <div class="left">
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Control Panel', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('tmenu_emcontrolpanel', $showhide, jsjobs::$_data[0]['tmenu_emcontrolpanel']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Add','js-jobs') .' '. __('Job', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('tmenu_emnewjob', $showhide, jsjobs::$_data[0]['tmenu_emnewjob']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Resume Search', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('tmenu_emsearchresume', $showhide, jsjobs::$_data[0]['tmenu_emsearchresume']); ?></div>
                            </div>
                        </div>
                        <div class="right">
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Companies', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('tmenu_emmycompanies', $showhide, jsjobs::$_data[0]['tmenu_emmycompanies']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Jobs', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('tmenu_emmyjobs', $showhide, jsjobs::$_data[0]['tmenu_emmyjobs']); ?></div>
                            </div>
                        </div>
                    <?php }else{ ?>
                        <h3 class="js-job-configuration-heading-main"><?php echo __('Employer Dashboard', 'js-jobs'); ?></h3>
                        <div class="left">
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Stats Graph', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('temp_employer_dashboard_stats_graph', $showhide, jsjobs::$_data[0]['temp_employer_dashboard_stats_graph']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Useful Links','js-jobs') .' '. __('Job', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('temp_employer_dashboard_useful_links', $showhide, jsjobs::$_data[0]['temp_employer_dashboard_useful_links']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Applied Resume', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('temp_employer_dashboard_applied_resume', $showhide, jsjobs::$_data[0]['temp_employer_dashboard_applied_resume']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Saved Search', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('temp_employer_dashboard_saved_search', $showhide, jsjobs::$_data[0]['temp_employer_dashboard_saved_search']); ?></div>
                            </div>
                        </div>
                        <div class="right">
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Credits Log', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('temp_employer_dashboard_credits_log', $showhide, jsjobs::$_data[0]['temp_employer_dashboard_credits_log']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Purchase History', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('temp_employer_dashboard_purchase_history', $showhide, jsjobs::$_data[0]['temp_employer_dashboard_purchase_history']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Newest Resume', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('temp_employer_dashboard_newest_resume', $showhide, jsjobs::$_data[0]['temp_employer_dashboard_newest_resume']); ?></div>
                            </div>
                        </div>
                    <?php } ?>
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Employer Control Panel Links', 'js-jobs'); ?></h3>
                    <div class="left">
                        <?php if($theme_chk == 0){ ?>     
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Jobs Graph', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('jobs_graph', $showhide, jsjobs::$_data[0]['jobs_graph']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Resume Graph', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('resume_graph', $showhide, jsjobs::$_data[0]['resume_graph']); ?></div>
                            </div>
                        <?php } ?>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('User Notifications', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('em_cpnotification', $showhide, jsjobs::$_data[0]['em_cpnotification']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('User Messages', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('em_cpmessage', $showhide, jsjobs::$_data[0]['em_cpmessage']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Companies', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('mycompanies', $showhide, jsjobs::$_data[0]['mycompanies']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Add','js-jobs') .' '. __('Company', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('formcompany', $showhide, jsjobs::$_data[0]['formcompany']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Jobs', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('myjobs', $showhide, jsjobs::$_data[0]['myjobs']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Add','js-jobs') .' '. __('Job', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('formjob', $showhide, jsjobs::$_data[0]['formjob']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Resume Search', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('resumesearch', $showhide, jsjobs::$_data[0]['resumesearch']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Resume By Categories', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('emresumebycategory', $showhide,jsjobs::$_data[0]['emresumebycategory']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Saved Searches', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('my_resumesearches', $showhide, jsjobs::$_data[0]['my_resumesearches']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Register', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('empregister', $showhide, jsjobs::$_data[0]['empregister']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Rate List', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('empratelist', $showhide, jsjobs::$_data[0]['empratelist']); ?></div>
                        </div>
                        
                    </div>
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Stats', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('empmystats', $showhide, jsjobs::$_data[0]['empmystats']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Messages', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('empmessages', $showhide, jsjobs::$_data[0]['empmessages']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Credits', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('empcredits', $showhide, jsjobs::$_data[0]['empcredits']); ?></div>
                        </div>
                        <?php if($theme_chk == 0){ ?>     
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Newest Resume Box', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('box_newestresume', $showhide, jsjobs::$_data[0]['box_newestresume']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Applied Resume Box', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('box_appliedresume', $showhide, jsjobs::$_data[0]['box_appliedresume']); ?></div>
                            </div>
                        <?php } ?>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Credits Log', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('empcreditlog', $showhide, jsjobs::$_data[0]['empcreditlog']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Purchase History', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('emppurchasehistory', $showhide, jsjobs::$_data[0]['emppurchasehistory']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Departments', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('mydepartment', $showhide, jsjobs::$_data[0]['mydepartment']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Add','js-jobs') .' '. __('Department', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('formdepartment', $showhide, jsjobs::$_data[0]['formdepartment']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Folders', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('myfolders', $showhide, jsjobs::$_data[0]['myfolders']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Add','js-jobs') .' '. __('Folder', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('newfolders', $showhide, jsjobs::$_data[0]['newfolders']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Resume RSS', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('empresume_rss', $showhide, jsjobs::$_data[0]['empresume_rss']); ?></div>
                        </div>
                        
                    </div>
                </div>
                <div id="emp_visitorlinks">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Control Panel', 'js-jobs'); ?></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Control Panel', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('visitorview_emp_conrolpanel', $showhide, jsjobs::$_data[0]['visitorview_emp_conrolpanel']); ?></div>
                            <div><small><?php echo __('Enable disable control panel for visitor', 'js-jobs'); ?></small></div>
                        </div>
                    </div>
                    <?php if($theme_chk == 0){ ?>     
                        <h3 class="js-job-configuration-heading-main"><?php echo __('Employer Top Menu Links', 'js-jobs'); ?></h3>
                        <div class="left">
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Control Panel', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('tmenu_vis_emcontrolpanel', $showhide, jsjobs::$_data[0]['tmenu_vis_emcontrolpanel']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Add','js-jobs') .' '. __('Job', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('tmenu_vis_emnewjob', $showhide, jsjobs::$_data[0]['tmenu_vis_emnewjob']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Resume Search', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('tmenu_vis_emsearchresume', $showhide, jsjobs::$_data[0]['tmenu_vis_emsearchresume']); ?></div>
                            </div>
                        </div>
                        <div class="right">
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Jobs', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('tmenu_vis_emmyjobs', $showhide, jsjobs::$_data[0]['tmenu_vis_emmyjobs']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Companies', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('tmenu_vis_emmycompanies', $showhide, jsjobs::$_data[0]['tmenu_vis_emmycompanies']); ?></div>
                            </div>
                        </div>
                    <?php }else{ ?>
                        <h3 class="js-job-configuration-heading-main"><?php echo __('Employer Dashboard', 'js-jobs'); ?></h3>
                        <div class="left">
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Stats Graph', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_temp_employer_dashboard_stats_graph', $showhide, jsjobs::$_data[0]['temp_employer_dashboard_stats_graph']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Useful Links','js-jobs') .' '. __('Job', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_temp_employer_dashboard_useful_links', $showhide, jsjobs::$_data[0]['temp_employer_dashboard_useful_links']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Applied Resume', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_temp_employer_dashboard_applied_resume', $showhide, jsjobs::$_data[0]['temp_employer_dashboard_applied_resume']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Saved Search', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_temp_employer_dashboard_saved_search', $showhide, jsjobs::$_data[0]['temp_employer_dashboard_saved_search']); ?></div>
                            </div>
                        </div>
                        <div class="right">
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Credits Log', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_temp_employer_dashboard_credits_log', $showhide, jsjobs::$_data[0]['temp_employer_dashboard_credits_log']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Purchase History', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_temp_employer_dashboard_purchase_history', $showhide, jsjobs::$_data[0]['temp_employer_dashboard_purchase_history']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Newest Resume', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_temp_employer_dashboard_newest_resume', $showhide, jsjobs::$_data[0]['temp_employer_dashboard_newest_resume']); ?></div>
                            </div>
                        </div>
                    <?php } ?>
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Employer Control Panel Links', 'js-jobs'); ?></h3>    
                    <div class="left">
                        <?php if($theme_chk == 0){ ?>     
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Jobs Graph', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_jobs_graph', $showhide, jsjobs::$_data[0]['vis_jobs_graph']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Resume Graph', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_resume_graph', $showhide, jsjobs::$_data[0]['vis_resume_graph']); ?></div>
                            </div>
                        <?php } ?>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Companies', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_emmycompanies', $showhide, jsjobs::$_data[0]['vis_emmycompanies']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Add','js-jobs') .' '. __('Company', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_emformcompany', $showhide, jsjobs::$_data[0]['vis_emformcompany']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Jobs', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_emmyjobs', $showhide, jsjobs::$_data[0]['vis_emmyjobs']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Add','js-jobs') .' '. __('Job', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_emformjob', $showhide, jsjobs::$_data[0]['vis_emformjob']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Applied Resume', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_emalljobsappliedapplications', $showhide, jsjobs::$_data[0]['vis_emalljobsappliedapplications']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Resume Search', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_emresumesearch', $showhide, jsjobs::$_data[0]['vis_emresumesearch']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Saved Searches', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_emmy_resumesearches', $showhide, jsjobs::$_data[0]['vis_emmy_resumesearches']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Rate List', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_empratelist', $showhide, jsjobs::$_data[0]['vis_empratelist']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Credits', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_empcredits', $showhide, jsjobs::$_data[0]['vis_empcredits']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Credits Log', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_empcreditlog', $showhide, jsjobs::$_data[0]['vis_empcreditlog']); ?></div>
                        </div>
                    </div>
                    <div class="right">
                        <?php if($theme_chk == 0){ ?>     
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Newest Resume Box', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_box_newestresume', $showhide, jsjobs::$_data[0]['vis_box_newestresume']); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Applied Resume Box', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_box_appliedresume', $showhide, jsjobs::$_data[0]['vis_box_appliedresume']); ?></div>
                            </div>
                        <?php } ?>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Departments', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_emmydepartment', $showhide, jsjobs::$_data[0]['vis_emmydepartment']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Add','js-jobs') .' '. __('Department', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_emformdepartment', $showhide, jsjobs::$_data[0]['vis_emformdepartment']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Folders', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_emmyfolders', $showhide, jsjobs::$_data[0]['vis_emmyfolders']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Add','js-jobs') .' '. __('Folder', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_emnewfolders', $showhide, jsjobs::$_data[0]['vis_emnewfolders']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Purchase History', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_emppurchasehistory', $showhide, jsjobs::$_data[0]['vis_emppurchasehistory']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Messages', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_emmessages', $showhide, jsjobs::$_data[0]['vis_emmessages']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Resume RSS', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_resume_rss', $showhide, jsjobs::$_data[0]['vis_resume_rss']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Register', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_emempregister', $showhide, jsjobs::$_data[0]['vis_emempregister']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Stats', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_empmystats', $showhide, jsjobs::$_data[0]['vis_empmystats']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Resume By Categories', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('vis_emresumebycategory', $showhide,jsjobs::$_data[0]['vis_emresumebycategory']); ?></div>
                        </div>                        
                    </div>
                </div>
                <div id="email">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Email Alert To Employer On Resume Apply', 'js-jobs'); ?></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                           <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('What to include in email', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                           <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('show_only_section_that_have_value', $yesnosectino, jsjobs::$_data[0]['show_only_section_that_have_value']); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><small><?php echo __('All sections are included in employer email content or only sections that have value','js-jobs') .'.'.__('This option is only valid if employer selected send resume data in email settings while posting job', 'js-jobs'); ?></small></div>
                       </div>
                    </div>
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('What to include in email', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::select('employer_resume_alert_fields', $resumealert, jsjobs::$_data[0]['employer_resume_alert_fields']); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><small><?php echo __('All fields are included in employer email content or only filled fields','js-jobs') .'.'.__('This option is only valid if employer selected send resume data in email settings while posting job', 'js-jobs'); ?></small></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo JSJOBSformfield::hidden('isgeneralbuttonsubmit', 0); ?>
        <?php echo JSJOBSformfield::hidden('jsjobslt', 'configurationsemployer'); ?>
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

    <script>
        jQuery(document).ready(function () {
            jQuery("#tabs").tabs();
        });
    </script>
</div>
</div>
