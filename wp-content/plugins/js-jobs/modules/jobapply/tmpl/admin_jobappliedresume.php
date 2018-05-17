<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_style('jsjob-jsjobsrating', jsjobs::$_pluginpath . 'includes/css/jsjobsrating.css');
?>
<script type="text/javascript">


    function actioncall(jobapplyid, jobid, resumeid, action) {
        if (action == 3) { // folder
            getfolders('resumeaction_' + jobapplyid, jobid, resumeid, jobapplyid);
        } else if (action == 4) { // comments
            getresumecomments('resumeaction_' + jobapplyid, jobapplyid);
        } else if (action == 5) { // email candidate
            mailtocandidate('resumeaction_' + jobapplyid, resumeid, jobapplyid);
        } else {
            var src = '#resumeactionmessage_' + jobapplyid;
            var htmlsrc = '#jsjobs_appliedresume_data_action_message_' + jobapplyid;
            jQuery(src).html("Loading ...");
        }
    }


    function setresumeid(resumeid, action) {
        jQuery('#resumeid').val(resumeid);
        jQuery('#action').val(jQuery("#" + action).val());
        jQuery('jsjobs-form').submit();
    }



    function clsjobdetail(src) {
        jQuery("#" + src).html("");
    }

    function clsaddtofolder(src) {
        jQuery("#" + src).html("");
    }

    function echeck(str) {
        var at = "@";
        var dot = ".";
        var lat = str.indexOf(at);
        var lstr = str.length;
        var ldot = str.indexOf(dot);

        if (str.indexOf(at) == -1)
            return false;
        if (str.indexOf(at) == -1 || str.indexOf(at) == 0 || str.indexOf(at) == lstr)
            return false;
        if (str.indexOf(dot) == -1 || str.indexOf(dot) == 0 || str.indexOf(dot) == lstr)
            return false;
        if (str.indexOf(at, (lat + 1)) != -1)
            return false;
        if (str.substring(lat - 1, lat) == dot || str.substring(lat + 1, lat + 2) == dot)
            return false;
        if (str.indexOf(dot, (lat + 2)) == -1)
            return false;
        if (str.indexOf(" ") != -1)
            return false;
        return true;
    }

    function closeSection() {
        jQuery("div#comments").html('').hide();
    }


    function showPopupAndSetValues(name, title, id) {
        var desc = jQuery("input#cover-letter-text_" + id).val();
        jQuery("div#full_background").css("display", "block");
        jQuery("div#popup-main.coverletter").css("display", "block");
        jQuery("div#popup-main-outer.coverletter").css("display", "block");
        jQuery("div#full_background").click(function () {
            closePopup();
        });
        jQuery("img#popup_cross").click(function () {
            closePopup();
        });
        jQuery("div#popup_main.coverletter").slideDown('slow');
        jQuery("span#popup_title.coverletter").html(name);
        jQuery("span#popup_coverletter_title.coverletter").html(title);
        jQuery("span#popup_coverletter_desc.coverletter").html(desc);
    }


    function closePopup() {
        jQuery("div#popup-main-outer").slideUp('slow');
        setTimeout(function () {
            jQuery("div#full_background").hide();
            jQuery("span#popup_title.coverletter").html('');
            jQuery("div#popup-main").css("display", "none");
            jQuery("span#popup_coverletter_title.coverletter").html('');
            jQuery("span#popup_coverletter_desc.coverletter").html('');
        }, 700);
    }

    function getResumeDetails(resumeid, salary, exp, inisi, study, available) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
        jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'jobapply', task: 'getResumeDetail', sal: salary, expe: exp, institue: inisi, stud: study, ava: available}, function (data) {
            if (data) {
                jQuery("div." + resumeid).html(data).show();
            }
        });

    }

    function getEmailFields(emailid, resumeid) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
        jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'jobapply', task: 'getEmailFields', em: emailid,resumeid: resumeid}, function (data) {
            if (data) {
                jQuery("div." + resumeid).html(data).show();
            }
        });
    }

</script>
<div id="jsjobsadmin-wrapper">
	<div id="full_background" style="display:none;"></div>
    <div id="popup-main-outer" class="coverletter" style="display:none;">
        <div id="popup-main" class="coverletter" style="display:none;">
            <span class="popup-top"><span id="popup_title" class="coverletter"></span><img id="popup_cross" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/popup-close.png">
            </span>
            <div class="js-field-wrapper js-row no-margin" id="popup-bottom-part">
                <span id="popup_coverletter_title" class="coverletter"></span>
                <span id="popup_coverletter_desc" class="coverletter"> </span>
            </div>
        </div>
    </div>
</div>
<div id="jsjobsadmin-wrapper">
<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    
    <span class="js-admin-title">
        <a href="<?php echo admin_url('admin.php?page=jsjobs_job'); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Job Applied Resume', 'js-jobs') ?>
    </span>
    <div class="jobtitleappliedresume">
        <?php echo jsjobs::$_data['jobtitle']; ?>
    </div>

    <?php
    if (!empty(jsjobs::$_data[0]['data'])) {
        foreach (jsjobs::$_data[0]['data'] as $data) {
                        $photo = '';
                        if (isset($data->photo) && $data->photo != '') {
                            $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                            $wpdir = wp_upload_dir();
                            $photo = $wpdir['baseurl'] . '/' . $data_directory . '/data/jobseeker/resume_' . $data->resumeid . '/photo/' . $data->photo;
                            $padding = "";
                        } else {
                            $photo = jsjobs::$_pluginpath . '/includes/images/users.png';
                            $padding = ' style="padding:15px;" ';
                        }
                        ?>
                        <div id="user_<?php echo 1; ?>" class="user-container js-col-lg-12 js-col-md-12 no-padding">
                            <div id="item-data" class="item-data js-row no-margin">
                                <div class="item-icon admin-applied-resume-left js-col-lg-2 js-col-md-2 js-col-xs-12 no-padding">
                                    <div class="job-img">
                                        <img src="<?php echo $photo; ?>" <?php echo $padding; ?> />
                                    </div>
                                    <div id="view-resume">
                                        <a id="view-resume" href="<?php echo admin_url('admin.php?page=jsjobs_resume&jsjobslt=formresume&jsjobsid='.$data->appid); ?>">
                                            <img id="view-resume" src="<?php echo jsjobs::$_pluginpath; ?>/includes/images/jopappliedapplication/white-reume-icon.png" /><?php echo __('Resume', 'js-jobs'); ?>
                                        </a>
                                    </div>
                                    <?php if($data->cletterid != '') { ?>
                                        <div id="view-cover-letter">
                                            <a id="view-cover-letter" href="#" onclick="showPopupAndSetValues('<?php echo $data->first_name . ' ' . $data->last_name; ?>', '<?php echo $data->clettertitle; ?>',<?php echo $data->appid; ?>);">
                                                <img id="view-resume" src="<?php echo jsjobs::$_pluginpath; ?>/includes/images/jopappliedapplication/view-coverletter.png"><?php echo __('Cover Letter', 'js-jobs'); ?>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="item-details js-col-lg-10 js-col-md-10 js-col-xs-12 no-padding">
                                    <div class="item-title js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                        <span class="value">
                                            <?php echo $data->first_name . " " . $data->last_name ?>
                                        </span>
                                        <div id="applied-resume-ratting">

                                        </div>     
                                        <div class="created-onright">
                                            <span class="heading"><?php echo __('Created','js-jobs') . ': '; ?></span>
                                            <span class="value"><?php echo date_i18n(jsjobs::$_configuration['date_format'], strtotime($data->apply_date)); ?></span>
                                        </div>
                                    </div>
                                    <div id="change-padding" class="item-values js-col-lg-12 js-col-md-12 js-col-xs-12">
                                        <span class="heading">
                                        <?php if(!isset(jsjobs::$_data['fields']['application_title'])){
                                                        jsjobs::$_data['fields']['application_title'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('application_title',3);
                                                    }                                    
                                                    echo __(jsjobs::$_data['fields']['application_title'], 'js-jobs') . ': '; ?></span><span class="value"><?php echo $data->applicationtitle ?></span>
                                    </div>
                                    <div id="change-padding" class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12">
                                        <span class="heading">
                                        <?php if(!isset(jsjobs::$_data['fields']['desired_salary'])){
                                                        jsjobs::$_data['fields']['desired_salary'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('desired_salary',3);
                                                    }                                    
                                                    echo __(jsjobs::$_data['fields']['desired_salary'], 'js-jobs') . ': '; ?></span><span class="value"><?php echo $data->dsalary; ?></span>
                                    </div>
                                    <div id="change-padding" class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12">
                                        <span class="heading"><?php if(!isset(jsjobs::$_data['fields']['total_experience'])){
                                                        jsjobs::$_data['fields']['total_experience'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('total_experience',3);
                                                    }                                    
                                                    echo __(jsjobs::$_data['fields']['total_experience'], 'js-jobs') . ': '; ?></span><span class="value"><?php echo __($data->total_experience,'js-jobs'); ?></span>
                                    </div>
                                    <div id="change-padding" class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12">
                                        <span class="heading"><?php if(!isset(jsjobs::$_data['fields']['heighestfinisheducation'])){
                                                        jsjobs::$_data['fields']['heighestfinisheducation'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('heighestfinisheducation',3);
                                                    }                                    
                                                    echo __(jsjobs::$_data['fields']['heighestfinisheducation'], 'js-jobs') . ': '; ?></span><span class="value"><?php echo __($data->educationtitle,'js-jobs'); ?></span>
                                    </div>
                                    <div id="change-padding" class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12">
                                        <span class="heading"><?php if(!isset(jsjobs::$_data['fields']['gender'])){
                                                        jsjobs::$_data['fields']['gender'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('gender',3);
                                                    }                                    
                                                    echo __(jsjobs::$_data['fields']['gender'], 'js-jobs') . ': '; ?></span><span class="value"><?php
                                            if ($data->gender == 1) {
                                                echo __('Male', 'js-jobs');
                                            } elseif ($data->gender == 2) {
                                                echo __('Female', 'js-jobs');
                                            };
                                            ?></span>
                                    </div>
                                    <div id="change-padding" class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12">
                                        <span class="heading"><?php if(!isset(jsjobs::$_data['fields']['iamavailable'])){
                                                        jsjobs::$_data['fields']['iamavailable'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('iamavailable',3);
                                                    }                                    
                                                    echo __(jsjobs::$_data['fields']['iamavailable'], 'js-jobs') . ': '; ?></span><span class="value"><?php echo $data->iamavailable == 1 ? __('Yes', 'js-jobs') : __('No', 'js-jobs'); ?></span>
                                    </div>
                                    <div id="change-padding" class="item-values js-col-lg-12 js-col-md-12 js-col-xs-12">
                                        <span class="heading"><?php echo __('Location', 'js-jobs') . ': '; ?></span><span class="value"><?php echo $data->location; ?></span>
                                    </div>
                                </div>
                                <div id="<?php echo $data->appid; ?>" ></div>
                                <div id="comments" class="<?php echo $data->appid; ?>" ></div>
                            </div>
                        </div>
                        <div id="item-actions" class="item-actions js-row no-margin jobapplied-css">
                            <a href="#" id="print-link"  class="js-action-link button applied-a" data-resumeid="<?php echo $data->appid; ?>" data-print-url="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'printresume', 'jsjobsid'=>$data->appid, 'issocial'=>'0', 'jsjobspageid'=>jsjobs::getPageid())); ?>" ><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/print.png" /><?php echo __('Print', 'js-jobs') ?></a>
                            <a target="_blank" href="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'pdf', 'jsjobsid'=>$data->appid,'jsjobspageid'=>jsjobs::getPageid())); ?>" class="js-action-link button applied-a"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/jopappliedapplication/pdf.png" /><?php echo __('PDF', 'js-jobs') ?></a>
                            <a class="js-action-link button applied-a" onclick="getResumeDetails(<?php echo $data->appid; ?>, '<?php echo $data->salary; ?>', '<?php echo $data->total_experience; ?>', '<?php echo $data->institute; ?>', '<?php echo$data->institute_study_area; ?>',<?php echo $data->iamavailable; ?>)"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/jopappliedapplication/details.png" /><?php echo __('Details', 'js-jobs') ?></a>
                        </div>
                        <?php
                        echo JSJOBSformfield::hidden('cover-letter-text_' . $data->appid, $data->cletterdescription);
          
        } // loop End
        if (jsjobs::$_data[1]) {
            echo '<div class="tablenav"><div class="tablenav-pages">' . jsjobs::$_data[1] . '</div></div>';
        }

        $jobapplyid = jsjobs::$_data[0]['data'][0]->jobapplyid;
        echo JSJOBSformfield::hidden('id', '');
        echo JSJOBSformfield::hidden('jobapplyid', $jobapplyid );
        echo JSJOBSformfield::hidden('task', 'actionresume');
        echo JSJOBSformfield::hidden('action', '');
        echo JSJOBSformfield::hidden('action_status', '');
        echo JSJOBSformfield::hidden('tab_action', '');
        echo JSJOBSformfield::hidden('boxchecked', '');
        echo JSJOBSformfield::hidden('jobid', jsjobs::$_data[0]['ta']);
        echo JSJOBSformfield::hidden('ta', jsjobs::$_data[0]['ta']);
        echo JSJOBSformfield::hidden('form_request', 'jsjobs');
    } else {
        $msg = __('No record found','js-jobs');
        echo JSJOBSlayout::getNoRecordFound($msg);
    }
    ?>
</form>
</div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('a#print-link').click(function (e) {
            e.preventDefault();
            var printurl = jQuery(this).attr('data-print-url');
            print = window.open(printurl, 'print_win', 'width=1024, height=800, scrollbars=yes');
        });
    });
</script>
