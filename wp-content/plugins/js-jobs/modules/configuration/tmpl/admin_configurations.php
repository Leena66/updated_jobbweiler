<script>
    jQuery(document).ready(function ($) {
        jQuery("#tabs").tabs();

        $.validate();
        jQuery('#loginwithlinkedin').change(function(){
            var isselect = jQuery('#loginwithlinkedin option:selected').val();
            if(isselect == 1){
                jQuery('#apikeylinkedin').attr('data-validation', 'required');
            }else{
                jQuery('#apikeylinkedin').removeAttr('data-validation');
            }
        });

        jQuery('#loginwithfacebook').change(function(){
            var isselect = jQuery('#loginwithfacebook option:selected').val();
            if(isselect == 1){
                jQuery('#apikeyfacebook').attr('data-validation', 'required');
            }else{
                jQuery('#apikeyfacebook').removeAttr('data-validation');
            }
        });
        //indeed validation
        jQuery('#indeedjob_enabled').change(function(){
            var isselect = jQuery('#indeedjob_enabled option:selected').val();
            if(isselect == 1){
                jQuery('#indeedjob_apikey').attr('data-validation', 'required');
                jQuery('#indeedjob_category').attr('data-validation', 'required');
                jQuery('#indeedjob_location').attr('data-validation', 'required');
            }else{
                jQuery('#indeedjob_apikey').removeAttr('data-validation');
                jQuery('#indeedjob_category').removeAttr('data-validation');
                jQuery('#indeedjob_location').removeAttr('data-validation');
            }
        });

        //career builder validation
        jQuery('#careerbuilder_enabled').change(function(){
            var isselect = jQuery('#careerbuilder_enabled option:selected').val();
            if(isselect == 1){
                jQuery('#careerbuilder_developerkey').attr('data-validation', 'required');
            }else{
                jQuery('#careerbuilder_developerkey').removeAttr('data-validation');
                
            }
        });
        
    });
</script>

<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

$theme = wp_get_theme();
$theme_chk = 0;
if($theme == 'Job Manager'){
    $theme_chk = 1;
}

wp_enqueue_script('jquery-ui-tabs');

// Lists objecs
$date_format = array((object) array('id' => 'd-m-Y', 'text' => __('dd-mm-yyyy', 'js-jobs')), (object) array('id' => 'm/d/Y', 'text' => __('mm/dd/yyyy', 'js-jobs')), (object) array('id' => 'Y-m-d', 'text' => __('yyyy-mm-dd', 'js-jobs')));
$yesno = array((object) array('id' => 1, 'text' => __('Yes', 'js-jobs')), (object) array('id' => 0, 'text' => __('No', 'js-jobs')));
$searchjobtag = array((object) array('id' => 1, 'text' => __('Top left', 'js-jobs')), (object) array('id' => 2, 'text' => __('Top right', 'js-jobs')), (object) array('id' => 3, 'text' => __('Middle left', 'js-jobs')), (object) array('id' => 4, 'text' => __('Middle right', 'js-jobs')), (object) array('id' => 5, 'text' => __('Bottom left', 'js-jobs')), (object) array('id' => 6, 'text' => __('Bottom right', 'js-jobs')));
$captchalist = array((object) array('id' => 1, 'text' => __('Google Captcha', 'js-jobs')), (object) array('id' => 2, 'text' => __('JS Jobs Captcha', 'js-jobs')));
$captchacalculation = array((object) array('id' => 0, 'text' => __('Any', 'js-jobs')), (object) array('id' => 1, 'text' => __('Addition', 'js-jobs')), (object) array('id' => 2, 'text' => __('Subtraction', 'js-jobs')));
$captchaop = array((object) array('id' => 2, 'text' => 2), (object) array('id' => 3, 'text' => 3));
$showhide = array((object) array('id' => 1, 'text' => __('Show', 'js-jobs')), (object) array('id' => 0, 'text' => __('Hide', 'js-jobs')));
$defaultradius = array((object) array('id' => 1, 'text' => __('Meters', 'js-jobs')), (object) array('id' => 2, 'text' => __('Kilometers', 'js-jobs')), (object) array('id' => 3, 'text' => __('Miles', 'js-jobs')), (object) array('id' => 4, 'text' => __('Nautical Miles', 'js-jobs')));
$defaultaddressdisplaytype = array((object) array('id' => 'csc', 'text' => __('City','js-jobs').', ' .__('State','js-jobs').', ' .__('Country', 'js-jobs')), (object) array('id' => 'cs', 'text' => __('City','js-jobs').', ' .__('State', 'js-jobs')), (object) array('id' => 'cc', 'text' => __('City','js-jobs').', ' .__('Country', 'js-jobs')), (object) array('id' => 'c', 'text' => __('City', 'js-jobs')));
$social = array(1 => '');
$leftright = array((object) array('id' => 1, 'text' => __('Left align', 'js-jobs')),(object) array('id' => 2, 'text' => __('Right align', 'js-jobs')));

global $wp_roles;
$roles = $wp_roles->get_names();
$userroles = array();
foreach ($roles as $key => $value) {
    $userroles[] = (object) array('id' => $key, 'text' => $value);
}
$msgkey = JSJOBSincluder::getJSModel('configuration')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey); ?>
<div id="jsjobsadmin-wrapper">
    <div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <span class="js-admin-title">
        <a href="<?php echo admin_url('admin.php?page=jsjobs'); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Configuration', 'js-jobs'); ?>
    </span>
    <form id="jsjobs-form" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_configuration&task=saveconfiguration") ?>">
        <div id="tabs" class="tabs">
            <ul>
                <li><a href="#site_setting"><?php echo __('Site Settings', 'js-jobs'); ?></a></li>
                <li><a href="#js_visitor_setting"><?php echo __('Visitor setting', 'js-jobs'); ?></a></li>
                <li><a href="#listjobs"><?php echo __('List Job', 'js-jobs'); ?></a></li>
                <li><a href="#package"><?php echo __('packages', 'js-jobs'); ?></a></li>
                <?php /* <li><a href="#payment"><?php echo __('Payment','js-jobs'); ?></a></li>  */ ?>
                <li><a href="#email"><?php echo __('Email', 'js-jobs'); ?></a></li>
                <li><a href="#socialmedia"><?php echo __('Social Apps', 'js-jobs'); ?></a></li>
                <li><a href="#googlemapadsense"><?php echo __('Google Map And Adsense', 'js-jobs'); ?></a></li>
                <li><a href="#socialsharing"><?php echo __('Job Social Sharing', 'js-jobs'); ?></a></li>
                <li><a href="#rssjob"><?php echo __('RSS Job Settings', 'js-jobs'); ?></a></li>
                <li><a href="#rssresume"><?php echo __('RSS Resume Settings', 'js-jobs'); ?></a></li>
            </ul>
            <div class="tabInner">
                <div id="site_setting">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Site Settings', 'js-jobs'); ?></h3>
                    <div class="js-job-configuration-table">
                        <div class="left">
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Title', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('title', jsjobs::$_data[0]['title'], array('class' => 'inputbox')); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Offline', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"> <?php echo JSJOBSformfield::select('offline', $yesno, jsjobs::$_data[0]['offline']); ?> </div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo wp_editor(jsjobs::$_data[0]['offline_text'], 'offline_text', array('media_buttons' => false)); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Data directory', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('data_directory', jsjobs::$_data[0]['data_directory'], array('class' => 'inputbox')); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('System will upload all user files in this folder', 'js-jobs'); echo '<br/><b>"'.jsjobs::$_path.jsjobs::$_data[0]['data_directory'].'"</b>'; ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('System slug', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('system_slug', jsjobs::$_data[0]['system_slug'], array('class' => 'inputbox')); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Default page', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('default_pageid', JSJOBSincluder::getJSModel('postinstallation')->getPageList(), jsjobs::$_data[0]['default_pageid']); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Select JS Jobs default page, on action system will redirect on selected page. If not select default page, email links and support icon might not work.', 'js-jobs'); ?></small></div>
                            </div>
                            <?php if($theme_chk == 0){ ?>     
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Show breadcrumbs', 'js-jobs')?></div>
                                    <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('cur_location', $yesno, jsjobs::$_data[0]['cur_location']); ?></div>
                                    <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Show navigation in breadcrumbs', 'js-jobs'); ?></small></div>
                                </div>
                            <?php } ?> 
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Date format', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('date_format', $date_format, jsjobs::$_data[0]['date_format'], '', array('class' => 'inputbox', 'data-validation' => '')); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Date format for plugin', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Default address display style', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('defaultaddressdisplaytype', $defaultaddressdisplaytype, jsjobs::$_data[0]['defaultaddressdisplaytype']); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Employer default role', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('employer_defaultgroup', $userroles, jsjobs::$_data[0]['employer_defaultgroup']); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('This role will auto assign to new employer','js-jobs');?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Job Seeker default role', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('jobseeker_defaultgroup', $userroles, jsjobs::$_data[0]['jobseeker_defaultgroup']); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('This role will auto assign to new job seeker','js-jobs');?></small></div>
                            </div>
                        </div>          
                        <div class="right">
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Default Pagination size', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('pagination_default_page_size', jsjobs::$_data[0]['pagination_default_page_size'], array('class' => 'inputbox')); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Maximum number of records per page', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Mark Job New', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::text('newdays', jsjobs::$_data[0]['newdays'], array('class' => 'inputbox not-full-width')); ?>&nbsp;<?php echo __('Days', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-description"><small><?php echo __('How many days system show New tag', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Image file extensions', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('image_file_type', jsjobs::$_data[0]['image_file_type'], array('class' => 'inputbox')); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Add image allowed extensions', 'js-jobs') .'. '. __('Must be comma separated', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('User can add city in database', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('newtyped_cities', $yesno, jsjobs::$_data[0]['newtyped_cities']); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('User can add new city in the system', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Maximum record for city field', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('number_of_cities_for_autocomplete', jsjobs::$_data[0]['number_of_cities_for_autocomplete'], array('class' => 'inputbox')); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Set number of cities to show in result of the location input box', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('User can add tag in database', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('newtyped_tags', $yesno, jsjobs::$_data[0]['newtyped_tags']); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('User can add new tags in the system', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Maximum record for tag field', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('number_of_tags_for_autocomplete', jsjobs::$_data[0]['number_of_tags_for_autocomplete'], array('class' => 'inputbox')); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Set number of tags to show in result of the tag input box', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Message auto approve', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('message_auto_approve', $yesno, jsjobs::$_data[0]['message_auto_approve']); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Auto approve messages for job seeker and employer', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Conflict message auto approve', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('conflict_message_auto_approve', $yesno, jsjobs::$_data[0]['conflict_message_auto_approve']); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Auto approve conflicted messages for job seeker and employer', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Categories per row', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('categories_colsperrow', jsjobs::$_data[0]['categories_colsperrow'], array('class' => 'inputbox')); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Show number of categories per row in \'job/resume by category\' page', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Sub-categories limit', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('subcategory_limit', jsjobs::$_data[0]['subcategory_limit'], array('class' => 'inputbox')); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('How many sub categories show in popup on \'job/resume by category\' page', 'js-jobs') .'.'; ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Job types per row', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('jobtype_per_row', jsjobs::$_data[0]['jobtype_per_row'], array('class' => 'inputbox')); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Show number of job types per row on \'job by type\' page', 'js-jobs') .'.'; ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Currency symbol position', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('currency_align', $leftright, jsjobs::$_data[0]['currency_align'], '', array('class' => 'inputbox', 'data-validation' => '')); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Show currency symbol left or right to the amount', 'js-jobs'); ?></small></div>
                            </div>
                        </div>      
                    </div>
                    <h3 class="js-job-configuration-heading-main"><?php echo __('SEO', 'js-jobs'); ?></h3>
                    <div class="js-job-configuration-table">
                        <div class="left">
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Job SEO', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('job_seo', jsjobs::$_data[0]['job_seo'], array('class' => 'inputbox')); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Job seo options are title, company, category, location, jobtype','js-jobs');?>. eg- [title] [company]</small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Resume SEO', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('resume_seo', jsjobs::$_data[0]['resume_seo'], array('class' => 'inputbox')); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Resume seo options are title, category, location','js-jobs');?>. eg- [title] [location] </small></div>
                            </div>
                        </div>
                        <div class="right">
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Company SEO', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('company_seo', jsjobs::$_data[0]['company_seo'], array('class' => 'inputbox')); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Set company seo options are name, category, location','js-jobs');?>. eg- [name] [category] [location] </small></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="js_visitor_setting">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Visitors Settings', 'js-jobs'); ?></h3>
                    <div class="js-job-configuration-table">
                        <div class="left">
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Show captcha on registration form', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('cap_on_reg_form', $yesno, jsjobs::$_data[0]['cap_on_reg_form'], '', array('class' => 'inputbox', 'data-validation' => '')); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Show captcha on JS Jobs registration form','js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('default captcha', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('captcha_selection', $captchalist, jsjobs::$_data[0]['captcha_selection']); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Select captcha for plugin', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('JS Jobs captcha calculation type', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('owncaptcha_calculationtype', $captchacalculation, jsjobs::$_data[0]['owncaptcha_calculationtype']); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Select calculation type (addition, subtraction)', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('JS Jobs captcha answer always positive', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('owncaptcha_subtractionans', $yesno, jsjobs::$_data[0]['owncaptcha_subtractionans']); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Subtraction answer should be positive', 'js-jobs'); ?></small></div>
                            </div>
                        </div>          
                        <div class="right">
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Number of operands for JS Jobs captcha', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('owncaptcha_totaloperand', $captchaop, jsjobs::$_data[0]['owncaptcha_totaloperand']); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Number of operands for captcha', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Google recaptcha private key', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('recaptcha_privatekey', jsjobs::$_data[0]['recaptcha_privatekey'], array('class' => 'inputbox')); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Enter the google recaptcha private key from','js-jobs') .'https://www.google.com/recaptcha/admin' ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Google recaptcha public key', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('recaptcha_publickey', jsjobs::$_data[0]['recaptcha_publickey'], array('class' => 'inputbox')); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Enter the google recaptcha public key from','js-jobs').'https://www.google.com/recaptcha/admin'; ?></small></div>
                            </div>
                        </div>      
                    </div>
                </div>
                <div id="listjobs">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Listing Style', 'js-jobs'); ?></h3>
                    <div class="left">
                        <?php if($theme_chk == 0){ ?>     
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Search icon position', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('searchjobtag', $searchjobtag, jsjobs::$_data[0]['searchjobtag']); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Postion for search icon on jobs listing page.', 'js-jobs'); ?></small></div>
                            </div>
                        <?php } ?>
                        <?php /*
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Show gold jobs', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('showgoldjobsinlistjobs', $yesno, jsjobs::$_data[0]['showgoldjobsinlistjobs']); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Show gold jobs in jobs lising page', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Number of gold jobs', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('noofgoldjobsinlisting', jsjobs::$_data[0]['noofgoldjobsinlisting'], array('class' => 'inputbox')); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Number of gold job show per scroll', 'js-jobs'); ?></small></div>
                        </div>
                        */?>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Show featured jobs', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('showfeaturedjobsinlistjobs', $yesno, jsjobs::$_data[0]['showfeaturedjobsinlistjobs']); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Show featured jobs in jobs lising page', 'js-jobs'); ?></small></div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Number of featured jobs', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('nooffeaturedjobsinlisting', jsjobs::$_data[0]['nooffeaturedjobsinlisting'], array('class' => 'inputbox')); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Number of featured job show per scroll', 'js-jobs'); ?></small></div>
                        </div>
                        <?php if($theme_chk == 0){ ?>     
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Show labels in jobs listing', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('labelinlisting', $yesno, jsjobs::$_data[0]['labelinlisting']); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Show labels in jobs listings, my jobs etc', 'js-jobs'); ?></small></div>
                            </div>
                        <?php } ?>
                    </div>
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Indeed Jobs', 'js-jobs'); ?><font style="color:#fff;font-size:22px;margin:0px 5px;">*</font></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Show Indeed jobs on jobs listings', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('indeedjob_enabled', $yesno, jsjobs::$_data[0]['indeedjob_enabled']); ?><div><small><?php echo __('Show company logo with job feeds', 'js-jobs'); ?></small></div></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('API key', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('indeedjob_apikey', jsjobs::$_data[0]['indeedjob_apikey'], array('class' => 'inputbox')); ?><div><small></small></div></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Number of jobs before showing indeed jobs', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('indeedjob_showafter', jsjobs::$_data[0]['indeedjob_showafter'], array('class' => 'inputbox')); ?>
                            <div><small><?php echo __('How many plugin jobs show before indeed jobs', 'js-jobs'); ?></small></div></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Number of Indeed jobs per page', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('indeedjob_jobperrequest', jsjobs::$_data[0]['indeedjob_jobperrequest'], array('class' => 'inputbox')); ?>
                            <div><small><?php echo __('Number of Indeed Jobs per scroll', 'js-jobs'); ?></small></div></div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Categories', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('indeedjob_category', jsjobs::$_data[0]['indeedjob_category'], array('class' => 'inputbox')); ?>
                            <div><small><?php echo __('Comma separated list of categories i.e Accounting, Management etc', 'js-jobs'); ?></small></div></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Location', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('indeedjob_location', jsjobs::$_data[0]['indeedjob_location'], array('class' => 'inputbox')); ?>
                            <div><small><?php echo __('Location format must be country, state, city', 'js-jobs'); ?></small></div></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Job types', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('indeedjob_jobtype', jsjobs::$_data[0]['indeedjob_jobtype'], array('class' => 'inputbox')); ?>
                            <div><small><?php echo __('Comma separated list of job types i.e full time, part time etc', 'js-jobs'); ?></small></div></div>
                        </div>
                    </div>
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Career Builder', 'js-jobs'); ?><font style="color:#fff;font-size:22px;margin:0px 5px;">*</font></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Show Career Builder jobs in job listings', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('careerbuilder_enabled', $yesno, jsjobs::$_data[0]['careerbuilder_enabled']); ?><div><small><?php echo __('Use rss categories with our job categories', 'js-jobs'); ?></small></div></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('API key', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('careerbuilder_developerkey', jsjobs::$_data[0]['careerbuilder_developerkey'], array('class' => 'inputbox')); ?>
                            <div><small></small></div></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Number of jobs before showing Career Builder jobs', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('careerbuilder_showafter', jsjobs::$_data[0]['careerbuilder_showafter'], array('class' => 'inputbox')); ?>
                            <div><small><?php echo __('How many plugin jobs show before career builder jobs', 'js-jobs'); ?></small></div></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Number of Career Builder jobs per page', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('careerbuilder_jobperrequest', jsjobs::$_data[0]['careerbuilder_jobperrequest'], array('class' => 'inputbox')); ?>
                            <div><small><?php echo __('Number of Career Builder jobs per scroll', 'js-jobs'); ?></small></div></div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Country Code', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('careerbuilder_countrycode', jsjobs::$_data[0]['careerbuilder_countrycode'], array('class' => 'inputbox')); ?>
                            <div><small><?php echo __('Comma separated list of country codes', 'js-jobs'); ?></small></div></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Categories', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('careerbuilder_category', jsjobs::$_data[0]['careerbuilder_category'], array('class' => 'inputbox')); ?>
                            <div><small><?php echo __('Comma separated list of categories i.e Accounting, Management etc', 'js-jobs'); ?></small></div></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Job Types', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('careerbuilder_emptype', jsjobs::$_data[0]['careerbuilder_emptype'], array('class' => 'inputbox')); ?>
                                <div>
                                    <small><?php echo __('Comma separated list of job types i.e JTFT, JTPT, JTFP, JTCT, JTIN ', 'js-jobs'); ?></small><br/>
                                    <small>JTFT : <?php echo __('Full-time', 'js-jobs'); ?></small><br/>
                                    <small>JTPT : <?php echo __('Part-time', 'js-jobs'); ?></small><br/>
                                    <small>JTFP : <?php echo __('Full-time/part-time', 'js-jobs'); ?></small><br/>
                                    <small>JTCT : <?php echo __('Contractant', 'js-jobs'); ?></small><br/>
                                    <small>JTIN : <?php echo __('Stagiair', 'js-jobs'); ?></small><br/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="package">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Package Settings', 'js-jobs'); ?></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Auto Assign Free package to new user', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('auto_assign_free_package', $yesno, jsjobs::$_data[0]['auto_assign_free_package']); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('This configuration controls whethre new user will get free package (if free package exsist in the system)', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('free package purchase', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('free_package_purchase_only_once', $yesno, jsjobs::$_data[0]['free_package_purchase_only_once']); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('This configuration controls whether user can be free package more than once', 'js-jobs'); ?></small></div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Free Package purchase auto approve', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('free_package_auto_approve', $yesno, jsjobs::$_data[0]['free_package_auto_approve']); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('This configuration controls whether free package will be auto approve or not', 'js-jobs'); ?></small></div>
                        </div>
                    </div>
                </div>
                <div id="email">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Email Settings', 'js-jobs'); ?></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Sender email address', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('mailfromaddress', jsjobs::$_data[0]['mailfromaddress'], array('class' => 'inputbox')); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Email address that will be used to send emails', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Sender name', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('mailfromname', jsjobs::$_data[0]['mailfromname'], array('class' => 'inputbox')); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Sender name that will be used in emails', 'js-jobs'); ?></small></div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Admin email address', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('adminemailaddress', jsjobs::$_data[0]['adminemailaddress'], array('class' => 'inputbox')); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Admin will receive email notifications on this address', 'js-jobs'); ?></small></div>
                        </div>
                    </div>
                </div>
                <div id="socialsharing">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Social Links', 'js-jobs') ?><font style="color:#fff;font-size:22px;margin:0px 5px;">*</font></h3>
                    <div class="js-col-md-4">
                        <div class="js-col-xs-12 js-col-md-2 js-job-configuration-row"><label><?php echo JSJOBSformfield::checkbox('employer_share_fb_like', $social, (jsjobs::$_data[0]['employer_share_fb_like'] == 1) ? 1 : 0, array('class' => 'checkbox')); ?><?php echo __('Facebook likes', 'js-jobs'); ?></div>
                    </div>
                    <div class="js-col-md-4">
                        <div class="js-col-xs-12 js-col-md-2 js-job-configuration-row"><label><?php echo JSJOBSformfield::checkbox('employer_share_fb_share', $social, (jsjobs::$_data[0]['employer_share_fb_share'] == 1) ? 1 : 0, array('class' => 'checkbox')); ?><?php echo __('Facebook share', 'js-jobs'); ?></label></div>
                    </div>
                    <div class="js-col-md-4">
                        <div class="js-col-xs-12 js-col-md-2 js-job-configuration-row"><label><?php echo JSJOBSformfield::checkbox('employer_share_fb_comments', $social, (jsjobs::$_data[0]['employer_share_fb_comments'] == 1) ? 1 : 0, array('class' => 'checkbox')); ?><?php echo __('Facebook comments', 'js-jobs'); ?></label></div>
                    </div>
                    <div class="js-col-md-4">
                        <div class="js-col-xs-12 js-col-md-2 js-job-configuration-row"><label><?php echo JSJOBSformfield::checkbox('employer_share_google_like', $social, (jsjobs::$_data[0]['employer_share_google_like'] == 1) ? 1 : 0, array('class' => 'checkbox')); ?><?php echo __('Google likes', 'js-jobs'); ?></label></div>
                    </div>
                    <div class="js-col-md-4">
                        <div class="js-col-xs-12 js-col-md-2 js-job-configuration-row"><label><?php echo JSJOBSformfield::checkbox('employer_share_google_share', $social, (jsjobs::$_data[0]['employer_share_google_share'] == 1) ? 1 : 0, array('class' => 'checkbox')); ?><?php echo __('Google share', 'js-jobs'); ?></label></div>
                    </div>
                    <div class="js-col-md-4">
                        <div class="js-col-xs-12 js-col-md-2 js-job-configuration-row"><label><?php echo JSJOBSformfield::checkbox('employer_share_blog_share', $social, (jsjobs::$_data[0]['employer_share_blog_share'] == 1) ? 1 : 0, array('class' => 'checkbox')); ?><?php echo __('Blogger', 'js-jobs'); ?></label></div>
                    </div>
                    <div class="js-col-md-4">
                        <div class="js-col-xs-12 js-col-md-2 js-job-configuration-row"><label><?php echo JSJOBSformfield::checkbox('employer_share_linkedin_share', $social, (jsjobs::$_data[0]['employer_share_linkedin_share'] == 1) ? 1 : 0, array('class' => 'checkbox')); ?><?php echo __('Linkedin', 'js-jobs'); ?></label></div>
                    </div>
                    <div class="js-col-md-4">
                        <div class="js-col-xs-12 js-col-md-2 js-job-configuration-row"><label><?php echo JSJOBSformfield::checkbox('employer_share_digg_share', $social, (jsjobs::$_data[0]['employer_share_digg_share'] == 1) ? 1 : 0, array('class' => 'checkbox')); ?><?php echo __('Digg', 'js-jobs'); ?></label></div>
                    </div>
                    <div class="js-col-md-4">
                        <div class="js-col-xs-12 js-col-md-2 js-job-configuration-row"><label><?php echo JSJOBSformfield::checkbox('employer_share_twitter_share', $social, (jsjobs::$_data[0]['employer_share_twitter_share'] == 1) ? 1 : 0, array('class' => 'checkbox')); ?><?php echo __('Twitter', 'js-jobs'); ?></label></div>
                    </div>
                    <div class="js-col-md-4">
                        <div class="js-col-xs-12 js-col-md-2 js-job-configuration-row"><label><?php echo JSJOBSformfield::checkbox('employer_share_myspace_share', $social, (jsjobs::$_data[0]['employer_share_myspace_share'] == 1) ? 1 : 0, array('class' => 'checkbox')); ?><?php echo __('Myspace', 'js-jobs'); ?></label></div>
                    </div>
                    <div class="js-col-md-4">
                        <div class="js-col-xs-12 js-col-md-2 js-job-configuration-row"><label><?php echo JSJOBSformfield::checkbox('employer_share_yahoo_share', $social, (jsjobs::$_data[0]['employer_share_yahoo_share'] == 1) ? 1 : 0, array('class' => 'checkbox')); ?><?php echo __('Yahoo', 'js-jobs'); ?></label></div>
                    </div>

                </div>
                <div id="rssjob">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('RSS Job Settings', 'js-jobs'); ?><font style="color:#fff;font-size:22px;margin:0px 5px;">*</font></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Jobs RSS', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('job_rss', $showhide, jsjobs::$_data[0]['job_rss']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Title', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('rss_job_title', jsjobs::$_data[0]['rss_job_title'], array('class' => 'inputbox')); ?><div><small><?php echo __('Must provide title for job feed', 'js-jobs'); ?></small></div></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Description', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::textarea('rss_job_description', jsjobs::$_data[0]['rss_job_description']); ?><div><small><?php echo __('Must provide description for job feed', 'js-jobs'); ?></small></div></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Copyright', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('rss_job_copyright', jsjobs::$_data[0]['rss_job_copyright'], array('class' => 'inputbox')); ?><div><small><?php echo __('Leave blank to hide', 'js-jobs'); ?></small></div></div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Editor', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('rss_job_editor', jsjobs::$_data[0]['rss_job_editor'], array('class' => 'inputbox')); ?><div><small><?php echo __('Leave blank to hide editor used for feed content issue', 'js-jobs'); ?></small></div></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Time to live', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('rss_job_ttl', jsjobs::$_data[0]['rss_job_ttl'], array('class' => 'inputbox')); ?><div><small><?php echo __('Time to live for job feed', 'js-jobs'); ?></small></div></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Webmaster', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('rss_job_webmaster', jsjobs::$_data[0]['rss_job_webmaster'], array('class' => 'inputbox')); ?><div><small><?php echo __('Leave blank to hide webmaster used for technical issue', 'js-jobs'); ?></small></div></div>
                        </div>
                    </div>
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Job block', 'js-jobs'); ?><font style="color:#fff;font-size:22px;margin:0px 5px;">*</font></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Show with categories', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('rss_job_categories', $showhide, jsjobs::$_data[0]['rss_job_categories']); ?><div><small><?php echo __('Use rss categories with our job categories', 'js-jobs'); ?></small></div></div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Company image', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('rss_job_image', $showhide, jsjobs::$_data[0]['rss_job_image']); ?><div><small><?php echo __('Show company logo with job feeds', 'js-jobs'); ?></small></div></div>
                        </div>
                    </div>
                </div>
                <div id="rssresume">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('RSS Resume Settings', 'js-jobs'); ?><font style="color:#fff;font-size:22px;margin:0px 5px;">*</font></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Resume RSS', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('resume_rss', $showhide, jsjobs::$_data[0]['resume_rss']); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Title', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('rss_resume_title', jsjobs::$_data[0]['rss_resume_title'], array('class' => 'inputbox')); ?><div><small><?php echo __('Must provide title for resume feed', 'js-jobs'); ?></small></div></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Description', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::textarea('rss_resume_description', jsjobs::$_data[0]['rss_resume_description']); ?><div><small><?php echo __('Must provide description for resume feed', 'js-jobs'); ?></small></div></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Webmaster', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('rss_resume_webmaster', jsjobs::$_data[0]['rss_resume_webmaster'], array('class' => 'inputbox')); ?><div><small><?php echo __('Leave blank to hide webmaster used for technical issue', 'js-jobs'); ?></small></div></div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Editor', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('rss_resume_editor', jsjobs::$_data[0]['rss_resume_editor'], array('class' => 'inputbox')); ?><div><small><?php echo __('Leave blank to hide editor used for feed content issue', 'js-jobs'); ?></small></div></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Time to live', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('rss_resume_ttl', jsjobs::$_data[0]['rss_resume_ttl'], array('class' => 'inputbox')); ?><div><small><?php echo __('Time to live for resume feed', 'js-jobs'); ?></small></div></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Copyright', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('rss_resume_copyright', jsjobs::$_data[0]['rss_resume_copyright'], array('class' => 'inputbox')); ?><div><small><?php echo __('Leave blank to hide', 'js-jobs'); ?></small></div></div>
                        </div>
                    </div>
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Resume block', 'js-jobs'); ?><font style="color:#fff;font-size:22px;margin:0px 5px;">*</font></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Show with categories', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('rss_resume_categories', $showhide, jsjobs::$_data[0]['rss_resume_categories']); ?><div><small><?php echo __('Use rss categories with our resume categories', 'js-jobs'); ?></small></div></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Show resume file', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('rss_resume_file', $showhide, jsjobs::$_data[0]['rss_resume_file']); ?><div><small><?php echo __('Show resume files to downloadable from feed', 'js-jobs'); ?></small></div></div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Resume image', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('rss_resume_image', $showhide, jsjobs::$_data[0]['rss_resume_image']); ?><div><small><?php echo __('Show resume image with resume feed', 'js-jobs'); ?></small></div></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Email address', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('rss_resume_email', $showhide, jsjobs::$_data[0]['rss_resume_email']); ?><div><small><?php echo __('Show email address in resume feed', 'js-jobs'); ?></small></div></div>
                        </div>
                    </div>
                </div>
                <div id="googlemapadsense">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Map', 'js-jobs'); ?></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Map height', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('mapheight', jsjobs::$_data[0]['mapheight'], array('class' => 'inputbox not-full-width')); ?>px</div>
                            <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Set map height for plugin','js-jobs'); ?></small></div>
                        </div>
                        <div id="full_background" style="display:none;" onclick="hidediv();"></div>
                        <div id="popup_main" style="display:none;width:70%; height:<?php echo jsjobs::$_configuration['mapheight'] + 70; ?>px">
                            <span class="popup-top"><span id="popup_title" ><?php echo __('Map', 'js-jobs'); ?></span><img id="popup_cross" onclick="hidediv();" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/popup-close.png">
                            </span>
                            <div id="map" style="width:100%; height:<?php echo jsjobs::$_configuration['mapheight']; ?>px">
                                <div id="map_container">
                                </div>
                            </div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Map', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><a href="Javascript: showdiv();loadMap();"><?php echo __('Show Map', 'js-jobs'); ?></a></div>
                            <div class="js-col-xs-12  js-job-configuration-description"><small></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Google Map API key', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('google_map_api_key', jsjobs::$_data[0]['google_map_api_key'], array('class' => 'inputbox')); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Get API key from','js-jobs'); ?>&nbsp;<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank"><?php echo __('here','js-jobs'); ?></a></small></div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Default longitude', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('default_longitude', jsjobs::$_data[0]['default_longitude'], array('class' => 'inputbox')); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-description"><small></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Default latitude', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('default_latitude', jsjobs::$_data[0]['default_latitude'], array('class' => 'inputbox')); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-description"><small></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Default map radius type', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('defaultradius', $defaultradius, jsjobs::$_data[0]['defaultradius']); ?></div>
                            <div class="js-col-xs-12  js-job-configuration-description"><small></small></div>
                        </div>
                    </div>
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Google Adsense Settings', 'js-jobs'); ?><font style="color:#fff;font-size:22px;margin:0px 5px;">*</font></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Show Google adds in list jobs', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::select('googleadsenseshowinlistjobs', $showhide, jsjobs::$_data[0]['googleadsenseshowinlistjobs']); ?><div><small><?php echo __('Show google adds in jobs lisings', 'js-jobs'); ?></small></div></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Google adsense client id', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('googleadsenseclient', jsjobs::$_data[0]['googleadsenseclient'], array('class' => 'inputbox')); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Google adsense slot id', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('googleadsenseslot', jsjobs::$_data[0]['googleadsenseslot'], array('class' => 'inputbox')); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Google adds show after number of jobs', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('googleadsenseshowafter', jsjobs::$_data[0]['googleadsenseshowafter'], array('class' => 'inputbox')); ?></div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Google adds width', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('googleadsensewidth', jsjobs::$_data[0]['googleadsensewidth'], array('class' => 'inputbox')); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Google adds height', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::text('googleadsenseheight', jsjobs::$_data[0]['googleadsenseheight'], array('class' => 'inputbox')); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Google adds custom css', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo JSJOBSformfield::textarea('googleadsensecustomcss', jsjobs::$_data[0]['googleadsensecustomcss'], array('class' => 'textarea')); ?></div>
                        </div>
                    </div>
                </div>
                <div id="socialmedia">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Facebook', 'js-jobs'); ?><font style="color:#fff;font-size:22px;margin:0px 5px;">*</font></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Login with facebook', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value">
                                <?php echo JSJOBSformfield::select('loginwithfacebook', $yesno, jsjobs::$_data[0]['loginwithfacebook']); ?>
                            </div>
                            <div class="js-col-xs-12 js-job-configuration-description"><small><?php echo __('Facebook user can login in JS Jobs', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Job apply with facebook', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value">
                                <?php echo JSJOBSformfield::select('applywithfacebook', $yesno, jsjobs::$_data[0]['applywithfacebook']); ?>
                            </div>
                            <div class="js-col-xs-12 js-job-configuration-description"><small><?php echo __('Facebook user can apply to jobs', 'js-jobs'); ?></small></div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('API key', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::text('apikeyfacebook', jsjobs::$_data[0]['apikeyfacebook'], array('class' => 'inputbox')); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-description"><small><?php echo __('API key is required for facebook app', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Secret', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::text('clientsecretfacebook', jsjobs::$_data[0]['clientsecretfacebook'], array('class' => 'inputbox')); ?></div>
                           <div class="js-col-xs-12 js-job-configuration-description"><small></small></div>
                        </div>
                    </div>
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Linkedin', 'js-jobs'); ?><font style="color:#fff;font-size:22px;margin:0px 5px;">*</font></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Login with linkedin', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value">
                                <?php echo JSJOBSformfield::select('loginwithlinkedin', $yesno, jsjobs::$_data[0]['loginwithlinkedin']); ?>
                            </div>
                            <div class="js-col-xs-12 js-job-configuration-description"><small><?php echo __('Linkedin user can login in JS Jobs', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Job apply with linkedin', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value">
                                <?php echo JSJOBSformfield::select('applywithlinkedin', $yesno, jsjobs::$_data[0]['applywithlinkedin']); ?>
                            </div>
                            <div class="js-col-xs-12 js-job-configuration-description"><small><?php echo __('Linkedin user can apply to jobs', 'js-jobs'); ?></small></div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('API key', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::text('apikeylinkedin', jsjobs::$_data[0]['apikeylinkedin'], array('class' => 'inputbox')); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-description"><small><?php echo __('API key is required for linkedin app', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Secret', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::text('clientsecretlinkedin', jsjobs::$_data[0]['clientsecretlinkedin'], array('class' => 'inputbox')); ?></div>
                           <div class="js-col-xs-12 js-job-configuration-description"><small></small></div>
                        </div>
                    </div>
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Xing', 'js-jobs'); ?><font style="color:#fff;font-size:22px;margin:0px 5px;">*</font></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Login with xing', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value">
                                <?php echo JSJOBSformfield::select('loginwithxing', $yesno, jsjobs::$_data[0]['loginwithxing']); ?>
                            </div>
                            <div class="js-col-xs-12 js-job-configuration-description"><small><?php echo __('Xing user can login in JS Jobs', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Job apply with xing', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value">
                                <?php echo JSJOBSformfield::select('applywithxing', $yesno, jsjobs::$_data[0]['applywithxing']); ?>
                            </div>
                            <div class="js-col-xs-12 js-job-configuration-description"><small><?php echo __('Xing user can apply to jobs', 'js-jobs'); ?></small></div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('API key', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::text('apikeyxing', jsjobs::$_data[0]['apikeyxing'], array('class' => 'inputbox')); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-description"><small><?php echo __('API key is required for xing app', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo __('Secret', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo JSJOBSformfield::text('clientsecretxing', jsjobs::$_data[0]['clientsecretxing'], array('class' => 'inputbox')); ?></div>
                           <div class="js-col-xs-12 js-job-configuration-description"><small></small></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php echo JSJOBSformfield::hidden('isgeneralbuttonsubmit', 1); ?>
        <?php echo JSJOBSformfield::hidden('jsjobslt', 'configurations'); ?>
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
        function hideshowtables(table_id) {
            var obj = document.getElementById(table_id);
            var bool = obj.style.display;
            if (bool == '')
                obj.style.display = "none";
            else
                obj.style.display = "";
        }
    </script>

    <style type="text/css">
        div#map_container{
            z-index:1000;
            position:relative;
            background:#000;
            width:100%;
            height:<?php echo jsjobs::$_configuration['mapheight'] . 'px'; ?>;}
    </style>

    <?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; ?>
    <script type="text/javascript" src="<?php echo $protocol; ?>maps.googleapis.com/maps/api/js?key=<?php echo jsjobs::$_configuration['google_map_api_key']; ?>"></script>
    <script type="text/javascript">
        function loadMap() {
            var default_latitude = document.getElementById('default_latitude').value;
            var default_longitude = document.getElementById('default_longitude').value;
            var latlng = new google.maps.LatLng(default_latitude, default_longitude);
            zoom = 10;
            var myOptions = {
                zoom: zoom,
                center: latlng,
                scrollwheel: false,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            var map = new google.maps.Map(document.getElementById("map_container"), myOptions);
            var lastmarker = new google.maps.Marker({
                postiion: latlng,
                map: map,
            });
            var marker = new google.maps.Marker({
                position: latlng,
                map: map,
            });
            marker.setMap(map);
            lastmarker = marker;

            google.maps.event.addListener(map, "click", function (e) {
                var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
                geocoder = new google.maps.Geocoder();
                geocoder.geocode({'latLng': latLng}, function (results, status) {

                    if (status == google.maps.GeocoderStatus.OK) {
                        if (lastmarker != '')
                            lastmarker.setMap(null);
                        var marker = new google.maps.Marker({
                            position: results[0].geometry.location,
                            map: map,
                        });
                        marker.setMap(map);
                        lastmarker = marker;
                        document.getElementById('default_latitude').value = marker.position.lat();
                        document.getElementById('default_longitude').value = marker.position.lng();

                    } else {
                        alert("<?php echo __('Geocode was not successful for the following reason', 'js-jobs'); ?>: " + status);
                    }
                });
            });
        }
        function showdiv() {
            document.getElementById('map').style.visibility = 'visible';
            jQuery("div#full_background").css("display", "block");
            jQuery("div#popup_main").slideDown('slow');
        }
        function hidediv() {
            document.getElementById('map').style.visibility = 'hidden';
            jQuery("div#popup_main").slideUp('slow');
            jQuery("div#full_background").hide();

        }
    </script>
</div>
</div>
