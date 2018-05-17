<?php

if (!defined('ABSPATH')) die('Restricted Access');
$msgkey = JSJOBSincluder::getJSModel('resumesearch')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(jsjobs::$_path . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    ?>
    <div id="jsjobs-wrapper">
        <div class="page_heading"><?php echo __('Resume Search', 'js-jobs'); ?></div>
        <form class="resume_form" id="resume_form" method="post" action="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'resumes', 'jsjobspageid'=>jsjobs::getPageid())); ?>">
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
                    case 'application_title':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::text('application_title', isset(jsjobs::$_data[0]->application_title) ? jsjobs::$_data[0]->application_title : '', array('class' => 'inputbox'));
                        echo getRow($title, $value);
                        break;
                    case 'first_name':
                        $title = $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::text('first_name', isset(jsjobs::$_data[0]->first_name) ? jsjobs::$_data[0]->first_name : '', array('class' => 'inputbox'));
                        echo getRow($title, $value);
                        break;
                    case 'middle_name':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::text('middle_name', isset(jsjobs::$_data[0]->middle_name) ? jsjobs::$_data[0]->middle_name : '', array('class' => 'inputbox'));
                        echo getRow($title, $value);
                        break;
                    case 'last_name':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::text('last_name', isset(jsjobs::$_data[0]->last_name) ? jsjobs::$_data[0]->last_name : '', array('class' => 'inputbox'));
                        echo getRow($title, $value);
                        break;
                    case 'nationality':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('nationality', JSJOBSincluder::getJSModel('country')->getCountriesForCombo(), isset(jsjobs::$_data[0]->nationality) ? jsjobs::$_data[0]->nationality : '', __('Select','js-jobs') .'&nbsp;'. __('Nationality', 'js-jobs'), array('class' => 'inputbox'));
                        echo getRow($title, $value);
                        break;
                    case 'gender':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('gender', JSJOBSincluder::getJSModel('common')->getGender(), isset(jsjobs::$_data[0]->gender) ? jsjobs::$_data[0]->gender : '', __('Select','js-jobs') .'&nbsp;'. __('Gender', 'js-jobs'), array('class' => 'inputbox'));
                        echo getRow($title, $value);
                        break;
                    case 'job_category':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('category', JSJOBSincluder::getJSModel('category')->getCategoriesForCombo(), isset(jsjobs::$_data[0]->category) ? jsjobs::$_data[0]->category : '', __('Select','js-jobs') .'&nbsp;'. __('Category', 'js-jobs'), array('class' => 'inputbox'));
                        echo getRow($title, $value);
                        break;
                    case 'jobtype':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('jobtype', JSJOBSincluder::getJSModel('jobtype')->getJobTypeForCombo(), isset(jsjobs::$_data[0]->jobtype) ? jsjobs::$_data[0]->jobtype : '', __('Select','js-jobs') .'&nbsp;'. __('Job Type', 'js-jobs'), array('class' => 'inputbox'));
                        echo getRow($title, $value);
                        break;
                    case 'salary':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('currencyid', JSJOBSincluder::getJSModel('currency')->getCurrencyForCombo(), isset(jsjobs::$_data[0]->currencyid) ? jsjobs::$_data[0]->currencyid : '', __('Select','js-jobs') .'&nbsp;'. __('Currency', 'js-jobs'), array('class' => 'inputbox sal'))
                                . JSJOBSformfield::select('salaryrangefrom', JSJOBSincluder::getJSModel('salaryrange')->getJobStartSalaryRangeForCombo(), isset(jsjobs::$_data[0]->salaryrange) ? jsjobs::$_data[0]->salaryrange : '', __('Select','js-jobs') .'&nbsp;'. __('Salary Range','js-jobs') .'&nbsp;'. __('Start', 'js-jobs'), array('class' => 'inputbox sal'))
                                . JSJOBSformfield::select('salaryrangeend', JSJOBSincluder::getJSModel('salaryrange')->getJobEndSalaryRangeForCombo(), isset(jsjobs::$_data[0]->salaryrange) ? jsjobs::$_data[0]->salaryrange : '', __('Select','js-jobs') .'&nbsp;'. __('Salary Range','js-jobs') .'&nbsp;'. __('End', 'js-jobs'), array('class' => 'inputbox sal'))
                                . JSJOBSformfield::select('salaryrangetype', JSJOBSincluder::getJSModel('salaryrangetype')->getSalaryRangeTypesForCombo(), isset(jsjobs::$_data[0]->salaryrangetype) ? jsjobs::$_data[0]->salaryrangetype : '', __('Select','js-jobs') .'&nbsp;'. __('Salary Range Type', 'js-jobs'), array('class' => 'inputbox sal'));
                        echo getRow($title, $value);
                        break;
                    case 'heighestfinisheducation':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('highesteducation', JSJOBSincluder::getJSModel('highesteducation')->getHighestEducationForCombo(), isset(jsjobs::$_data[0]->highesteducation) ? jsjobs::$_data[0]->highesteducation : '', __('Select','js-jobs') .'&nbsp;'. __('Highest Education', 'js-jobs'), array('class' => 'inputbox'));
                        echo getRow($title, $value);
                        break;
                    case 'total_experience':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('experience', JSJOBSincluder::getJSModel('experience')->getexperiencesForCombo(), isset(jsjobs::$_data[0]->experience) ? jsjobs::$_data[0]->experience : '', __('Select','js-jobs') .'&nbsp;'. __('Experience', 'js-jobs'), array('class' => 'inputbox'));
                        echo getRow($title, $value);
                        break;
                    case 'address_zipcode':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::text('zipcode', isset(jsjobs::$_data[0]->zipcode) ? jsjobs::$_data[0]->zipcode : '', array('class' => 'inputbox'));
                        echo getRow($title, $value);
                        break;
                    case 'keywords':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::text('keywords', isset(jsjobs::$_data[0]->keywords) ? jsjobs::$_data[0]->keywords : '', array('class' => 'inputbox'));
                        echo getRow($title, $value);
                        break;
                    default:
                        $i = 0;
                        JSJOBSincluder::getObjectClass('customfields')->formCustomFieldsForSearch($field, $i);
                        break;
                }
            }
            ?>
            <div class="js-col-md-12 js-form-wrapper" id="save-button">
                <?php echo JSJOBSformfield::hidden('id', isset(jsjobs::$_data[0]->id) ? jsjobs::$_data[0]->id : '' ); ?>
                <?php echo JSJOBSformfield::hidden('uid', JSJOBSincluder::getObjectClass('user')->uid()); ?>
                <?php echo JSJOBSformfield::hidden('created', isset(jsjobs::$_data[0]->created) ? jsjobs::$_data[0]->created : date('Y-m-d H:i:s')); ?>
                <?php echo JSJOBSformfield::hidden('jsformresumesearch', 1); ?>
                <div class="js-col-md-12 js-form">			    	
                    <?php
                    echo JSJOBSformfield::submitbutton('save', __('Resume Search', 'js-jobs'), array('class' => 'button'));
                    ?>
                </div>
            </div>
        </form>
    </div>
<?php 
}else{
    echo jsjobs::$_error_flag_message;
} ?>

