<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
$dateformat = jsjobs::$_configuration['date_format'];
if ($dateformat == 'm/d/Y' || $dateformat == 'd/m/y' || $dateformat == 'm/d/y' || $dateformat == 'd/m/Y') {
    $dash = '/';
} else {
    $dash = '-';
}
$firstdash = strpos($dateformat, $dash, 0);
$firstvalue = substr($dateformat, 0, $firstdash);
$firstdash = $firstdash + 1;
$seconddash = strpos($dateformat, $dash, $firstdash);
$secondvalue = substr($dateformat, $firstdash, $seconddash - $firstdash);
$seconddash = $seconddash + 1;
$thirdvalue = substr($dateformat, $seconddash, strlen($dateformat) - $seconddash);
$js_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;
$js_scriptdateformat = $firstvalue . $dash . $secondvalue . $dash . $thirdvalue;
$js_scriptdateformat = str_replace('Y', 'yy', $js_scriptdateformat);
?>
<script type="text/javascript">

    jQuery(document).ready(function () {
        jQuery('.custom_date').datepicker({dateFormat: '<?php echo $js_scriptdateformat; ?>'});
        //start Approval queue jquery

        //end approval queue jquery
        jQuery("div#full_background").click(function () {
            closePopup();
        });
        jQuery("img#popup_cross").click(function () {
            closePopup();
        });
        jQuery("div#js-jobs-comp-listwrapper").each(function () {
            jQuery(this).hover(function () {
                jQuery(this).find("span.selector").show();
            }, function () {
                if (jQuery(this).find("span.selector input:checked").length > 0) {
                    jQuery(this).find("span.selector").show();
                } else {
                    jQuery(this).find("span.selector").hide();
                }
            });
        });
        jQuery("span#showhidefilter").click(function (e) {
            e.preventDefault();
            var img2 = "<?php echo jsjobs::$_pluginpath . "includes/images/filter-up.png"; ?>";
            var img1 = "<?php echo jsjobs::$_pluginpath . "includes/images/filter-down.png"; ?>";
            if (jQuery('.default-hidden').is(':visible')) {
                jQuery(this).find('img').attr('src', img1);
            } else {
                jQuery(this).find('img').attr('src', img2);
            }
            jQuery(".default-hidden").toggle();
            var height = jQuery(this).height();
            var imgheight = jQuery(this).find('img').height();
            var currenttop = (height - imgheight) / 2;
            jQuery(this).find('img').css('top', currenttop);
        });
    });

    function highlight(id) {
        if (jQuery("div.company_" + id + " span input").is(":checked")) {
            jQuery("div.company_" + id).addClass('blue');
        } else {
            jQuery("div.company_" + id).removeClass('blue');
        }
    }
    function highlightAll() {
        if (jQuery("span.selector input").is(':checked') == false) {
            jQuery("span.selector").css('display', 'none');
            jQuery("div#js-jobs-comp-listwrapper").removeClass('blue');
        }
        if (jQuery("span.selector input").is(':checked') == true) {
            jQuery("div#js-jobs-comp-listwrapper").addClass('blue');
            jQuery("span.selector").css('display', 'block');
        }
    }
    function showBorder(id) {
        jQuery("div#resume_" + id + " div#item-data").css('border', '1px solid rgb(78, 140, 245)');
        jQuery("div#resume_" + id + " div#item-data").css('border-bottom', '1px solid #dedede');
        jQuery("div#resume_" + id + " div#item-actions").css('border', '1px solid rgb(78, 140, 245)');
        jQuery("div#resume_" + id + " div#item-actions").css('border-top', 'none');
    }
    function hideBorder(id) {
        jQuery("div#resume_" + id + " div#item-data").css('border', '1px solid #dedede');
        jQuery("div#resume_" + id + " div#item-actions").css('border', '1px solid #dedede');
        jQuery("div#resume_" + id + " div#item-actions").css('border-top', 'none');
    }
    function checkAllSelection() {
        var totalItems = jQuery("div.resume-container").length;
        jQuery("div.resume-container").each(function () {
            // if (jQuery("div.item"))
        });
    }

    function resetFrom() {
        document.getElementById('searchtitle').value = '';
        document.getElementById('searchname').value = '';
        document.getElementById('searchjobcategory').value = '';
        document.getElementById('searchjobtype').value = '';
        document.getElementById('datestart').value = '';
        document.getElementById('dateend').value = '';
        document.getElementById('jsjobsform').submit();
    }
    function changeSortBy() {
        var value = jQuery('a.sort-icon').attr('data-sortby');
        var img = '';
        if (value == 1) {
            value = 2;
            img = jQuery('a.sort-icon').attr('data-image2');
        } else {
            img = jQuery('a.sort-icon').attr('data-image1');
            value = 1;
        }
        jQuery("img#sortingimage").attr('src', img);
        jQuery('input#sortby').val(value);
        jQuery('form#jsjobsform').submit();
    }
    function changeCombo() {
        jQuery("input#sorton").val(jQuery('select#sorting').val());
        changeSortBy();
    }
    function approveActionPopup(id) {
        var cname = '.jobsqueueapprove_' + id;
        jQuery(cname).show();
        jQuery(cname).mouseout(function () {
            jQuery(cname).hide();
        });
    }

    function rejectActionPopup(id) {
        var cname = '.jobsqueuereject_' + id;
        jQuery(cname).show();
        jQuery(cname).mouseout(function () {
            jQuery(cname).hide();
        });
    }
    function hideThis(obj) {
        jQuery(obj).find('div#jsjobs-queue-actionsbtn').hide();
    }
</script>

<?php
//echo '<pre>';print_r(jsjobs::$_data[0]);exit;
$categoryarray = array(
    (object) array('id' => 1, 'text' => __('Application title', 'js-jobs')),
    (object) array('id' => 2, 'text' => __('First name', 'js-jobs')),
    (object) array('id' => 3, 'text' => __('Category', 'js-jobs')),
    (object) array('id' => 4, 'text' => __('Job type', 'js-jobs')),
    (object) array('id' => 5, 'text' => __('Location', 'js-jobs')),
    (object) array('id' => 6, 'text' => __('Created', 'js-jobs')),
    (object) array('id' => 7, 'text' => __('Status', 'js-jobs'))
);
?>

<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <?php 
    $msgkey = JSJOBSincluder::getJSModel('resume')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey);
?>

    <span class="js-admin-title">
        <span class="heading">
            <a href="<?php echo admin_url('admin.php?page=jsjobs'); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/back-icon.png" /></a>
            <span class="text-heading"><?php echo __('Resume Approval Queue', 'js-jobs') ?></span>    
        </span>
    </span>

    <div class="page-actions js-row no-margin">
        <label class="js-bulk-link button" onclick="return highlightAll();" for="selectall"><input type="checkbox" name="selectall" id="selectall" value=""><?php echo __('Select All', 'js-jobs') ?></label>
        <a class="js-bulk-link button multioperation" message="<?php echo JSJOBSMessages::getMSelectionEMessage(); ?>" confirmmessage="<?php echo __('Are you sure to delete', 'js-jobs') .' ?'; ?>" data-for="removeresume" href="#"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/delete-icon.png" /><?php echo __('Delete', 'js-jobs') ?></a>
        <?php
        $image1 = jsjobs::$_pluginpath . "includes/images/up.png";
        $image2 = jsjobs::$_pluginpath . "includes/images/down.png";
        if (jsjobs::$_data['sortby'] == 1) {
            $image = $image1;
        } else {
            $image = $image2;
        }
        ?>
        <span class="sort">
            <span class="sort-text"><?php echo __('Sort by', 'js-jobs'); ?>:</span>
            <span class="sort-field"><?php echo JSJOBSformfield::select('sorting', $categoryarray, jsjobs::$_data['combosort'], '', array('class' => 'inputbox', 'onchange' => 'changeCombo();')); ?></span>
            <a class="sort-icon" onclick="changeSortBy();"href="#" data-image1="<?php echo $image1; ?>" data-image2="<?php echo $image2; ?>" data-sortby="<?php echo jsjobs::$_data['sortby']; ?>"><img id="sortingimage" src="<?php echo $image; ?>" /></a>
        </span>
    </div>
    <form class="js-filter-form" name="jsjobsform" id="jsjobsform" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_resume&jsjobslt=resumequeue"); ?>">
        <?php echo JSJOBSformfield::text('searchtitle', jsjobs::$_data['filter']['searchtitle'], array('class' => 'inputbox', 'placeholder' => __('Title', 'js-jobs'))); ?>
        <?php echo JSJOBSformfield::text('searchname', jsjobs::$_data['filter']['searchname'], array('class' => 'inputbox', 'placeholder' => __('Name', 'js-jobs'))); ?>
        <?php echo JSJOBSformfield::select('searchjobcategory', JSJOBSincluder::getJSModel('category')->getCategoriesForCombo('kb'), jsjobs::$_data['filter']['searchjobcategory'], __('Select','js-jobs') .'&nbsp;'. __('Category', 'js-jobs'), array('class' => 'inputbox')); ?>
        <?php echo JSJOBSformfield::select('searchjobtype', JSJOBSincluder::getJSModel('jobtype')->getJobTypeForCombo(), jsjobs::$_data['filter']['searchjobtype'], __('Select','js-jobs') .'&nbsp;'. __('Job Type', 'js-jobs'), array('class' => 'inputbox default-hidden')); ?>
        <?php //echo JSJOBSformfield::select('searchjobsalaryrange', JSJOBSincluder::getJSModel('salaryrange')->getSalaryRangeForCombo(), jsjobs::$_data['filter']['searchjobsalaryrange'], __('Select','js-jobs') .'&nbsp;'. __('Salary Range', 'js-jobs'), array('class' => 'inputbox default-hidden')); ?>
        <?php echo JSJOBSformfield::text('datestart', jsjobs::$_data['filter']['datestart'], array('class' => 'custom_date default-hidden', 'placeholder' => __('Date Start', 'js-jobs'))); ?>
        <?php echo JSJOBSformfield::text('dateend', jsjobs::$_data['filter']['dateend'], array('class' => 'custom_date default-hidden', 'placeholder' => __('Date End', 'js-jobs'))); ?>
        <?php echo JSJOBSformfield::hidden('JSJOBS_form_search', 'JSJOBS_SEARCH'); ?>
        <div class="filterbutton">
            <?php echo JSJOBSformfield::submitbutton('btnsubmit', __('Search', 'js-jobs'), array('class' => 'button')); ?>
            <?php echo JSJOBSformfield::button('reset', __('Reset', 'js-jobs'), array('class' => 'button', 'onclick' => 'resetFrom();')); ?>
        </div>
        <?php echo JSJOBSformfield::hidden('sortby', jsjobs::$_data['sortby']); ?>
        <?php echo JSJOBSformfield::hidden('sorton', jsjobs::$_data['sorton']); ?>
        <span id="showhidefilter"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/filter-down.png"/></span>
    </form>
    <hr class="listing-hr" />
    <?php
    if (!empty(jsjobs::$_data[0])) {
        // print_r(jsjobs::$_data[0]);
        ?>  
        <div id="resume-approval-queue">
            <form id="jsjobs-list-form" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_resume"); ?>">
                <?php
                $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                foreach (jsjobs::$_data[0] AS $resume) {
                    $photo = '';
                    if (isset($resume->photo) && $resume->photo != '') {
                        $wpdir = wp_upload_dir();
                        $photo = $wpdir['baseurl'] . '/' . $data_directory . '/data/jobseeker/resume_' . $resume->id. '/photo/' . $resume->photo;
                        $padding = "";
                    } else {
                        $photo = jsjobs::$_pluginpath . '/includes/images/users.png';
                        $padding = "";
                    }


                    $approved = ($resume->status == 1) ? '<span style="color:Green">' . __('Approved', 'js-jobs') . '</span>' : '<span style="color:Green">' . __('Rejected', 'js-jobs') . '</span>';
                    ?>
                    <div id="js-jobs-comp-listwrapper">
                        <span id="selector_<?php echo $resume->id; ?>" class="selector"><input type="checkbox" onclick="javascript:highlight(<?php echo $resume->id; ?>);" class="jsjobs-cb" id="jsjobs-cb" name="jsjobs-cb[]" value="<?php echo $resume->id; ?>" /></span>
                        <div id="jsjobs-top-comp-left" class=" js_circle">
                            <div class="profile">
                                <a class="js-anchor" href="<?php echo admin_url('admin.php?page=jsjobs_resume&jsjobslt=formresume&jsjobsid='.$resume->id); ?>">
                                    <span class="js-border">
                                        <img src="<?php echo $photo; ?>" <?php echo $padding; ?> />
                                    </span>
                                </a>
                            </div>
                        </div>
                        <div id="jsjobs-top-comp-right">
                            <div id="jsjobslist-comp-header" class="jsjobsqueuereletive">
                                <div id="innerheaderlefti">
                                    <span class="datablockhead-left"></span><span class="datablockhead-left"><span class="notbold color-blue"><a href="<?php echo admin_url("admin.php?page=jsjobs_resume&jsjobslt=formresume&jsjobsid=".$resume->id);?>"><?php echo $resume->application_title; ?></a></span>

                                        <?php
                                            $dateformat = jsjobs::$_configuration['date_format'];
                                            $curdate = date_i18n($dateformat);
                                         ?>
                                    </span>
                                </div>
                                <div class="flag-and-type">
                                    <span id="js-queues-statuses" class="for-responsive"><?php
                                        $class_color = '';
                                        $arr = array();
                                        if ($resume->status == 0) {
                                            if ($class_color == '') {
                                                ?>

                                            <?php } ?>
                                            <?php
                                            $class_color = 'q-self';
                                            $arr['self'] = 1;
                                        }
                                        ?>

                                    </span>
                                </div>
                            </div>
                            <div id="jsjobslist-comp-body" class="jsjobslist-comp-body-for-responsive">
                                <span class="datablock" ><span class="txt-resume"><?php echo __('Name', 'js-jobs'); ?>: </span><span class="txt notbold color"><?php echo $resume->first_name . ' ' . $resume->last_name; ?></span></span>
                                <span class="datablock" ><span class="txt-resume"><?php echo __(jsjobs::$_data['fields']['job_category'], 'js-jobs'); ?>: </span><span class="txt notbold color"><?php echo __($resume->cat_title,'js-jobs'); ?></span></span>
                                <span class="datablock" ><span class="txt-resume">
                                <?php 
                                    if(!isset(jsjobs::$_data['fields']['desired_salary'])){
                                        jsjobs::$_data['fields']['desired_salary'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('desired_salary',3);
                                    }                                    
                                    echo __(jsjobs::$_data['fields']['desired_salary'], 'js-jobs'); ?>: </span><span class="txt notbold color"><?php echo JSJOBSincluder::getJSModel('common')->getSalaryRangeView($resume->symbol, $resume->rangestart, $resume->rangeend, $resume->rangetype); ?></span></span>
                                <span class="datablock job-que-category" ><span class="txt-resume"><?php echo __('Location', 'js-jobs'); ?>: </span><span class="txt notbold color"><?php echo JSJOBSincluder::getJSModel('city')->getLocationDataForView($resume->city); ?></span></span>
                            </div>
                        </div>
                        <div id="jsjobs-bottom-comp">
                            <div id="bottomleftnew" class="resumepaddingleftqueue">
                                <span class="js-created"><b><?php echo __('Created', 'js-jobs'); ?></b>:&nbsp;<span class="color"><?php echo date_i18n($dateformat, strtotime($resume->created)); ?></span></span>
                            </div>
                            <div id="bottomrightnew"> 
                                <a class="js-bottomspan" href="admin.php?page=jsjobs_resume&jsjobslt=viewresume&jsjobsid=<?php echo $resume->id; ?>" ><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/ad-resume.png" alt="del">&nbsp;&nbsp;<?php echo __('View', 'js-jobs'); ?></a>                  
                                <a class="js-bottomspan" href="admin.php?page=jsjobs_resume&jsjobslt=formresume&jsjobsid=<?php echo $resume->id; ?>" ><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/delete-icon.png" alt="del">&nbsp;&nbsp;<?php echo __('Edit', 'js-jobs'); ?></a>
                            <?php
                                $total = count($arr);
                                if ($total == 3) {
                                    $objid = 4; //for all
                                } elseif ($total != 1) {
                                }
                                if ($total == 1) {
                                    if (isset($arr['self'])) {
                                        ?>
                                        <a class="js-bottomspan" href="admin.php?page=jsjobs_resume&task=approveQueueResume&id=<?php echo $resume->id; ?>&action=jsjobtask"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/hired.png"><?php echo __('Approve', 'js-jobs'); ?></a>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <div class="js-bottomspan jobsqueue-approvalqueue" onmouseout="hideThis(this);" onmouseover="approveActionPopup('<?php echo $resume->id; ?>');"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/publish-icon.png">&nbsp;&nbsp;<?php echo __('Approve', 'js-jobs'); ?>
                                        <div id="jsjobs-queue-actionsbtn" class="jobsqueueapprove_<?php echo $resume->id; ?>">
                                            <?php if (isset($arr['self'])) { ?>
                                                <a id="jsjobs-act-row" class="jsjobs-act-row" href="admin.php?page=jsjobs_resume&task=approveQueueResume&id=<?php echo $resume->id; ?>&action=jsjobtask"><img class="jobs-action-image" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/comapny-logo.png"><?php echo __("Resume Approve", 'js-jobs'); ?></a>
                                            <?php
                                            } ?>
                                            <a id="jsjobs-act-row-all" class="jsjobs-act-row-all" href="admin.php?page=jsjobs_resume&task=approveQueueAllResumes&objid=<?php echo $objid; ?>&id=<?php echo $resume->id; ?>&action=jsjobtask"><img class="jobs-action-image" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/select-all.png"><?php echo __("All Approve", 'js-jobs'); ?></a>
                                        </div>
                                    </div>
                                    <?php
                                } // End approve
                                if ($total == 1) {
                                    if (isset($arr['self'])) {
                                        ?>
                                        <a class="js-bottomspan" href="admin.php?page=jsjobs_resume&task=rejectQueueResume&id=<?php echo $resume->id; ?>&action=jsjobtask"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/reject-s.png"><?php echo __('Reject', 'js-jobs'); ?></a>
            <?php
            }
        } else {
            ?>
                                    <div class="js-bottomspan jobsqueue-approvalqueue" onmouseout="hideThis(this);" onmouseover="rejectActionPopup('<?php echo $resume->id; ?>');"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/reject-s.png">&nbsp;&nbsp;<?php echo __('Reject', 'js-jobs'); ?>
                                        <div id="jsjobs-queue-actionsbtn" class="jobsqueuereject_<?php echo $resume->id; ?>">
                                            <?php if (isset($arr['self'])) { ?>
                                                <a id="jsjobs-act-row" class="jsjobs-act-row" href="admin.php?page=jsjobs_resume&task=rejectQueueResume&id=<?php echo $resume->id; ?>&action=jsjobtask"><img class="jobs-action-image" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/comapny-logo.png"><?php echo __("Resume Reject", 'js-jobs'); ?></a>
                                            <?php
                                            } ?>
                                            <a id="jsjobs-act-row-all" class="jsjobs-act-row-all" href="admin.php?page=resume&task=rejectQueueAllResumes&objid=<?php echo $objid; ?>&id=<?php echo $resume->id; ?>&action=jsjobtask"><img class="jobs-action-image" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/select-all.png"><?php echo __("All Reject", 'js-jobs'); ?></a>
                                        </div>
                                    </div>
        <?php }//End Reject 
        ?>
                                
                                <a class="js-bottomspan" href="admin.php?page=jsjobs_resume&task=removeresume&jsjobs-cb[]=<?php echo $resume->id; ?>&action=jsjobtask&callfrom=2" onclick="return confirm('<?php echo __('Are you sure to delete','js-jobs').' ?'; ?>');">
                                    <img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/delete-icon.png" alt="del" message="<?php echo JSJOBSMessages::getMSelectionEMessage(); ?>">&nbsp;&nbsp;<?php echo __('Delete', 'js-jobs'); ?>
                                </a>
                                <a class="js-bottomspan" href="admin.php?page=jsjobs_resume&task=resumeEnforceDelete&resumeid=<?php echo $resume->id; ?>&action=jsjobtask&callfrom=2" onclick="return confirmdelete('<?php echo __('This will delete every thing about this record','js-jobs').'. '.__('Are you sure to delete','js-jobs').'?'; ?>');" >
                                    <img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/fe-forced-delete.png" alt="fdel" message="<?php echo JSJOBSMessages::getMSelectionEMessage(); ?>">&nbsp;&nbsp;<?php echo __('Force Delete', 'js-jobs'); ?>
                                </a>        
                            </div>
                        </div>  
                    </div>

                    <?php
                }
                ?>
        <?php echo JSJOBSformfield::hidden('action', 'resume_remove'); ?>
        <?php echo JSJOBSformfield::hidden('task', ''); ?>
        <?php echo JSJOBSformfield::hidden('form_request', 'jsjobs'); ?>
        <?php echo JSJOBSformfield::hidden('callfrom', 2); ?>
            </form>
        </div>
        <?php
        if (jsjobs::$_data[1]) {
            echo '<div class="tablenav"><div class="tablenav-pages">' . jsjobs::$_data[1] . '</div></div>';
        }
    } else {
        $msg = __('No record found','js-jobs');
        echo JSJOBSlayout::getNoRecordFound($msg);
    }
    ?>
</div>
</div>