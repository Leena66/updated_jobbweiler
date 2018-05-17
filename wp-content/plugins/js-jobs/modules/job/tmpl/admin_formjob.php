<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

$config = jsjobs::$_configuration;
if ($config['date_format'] == 'm/d/Y' || $config['date_format'] == 'd/m/y' || $config['date_format'] == 'm/d/y' || $config['date_format'] == 'd/m/Y') {
    $dash = '/';
} else {
    $dash = '-';
}
$dateformat = $config['date_format'];
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
<style type="text/css">
    div#map_container{
        width: 100%;
        height:<?php echo jsjobs::$_configuration['mapheight'] . 'px'; ?>;
    }    
</style>
<?php
$lists = array();
$defaultCategory = JSJOBSincluder::getJSModel('common')->getDefaultValue('categories');
$defaultJobtype = JSJOBSincluder::getJSModel('common')->getDefaultValue('jobtypes');
$defaultJobstatus = JSJOBSincluder::getJSModel('common')->getDefaultValue('jobstatus');
$defaultShifts = JSJOBSincluder::getJSModel('common')->getDefaultValue('shifts');
$defaultEducation = JSJOBSincluder::getJSModel('common')->getDefaultValue('highesteducation');
$defaultSalaryrange = JSJOBSincluder::getJSModel('common')->getDefaultValue('salaryrange');
$defaultSalaryrangeType = JSJOBSincluder::getJSModel('common')->getDefaultValue('salaryrangetypes');
$defaultAge = JSJOBSincluder::getJSModel('common')->getDefaultValue('ages');
$defaultExperiences = JSJOBSincluder::getJSModel('common')->getDefaultValue('experiences');
$defaultCareerlevels = JSJOBSincluder::getJSModel('common')->getDefaultValue('careerlevels');
$defaultCurrencies = JSJOBSincluder::getJSModel('common')->getDefaultValue('currencies');
?>

<script type="text/javascript">


    jQuery(document).ready(function () {
        jQuery("div#full_background,img#popup_cross").click(function (e) {
            jQuery("div#popup_main").slideUp('slow', function () {
                jQuery("div#full_background").hide();
            });
        });
    });
    //End PoPuP
</script>

<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <span class="js-admin-title">
        <a href="<?php echo admin_url('admin.php?page=jsjobs_job'); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/back-icon.png" /></a>
        <?php
        $heading = isset(jsjobs::$_data[0]) ? __('Edit', 'js-jobs') : __('Add New', 'js-jobs');
        echo $heading . '&nbsp' . __('Job', 'js-jobs');
        ?>
    </span>
    <form id="job_form" class="jsjobs-form" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_job&task=savejob"); ?>">
        <?php if (isset(jsjobs::$_data[0]->msg) AND jsjobs::$_data[0]->msg != '') { ?>
            <span class="formMsg"><font color="red"><strong><?php echo __(jsjobs::$_data[0]->msg); ?></strong></font></span>
        <?php } ?>

        <?php
        $validation = '';

        function printFormField($title, $field) {
            $html = '<div class="js-field-wrapper js-row no-margin">
                           <div class="js-field-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding">' . $title . '</div>
                           <div class="js-field-obj js-col-lg-9 js-col-md-9 js-col-xs-12 no-padding">' . $field . '</div>
                       </div>';
            return $html;
        }

        $i = 0;
        foreach (jsjobs::$_data[2] AS $field) {
            if ($field->published) {
                if ($field->required == 1) {
                    $validation = 'required';
                }
                switch ($field->field) {
                    case 'jobtitle':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">&nbsp*</font>';
                        }
                        $field = JSJOBSformfield::text('title', isset(jsjobs::$_data[0]->title) ? jsjobs::$_data[0]->title : '', array('class' => 'inputbox one', 'data-validation' => $req));
                        echo printFormField($title, $field);
                        break;
                    case 'company':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">&nbsp*</font>';
                        }
                        $field = JSJOBSformfield::select('companyid', JSJOBSincluder::getJSModel('company')->getCompaniesForCombo(), isset(jsjobs::$_data[0]->companyid) ? jsjobs::$_data[0]->companyid : 0, __('Select','js-jobs') .'&nbsp;'. __('Company','js-jobs'), array('class' => 'inputbox one', 'onchange' => 'getdepartments(\'departmentid\', this.value);', 'data-validation' => $req));
                        echo printFormField($title, $field);
                        break;
                    case 'jobcategory':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">&nbsp*</font>';
                        }
                        $field = JSJOBSformfield::select('jobcategory', JSJOBSincluder::getJSModel('category')->getCategoryForCombobox(), isset(jsjobs::$_data[0]->jobcategory) ? jsjobs::$_data[0]->jobcategory : $defaultCategory, '', array('class' => 'inputbox one', 'data-validation' => $req, 'onchange' => 'getsubcategories(\'subcategory-field\', this.value);'));
                        echo printFormField($title, $field);
                        break;
                    case 'jobtype':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">&nbsp*</font>';
                        }
                        $field = JSJOBSformfield::select('jobtype', JSJOBSincluder::getJSModel('jobtype')->getJobTypeForCombo(), isset(jsjobs::$_data[0]->jobtype) ? jsjobs::$_data[0]->jobtype : $defaultJobtype, '', array('class' => 'inputbox one', 'data-validation' => $req));
                        echo printFormField($title, $field);
                        break;
                    case 'jobstatus':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">&nbsp*</font>';
                        }
                        $field = JSJOBSformfield::select('jobstatus', JSJOBSincluder::getJSModel('jobstatus')->getJobStatusForCombo(), isset(jsjobs::$_data[0]->jobstatus) ? jsjobs::$_data[0]->jobstatus : $defaultJobstatus, '', array('class' => 'inputbox one', 'data-validation' => $req));
                        echo printFormField($title, $field);
                        break;
                    case 'gender':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">&nbsp*</font>';
                        }
                        $field = JSJOBSformfield::select('gender', JSJOBSincluder::getJSModel('common')->getGender(''), isset(jsjobs::$_data[0]->gender) ? jsjobs::$_data[0]->gender : 0, '', array('class' => 'inputbox one', 'data-validation' => $req));
                        echo printFormField($title, $field);
                        break;
                    case 'age':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">&nbsp*</font>';
                        }
                        $field = JSJOBSformfield::select('agefrom', JSJOBSincluder::getJSModel('age')->getAgesForCombo(), isset(jsjobs::$_data[0]->agefrom) ? jsjobs::$_data[0]->agefrom : $defaultAge, __('From', 'js-jobs'), array('class' => 'inputbox two', 'data-validation' => $req));
                        $field .= JSJOBSformfield::select('ageto', JSJOBSincluder::getJSModel('age')->getAgesForCombo(), isset(jsjobs::$_data[0]->ageto) ? jsjobs::$_data[0]->ageto : $defaultAge, __('To', 'js-jobs'), array('class' => 'inputbox two', 'data-validation' => $req));
                        echo printFormField($title, $field);
                        break;
                    case 'jobsalaryrange':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">&nbsp*</font>';
                        }
                        $field = JSJOBSformfield::select('currencyid', JSJOBSincluder::getJSModel('currency')->getCurrencyForCombo(), isset(jsjobs::$_data[0]->currencyid) ? jsjobs::$_data[0]->currencyid : $defaultCurrencies, '', array('class' => 'inputbox two', 'data-validation' => $req));
                        $field .= JSJOBSformfield::select('salaryrangefrom', JSJOBSincluder::getJSModel('salaryrange')->getJobStartSalaryRangeForCombo(), isset(jsjobs::$_data[0]->salaryrangefrom) ? jsjobs::$_data[0]->salaryrangefrom : $defaultSalaryrange, '', array('class' => 'inputbox two', 'data-validation' => $req));
                        $field .= JSJOBSformfield::select('salaryrangeto', JSJOBSincluder::getJSModel('salaryrange')->getJobEndSalaryRangeForCombo(), isset(jsjobs::$_data[0]->salaryrangeto) ? jsjobs::$_data[0]->salaryrangeto : $defaultSalaryrange, '', array('class' => 'inputbox two', 'data-validation' => $req));
                        $field .= JSJOBSformfield::select('salaryrangetype', JSJOBSincluder::getJSModel('salaryrangetype')->getSalaryRangeTypesForCombo(), isset(jsjobs::$_data[0]->salaryrangetype) ? jsjobs::$_data[0]->salaryrangetype : $defaultSalaryrangeType, '', array('class' => 'inputbox two', 'data-validation' => $req));
                        echo printFormField($title, $field);
                        break;
                    case 'jobshift':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">&nbsp*</font>';
                        }
                        $field = JSJOBSformfield::select('shift', JSJOBSincluder::getJSModel('shift')->getShiftForCombo(), isset(jsjobs::$_data[0]->shift) ? jsjobs::$_data[0]->shift : $defaultShifts, '', array('class' => 'inputbox one', 'data-validation' => $req));
                        echo printFormField($title, $field);
                        break;
                    case 'noofjobs':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">&nbsp*</font>';
                        }
                        $field = JSJOBSformfield::text('noofjobs', isset(jsjobs::$_data[0]->noofjobs) ? jsjobs::$_data[0]->noofjobs : '', array('class' => 'inputbox one', 'data-validation' => $req));
                        echo printFormField($title, $field);
                        break;
                    case 'careerlevel':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">&nbsp*</font>';
                        }
                        $field = JSJOBSformfield::select('careerlevel', JSJOBSincluder::getJSModel('careerlevel')->getCareerLevelsForCombo(), isset(jsjobs::$_data[0]->careerlevel) ? jsjobs::$_data[0]->careerlevel : $defaultCareerlevels, '', array('class' => 'inputbox one', 'data-validation' => $req));
                        echo printFormField($title, $field);
                        break;
                    case 'workpermit':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">&nbsp*</font>';
                        }
                        $field = JSJOBSformfield::select('workpermit', JSJOBSincluder::getJSModel('country')->getCountriesForCombo(), isset(jsjobs::$_data[0]->workpermit) ? jsjobs::$_data[0]->workpermit : 0, __('Select','js-jobs').'&nbsp;'. __('work permit','js-jobs'), array('class' => 'inputbox one', 'data-validation' => $req));
                        echo printFormField($title, $field);
                        break;
                    case 'requiredtravel':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">&nbsp*</font>';
                        }
                        $field = JSJOBSformfield::select('requiredtravel', JSJOBSincluder::getJSModel('common')->getRequiredTravel(''), isset(jsjobs::$_data[0]->requiredtravel) ? jsjobs::$_data[0]->requiredtravel : 0, '', array('class' => 'inputbox one', 'data-validation' => $req));
                        echo printFormField($title, $field);
                        break;
                    case 'startpublishing':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">&nbsp*</font>';
                        }
                        $field = JSJOBSformfield::text('startpublishing', isset(jsjobs::$_data[0]->startpublishing) ? jsjobs::$_data[0]->startpublishing : '', array('class' => 'custom_date one', 'data-validation' => $req));
                        echo printFormField($title, $field);
                        break;
                    case 'stoppublishing':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">&nbsp*</font>';
                        }
                        $field = JSJOBSformfield::text('stoppublishing', isset(jsjobs::$_data[0]->stoppublishing) ? jsjobs::$_data[0]->stoppublishing : '', array('class' => 'custom_date one', 'data-validation' => $req));
                        echo printFormField($title, $field);
                        break;
                    case 'metadescription':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">&nbsp*</font>';
                        }
                        $field = JSJOBSformfield::textarea('metadescription', isset(jsjobs::$_data[0]->metadescription) ? jsjobs::$_data[0]->metadescription : '', array('class' => 'inputbox one', 'rows' => '7', 'cols' => '94', 'data-validation' => $req));
                        echo printFormField($title,$field);
                        break;
                    case 'metakeywords':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">&nbsp*</font>';
                        }
                        $field = JSJOBSformfield::textarea('metakeywords', isset(jsjobs::$_data[0]->metakeywords) ? jsjobs::$_data[0]->metakeywords : '', array('class' => 'inputbox one', 'rows' => '7', 'cols' => '94', 'data-validation' => $req));
                        echo printFormField($title, $field);
                        break;
                    case 'department':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">&nbsp*</font>';
                        }
                        $uid = isset(jsjobs::$_data[0]->uid) ? jsjobs::$_data[0]->uid : 0;
                        $field = JSJOBSformfield::select('departmentid', JSJOBSincluder::getJSModel('departments')->getDepartmentForCombo($uid), isset(jsjobs::$_data[0]->departmentid) ? jsjobs::$_data[0]->departmentid : __('Select','js-jobs') .'&nbsp;'. __('Department', 'js-jobs'), '', array('class' => 'inputbox one', 'data-validation' => $req));
                        echo printFormField($title, $field);

                        break;
                    case 'city':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">&nbsp*</font>';
                        }
                        $field = JSJOBSformfield::text('city', '', array('class' => 'inputbox one', 'data-validation' => $req));
                        $field .= JSJOBSformfield::hidden('cityforedit', isset(jsjobs::$_data[0]->multicity) ? jsjobs::$_data[0]->multicity : '', array('class' => 'inputbox one'));
                        echo printFormField($title, $field);
                        break;
                    case 'duration':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">&nbsp*</font>';
                        }
                        $field = JSJOBSformfield::text('duration', isset(jsjobs::$_data[0]->duration) ? jsjobs::$_data[0]->duration : '', array('class' => 'inputbox one', 'data-validation' => $req)) . __('IE 18 Months Or 3 Years', 'js-jobs');
                        echo printFormField($title, $field);
                        break;
                    case 'zipcode':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">&nbsp*</font>';
                        }
                        $field = JSJOBSformfield::text('zipcode', isset(jsjobs::$_data[0]->zipcode) ? jsjobs::$_data[0]->zipcode : '', array('class' => 'inputbox one', 'data-validation' => $req));
                        echo printFormField($title, $field);
                        break;
                    case 'joblink':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">&nbsp*</font>';
                        }
                        $field = '<div id="chk-for-joblink" class="js-field-obj chck-box-fields js-col-lg-6 js-col-md-6 js-col-xs-12 no-padding">' . JSJOBSformfield::checkbox('jobapplylink', array('1' => __('Set Job Apply Redirect Link', 'js-jobs')), (isset(jsjobs::$_data[0]->jobapplylink) && jsjobs::$_data[0]->jobapplylink == 1) ? '1' : '0') . '</div>';
                        echo printFormField($title, $field);
                        
                        $joblink_field = JSJOBSformfield::text('joblink', isset(jsjobs::$_data[0]->joblink) ? jsjobs::$_data[0]->joblink : '', array('class' => 'inputbox one input-text-joblink', 'data-validation' => $req));
                        $linkfield = printFormField(__('Redirect link','js-jobs'), $joblink_field);
                        echo '<div id="input-text-joblink">' . $linkfield . '</div>';

                        break;
                    case 'heighesteducation':
                        $req = '';
                        if ($field->required == 1) {
                            $req = 'required';
                        }
                        $lists['educationminimax'] = JSJOBSformfield::select('educationminimax', JSJOBSincluder::getJSModel('common')->getMiniMax(''), isset(jsjobs::$_data[0]->educationminimax) ? jsjobs::$_data[0]->educationminimax : 1, '', array('class' => 'inputbox two', 'data-validation' => $req));
                        $lists['educationid'] = JSJOBSformfield::select('educationid', JSJOBSincluder::getJSModel('highesteducation')->getHighestEducationForCombo(), isset(jsjobs::$_data[0]->educationid) ? jsjobs::$_data[0]->educationid : $defaultEducation, '', array('class' => 'inputbox two', 'data-validation' => $req));
                        $lists['mineducationrange'] = JSJOBSformfield::select('mineducationrange', JSJOBSincluder::getJSModel('highesteducation')->getHighestEducationForCombo(), isset(jsjobs::$_data[0]->mineducationrange) ? jsjobs::$_data[0]->mineducationrange : 0, __('Minimum', 'js-jobs'), array('class' => 'inputbox two', 'data-validation' => $req));
                        $lists['maxeducationrange'] = JSJOBSformfield::select('maxeducationrange', JSJOBSincluder::getJSModel('highesteducation')->getHighestEducationForCombo(), isset(jsjobs::$_data[0]->maxeducationrange) ? jsjobs::$_data[0]->maxeducationrange : 0, __('Maximum', 'js-jobs'), array('class' => 'inputbox two', 'data-validation' => $req));
                        if (isset(jsjobs::$_data[0]->id))
                            $iseducationminimax = jsjobs::$_data[0]->iseducationminimax;
                        else
                            $iseducationminimax = 1;
                        if ($iseducationminimax == 1) {
                            $minimaxEdu = "display:block;";
                            $rangeEdu = "display:none;";
                        } else {
                            $minimaxEdu = "display:none;";
                            $rangeEdu = "display:block;";
                        }
                        echo JSJOBSformfield::hidden('iseducationminimax', $iseducationminimax);
                        ?>
                        <div class="js-field-wrapper js-row no-margin">
                            <div class="js-field-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding"><?php echo __($field->fieldtitle, 'js-jobs'); ?><?php if ($req != '') { ?><font class="required-notifier">*</font><?php } ?></div>
                            <div id="defaultEdu" class="js-field-obj js-col-lg-9 js-col-xs-12 js-col-md-5 no-padding" style="<?php echo $minimaxEdu; ?>"><?php echo $lists['educationminimax']; ?><?php echo $lists['educationid']; ?></div>
                            <div id="eduRanges" class="js-field-obj js-col-lg-9 js-col-xs-12 js-col-md-5 no-padding" style="<?php echo $rangeEdu; ?>"><?php echo $lists['mineducationrange']; ?><?php echo $lists['maxeducationrange']; ?></div>
                            <div id="defaultEduShow" class="js-field-obj js-col-lg-2 js-col-xs-12 js-col-md-2 no-padding" style="<?php echo $minimaxEdu; ?>"><a class="show-hide-link" onclick="hideShowRange('defaultEdu', 'eduRanges', 'defaultEduShow', 'hideEduRanges', 'iseducationminimax', 0);"><?php echo __('Specify range', 'js-jobs'); ?></a></div>
                            <div id="hideEduRanges" class="js-field-obj js-col-lg-2 js-col-xs-12 js-col-md-2 no-padding" style="<?php echo $rangeEdu; ?>"><a class="show-hide-link" onclick="hideShowRange('eduRanges', 'defaultEdu', 'defaultEduShow', 'hideEduRanges', 'iseducationminimax', 1);"><?php echo __('Cancel range', 'js-jobs'); ?></a></div>
                        </div>
                        <div class="js-field-wrapper js-row no-margin">
                            <div class="js-field-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding"><?php echo __('Degree title', 'js-jobs'); ?><?php if ($req != '') { ?><font class="required-notifier">*</font><?php } ?></div>
                            <div class="js-field-obj js-col-lg-9 js-col-md-9 js-col-xs-12 no-padding"><?php echo JSJOBSformfield::text('degreetitle', isset(jsjobs::$_data[0]->degreetitle) ? jsjobs::$_data[0]->degreetitle : '', array('class' => 'inputbox one', 'data-validation' => $req)); ?></div>
                        </div>
                        <?php
                        break;
                    case 'experience':
                        $req = '';
                        if ($field->required == 1) {
                            $req = 'required';
                        }
                        $lists['experienceminimax'] = JSJOBSformfield::select('experienceminimax', JSJOBSincluder::getJSModel('common')->getMiniMax(''), isset(jsjobs::$_data[0]->experienceminimax) ? jsjobs::$_data[0]->experienceminimax : 0, '', array('class' => 'inputbox two', 'data-validation' => $req));
                        $lists['experienceid'] = JSJOBSformfield::select('experienceid', JSJOBSincluder::getJSModel('experience')->getExperiencesForCombo(), isset(jsjobs::$_data[0]->experienceid) ? jsjobs::$_data[0]->experienceid : $defaultExperiences, '', array('class' => 'inputbox two', 'data-validation' => $req));
                        $lists['minexperiencerange'] = JSJOBSformfield::select('minexperiencerange', JSJOBSincluder::getJSModel('experience')->getExperiencesForCombo(), isset(jsjobs::$_data[0]->minexperiencerange) ? jsjobs::$_data[0]->minexperiencerange : 0, __('Minimum', 'js-jobs'), array('class' => 'inputbox two', 'data-validation' => $req));
                        $lists['maxexperiencerange'] = JSJOBSformfield::select('maxexperiencerange', JSJOBSincluder::getJSModel('experience')->getExperiencesForCombo(), isset(jsjobs::$_data[0]->maxexperiencerange) ? jsjobs::$_data[0]->maxexperiencerange : 0, __('Maximum', 'js-jobs'), array('class' => 'inputbox two', 'data-validation' => $req));
                        ?>
                        <?php
                        if (isset(jsjobs::$_data[0]->id))
                            $isexperienceminimax = jsjobs::$_data[0]->isexperienceminimax;
                        else
                            $isexperienceminimax = 1;
                        if ($isexperienceminimax == 1) {
                            $minimaxExp = "display:block;";
                            $rangeExp = "display:none;";
                        } else {
                            $minimaxExp = "display:none;";
                            $rangeExp = "display:block;";
                        }
                        echo JSJOBSformfield::hidden('isexperienceminimax', $isexperienceminimax);
                        ?>
                        <div class="js-field-wrapper js-row no-margin">
                            <div class="js-field-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding"><?php echo __($field->fieldtitle, 'js-jobs'); ?><?php if ($req != '') { ?><font class="required-notifier">*</font><?php } ?></div>
                            <div id="defaultExp" class="js-field-obj js-col-lg-9 js-col-md-9 js-col-xs-12 no-padding" style="<?php echo $minimaxExp; ?>"><?php echo $lists['experienceminimax']; ?><?php echo $lists['experienceid']; ?></div>
                            <div id="expRanges" class="js-field-obj js-col-lg-9 js-col-md-9 js-col-xs-12 no-padding" style="<?php echo $rangeExp; ?>"><?php echo $lists['minexperiencerange']; ?><?php echo $lists['maxexperiencerange']; ?></div>
                            <div id="defaultExpShow" class="js-field-obj js-col-lg-2 js-col-md-2 js-col-xs-12 no-padding" style="<?php echo $minimaxExp; ?>"><a class="show-hide-link" onclick="hideShowRange('defaultExp', 'expRanges', 'defaultExpShow', 'hideExpRanges', 'isexperienceminimax', 0);"><?php echo __('Specify range', 'js-jobs'); ?></a></div>
                            <div id="hideExpRanges" class="js-field-obj js-col-lg-2 js-col-md-2 js-col-xs-12 no-padding" style="<?php echo $rangeExp; ?>"><a class="show-hide-link" onclick="hideShowRange('expRanges', 'defaultExp', 'defaultExpShow', 'hideExpRanges', 'isexperienceminimax', 1);"><?php echo __('Cancel range', 'js-jobs'); ?></a></div>
                            <div class="js-field-obj js-col-lg-9 js-col-md-9 js-col-lg-offset-3 js-col-xs-12 js-col-md-offset-3 no-padding"><?php echo JSJOBSformfield::text('experiencetext', isset(jsjobs::$_data[0]->experiencetext) ? jsjobs::$_data[0]->experiencetext : '', array('class' => 'inputbox one', 'data-validation' => $req)) . __('If Any Other Experience', 'js-jobs'); ?></div>
                        </div>
                        <?php
                        break;
                    case 'map':
                        $req = '';
                        if ($field->required == 1) {
                            $req = 'required';
                        }
                        ?>
                        <div class="js-field-wrapper js-row no-margin">
                            <div class="js-field-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding"><?php echo __($field->fieldtitle, 'js-jobs'); ?><?php if ($req != '') { ?><font class="required-notifier">*</font><?php } ?></div>
                            <div class="js-field-obj js-col-lg-5 js-col-md-5 js-col-xs-12 no-padding"> <div id="map_container"></div> </div>
                        </div>
                        <div class="js-field-wrapper js-row no-margin">
                            <div class="js-field-obj js-col-lg-6 js-col-md-6 js-col-md-offset-3 js-col-lg-offset-3 no-padding"><?php echo JSJOBSformfield::text('longitude', isset(jsjobs::$_data[0]->longitude) ? jsjobs::$_data[0]->longitude : '', array('class' => 'inputbox one', 'data-validation' => $req)) . __('Longitude', 'js-jobs'); ?></div>
                        </div>
                        <div class="js-field-wrapper js-row no-margin">
                            <div class="js-field-obj js-col-lg-6 js-col-md-6 js-col-md-offset-3 js-col-lg-offset-3 no-padding"><?php echo JSJOBSformfield::text('latitude', isset(jsjobs::$_data[0]->latitude) ? jsjobs::$_data[0]->latitude : '', array('class' => 'inputbox one', 'data-validation' => $req)) . __('Latitude', 'js-jobs'); ?></div>
                            <div class="js-field-obj js-col-lg-2 js-col-md-2 js-col-xs-12 no-padding"><span class="button map-marker-selector"  onclick="Javascript: loadMap(3);"><?php echo __('Set Marker From Address', 'js-jobs'); ?></span></div>
                        </div>
                        <?php
                        break;
                    case 'description':
                        $req = '';
                        if ($field->required == 1) {
                            $req = 'required';
                        }
                        ?>
                        <div class="js-field-wrapper js-row no-margin">
                            <div class="js-field-title form-editor-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding"><?php echo __($field->fieldtitle, 'js-jobs'); ?><?php if ($req != '') { ?><font class="required-notifier">*</font><?php } ?></div>
                            <div class="js-field-obj js-col-lg-8 js-col-md-8 js-col-xs-12 no-padding"><?php echo wp_editor(isset(jsjobs::$_data[0]->description) ? jsjobs::$_data[0]->description : '', 'description', array('media_buttons' => false, 'data-validation' => $req)); ?></div>
                        </div>
                        <?php
                        break;
                    case 'qualifications':
                        $req = '';
                        if ($field->required == 1) {
                            $req = 'required';
                        }
                        ?>
                        <div class="js-field-wrapper js-row no-margin">
                            <div class="js-field-title form-editor-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding"><?php echo __($field->fieldtitle, 'js-jobs'); ?><?php if ($req != '') { ?><font class="required-notifier">*</font><?php } ?></div>
                            <div class="js-field-obj js-col-lg-8 js-col-md-8 js-col-xs-12 no-padding"><?php echo wp_editor(isset(jsjobs::$_data[0]->qualifications) ? jsjobs::$_data[0]->qualifications : '', 'qualifications', array('media_buttons' => false, 'data-validation' => $req)); ?></div>
                        </div>
                        <?php
                        break;
                    case 'prefferdskills':
                        $req = '';
                        if ($field->required == 1) {
                            $req = 'required';
                        }
                        ?>
                        <div class="js-field-wrapper js-row no-margin">
                            <div class="js-field-title form-editor-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding"><?php echo __($field->fieldtitle, 'js-jobs'); ?><?php if ($req != '') { ?><font class="required-notifier">*</font><?php } ?></div>
                            <div class="js-field-obj js-col-lg-8 js-col-md-8 js-col-xs-12 no-padding"><?php echo wp_editor(isset(jsjobs::$_data[0]->prefferdskills) ? jsjobs::$_data[0]->prefferdskills : '', 'prefferdskills', array('media_buttons' => false, 'data-validation' => $req)); ?></div>
                        </div>
                        <?php
                        break;
                    case 'agreement':
                        $req = '';
                        if ($field->required == 1) {
                            $req = 'required';
                        }
                        ?>
                        <div class="js-field-wrapper js-row no-margin">
                            <div class="js-field-title form-editor-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding"><?php echo __($field->fieldtitle, 'js-jobs'); ?><?php if ($req != '') { ?><font class="required-notifier">*</font><?php } ?></div>
                            <div class="js-field-obj js-col-lg-8 js-col-md-8 js-col-xs-12 no-padding"><?php echo wp_editor(isset(jsjobs::$_data[0]->agreement) ? jsjobs::$_data[0]->agreement : '', 'agreement', array('media_buttons' => false, 'data-validation' => $req)); ?></div>
                        </div>
                        <?php
                        break;
                    default:
                        JSJOBSincluder::getObjectClass('customfields')->formCustomFields($field);
                        break;
                }
            }
            $validation = "";
        }
        ?>
        <?php echo JSJOBSformfield::hidden('isqueue', isset($_GET['isqueue']) ? 1 : 0); ?>
        <?php echo JSJOBSformfield::hidden('id', isset(jsjobs::$_data[0]->id) ? jsjobs::$_data[0]->id : ''); ?>
        <?php echo JSJOBSformfield::hidden('default_longitude', jsjobs::$_configuration['default_longitude']); ?>
        <?php echo JSJOBSformfield::hidden('default_latitude', jsjobs::$_configuration['default_latitude']); ?>
        <?php echo JSJOBSformfield::hidden('action', 'job_savejob'); ?>
        <?php echo JSJOBSformfield::hidden('form_request', 'jsjobs'); ?>
        <?php echo JSJOBSformfield::hidden('isadmin', '1'); ?>
        <?php echo JSJOBSformfield::hidden('payment', ''); ?>
        <?php echo JSJOBSformfield::hidden('creditid', ''); ?>
        <?php 
            $status = array((object) array('id' => 0, 'text' => __('Pending', "js-jobs")), (object) array('id' => 1, 'text' => __('Approved', "js-jobs")), (object) array('id' => -1, 'text' => __('Rejected', "js-jobs")));
            $title = __('Status', 'js-jobs');
            $field = JSJOBSformfield::select('status', $status, isset(jsjobs::$_data[0]->status) ? jsjobs::$_data[0]->status : 1, __('Select Status', 'js-jobs'), array('class' => 'inputbox one'));
            echo printFormField($title, $field);
        ?>
        <div class="js-submit-container js-col-lg-8 js-col-md-8 js-col-xs-12 js-col-md-offset-2 js-col-md-offset-2">
            <a id="form-cancel-button" href="<?php echo admin_url('admin.php?page=jsjobs_job'); ?>" ><?php echo __('Cancel', 'js-jobs'); ?></a>
            <?php
                echo JSJOBSformfield::submitbutton('save', __('Save','js-jobs') .' '. __('Job', 'js-jobs'), array('class' => 'button'));
            ?>
        </div>
    </form>
<?php 
$mapfield = null;
foreach(jsjobs::$_data[2] AS $key => $value){
    $value = (array) $value;
    if(in_array('map', $value)){
        $mapfield = $key;
        break;
    }
}
if($mapfield):
    $mapfield = jsjobs::$_data[2][$mapfield];
    if($mapfield->published == 1){ ?>
        <style type="text/css">
            div#map_container{border:2px solid #fff;}
        </style>
        <?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; ?>
        <script type="text/javascript" src="<?php echo $protocol; ?>maps.googleapis.com/maps/api/js?key=<?php echo jsjobs::$_configuration['google_map_api_key']; ?>"></script>
        <script type="text/javascript">
            var map = null;
            function addMarker(latlang){
                var marker = new google.maps.Marker({
                    position: latlang,
                    map: map,
                    draggable: true,
                });
                marker.setMap(map);
                marker.addListener("dblclick", function() {
                    var latitude = document.getElementById('latitude').value;
                    latitude = latitude.replace(','+marker.position.lat(), "");
                    latitude = latitude.replace(marker.position.lat()+',', "");
                    latitude = latitude.replace(marker.position.lat(), "");
                    document.getElementById('latitude').value = latitude;
                    var longitude = document.getElementById('longitude').value;
                    longitude = longitude.replace(','+marker.position.lng(), "");
                    longitude = longitude.replace(marker.position.lng()+',', "");
                    longitude = longitude.replace(marker.position.lng(), "");
                    document.getElementById('longitude').value = longitude;
                    marker.setMap(null);
                });
                if(document.getElementById('latitude').value == ''){
                    document.getElementById('latitude').value = marker.position.lat();
                }else{
                    document.getElementById('latitude').value += ',' + marker.position.lat();
                }
                if(document.getElementById('longitude').value == ''){
                    document.getElementById('longitude').value = marker.position.lng();
                }else{
                    document.getElementById('longitude').value += ',' + marker.position.lng();
                }
            }
            function addMarkerOnMap(location){
                var geocoder =  new google.maps.Geocoder();
                geocoder.geocode( { 'address': location}, function(results, status) {
                    var latlng = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
                    if (status == google.maps.GeocoderStatus.OK) {
                        if(map != null){
                            addMarker(latlng);
                        }
                    } else {
                        //alert("<?php echo __('Something got wrong','js-jobs');?>:"+status);
                    }
                });    
            }
            function loadMap(callfrom) {
                var values_longitude = [];
                var values_latitude = [];
                var latedit = [];
                var longedit = [];
                var longitude = '';
                var latitude = '';
                var long_obj = document.getElementById('longitude');
                if (typeof long_obj !== 'undefined' && long_obj !== null) {
                    longitude = document.getElementById('longitude').value;
                    if (longitude != '') longedit = longitude.split(",");
                }
                var lat_obj = document.getElementById('latitude');
                if (typeof long_obj !== 'undefined' && long_obj !== null) {
                    latitude = document.getElementById('latitude').value;
                    if (latitude != '') latedit = latitude.split(",");
                }
                var default_latitude = document.getElementById('default_latitude').value;
                var default_longitude = document.getElementById('default_longitude').value;
                if (latedit != '' && longedit != ''){
                    for (var i = 0; i < latedit.length; i++) {
                        var latlng = new google.maps.LatLng(latedit[i], longedit[i]); zoom = 4;
                        var myOptions = {
                            zoom: zoom,
                            center: latlng,
                            scrollwheel: false,
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        };
                        if (i == 0) map = new google.maps.Map(document.getElementById("map_container"), myOptions);
                        if (callfrom == 1){
                            var marker = new google.maps.Marker({
                                            position: latlng,
                                            map: map,
                                            visible: true,
                                        });
                            document.getElementById('longitude').value = marker.position.lng();
                            document.getElementById('latitude').value = marker.position.lat();
                            marker.setMap(map);
                            values_longitude.push(longedit[i]);
                            values_latitude.push(latedit[i]);
                            document.getElementById('latitude').value = values_latitude;
                            document.getElementById('longitude').value = values_longitude;
                        }
                    }
                } else {
                    var latlng = new google.maps.LatLng(default_latitude, default_longitude); zoom = 4;
                    var myOptions = {
                            zoom: zoom,
                            center: latlng,
                            scrollwheel: false,
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                    };
                    map = new google.maps.Map(document.getElementById("map_container"), myOptions);
                    /*
                    var lastmarker = new google.maps.Marker({
                            postiion:latlng,
                            map:map,
                    });
                    if (callfrom == 1){
                        var marker = new google.maps.Marker({
                                            position: latlng,
                                            map: map,
                                        });
                        document.getElementById('longitude').value = marker.position.lng();
                        document.getElementById('latitude').value = marker.position.lat();
                        //marker.setMap(map);
                        values_longitude.push(document.getElementById('longitude').value);
                        values_latitude.push(document.getElementById('latitude').value);
                        //lastmarker = marker;
                    }
                    */
                }
                google.maps.event.addListener(map, "click", function(e){
                    var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
                    geocoder = new google.maps.Geocoder();
                    geocoder.geocode({ 'latLng': latLng}, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            var marker = new google.maps.Marker({
                                                position: results[0].geometry.location,
                                                map: map,
                                            });
                            //marker.setMap(map);
                            document.getElementById('latitude').value = marker.position.lat();
                            document.getElementById('longitude').value = marker.position.lng();
                            values_longitude.push(document.getElementById('longitude').value);
                            values_latitude.push(document.getElementById('latitude').value);
                            document.getElementById('latitude').value = values_latitude;
                            document.getElementById('longitude').value = values_longitude;
                        } else {
                            alert("<?php echo __('Geocode was not successful for the following reason', 'js-jobs'); ?>: " + status);
                        }
                    });
                });
                if (callfrom == 3){
                    var value = '';
                    var zoom = 4;
                    jQuery("div#jobcities > ul > li > p").each(function(){
                        value = jQuery(this).html();
                        if (value != ''){
                            geocoder = new google.maps.Geocoder();
                            geocoder.geocode({ 'address': value}, function(results, status) {
                                if (status == google.maps.GeocoderStatus.OK) {
                                    map.setCenter(results[0].geometry.location);
                                    document.getElementById('latitude').value = results[0].geometry.location.lat();
                                    document.getElementById('longitude').value = results[0].geometry.location.lng();
                                    map.setZoom(zoom);
                                    //lastmarker.setMap(null);
                                    var marker = new google.maps.Marker({
                                        position: results[0].geometry.location,
                                        map: map,
                                    });
                                    //marker.setMap(map);
                                    values_longitude.push(document.getElementById('longitude').value);
                                    values_latitude.push(document.getElementById('latitude').value);
                                    document.getElementById('latitude').value = values_latitude;
                                    document.getElementById('longitude').value = values_longitude;
                                    //lastmarker = marker;
                                } else {
                                    alert("<?php echo __('Geocode was not successful for the following reason', 'js-jobs'); ?>: " + status);
                                }
                            });
                        }
                    });
                }
            }        
        </script>
    <?php } ?>    
<?php endif; ?>
    <script type="text/javascript">
            jQuery(document).ready(function ($) {
                /*job apply link start*/
                if (jQuery("input#jobapplylink1").is(":checked")){
                jQuery("div#input-text-joblink").show();
                }
                jQuery("input#jobapplylink1").click(function(){
                if (jQuery(this).is(":checked")){
                jQuery("div#input-text-joblink").show();
                } else{
                jQuery("div#input-text-joblink").hide();
                }
                });
                        /*job apply link end*/
                        $('.custom_date').datepicker({dateFormat: '<?php echo $js_scriptdateformat; ?>'});
                        $.validate();
                        //Token Input
                        var multicities = <?php echo isset(jsjobs::$_data[0]->multicity) ? jsjobs::$_data[0]->multicity : "''" ?>;
                        getTokenInput(multicities);
                        var map_obj = document.getElementById('map_container');
                        if (typeof map_obj !== 'undefined' && map_obj !== null) {
                window.onload = loadMap(1);
                }


                });

                function getdepartments(src, val){
                    
                    var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
                    jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'departments', task: 'listdepartments', val: val}, function(data){
                        if (data){
                            jQuery("#" + src).html(data); //retuen value
                        }
                    });
                
                }

                function hideShowRange(hideSrc, showSrc, showLink, hideLink, showName, showVal){
                jQuery("#" + hideSrc).toggle();
                        jQuery("#" + showSrc).toggle();
                        jQuery("#" + showLink).toggle();
                        jQuery("#" + hideLink).toggle();
                }


                function getTokenInput(multicities) {
                var cityArray = '<?php echo admin_url("admin.php?page=jsjobs_city&action=jsjobtask&task=getaddressdatabycityname"); ?>';
                        var city = jQuery("#cityforedit").val();
                        if (city != "") {
                jQuery("#city").tokenInput(cityArray, {
                theme: "jsjobs",
                        preventDuplicates: true,
                        hintText: "<?php echo __('Type In A Search Term', 'js-jobs'); ?>",
                        noResultsText: "<?php echo __('No Results', 'js-jobs'); ?>",
                        searchingText: "<?php echo __('Searching', 'js-jobs'); ?>",
                        // tokenLimit: 1,
                        prePopulate: multicities,
<?php $newtyped_cities = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('newtyped_cities');
 if ($newtyped_cities == 1) { ?>

                        onResult: function(item) {
                        if (jQuery.isEmptyObject(item)){
                        return [{id:0, name: jQuery("tester").text()}];
                        } else {
                        //add the item at the top of the dropdown
                        item.unshift({id:0, name: jQuery("tester").text()});
                                return item;
                        }
                        },
                        onAdd: function(item) {
                        if (item.id > 0){
<?php
if($mapfield):
    if($mapfield->published == 1){ ?>
                            addMarkerOnMap(item.name);
<?php
    }
    endif; ?>
                            return; 
                        }
                        if (item.name.search(",") == - 1) {
                        var input = jQuery("tester").text();
                                alert ("<?php echo __('Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name', 'js-jobs'); ?>");
                                jQuery("#city").tokenInput("remove", item);
                                return false;
                        } else{
                        var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
                                jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'city', task: 'savetokeninputcity', citydata: jQuery("tester").text()}, function(data){
                                if (data){
                                try {
                                var value = jQuery.parseJSON(data);
                                        jQuery('#city').tokenInput('remove', item);
                                        jQuery('#city').tokenInput('add', {id: value.id, name: value.name});
                                }
                                catch (err) {
                                jQuery("#city").tokenInput("remove", item);
                                        alert(data);
                                }
                                }
                                });
                        }
                        }
                        <?php } ?>
                });
                } else {
                jQuery("#city").tokenInput(cityArray, {
                theme: "jsjobs",
                        preventDuplicates: true,
                        hintText: "<?php echo __('Type In A Search Term', 'js-jobs'); ?>",
                        noResultsText: "<?php echo __('No Results', 'js-jobs'); ?>",
                        searchingText: "<?php echo __('Searching', 'js-jobs'); ?>",
                        // tokenLimit: 1,
<?php $newtyped_cities = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('newtyped_cities');
 if ($newtyped_cities == 1) { ?>

                        onResult: function(item) {
                        if (jQuery.isEmptyObject(item)){
                        return [{id:0, name: jQuery("tester").text()}];
                        } else {
                        //add the item at the top of the dropdown
                        item.unshift({id:0, name: jQuery("tester").text()});
                                return item;
                        }
                        },
                        onAdd: function(item) {
                        if (item.id > 0){
<?php 
if($mapfield):
    if($mapfield->published == 1){ ?>
                            addMarkerOnMap(item.name);
<?php 
    }
    endif; ?>
                            return; 
                        }
                        if (item.name.search(",") == - 1) {
                        var input = jQuery("tester").text();
                                alert ("<?php echo __('Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name', 'js-jobs'); ?>");
                                jQuery("#city").tokenInput("remove", item);
                                return false;
                        } else{
                        var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
                                jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'city', task: 'savetokeninputcity', citydata: jQuery("tester").text()}, function(data){
                                if (data){
                                try {
                                var value = jQuery.parseJSON(data);
                                        jQuery('#city').tokenInput('remove', item);
                                        jQuery('#city').tokenInput('add', {id: value.id, name: value.name});
                                }
                                catch (err) {
                                jQuery("#city").tokenInput("remove", item);
                                        alert(data);
                                }
                                }
                                });
                        }
                        }
                        <?php } ?>
                });
                }
                }
    </script>
</div>
</div>

