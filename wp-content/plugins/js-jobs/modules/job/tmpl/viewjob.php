<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
$msgkey = JSJOBSincluder::getJSModel('job')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(jsjobs::$_path . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    $config_array = jsjobs::$_data['config'];
    function getDataRow($title, $value) {
        $html = '<div class="detail-wrapper"  >
                        <span class="heading">' . $title . ':&nbsp</span>
                        <span class="txt">' . $value . '</span>
                </div>';
        return $html;
    }

    function getHeading2($value) {
        $html = '<div class="heading2">' . $value . '</div>';
        return $html;
    }

    function getPeragraph($value) {
        $html = '<div class="peragraph">' . $value . '</div>';
        return $html;
    }
    echo '<meta property="description" content="'.jsjobs::$_data[0]->metadescription.'"/>';
    echo '<meta property="keywords" content="'.jsjobs::$_data[0]->metakeywords.'"/>';
    ?>
    <div id="jsjobs-wrapper"> 
        <div id="jsjob-popup-background"></div>
        <div id="jsjobs-listpopup">
            <span class="popup-title"><span class="title"></span><img id="popup_cross" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/popup-close.png"></span>
            <div class="jsjob-contentarea"></div>
        </div>
        <div class="page_heading"><?php echo __('View Job', 'js-jobs'); ?></div>
        <div id="view-job-wrapper">
            <div class="top">
                <div class="inner-wrapper">
                    <div class="jobname"><?php echo jsjobs::$_data[0]->title; ?></div>
                    <div class="jobdetail">
                        <span class="get-text"><?php
                            if($config_array['comp_name'] == 1){ ?>
                                <a href="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>jsjobs::$_data[0]->companyid)); ?>">
                                    <span class="comp-name"><?php echo jsjobs::$_data[0]->companyname; ?></span>
                                </a><?php
                            }
                            $dateformat = jsjobs::$_configuration['date_format'];
                            $curdate = date_i18n($dateformat);
                            ?>
                        </span>
                        <span>
                            <img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/location.png">
                            <span class="city">
                                <?php echo jsjobs::$_data[0]->multicity; ?>
                            </span>
                        </span>
                        <span class="agodays">
                            <?php echo date_i18n($dateformat, strtotime(jsjobs::$_data[0]->startpublishing)); ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="btn-div">
                <a class="btn blue" href="#heading_overview"><?php echo __('Overview', 'js-jobs'); ?></a>
                <a class="btn" href="#heading_requirements"><?php echo __('Requirements', 'js-jobs'); ?></a>
                <a class="btn" href="#heading_jobstatus"><?php echo __('Job Status', 'js-jobs'); ?></a>
                <a class="btn" href="#heading_location"><?php echo __('Location', 'js-jobs'); ?></a>
            </div>
            <div class="main">
                <?php
                if (isset(jsjobs::$_data[2])) {
                    ?>
                    <div id="heading_overview" class="heading1"  ><?php echo __('Overview', 'js-jobs'); ?></div>
                    <div class="left">
                        <?php
                        foreach (jsjobs::$_data[2] AS $key => $fields) {
                            switch ($fields->field) {
                                case 'jobtype':
                                    echo getDataRow(__($fields->fieldtitle, 'js-jobs'), __(jsjobs::$_data[0]->jobtypetitle,'js-jobs'));
                                    break;
                                case 'duration':
                                    echo getDataRow(__($fields->fieldtitle, 'js-jobs'), jsjobs::$_data[0]->duration);
                                    break;
                                case 'jobsalaryrange':
                                    echo getDataRow(__($fields->fieldtitle, 'js-jobs'), jsjobs::$_data[0]->salary);
                                    break;
                                case 'department':
                                    echo getDataRow(__($fields->fieldtitle, 'js-jobs'), jsjobs::$_data[0]->departmentname);
                                    break;
                                case 'jobcategory':
                                    echo getDataRow(__($fields->fieldtitle, 'js-jobs'), __(jsjobs::$_data[0]->cat_title,'js-jobs'));
                                    break;
                                case 'jobshift':
                                    echo getDataRow(__($fields->fieldtitle, 'js-jobs'), __(jsjobs::$_data[0]->shifttitle,'js-jobs'));
                                    break;
                                case 'zipcode':
                                    echo getDataRow(__($fields->fieldtitle, 'js-jobs'), jsjobs::$_data[0]->zipcode);
                                    break;
                                default:
                                    if($fields->isuserfield == 1){
                                        echo JSJOBSincluder::getObjectClass('customfields')->showCustomFields($fields, 2, jsjobs::$_data[0]->params);
                                        unset(jsjobs::$_data[2][$key]);
                                    }
                                    break;
                            }
                        }
                            echo getDataRow(__('Posted', 'js-jobs'), date_i18n($dateformat, strtotime(jsjobs::$_data[0]->startpublishing)));
                    }
                    if (isset(jsjobs::$_data[2])) {
                        ?>
                        <div id="heading_requirements" class="heading1"  ><?php echo __('Requirements', 'js-jobs'); ?></div>
                        <?php
                        if(jsjobs::$_data[0]->iseducationminimax == 0){
                            $edutitle = jsjobs::$_data[0]->mineducationtitle .'-'. __(jsjobs::$_data[0]->maxeducationtitle,'js-jobs');
                        }else{
                            if(jsjobs::$_data[0]->educationminimax == 2){
                                $edutitle = __('Maximum Education','js-jobs').' '. __(jsjobs::$_data[0]->educationtitle,'js-jobs');
                            }else{
                                $edutitle = __('Minimum Education','js-jobs').' '. __(jsjobs::$_data[0]->educationtitle,'js-jobs');
                            }
                        }
                        if(jsjobs::$_data[0]->isexperienceminimax == 0){
                            $exptitle = jsjobs::$_data[0]->minexperiencetitle .'-'. __(jsjobs::$_data[0]->maxexperiencetitle,'js-jobs');
                        }else{
                            if(jsjobs::$_data[0]->experienceminimax == 2){
                                $exptitle = __('Maximum Experience','js-jobs').' '. __(jsjobs::$_data[0]->experiencetitle,'js-jobs');
                            }else{
                                $exptitle = __('Minimum Experience','js-jobs').' '. __(jsjobs::$_data[0]->experiencetitle,'js-jobs');
                            }
                        }

                        foreach (jsjobs::$_data[2] AS $fields) {
                            switch ($fields->field) {
                                
                                case 'heighesteducation':
                                    echo getDataRow(__($fields->fieldtitle, 'js-jobs'), $edutitle);
                                    echo getDataRow(__('Degree title', 'js-jobs'), jsjobs::$_data[0]->degreetitle);
                                    break;
                                
                                case 'experience':
                                    echo getDataRow(__($fields->fieldtitle, 'js-jobs'), $exptitle);
                                    if(jsjobs::$_data[0]->experiencetext){
                                        echo getDataRow(__('Other experience', 'js-jobs'), jsjobs::$_data[0]->experiencetext);
                                    }
                                    break;
                                case 'age':
                                    echo getDataRow(__($fields->fieldtitle, 'js-jobs'), __(jsjobs::$_data[0]->agefrom,'js-jobs') . '&nbsp' . __(jsjobs::$_data[0]->ageto,'js-jobs'));
                                    break;
                                case 'workpermit':
                                    echo getDataRow(__($fields->fieldtitle, 'js-jobs'), jsjobs::$_data[0]->workpermittitle);
                                    break;
                                case 'requiredtravel':
                                    $value = JSJOBSincluder::getJSModel('common')->getRequiredTravelValue(jsjobs::$_data[0]->requiredtravel);
                                    echo getDataRow(__($fields->fieldtitle, 'js-jobs'), $value);
                                    break;
                                default:
                                    if($fields->isuserfield == 1){
                                        echo JSJOBSincluder::getObjectClass('customfields')->showCustomFields($fields, 2, jsjobs::$_data[0]->params);
                                        unset(jsjobs::$_data[2][$key]);
                                    }
                                    break;
                            }
                        }
                    }
                    if (isset(jsjobs::$_data[2])) {
                        ?>
                        <div id="heading_jobstatus" class="heading1"  ><?php echo __('Job Status', 'js-jobs'); ?></div>
                        <?php
                        foreach (jsjobs::$_data[2] AS $fields) {
                            switch ($fields->field) {
                                case 'jobstatus':
                                    echo getDataRow(__($fields->fieldtitle, 'js-jobs'), __(jsjobs::$_data[0]->jobstatustitle,'js-jobs'));
                                    break;
                                case 'startpublishing':
                                    echo getDataRow(__($fields->fieldtitle, 'js-jobs'), date_i18n($dateformat, strtotime(jsjobs::$_data[0]->startpublishing)));
                                    break;
                                case 'noofjobs':
                                    echo getDataRow(__($fields->fieldtitle, 'js-jobs'), jsjobs::$_data[0]->noofjobs);
                                    break;
                                case 'stoppublishing':
                                    echo getDataRow(__($fields->fieldtitle, 'js-jobs'), date_i18n($dateformat, strtotime(jsjobs::$_data[0]->stoppublishing)));
                                    break;
                                default:
                                    if($fields->isuserfield == 1){
                                        echo JSJOBSincluder::getObjectClass('customfields')->showCustomFields($fields, 2, jsjobs::$_data[0]->params);
                                        unset(jsjobs::$_data[2][$key]);
                                    }
                                    break;
                            }
                        }
                    }
                    ?>
                </div>
                <div class="right">
                    <div class="companywrapper">
                        <?php
                        if (jsjobs::$_data[0]->logofilename != "") {
                            $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                            $wpdir = wp_upload_dir();
                            $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . jsjobs::$_data[0]->companyid . '/logo/' . jsjobs::$_data[0]->logofilename;
                        } else {
                            $path = jsjobs::$_pluginpath . '/includes/images/default_logo.png';
                        }
                        ?>
                        <div class="company-img">
                            <a href="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>jsjobs::$_data[0]->companyid)); ?>">
                                <img src="<?php echo $path; ?>">
                            </a>
                        </div>
                        <div class="copmany-detail"><?php
                            if($config_array['comp_name']){ ?>
                               <span class="heading"><?php echo jsjobs::$_data[0]->companyname; ?></span><?php
                            } 
                            if($config_array['comp_show_url']){ ?>
                                <a href="<?php echo jsjobs::$_data[0]->companyurl; ?>" class="url"><?php echo jsjobs::$_data[0]->companyurl; ?></a><?php 
                            }if($config_array['comp_city']){ ?>
                                <span class="address">
                                    <img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/location.png"><?php echo JSJOBSIncluder::getJSModel('city')->getLocationDataForView(jsjobs::$_data[0]->compcity); ?>
                                </span><?php
                            } ?>

                            <div id="job-info-sociallink">
                                <?php
                                if (!empty(jsjobs::$_data[0]->facebook)) {
                                    echo '<a href="' . jsjobs::$_data[0]->facebook . '" target="_blank"><img src="' . jsjobs::$_pluginpath . 'includes/images/scround/fb.png"/></a>';
                                }
                                if (!empty(jsjobs::$_data[0]->twitter)) {
                                    echo '<a href="' . jsjobs::$_data[0]->twitter . '" target="_blank"><img src="' . jsjobs::$_pluginpath . 'includes/images/scround/twitter.png"/></a>';
                                }
                                if (!empty(jsjobs::$_data[0]->googleplus)) {
                                    echo '<a href="' . jsjobs::$_data[0]->googleplus . '" target="_blank"><img src="' . jsjobs::$_pluginpath . 'includes/images/scround/gmail.png"/></a>';
                                }
                                if (!empty(jsjobs::$_data[0]->linkedin)) {
                                    echo '<a href="' . jsjobs::$_data[0]->linkedin . '" target="_blank"><img src="' . jsjobs::$_pluginpath . 'includes/images/scround/in.png"/></a>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (isset(jsjobs::$_data[2]) && jsjobs::$_data[0]->longitude != '' && jsjobs::$_data[0]->latitude != '') { ?>
                <div id="heading_location" class="heading1"  ><?php echo __('Location', 'js-jobs'); ?></div>
                <?php
                foreach (jsjobs::$_data[2] AS $fields) {
                    switch ($fields->field) {
                        case 'city':
                            echo getDataRow(__('Address', 'js-jobs'), jsjobs::$_data[0]->multicity);
                            break;
                    }
                }
                ?>
                <div class="js-col-md-12 js-form-value"><div id="map_container" style="display:inline-block; width:100%;"><div id="map"></div></div></div>
                <?php
            }

            if (isset(jsjobs::$_data[2]) && jsjobs::$_data[0]->description != '') {
                echo getHeading2(__('Description', 'js-jobs'));
                echo getPeragraph(jsjobs::$_data[0]->description);
            }

            if (isset(jsjobs::$_data[2]) && jsjobs::$_data[0]->agreement != '') {
                echo getHeading2(__('Agreement', 'js-jobs'));
                echo getPeragraph(jsjobs::$_data[0]->agreement);
            }

            if (isset(jsjobs::$_data[2]) && jsjobs::$_data[0]->qualifications != '') {
                echo getHeading2(__('Qualifications', 'js-jobs'));
                echo getPeragraph(jsjobs::$_data[0]->qualifications);
            }
            if (isset(jsjobs::$_data[2]) && jsjobs::$_data[0]->prefferdskills != '') {
                echo getHeading2(__('Preferred Skills', 'js-jobs'));
                echo getPeragraph(jsjobs::$_data[0]->prefferdskills);
            }
            ?>
            <div class="apply"><?php
                if($config_array['showapplybutton'] == 1){  
                    if(jsjobs::$_data[0]->jobapplylink == 1 && !empty(jsjobs::$_data[0]->joblink)){
                        if(!strstr('http',jsjobs::$_data[0]->joblink)){
                            jsjobs::$_data[0]->joblink = 'http://'.jsjobs::$_data[0]->joblink;
                        } ?>
                        <a class="apply-btn" href= "<?php echo jsjobs::$_data[0]->joblink ;?>" target="_blank" ><?php echo __('Apply Now','js-jobs'); ?></a><?php 
                    }elseif(!empty($config_array['applybuttonredirecturl'])){ 
                        if(!strstr('http',$config_array['applybuttonredirecturl'])){
                            $joblink = 'http://'.$config_array['applybuttonredirecturl'];
                        }else{
                            $joblink = $config_array['applybuttonredirecturl'];
                        } ?>
                        <a class="apply-btn" href= "<?php echo $joblink; ?>" target="_blank" ><?php echo __('Apply Now','js-jobs'); ?></a><?php 
                    }else{ ?>
                        <a class="apply-btn" onclick="getApplyNowByJobid(<?php echo jsjobs::$_data[0]->id; ?>)"><?php echo __('Apply This Job', 'js-jobs'); ?></a><?php
                    }
                }?>    
            </div>
        </div>
    </div>
<?php 
}else{
    echo jsjobs::$_error_flag_message;
} ?>
