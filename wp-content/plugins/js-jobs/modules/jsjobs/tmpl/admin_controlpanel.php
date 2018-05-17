<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jsjob-res-tables', jsjobs::$_pluginpath . 'includes/js/responsivetable.js');
?>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script>
<script type="text/javascript">
            google.setOnLoadCallback(drawStackChartHorizontal);
            function drawStackChartHorizontal() {
            var data = google.visualization.arrayToDataTable([
<?php
echo jsjobs::$_data['stack_chart_horizontal']['title'] . ',';
echo jsjobs::$_data['stack_chart_horizontal']['data'];
?>
            ]);
                    var view = new google.visualization.DataView(data);
                    var options = {
                    curveType: 'function',
                            height:300,
                            legend: { position: 'top', maxLines: 3 },
                            pointSize: 4,
                            isStacked: true,
                            focusTarget: 'category',
                            chartArea: {width:'90%', top:50}
                    };
                    var chart = new google.visualization.LineChart(document.getElementById("stack_chart_horizontal"));
                    chart.draw(view, options);
            }
</script>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <div class="dashboard">
        <span class="heading-dashboard"><?php echo __('Dashboard', 'js-jobs'); ?></span>
        <span class="dashboard-icon">
            <?php
            $url = 'http://www.joomsky.com/appsys/latestversion.php?prod=wp-jobs';
            $pvalue = "dt=" . date_i18n('Y-m-d');
            if (in_array('curl', get_loaded_extensions())) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 8);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $pvalue);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
                $curl_errno = curl_errno($ch);
                $curl_error = curl_error($ch);
                $result = curl_exec($ch);
                curl_close($ch);
                $version = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('versioncode');
                if ($result == str_replace('.', '', $version)) {
                    $image = jsjobs::$_pluginpath . "includes/images/up-dated.png";
                    $lang = __('Your System Is Up To Date', 'js-jobs');
                    $class = "green";
                } elseif ($result) {
                    $image = jsjobs::$_pluginpath . "includes/images/new-version.png";
                    $lang = __('New Version Is Available', 'js-jobs');
                    $class = "orange";
                } else {
                    $image = jsjobs::$_pluginpath . "includes/images/connection-error.png";
                    $lang = __('Unable Connect To Server', 'js-jobs');
                    $class = "red";
                }
            } else {
                $image = jsjobs::$_pluginpath . "includes/images/connection-error.png";
                $lang = __('Unable Connect To Server', 'js-jobs');
            }
            ?>
            <span class="download <?php echo $class; ?>">
                <img src="<?php echo $image; ?>" />
                <span><?php echo $lang; ?></span>
            </span>
        </span>
    </div>
    <div id="jsjobs-admin-wrapper">
        <div class="count1">
            <div class="box">
                <img class="job" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/top-icons/job.png">
                <div class="text">
                    <div class="bold-text"><?php echo jsjobs::$_data['totaljobs']; ?></div>
                    <div class="nonbold-text"><?php echo __('Jobs', 'js-jobs'); ?></div>   
                </div>
            </div>
            <div class="box">
                <img class="company" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/top-icons/companies.png">
                <div class="text">
                    <div class="bold-text"><?php echo jsjobs::$_data['totalcompanies']; ?></div>
                    <div class="nonbold-text"><?php echo __('Companies', 'js-jobs'); ?></div>   
                </div>
            </div>
            <div class="box">
                <img class="resume" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/top-icons/reume.png">
                <div class="text">
                    <div class="bold-text"><?php echo jsjobs::$_data['totalresume']; ?></div>
                    <div class="nonbold-text"><?php echo __('Resume', 'js-jobs'); ?></div>
                </div>
            </div>
            <div class="box">
                <img class="activejobs" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/top-icons/active-jobs.png">
                <div class="text">
                    <div class="bold-text"><?php echo jsjobs::$_data['totalactivejobs']; ?></div>
                    <div class="nonbold-text"><?php echo __('Active Jobs', 'js-jobs'); ?></div>    
                </div>               
            </div>
            <div class="box1">
                <img class="appliedresume" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/top-icons/job-applied.png">
                <div class="text">
                    <div class="bold-text"><?php echo jsjobs::$_data['totaljobapply']; ?></div>
                    <div class="nonbold-text"><?php echo __('Applied Resume', 'js-jobs'); ?></div>
                </div>    
            </div>
        </div>
        <div class="newestjobs">
            <span class="header">
                <img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/newesticon.png">
                <span><?php echo __('Statistics', 'js-jobs'); ?>&nbsp;(<?php echo jsjobs::$_data['fromdate']; ?>&nbsp;-&nbsp;<?php echo jsjobs::$_data['curdate']; ?>)&nbsp;</span>
            </span>
            <div class="performance-graph" id="stack_chart_horizontal"></div>
        </div>
        <div class="count2">
            <div class="js-col-md-3 js-col-lg-3 js-col-xs-12 jsjobs- box-outer">
                <div class="box">
                    <img class="newjobs" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/lower-icons/jobs.png">
                    <div class="text">
                        <div class="bold-text"><?php echo jsjobs::$_data['totalnewjobs']; ?></div>
                        <div class="nonbold-text"><?php echo __('New Jobs', 'js-jobs'); ?></div>   
                    </div>
                </div>
            </div>
            <div class="js-col-md-3 js-col-lg-3 js-col-xs-12 jsjobs- box-outer">
                <div class="box">
                    <img class="newresume" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/lower-icons/reume.png">
                    <div class="text">
                        <div class="bold-text"><?php echo jsjobs::$_data['totalnewresume']; ?></div>
                        <div class="nonbold-text"><?php echo __('New Resume', 'js-jobs'); ?></div>   
                    </div>
                </div>
            </div>
            <div class="js-col-md-3 js-col-lg-3 js-col-xs-12 jsjobs- box-outer">
                <div class="box">
                    <img class="jobapplied" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/lower-icons/job-applied.png">
                    <div class="text">
                        <div class="bold-text"><?php echo jsjobs::$_data['totalnewjobapply']; ?></div>
                        <div class="nonbold-text"><?php echo __('Job Applied', 'js-jobs'); ?></div>   
                    </div>
                </div>
            </div>
            <div class="js-col-md-3 js-col-lg-3 js-col-xs-12 jsjobs- box-outer">
                <div class="box">
                    <img class="newcompanies" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/lower-icons/companies.png">
                    <div class="text">
                        <div class="bold-text"><?php echo jsjobs::$_data['totalnewcompanies']; ?></div>
                        <div class="nonbold-text"><?php echo __('New Companies', 'js-jobs'); ?></div>   
                    </div>
                </div>
            </div>    
        </div>
        <div class="main-heading">
            <span class="text"><?php echo __('Admin', 'js-jobs'); ?></span>
        </div>
        <div class="categories-admin">
            <a href="admin.php?page=jsjobs_job" class="box">
                <img class="jobs" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/jobs/job.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Jobs', 'js-jobs') ?></div>   
                </div>
            </a>
            <a href="admin.php?page=jsjobs_job&jsjobslt=jobqueue" class="box">
                <img class="approval-queue" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/jobs/approval-queue.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Approval Queue', 'js-jobs') ?></div>   
                </div>
            </a>
            <a href="admin.php?page=jsjobs_fieldordering&ff=2" class="box">
                <img class="Fields" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/jobs/fields.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Fields', 'js-jobs') ?></div>   
                </div>
            </a>
            <a href="admin.php?page=jsjobs_report&jsjobslt=overallreports" class="box">
                <img class="jsjobstats" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/report.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Reports', 'js-jobs'); ?></div>   
                </div>
            </a>
            <?php /*
            <a href="admin.php?page=jsjobs&jsjobslt=profeatures" class="box">
                <img id="js-proicon" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/pro-icon.png">
                <img class="packages" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/package.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Credits Pack', 'js-jobs'); ?></div>
                </div>
            </a>
            <a href="admin.php?page=jsjobs&jsjobslt=profeatures" class="box">
                <img id="js-proicon" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/pro-icon.png">
                <img class="payments" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/paymentt.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Credits Log', 'js-jobs'); ?></div>
                </div>
            </a>
            <a href="admin.php?page=jsjobs&jsjobslt=profeatures" class="box">
                <img id="js-proicon" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/pro-icon.png">
                <img class="messages" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/message.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Messages', 'js-jobs'); ?></div>   
                </div>
            </a>
            */ ?>
            <a href="admin.php?page=jsjobs_category" class="box">
                <img class="categories" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/category.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Categories', 'js-jobs'); ?></div>   
                </div>
            </a>
            <a href="admin.php?page=jsjobs&jsjobslt=info" class="box">
                <img class="information" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/information.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Information', 'js-jobs'); ?></div>   
                </div>
            </a>            
            <a href="admin.php?page=jsjobs_activitylog" class="box">
                <img class="information" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/activity-log.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Activity Log', 'js-jobs'); ?></div>   
                </div>
            </a>            
            <a href="admin.php?page=jsjobs_systemerror" class="box">
                <img class="information" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/system-error.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('System Errors', 'js-jobs'); ?></div>   
                </div>
            </a>            
            <a href="admin.php?page=jsjobs&jsjobslt=translations" class="box">
                <img class="information" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/language.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Translations', 'js-jobs'); ?></div>   
                </div>
            </a>            
        </div>

        <div style="margin-bottom:10px;" >
            <a href="https://www.joomsky.com/products/js-jobs-pro-wp.html" target="_blank" title="Job Manager Pro Plugin" >
                <img style="width: 100%;height: auto;" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/banner-plugin.png">
            </a>
        </div>
        <div class="main-heading">
            <span class="text"><?php echo __('Configuration', 'js-jobs'); ?></span>
        </div>
        <div class="categories-configuration">
            <a href="admin.php?page=jsjobs_configuration&jsjobslt=configurations" class="box">
                <img class="general" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/Configuration/cofigration.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('General', 'js-jobs') ?></div>   
                </div>
            </a>
            <a href="admin.php?page=jsjobs_configuration&jsjobslt=configurationsjobseeker" class="box">
                <img class="jobseeker" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/Configuration/jobseeker-2e.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Job Seeker', 'js-jobs') ?></div>   
                </div>
            </a>
            <a href="admin.php?page=jsjobs_configuration&jsjobslt=configurationsemployer" class="box">
                <img class="employer" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/Configuration/jobseeker.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Employer', 'js-jobs') ?></div>   
                </div>
            </a>
            <?php /*
            <a href="admin.php?page=jsjobs&jsjobslt=profeatures" class="box">
                <img id="js-proicon" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/pro-icon.png">
                <img class="payment-method" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/Configuration/paymentt.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Payment Methods', 'js-jobs') ?></div>   
                </div>
            </a>
            <a href="admin.php?page=jsjobs&jsjobslt=profeatures" class="box">
                <img id="js-proicon" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/pro-icon.png">
                <img class="themes" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/Configuration/theme.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Themes', 'js-jobs') ?></div>   
                </div>
            </a>
            */ ?>
        </div>

        <div class="newestjobs">
            <span class="header">
                <img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/newesticon.png">
                <span><?php echo __('Newest Jobs', 'js-jobs'); ?></span>
            </span>
            <table id="js-table" class="newestjobtable">
                <thead>
                    <tr>
                        <th class="colunm-heading"><?php echo __('Job title', 'js-jobs'); ?></th>
                        <th class="colunm-heading"><?php echo __('Company', 'js-jobs'); ?></th>
                        <th class="colunm-heading"><?php echo __('Location', 'js-jobs'); ?></th>
                        <th class="colunm-heading"><?php echo __('Status', 'js-jobs'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (jsjobs::$_data[0]['latestjobs'] AS $latestjobs) { ?>
                        <tr>
                            <td class="job-title"><a href="admin.php?page=jsjobs_job&jsjobslt=formjob&jsjobsid=<?php echo $latestjobs->id; ?>"><?php echo $latestjobs->title; ?></a></td>
                            <td class="description"><?php echo $latestjobs->name; ?></td>
                            <td class="description"><?php echo JSJOBSincluder::getJSModel('city')->getLocationDataForView($latestjobs->city); ?></td>
                            <?php
                            $status;
                            $startDate = date_i18n('Y-m-d',strtotime($latestjobs->startpublishing));
                            $stopDate = date_i18n('Y-m-d',strtotime($latestjobs->stoppublishing));                            
                            $currentDate = $date = date_i18n("Y-m-d");
                            if ($startDate > $currentDate) {
                                $status = __('Unpublished', 'js-jobs');
                                $class = "unpublished";
                            } elseif ($startDate <= $currentDate && $stopDate >= $currentDate) {
                                $status = __('Published', 'js-jobs');
                                $class = "published";
                            }elseif ($stopDate < $currentDate) {
                                $status = __('Expired', 'js-jobs');
                                $class = "expired";
                            }
                            ?>
                            <td class="status">
                                <span class="<?php echo $class; ?>"><?php echo $status; ?></span>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="main-heading">
            <span class="text"><?php echo __('Companies', 'js-jobs'); ?></span>
        </div>
        <div class="categories-companies">
            <a href="admin.php?page=jsjobs_company" class="box">
                <img class="companies" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/companies/companies.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Company', 'js-jobs') ?></div>   
                </div>
            </a>
            <a href="admin.php?page=jsjobs_company&jsjobslt=companiesqueue" class="box">
                <img class="approval-queue" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/companies/approval-queue.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Approval Queue', 'js-jobs') ?></div>   
                </div>
            </a>
            <a href="admin.php?page=jsjobs_fieldordering&ff=1" class="box">
                <img class="Fields" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/companies/fields.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Fields', 'js-jobs') ?></div>   
                </div>
            </a>
        </div>
        <div class="main-heading">
            <span class="text"><?php echo __('Resume', 'js-jobs'); ?></span>          
        </div>
        <div class="categories-resume">
            <a href="admin.php?page=jsjobs_resume" class="box">
                <img class="resume" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/resume/resume.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Resume', 'js-jobs') ?></div>   
                </div>
            </a>
            <a href="admin.php?page=jsjobs_resume&jsjobslt=resumequeue" class="box">
                <img class="approval-queue" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/resume/approval-queue.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Approval Queue', 'js-jobs') ?></div>   
                </div>
            </a>
            <a href="admin.php?page=jsjobs_fieldordering&ff=3" class="box">
                <img class="Fields" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/resume/fields.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Fields', 'js-jobs') ?></div>   
                </div>
            </a>
        </div>


                        <div class="main-heading">
                            <span class="text"><?php echo __('Misc.', 'js-jobs'); ?></span>
                            <?php /*
                              <span class="showmore">
                              <a class="img" href=""><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/Menu-icon.png">Show More</a>
                              </span>
                             */ ?>
                        </div>

                        <div class="categories-jobs">
                            <a href="admin.php?page=jsjobs_shift" class="box">
                                <img class="shifts" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/shift.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Shift', 'js-jobs'); ?></div>
                                </div>
                            </a>
                            <a href="admin.php?page=jsjobs_highesteducation" class="box">
                                <img class="heighesteducation" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/higest-edu.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Education', 'js-jobs'); ?></div>   
                                </div>
                            </a>
                            <a href="admin.php?page=jsjobs_careerlevel" class="box">
                                <img class="careerlavel" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/career-level.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Career Level', 'js-jobs'); ?></div>
                                </div>
                            </a>
                            <a href="admin.php?page=jsjobs_experience" class="box">
                                <img class="experince" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/experience.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Experience', 'js-jobs'); ?></div>   
                                </div>
                            </a>
                            <a href="admin.php?page=jsjobs_departments" class="box">
                                <img class="department" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/department.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Departments', 'js-jobs'); ?></div>   
                                </div>
                            </a>
                            <?php /*
                            <a href="admin.php?page=jsjobs&jsjobslt=profeatures" class="box">
                                <img id="js-proicon" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/pro-icon.png">
                                <img class="folders" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/folder.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Folders', 'js-jobs'); ?></div>   
                                </div>
                            </a>
                            */ ?>
                            <a href="admin.php?page=jsjobs_salaryrange" class="box">
                                <img class="salaryrange" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/salary-range.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Salary Range', 'js-jobs'); ?></div>   
                                </div>
                            </a>        
                            <a href="admin.php?page=jsjobs&jsjobslt=stepone" class="box">
                                <img class="salaryrange" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/report.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Update', 'js-jobs'); ?></div>   
                                </div>
                            </a> 
                            <?php /*       
                            <a href="admin.php?page=jsjobs&jsjobslt=profeatures" class="box">
                                <img id="js-proicon" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/pro-icon.png">
                                <img class="salaryrange" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/tag.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Tags', 'js-jobs'); ?></div>   
                                </div>
                            </a>   
                            */ ?>     
                            <a href="admin.php?page=jsjobs_emailtemplate" class="box">
                                <img class="salaryrange" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/email-temp.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Email Templates', 'js-jobs'); ?></div>   
                                </div>
                            </a>        
                            <a href="admin.php?page=jsjobs_user&jsjobslt=users" class="box">
                                <img class="salaryrange" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/users.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Users', 'js-jobs'); ?></div>   
                                </div>
                            </a>        
                        </div>
                        <?php /*
                        <a id="jsjobs_pro_feature_img_link" target="_blank" href="http://www.joomsky.com/products/js-jobs-pro-wp.html">
                            <img id="jobs-pro-img" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/final-banner.png">
                        </a>
*/?>
                        <div style="margin-bottom:10px;" >
                            <a href="https://www.joomsky.com/products/js-jobs/job-manager-theme.html" target="_blank" title="Job Manager Theme" >
                                <img style="width: 100%;height: auto;" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/banner-theme.png">
                            </a>
                        </div>

                        <div class="main-heading">
                            <span class="text"><?php echo __('Support', 'js-jobs'); ?></span>          
                        </div>
                        <div class="categories-resume">
                            <a href="<?php echo admin_url('?page=jsjobs&jsjobslt=shortcodes'); ?>" class="box">
                                <img class="resume" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/shortcode.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Short codes', 'js-jobs') ?></div>   
                                </div>
                            </a>
                            <a href="http://www.joomsky.com/appsys/documentations/wp-jobs" class="box">
                                <img class="resume" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/doc.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Documentation', 'js-jobs') ?></div>   
                                </div>
                            </a>
                            <a href="http://www.joomsky.com/appsys/forum/wp-jobs" class="box">
                                <img class="approval-queue" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/forum.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Forum', 'js-jobs') ?></div>   
                                </div>
                            </a>
                            <a href="http://www.joomsky.com/appsys/support/wp-jobs" class="box">
                                <img class="Fields" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/support.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Support', 'js-jobs') ?></div>   
                                </div>
                            </a>
                            <a href="http://www.joomsky.com/appsys/getstarted/wp-jobs" class="simple-wrapper">
                                <img class="icon" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/gst1.png" />
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Get Started', 'js-jobs') ?></div>
                                </div>
                                <img class="simple-arrow" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/control_panel/latest-icons-admin/gst2.png" />
                            </a>
                            
                        </div>
                        <div class="review">
                            <div class="upper">
                                <div class="imgs">
                                    <img class="reviewpic" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/review.png">
                                    <img class="reviewpic2" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/corner-1.png">
                                </div>
                                <div class="text">
                                    <div class="simple-text">
                                        <span class="nobold"><?php echo __('We\'d love to hear from ', 'js-jobs'); ?></span>
                                        <span class="bold"><?php echo __('You', 'js-jobs'); ?>.</span>
                                        <span class="nobold"><?php echo __('Please write appreciated review at', 'js-jobs'); ?></span>
                                    </div>
                                    <a href="https://wordpress.org/support/view/plugin-reviews/js-jobs" target="_blank"><?php echo __('Word Press Extension Directory', 'js-jobs'); ?><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/arrow2.png"></a>
                                </div>
                                <div class="right">
                                    <img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/star.png">
                                </div>
                            </div>
                            <div class="lower">

                            </div>
                        </div>
                        
                    </div>
                </div>  
            </div>  
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                    jQuery('div.resume').animate({left: '-100%'});
                            jQuery('div.companies span.img img').click(function (e) {
                    jQuery('div.companies').animate({left: '-100%'});
                            jQuery('div.resume').animate({left: '0%'});
                    });
                            jQuery('div.resume span.img img').click(function (e) {
                    jQuery('div.resume').animate({left: '-100%'});
                            jQuery('div.companies').animate({left: '0%'});
                    });
                            jQuery('div.jobs').animate({right: '-100%'});
                            jQuery('div.jobs span.img img').click(function (e) {
                    jQuery('div.jobs').animate({right: '-100%'});
                            jQuery('div.appliedjobs').animate({right: '0%'});
                    });
                            jQuery('div.appliedjobs span.img img').click(function (e) {
                    jQuery('div.appliedjobs').animate({right: '-100%'});
                            jQuery('div.jobs').animate({right: '0%'});
                    });
                            jQuery("span.dashboard-icon").find('span.download').hover(function(){
                    jQuery(this).find('span').toggle("slide");
                    }, function(){
                    jQuery(this).find('span').toggle("slide");
                    });
                    });
                </script>
