<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
$msgkey = JSJOBSincluder::getJSModel('jobsearch')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(jsjobs::$_path . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    $radiustype = array(
        (object) array('id' => '0', 'text' => __('Select One', 'js-jobs')),
        (object) array('id' => '1', 'text' => __('Meters', 'js-jobs')),
        (object) array('id' => '2', 'text' => __('Kilometers', 'js-jobs')),
        (object) array('id' => '3', 'text' => __('Miles', 'js-jobs')),
        (object) array('id' => '4', 'text' => __('Nautical Miles', 'js-jobs')),
    );
    ?>
    <div id="jsjobs-wrapper">
        <div class="page_heading"><?php echo __('Search Job', 'js-jobs'); ?></div>
        <form class="job_form" id="job_form" method="post" action="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'jobs', 'jsjobspageid'=>jsjobs::getPageid())); ?>">
            <?php

            function getRow($title, $value) {
                $html = '<div class="js-col-md-12 js-form-wrapper">
                            <div class="js-col-md-12 js-form-title">' . $title . '</div>
                            <div class="js-col-md-12 js-form-value">' . $value . '</div>
                        </div>';
                return $html;
            }

            foreach (jsjobs::$_data[2] AS $field) {
                switch ($field->field) {
                    case 'metakeywords':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::text('metakeywords', isset(jsjobs::$_data[0]['filter']->metakeywords) ? jsjobs::$_data[0]['filter']->metakeywords : '', array('class' => 'inputbox'));
                        echo getRow($title, $value);
                        break;
                    case 'jobtitle':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::text('jobtitle', isset(jsjobs::$_data[0]['filter']->jobtitle) ? jsjobs::$_data[0]['filter']->jobtitle : '', array('class' => 'inputbox'));
                        echo getRow($title, $value);
                        break;
                    case 'company':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('company[]', JSJOBSincluder::getJSModel('company')->getCompaniesForCombo(), isset(jsjobs::$_data[0]['filter']->company) ? jsjobs::$_data[0]['filter']->company : '', __('Select','js-jobs') .'&nbsp;'. __('Company', 'js-jobs'), array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                        echo getRow($title, $value);
                        break;
                    case 'jobcategory':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('category[]', JSJOBSincluder::getJSModel('category')->getCategoriesForCombo(), isset(jsjobs::$_data[0]['filter']->category) ? jsjobs::$_data[0]['filter']->category : '', __('Select','js-jobs') .'&nbsp;'. __('Category', 'js-jobs'), array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                        echo getRow($title, $value);
                        break;
                    case 'careerlevel':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('careerlevel[]', JSJOBSincluder::getJSModel('careerlevel')->getCareerLevelsForCombo(), isset(jsjobs::$_data[0]['filter']->careerlevel) ? jsjobs::$_data[0]['filter']->careerlevel : '', __('Select','js-jobs') .'&nbsp;'. __('Career Level', 'js-jobs'), array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                        echo getRow($title, $value);
                        break;
                    case 'age':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('age[]', JSJOBSincluder::getJSModel('age')->getAgesForCombo(), isset(jsjobs::$_data[0]['filter']->age) ? jsjobs::$_data[0]['filter']->age : '', __('Select','js-jobs') .'&nbsp;'. __('Age', 'js-jobs'), array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                        echo getRow($title, $value);
                        break;
                    case 'jobshift':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('shift[]', JSJOBSincluder::getJSModel('shift')->getShiftForCombo(), isset(jsjobs::$_data[0]['filter']->shift) ? jsjobs::$_data[0]['filter']->shift : '', __('Select','js-jobs') .'&nbsp;'. __('Shift', 'js-jobs'), array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                        echo getRow($title, $value);
                        break;
                    case 'gender':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('gender', JSJOBSincluder::getJSModel('common')->getGender(), isset(jsjobs::$_data[0]['filter']->gender) ? jsjobs::$_data[0]['filter']->gender : '', __('Select','js-jobs') .'&nbsp;'. __('Gender', 'js-jobs'), array('class' => 'inputbox'));
                        echo getRow($title, $value);
                        break;
                    case 'jobtype':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('jobtype[]', JSJOBSincluder::getJSModel('jobtype')->getJobTypeForCombo(), isset(jsjobs::$_data[0]['filter']->jobtype) ? jsjobs::$_data[0]['filter']->jobtype : '', __('Select','js-jobs') .'&nbsp;'. __('Job Type', 'js-jobs'), array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                        echo getRow($title, $value);
                        break;
                    case 'jobstatus':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('jobstatus[]', JSJOBSincluder::getJSModel('jobstatus')->getJobStatusForCombo(), isset(jsjobs::$_data[0]['filter']->jobstatus) ? jsjobs::$_data[0]['filter']->jobstatus : '', __('Select','js-jobs') .'&nbsp;'. __('Job Status', 'js-jobs'), array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                        echo getRow($title, $value);
                        break;
                    case 'workpermit':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('workpermit[]', JSJOBSincluder::getJSModel('country')->getCountriesForCombo(), isset(jsjobs::$_data[0]['filter']->workpermit) ? jsjobs::$_data[0]['filter']->workpermit : '', __('Select','js-jobs') .'&nbsp;'. __('Work Permit', 'js-jobs'), array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                        echo getRow($title, $value);
                        break;
                    case 'jobsalaryrange':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('currencyid', JSJOBSincluder::getJSModel('currency')->getCurrencyForCombo(), isset(jsjobs::$_data[0]['filter']->currencyid) ? jsjobs::$_data[0]['filter']->currencyid : '', __('Select','js-jobs') .'&nbsp;'. __('Currency', 'js-jobs'), array('class' => 'inputbox sal'));
                        $value .= JSJOBSformfield::select('salaryrangestart', JSJOBSincluder::getJSModel('salaryrange')->getJobStartSalaryRangeForCombo(), isset(jsjobs::$_data[0]['filter']->salaryrange) ? jsjobs::$_data[0]['filter']->salaryrange : '', __('Select','js-jobs') .'&nbsp;'. __('Salary Range','js-jobs') .'&nbsp;'. __('Start', 'js-jobs'), array('class' => 'inputbox sal'));
                        $value .= JSJOBSformfield::select('salaryrangeend', JSJOBSincluder::getJSModel('salaryrange')->getJobEndSalaryRangeForCombo(), isset(jsjobs::$_data[0]['filter']->salaryrange) ? jsjobs::$_data[0]['filter']->salaryrange : '', __('Select','js-jobs') .'&nbsp;'. __('Salary Range','js-jobs') .'&nbsp;'. __('End', 'js-jobs'), array('class' => 'inputbox sal'));
                        $value .= JSJOBSformfield::select('salaryrangetype', JSJOBSincluder::getJSModel('salaryrangetype')->getSalaryRangeTypesForCombo(), isset(jsjobs::$_data[0]['filter']->salaryrangetype) ? jsjobs::$_data[0]['filter']->salaryrangetype : '', __('Select','js-jobs') .'&nbsp;'. __('Salary Range Type', 'js-jobs'), array('class' => 'inputbox sal'));
                        echo getRow($title, $value);
                        break;
                    case 'heighesteducation':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('highesteducation[]', JSJOBSincluder::getJSModel('highesteducation')->getHighestEducationForCombo(), isset(jsjobs::$_data[0]['filter']->highesteducation) ? jsjobs::$_data[0]['filter']->highesteducation : '', __('Select','js-jobs') .'&nbsp;'. __('Highest Education', 'js-jobs'), array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                        echo getRow($title, $value);
                        break;
                    case 'city':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::text('city', isset(jsjobs::$_data[0]['filter']->city) ? jsjobs::$_data[0]['filter']->city : '', array('class' => 'inputbox'));
                        echo getRow($title, $value);
                        break;
                    case 'zipcode':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::text('zipcode', isset(jsjobs::$_data[0]['filter']->zipcode) ? jsjobs::$_data[0]['filter']->zipcode : '', array('class' => 'inputbox'));
                        echo getRow($title, $value);
                        break;
                    case 'requiredtravel':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('requiredtravel', JSJOBSincluder::getJSModel('common')->getRequiredTravel(), isset(jsjobs::$_data[0]['filter']->requiredtravel) ? jsjobs::$_data[0]['filter']->requiredtravel : '', __('Select one', 'js-jobs'), array('class' => 'inputbox'));
                        echo getRow($title, $value);
                        break;
                    case 'duration':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::text('duration', isset(jsjobs::$_data[0]['filter']->duration) ? jsjobs::$_data[0]['filter']->duration : '', array('class' => 'inputbox'));
                        echo getRow($title, $value);
                        break;
                    case 'map':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = '<div id="map_container"><div id="map"></div></div>';
                        echo getRow($title, $value);
                        $title = __('Longitude', 'js-jobs');
                        $value = JSJOBSformfield::text('longitude', isset(jsjobs::$_data[0]['filter']->longitude) ? jsjobs::$_data[0]['filter']->longitude : '', array('class' => 'inputbox'));
                        echo getRow($title, $value);
                        $title = __('Latitude', 'js-jobs');
                        $value = JSJOBSformfield::text('latitude', isset(jsjobs::$_data[0]['filter']->latitude) ? jsjobs::$_data[0]['filter']->latitude : '', array('class' => 'inputbox'));
                        echo getRow($title, $value);
                        $title = __('Radius', 'js-jobs');
                        $value = JSJOBSformfield::text('radius', isset(jsjobs::$_data[0]['filter']->radius) ? jsjobs::$_data[0]['filter']->radius : '', array('class' => 'inputbox'));
                        echo getRow($title, $value);
                        $title = __('Radius Length Type', 'js-jobs');
                        $value = JSJOBSformfield::select('radiuslengthtype', $radiustype, jsjobs::$_configuration['defaultradius'], __('Select','js-jobs') .'&nbsp;'. __('Radius Length Type', 'js-jobs'), array('class' => 'inputbox'));
                        echo getRow($title, $value);
                        break;
                    default:
                        $i = 0;
                        JSJOBSincluder::getObjectClass('customfields')->formCustomFieldsForSearch($field, $i);
                        break;
                }
            }
            ?>
            <div class="js-col-md-12 js-form-wrapper">
                <div class="js-col-md-12 bottombutton js-form" id="save-button">                 
                    <?php echo JSJOBSformfield::submitbutton('save', __('Search Job', 'js-jobs'), array('class' => 'button')); ?>
                </div>
            </div>
            <input type="hidden" id="default_longitude" name="default_longitude" value="<?php echo jsjobs::$_configuration['default_longitude']; ?>"/>
            <input type="hidden" id="default_latitude" name="default_latitude" value="<?php echo jsjobs::$_configuration['default_latitude']; ?>"/>
            <input type="hidden" id="issearchform" name="issearchform" value="1"/>
        </form>
    </div>

<?php 
}else{
    echo jsjobs::$_error_flag_message;
} ?>