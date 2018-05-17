<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSResumeViewlayout {

    public $config_array_sec=array();

    function __construct(){
        $this->config_array_sec = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('resume');
        $fieldsordering = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(3); // resume fields
        jsjobs::$_data[2] = array();
        foreach ($fieldsordering AS $field) {
            jsjobs::$_data['fieldtitles'][$field->field] = $field->fieldtitle;
            jsjobs::$_data[2][$field->section][$field->field] = $field;
        }
    }
    function getRowMapForView($text, $longitude, $latitude,$themecall=null) {
        $id = uniqid();
        if(null != $themecall){
            $html = '<div class="jsjb-jm-resumedetail-address-map-wrap">
                        <div class="jsjb-jm-resumedetail-address-map">
                            <span class="jsjb-jm-resumedetail-address-map-showhide"><img src="' . JOB_MANAGER_IMAGE . '/hide-map.png" class="image"/></span>
                            ' . $text . '
                        </div>                        
                        <div class="jsjb-jm-resumedetail-address-map-area" style="display: block;">
                            <div class="jsjb-jm-map-inner">
                                <div id="jsjb-jm-map" style="position: relative; overflow: hidden;">
                                    <div id="' . $id . '" class="map" style="width:100%;min-height:200px;">' . $longitude . ' - ' . $latitude . '</div>
                                </div>
                            </div>
                        </div>
                        <script type="text/javascript" id="script_' . $id . '">
                            jQuery(document).ready(function(){
                                initialize("' . $latitude . '","' . $longitude . '","' . $id . '");
                            });
                        </script>
                    </div>';
        }else{
            $html = '<div class="resume-map">
                    <div class="row-title"><img src="' . jsjobs::$_pluginpath . 'includes/images/resume/hide-map.png" class="image"/>' . $text . '</div>
                    <div class="row-value"><div id="' . $id . '" class="map" style="width:100%;min-height:200px;">' . $longitude . ' - ' . $latitude . '</div></div>
                    <script type="text/javascript" id="script_' . $id . '">
                        initialize("' . $latitude . '","' . $longitude . '","' . $id . '");
                    </script>
                </div>';
        }
        return $html;
    }
    function getRowForVideoViewJobManager($value, $vtype){
        $html = '';
        if (!empty($value)) {
            $html='<div id="jsjb-jm-resumedetail-video" class="jsjb-jm-resume-video-wrap">
                    <h3 class="jsjb-jm-resume-video-title">
                        '.esc_html__("Video","job-manager").'
                    </h3>
                    <div class="jsjb-jm-resume-video">';
                    parse_str(parse_url($value, PHP_URL_QUERY), $my_array_of_vars);
                    if ($vtype == 1 && !empty($my_array_of_vars)) { // youtube video link
                        $value = $my_array_of_vars['v'];
                        $html .= '<iframe title="YouTube video player" width="100%"  
                                        src="https://www.youtube.com/embed/' . $value . '" frameborder="0" allowfullscreen>
                                </iframe>';
                    } else { //Embed code
                        $html .= str_replace('\"', '', $value);
                    }
                    $html.='</div>
                </div>';
        }
        return $html;
    }

    function getRowForVideoView($text, $value, $vtype,$themecall=null) {
        if(1 == $themecall) return;
        $html = '';
        if (!empty($value)) {
            $html = '<div class="resume-row-full-view">
                    <div class="row-value video">';
                parse_str(parse_url($value, PHP_URL_QUERY), $my_array_of_vars);
            if ($vtype == 1 && !empty($my_array_of_vars)) { // youtube video link
                $value = $my_array_of_vars['v'];
                $html .= '<iframe title="YouTube video player" width="380" height="290" 
                                src="http://www.youtube.com/embed/' . $value . '" frameborder="0" allowfullscreen>
                        </iframe>';
            } else { //Embed code
                $html .= str_replace('\"', '', $value);
            }
            $html .= '</div>
                </div>';
        }
        return $html;
    }
    function getAttachmentRowForViewJobManager($adminLogin) {
        $html='<div id="jsjb-jm-resumedetail-attachment" class="jsjb-jm-resumedetail-section">
            <div class="jsjb-jm-resumedetail-section-title">
                <span class="jsjb-jm-resumedetail-section-icon">
                    <img alt="attachment" title="attachment" src="'.JOB_MANAGER_IMAGE.'/attchments.png">
                </span>
                <h5 class="jsjb-jm-resumedetail-section-txt">
                    '.__("Attachment","job-manager").'
                </h5>
            </div>
            <div class="jsjb-jm-resumedetail-sec-data">
                <div class="jsjb-jm-resumedetail-sec-download">
                    <div class="input-group">';
                        foreach (jsjobs::$_data[0]['file_section'] AS $file) {
                            $files=$file->filename;
                            $exp_extension = explode(".", $files);
                            $extension = end($exp_extension);
                            $filename=substr($files,'0','3')."...";
                            $html .= '<a target="_blank" href="' . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'action'=>'jsjobtask', 'task'=>'getresumefiledownloadbyid', 'jsjobsid'=>$file->id, 'jsjobspageid'=>JSJOBSRequest::getVar('jsjobspageid'))) . '" class="file">
                                        <span class="filename">' . $filename . '</span><span class="fileext">'.$extension.'</span>
                                        <i class="fa fa-download download" aria-hidden="true"></i>
                                    </a>';
                        }
                    $html .='</div>';
                    if(!empty(jsjobs::$_data[0]['file_section']) && (jsjobs::$_data['resumecontactdetail'] == true || $adminLogin)){
                        $html .= '<a class="downloadall"  href="' . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'action'=>'jsjobtask', 'task'=>'getallresumefiles', 'resumeid'=>jsjobs::$_data[0]['personal_section']->id, 'jsjobspageid'=>JSJOBSRequest::getVar('jsjobspageid'))) . '" ><img src="' . jsjobs::$_pluginpath . '/includes/images/download-all.png" /> </a>';
                    }
                $html .= '</div>
            </div>
        </div>';
        return $html;        
    }

    function getAttachmentRowForView($text,$themecall=null) {
        if(null !=$themecall) return; 
        $html = '<div class="resume-row-full-view resume-row-wrapper-wrapper">
                    <div class="row-title attachments">' . $text . ':</div>
                    <div class="row-value attachments">';
        if (!empty(jsjobs::$_data[0]['file_section'])) {
            foreach (jsjobs::$_data[0]['file_section'] AS $file) {
                $html .= '<a target="_blank" href="' . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'action'=>'jsjobtask', 'task'=>'getresumefiledownloadbyid', 'jsjobsid'=>$file->id, 'jsjobspageid'=>JSJOBSRequest::getVar('jsjobspageid'))) . '" class="file">
                            <span class="filename">' . $file->filename . '</span><span class="fileext"></span>
                            <img class="filedownload" src="' . jsjobs::$_pluginpath . 'includes/images/resume/download.png" />
                        </a>';
            }
        }
        $html .= '  </div>
                </div>';
        return $html;
    }
    function getLanguageSection($resumeformview, $call, $viewlayout = 0,$themecall=null) { // viewlayout use to use only on view resume  
        $html = '';
        if ($resumeformview == 0) { // edit form
            if (!empty(jsjobs::$_data[0]['language_section'][0]))
                foreach (jsjobs::$_data[0]['language_section'] AS $language) {
                    $html .= '<div class="section_wrapper jsjb-jm-resumedetail-sec-data" data-section="languages" data-sectionid="' . $language->id . '">';
                    $i = 0;
                    $value = $language->language;
                    if ($viewlayout == 0) {
                        $value .= '<a class="edit" href="#"><img src="' . jsjobs::$_pluginpath . 'includes/images/edit-resume.png" /></a>';
                        $value .= '<a class="delete" href="#"><img src="' . jsjobs::$_pluginpath . 'includes/images/delete-resume.png" /></a>';
                    }
                    $html .= $this->getHeadingRowForView($value,$themecall);
                    foreach (jsjobs::$_data[2][8] AS $field => $required) {
                        switch ($field) {
                            case 'language_reading':
                                $text = $this->getFieldTitleByField($field);
                                $value = $language->language_reading;
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            case 'language_writing':
                                $text = $this->getFieldTitleByField($field);
                                $value = $language->language_writing;
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            case 'language_understanding':
                                $text = $this->getFieldTitleByField($field);
                                $value = $language->language_understanding;
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            case 'language_where_learned':
                                $text = $this->getFieldTitleByField($field);
                                $value = $language->language_where_learned;
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            default:
                                $array = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 11, $language->params); //11 for view resume
                                if (is_array($array))
                                    $html .= $this->getRowForView($array['title'], $array['value'], $i,$themecall,1);
                                break;
                        }
                    }
                    if(null==$themecall){
                        if ($i % 2 != 0) { // close the div if one field is print and the function is finished;
                            $html .= '</div>';
                        }
                    }    
                    $html .= '</div>'; //section wrapper end;
                }
        }    
        return $html;
    }    
    function getReferenceSection($resumeformview, $call, $viewlayout = 0,$themecall=null) {
        $html = '';
        if ($resumeformview == 0) { // edit form
            if (!empty(jsjobs::$_data[0]['reference_section'][0]))
                foreach (jsjobs::$_data[0]['reference_section'] AS $reference) {
                    $html .= '<div class="section_wrapper jsjb-jm-resumedetail-sec-data" data-section="references" data-sectionid="' . $reference->id . '">';
                    $i = 0;
                    $loc = 0;
                    $value = $reference->reference;
                    if ($viewlayout == 0) {
                        $value .= '<a class="edit" href="#"><img src="' . jsjobs::$_pluginpath . 'includes/images/edit-resume.png" /></a>';
                        $value .= '<a class="delete" href="#"><img src="' . jsjobs::$_pluginpath . 'includes/images/delete-resume.png" /></a>';
                    }
                    $html .= $this->getHeadingRowForView($value,$themecall);
                    foreach (jsjobs::$_data[2][7] AS $field => $required) {
                        switch ($field) {
                            case 'reference_name':
                                $text = $this->getFieldTitleByField($field);
                                $value = $reference->reference_name;
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            case 'reference_state':
                            case 'reference_country':
                            case 'reference_city':
                                if ($loc == 0) {
                                    $text = $this->getFieldTitleByField($field);
                                    $value = JSJOBSincluder::getJSModel('common')->getLocationForView($reference->cityname, $reference->statename, $reference->countryname);
                                    $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                    $loc++;
                                }
                                break;
                            case 'reference_zipcode':
                                $text = $this->getFieldTitleByField($field);
                                $value = $reference->reference_zipcode;
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            case 'reference_address':
                                $text = $this->getFieldTitleByField($field);
                                $value = $reference->reference_address;
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            case 'reference_phone':
                                $text = $this->getFieldTitleByField($field);
                                $value = $reference->reference_phone;
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            case 'reference_relation':
                                $text = $this->getFieldTitleByField($field);
                                $value = $reference->reference_relation;
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            case 'reference_years':
                                $text = $this->getFieldTitleByField($field);
                                $value = $reference->reference_years;
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            default:
                                $array = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 11, $reference->params); //6 for view resume and 2 for resume section
                                if (is_array($array))
                                    $html .= $this->getRowForView($array['title'], $array['value'], $i,$themecall,1);
                                break;
                        }
                    }
                    if(null==$themecall){
                        if ($i % 2 != 0) { // close the div if one field is print and the function is finished;
                            $html .= '</div>';
                        }
                    }    
                    $html .= '</div>'; //section wrapper end;
                }
        }
        return $html;
    }

    function getResumeSection($resumeformview, $call, $viewlayout = 0,$themecall=null) {
        $html = '';
        if ($resumeformview == 0) { // edit form
            $html .= '<div class="section_wrapper jsjb-jm-resumedetail-sec-data" data-section="resume" data-sectionid="">';
            $i = 0;
            foreach (jsjobs::$_data[2][6] AS $field => $required) {
                switch ($field) {
                    case 'resume':
                        if(null==$themecall){
                            if ($i % 2 != 0) { // close the div if one field is print and the function is finished;
                                $html .= '</div>'; // closing div for the more option
                            }
                        }
                        $value = jsjobs::$_data[0]['personal_section']->resume;
                        $html .= '<div class="resume-section-data">' . $value . '</div>';
                        $i = 0;
                        break;
                    default:
                        $array = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 11,jsjobs::$_data[0]['personal_section']->params); //11 for view resume
                        if (is_array($array))
                            $html .= $this->getRowForView($array['title'], $array['value'], $i);
                        break;
                }
            }
            if(null==$themecall){
                if ($i % 2 != 0) { // close the div if one field is print and the function is finished;
                    $html .= '</div>'; // closing div for the more option
                }
            }
            $html .= '</div>';
        } 
        return $html;
    } 


    function getSkillSection($resumeformview, $call, $viewlayout = 0,$themecall=null) {
        $html = '';
        if ($resumeformview == 0) { // edit form
            $html .= '<div class="section_wrapper jsjb-jm-resumedetail-sec-data" data-section="skills" data-sectionid="">';
            $i = 0;
            foreach (jsjobs::$_data[2][5] AS $field => $required) {
                switch ($field) {
                    case 'skills':
                        if(null==$themecall){
                            if ($i % 2 != 0) { // close the div if one field is print and the function is finished;
                                $html .= '</div>'; // closing div for the more option
                            }
                        }    
                        $value = jsjobs::$_data[0]['personal_section']->skills;
                        $html .= '<div class="resume-section-data">' . $value . '</div>';
                        $i = 0;
                        break;
                    default:
                        $array = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 11, jsjobs::$_data[0]['personal_section']->params); //11 for view resume
                        if (is_array($array))
                            $html .= $this->getRowForView($array['title'], $array['value'], $i,$themecall,1);
                        break;
                }
            }
            if(null==$themecall){
                if ($i % 2 != 0) { // close the div if one field is print and the function is finished;
                    $html .= '</div>';
                }
            }
            $html .= '</div>'; // section wrapper end;
        }
        return $html;
    }    

    function getEmployerSection($resumeformview, $call, $viewlayout = 0,$themecall=null) {
        $html = '';
        if ($resumeformview == 0) { // edit form
            if (!empty(jsjobs::$_data[0]['employer_section'][0]))
                foreach (jsjobs::$_data[0]['employer_section'] AS $employer) {
                    $html .= '<div class="section_wrapper jsjb-jm-resumedetail-sec-data" data-section="employers" data-sectionid="' . $employer->id . '">';
                    $i = 0;
                    $value = $employer->employer;
                    $value .= '<span class="resume-employer-position">' . $employer->employer_position . '</span>';
                    $value .= '<span class="resume-employer-dates">(' . date_i18n('M Y', strtotime($employer->employer_from_date)) . ' - ' . date_i18n('M Y', strtotime($employer->employer_to_date)) . ')</span>';
                    if ($viewlayout == 0) {
                        $value .= '<a class="edit" href="#"><img src="' . jsjobs::$_pluginpath . 'includes/images/edit-resume.png" /></a>';
                        $value .= '<a class="delete" href="#"><img src="' . jsjobs::$_pluginpath . 'includes/images/delete-resume.png" /></a>';
                    }
                    $html .= $this->getHeadingRowForView($value,$themecall);
                    foreach (jsjobs::$_data[2][4] AS $field => $required) {
                        switch ($field) {
                            case 'employer_resp':
                                $text = $this->getFieldTitleByField($field);
                                $value = $employer->employer_resp;
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            case 'employer_pay_upon_leaving':
                                $text = $this->getFieldTitleByField($field);
                                $value = $employer->employer_pay_upon_leaving;
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            case 'employer_supervisor':
                                $text = $this->getFieldTitleByField($field);
                                $value = $employer->employer_supervisor;
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            case 'employer_leave_reason':
                                $text = $this->getFieldTitleByField($field);
                                $value = $employer->employer_leave_reason;
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            case 'employer_city':
                                $text = $this->getFieldTitleByField($field);
                                $value = JSJOBSincluder::getJSModel('common')->getLocationForView($employer->cityname, $employer->statename, $employer->countryname);
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            case 'employer_zip':
                                $text = $this->getFieldTitleByField($field);
                                $value = $employer->employer_zip;
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            case 'employer_phone':
                                $text = $this->getFieldTitleByField($field);
                                $value = $employer->employer_phone;
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            case 'employer_address':
                                $text = $this->getFieldTitleByField($field);
                                $value = $employer->employer_address;
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            default:
                                $array = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 11, $employer->params); //11 for view resume
                                if (is_array($array))
                                    $html .= $this->getRowForView($array['title'], $array['value'], $i,$themecall,1);
                                break;
                        }
                    }
                    if(null==$themecall){
                        if ($i % 2 != 0) { // close the div if one field is print and the function is finished;
                            $html .= '</div>';
                        }
                    }
                    $html .= '</div>'; // section wrapper end;
                }
        }
        return $html; 
    }    


    function getEducationSection($resumeformview, $call, $viewlayout = 0,$themecall=null) {
        $html = '';
        if ($resumeformview == 0) { // edit form
            if (!empty(jsjobs::$_data[0]['institute_section'][0]))
                foreach (jsjobs::$_data[0]['institute_section'] AS $institute) {
                    $html .= '<div class="section_wrapper jsjb-jm-resumedetail-sec-data" data-section="institutes" data-sectionid="' . $institute->id . '">';
                    $i = 0;
                    $value = $institute->institute;
                    if ($institute->iscontinue == 1) {
                        $todate = __('Continue', 'js-jobs');
                    } else {
                        $todate = date_i18n('M Y', strtotime($institute->todate));
                    }
                    if(null != $themecall){
                        $value .= '<span class="jsjb-jm-resumedetail-sec-date">( <i class="fa fa-calendar-o" aria-hidden="true"></i> ' . date_i18n('M Y', strtotime($institute->fromdate)) . ' - ' . $todate . ')</span>';
                    }else{
                        $value .= '<span class="resume-employer-dates">(' . date_i18n('M Y', strtotime($institute->fromdate)) . ' - ' . $todate . ')</span>';
                    }
                    if ($viewlayout == 0) {
                        $value .= '<a class="edit" href="#"><img src="' . jsjobs::$_pluginpath . 'includes/images/edit-resume.png" /></a>';
                        $value .= '<a class="delete" href="#"><img src="' . jsjobs::$_pluginpath . 'includes/images/delete-resume.png" /></a>';
                    }
                    $html .= $this->getHeadingRowForView($value,$themecall);
                    foreach (jsjobs::$_data[2][3] AS $field => $required) {
                        switch ($field) {
                            case 'institute_city':
                                $text = $this->getFieldTitleByField($field);
                                $value = JSJOBSincluder::getJSModel('common')->getLocationForView($institute->cityname, $institute->statename, $institute->countryname);
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            case 'institute_address':
                                $text = $this->getFieldTitleByField($field);
                                $value = $institute->institute_address;
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            case 'institute_certificate_name':
                                $text = $this->getFieldTitleByField($field);
                                $value = $institute->institute_certificate_name;
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            case 'institute_study_area':
                                $text = $this->getFieldTitleByField($field);
                                $value = $institute->institute_study_area;
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            default:
                                $array = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 11, $institute->params); //11 for view resume
                                if (is_array($array))
                                    $html .= $this->getRowForView($array['title'], $array['value'], $i,$themecall,1);
                                break;
                        }
                    }
                    if(null==$themecall){
                        if ($i % 2 != 0) { // close the div if one field is print and the function is finished;
                            $html .= '</div>';
                        }
                    }
                    $html .= '</div>'; // section wrapper end;
                }
        }
        return $html;
    }

    function getAddressesSection($resumeformview, $call, $viewlayout = 0,$themecall=null) {
        $html = '';
        if ($resumeformview == 0) { // view address sections
            if (!empty(jsjobs::$_data[0]['address_section'][0]))
                foreach (jsjobs::$_data[0]['address_section'] AS $address) {
                    $html .= '<div class="section_wrapper jsjb-jm-resumedetail-sec-data" data-section="addresses" data-sectionid="' . $address->id . '">';
                    $i = 0;
                    $loc = 0;
                    $value = $address->address;
                    if ($viewlayout == 0) {
                        $value .= '<a class="edit" href="#"><img src="' . jsjobs::$_pluginpath . 'includes/images/edit-resume.png" /></a>';
                        $value .= '<a class="delete" href="#"><img src="' . jsjobs::$_pluginpath . 'includes/images/delete-resume.png" /></a>';
                    }
                    $html .= $this->getHeadingRowForView($value,$themecall);
                    foreach (jsjobs::$_data[2][2] AS $field => $required) {
                        switch ($field) {
                            case 'address_city':
                            case 'address_state':
                            case 'address_country':
                                if ($loc == 0) {
                                    $text = $this->getFieldTitleByField($field);
                                    $value = JSJOBSincluder::getJSModel('common')->getLocationForView($address->cityname, $address->statename, $address->countryname);
                                    $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                    $loc++;
                                }
                                break;
                            case 'address_zipcode':
                                $text = $this->getFieldTitleByField($field);
                                $value = $address->address_zipcode;
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            case 'address_location':
                                $text = $this->getFieldTitleByField($field);
                                $html .= $this->getRowMapForView($text, $address->longitude, $address->latitude,$themecall);
                                break;
                            default:
                                $array = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 11, $address->params); //11 for view resume
                                if (is_array($array))
                                    $html .= $this->getRowForView($array['title'], $array['value'], $i,$themecall,1);
                                break;
                        }
                    }
                    if(null==$themecall){
                        if ($i % 2 != 0) { // close the div if one field is print and the function is finished;
                            $html .= '</div>';
                        }
                    }
                $html .= '</div>'; //section wrapper end;
            }
        }
        return $html;
    }    

    function getPersonalSection($resumeformview, $viewlayout = 0,$themecall=null) {
        $html = '';
        if ($resumeformview == 0) { // view section resume
            $html .= '<div class="section_wrapper jsjb-jm-resumedetail-sec-data" data-section="personal" data-sectionid="">';
            $i = 0;
            foreach (jsjobs::$_data[2][1] AS $field => $required) {
                switch ($field) {
                    case 'cell':
                        if (jsjobs::$_data['resumecontactdetail'] == true) {
                            $text = $this->getFieldTitleByField($field);
                            $value = jsjobs::$_data[0]['personal_section']->cell;
                            $html .= $this->getRowForView($text, $value, $i,$themecall);
                        }
                        break;
                    case 'nationality':
                        $text = $this->getFieldTitleByField($field);
                        $value = jsjobs::$_data[0]['personal_section']->nationality;
                        $html .= $this->getRowForView($text, $value, $i,$themecall);
                        break;
                    case 'gender':
                        $text = $this->getFieldTitleByField($field);
                        $value = '';
                        switch (jsjobs::$_data[0]['personal_section']->gender) {
                            case '0':$value = __('Does not matter', 'js-jobs');
                                break;
                            case '1':$value = __('Male', 'js-jobs');
                                break;
                            case '2':$value = __('Female', 'js-jobs');
                                break;
                        }
                        $html .= $this->getRowForView($text, $value, $i,$themecall);
                        break;
                    case 'job_category':
                        $text = $this->getFieldTitleByField($field);
                        $value = jsjobs::$_data[0]['personal_section']->categorytitle;
                        $html .= $this->getRowForView($text, $value, $i,$themecall);
                        break;
                    case 'jobtype':
                        $text = $this->getFieldTitleByField($field);
                        $value = jsjobs::$_data[0]['personal_section']->jobtypetitle;
                        $html .= $this->getRowForView($text, $value, $i,$themecall);
                        break;
                    case 'heighestfinisheducation':
                        $text = $this->getFieldTitleByField($field);
                        $value = jsjobs::$_data[0]['personal_section']->highestfinisheducation;
                        $html .= $this->getRowForView($text, $value, $i,$themecall);
                        break;
                    case 'total_experience':
                        $text = $this->getFieldTitleByField($field);
                        $value = jsjobs::$_data[0]['personal_section']->total_experience;
                        $html .= $this->getRowForView($text, $value, $i,$themecall);
                        break;
                    case 'home_phone':
                        if (jsjobs::$_data['resumecontactdetail'] == true) {
                            $text = $this->getFieldTitleByField($field);
                            $value = jsjobs::$_data[0]['personal_section']->home_phone;
                            $html .= $this->getRowForView($text, $value, $i,$themecall);
                        }
                        break;
                    case 'work_phone':
                        if (jsjobs::$_data['resumecontactdetail'] == true) {
                            $text = $this->getFieldTitleByField($field);
                            $value = jsjobs::$_data[0]['personal_section']->work_phone;
                            $html .= $this->getRowForView($text, $value, $i,$themecall);
                        }
                        break;
                    case 'date_of_birth':
                        $text = $this->getFieldTitleByField($field);
                        $dateformat = jsjobs::$_configuration['date_format'];
                        if(jsjobs::$_data[0]['personal_section']->date_of_birth != '0000-00-00 00:00:00' && jsjobs::$_data[0]['personal_section']->date_of_birth != ''){
                            $value = date_i18n($dateformat, strtotime(jsjobs::$_data[0]['personal_section']->date_of_birth));                           
                        }else{
                            $value = '';
                        }
                        $html .= $this->getRowForView($text, $value, $i,$themecall);
                        break;
                    case 'date_start':
                        $text = $this->getFieldTitleByField($field);
                        $dateformat = jsjobs::$_configuration['date_format'];
                        if(jsjobs::$_data[0]['personal_section']->date_start != '0000-00-00 00:00:00' && jsjobs::$_data[0]['personal_section']->date_start != ''){
                            $value = date_i18n($dateformat, strtotime(jsjobs::$_data[0]['personal_section']->date_start));                          
                        }else{
                            $value = '';
                        }
                        $html .= $this->getRowForView($text, $value, $i,$themecall);
                        break;
                    case 'salary':
                        $text = $this->getFieldTitleByField($field);
                        $value = jsjobs::$_data[0]['personal_section']->salary;
                        $html .= $this->getRowForView($text, $value, $i,$themecall);
                        break;
                    case 'desired_salary':
                        $text = $this->getFieldTitleByField($field);
                        $value = jsjobs::$_data[0]['personal_section']->dsalary;
                        $html .= $this->getRowForView($text, $value, $i,$themecall);
                        break;
                    case 'video':
                        if ($i % 2 != 0) { // close the div if one field is print and the function is finished;
                            $html .= '</div>'; // closing div for the more option
                        }
                        $text = $this->getFieldTitleByField($field);
                        $value = jsjobs::$_data[0]['personal_section']->video;
                        $vtype = jsjobs::$_data[0]['personal_section']->videotype;
                        $html .= $this->getRowForVideoView($text, $value, $vtype,$themecall);
                        $i = 0;
                        break;
                    case 'keywords':
                        $text = $this->getFieldTitleByField($field);
                        $value = jsjobs::$_data[0]['personal_section']->keywords;
                        $html .= $this->getRowForView($text, $value, $i,$themecall);
                        break;
                    case 'searchable':
                        $text = $this->getFieldTitleByField($field);
                        $value = (jsjobs::$_data[0]['personal_section']->searchable == 1) ? __('Yes', 'js-jobs') : __('No', 'js-jobs');
                        $html .= $this->getRowForView($text, $value, $i,$themecall);
                        break;
                    case 'driving_license':
                        $text = $this->getFieldTitleByField($field);
                        $value = (jsjobs::$_data[0]['personal_section']->driving_license == 1) ? __('Yes', 'js-jobs') : __('No', 'js-jobs');
                        $html .= $this->getRowForView($text, $value, $i,$themecall);
                        break;
                    case 'license_no':
                        $text = $this->getFieldTitleByField($field);
                        $value = (jsjobs::$_data[0]['personal_section']->license_no != '') ? jsjobs::$_data[0]['personal_section']->license_no : __('N/A', 'js-jobs');
                        $html .= $this->getRowForView($text, $value, $i,$themecall);
                        break;
                    case 'license_country':
                        $text = $this->getFieldTitleByField($field);
                        $value = (jsjobs::$_data[0]['personal_section']->licensecountryname != '') ? jsjobs::$_data[0]['personal_section']->licensecountryname : __('N/A', 'js-jobs');
                        $html .= $this->getRowForView($text, $value, $i,$themecall);
                        break;
                    case 'iamavailable':
                        $text = $this->getFieldTitleByField($field);
                        $value = (jsjobs::$_data[0]['personal_section']->iamavailable == 1) ? __('Yes', 'js-jobs') : __('No', 'js-jobs');
                        $html .= $this->getRowForView($text, $value, $i,$themecall);
                        break;
                    case 'resumefiles':
                        if ($i % 2 != 0) { // close the div if one field is print and the function is finished;
                            $html .= '</div>'; // closing div for the more option
                        }
                        $text = $this->getFieldTitleByField($field);
                        $html .= $this->getAttachmentRowForView($text,$themecall);
                        $i = 0;
                        break;
                    default:
                        $array = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 11, jsjobs::$_data[0]['personal_section']->params); // 11 view resume
                        if (is_array($array)){
                            $html .= $this->getRowForView($array['title'], $array['value'], $i,$themecall);
                        }
                        break;
                }
            }
            if ($i % 2 != 0) { // close the div if one field is print and the function is finished;
                $html .= '</div>'; // closing div for the more option
            }
            //$html .= '</div>'; //section wrapper end;// commented it to solve issue with design.
        }
        return $html;
    }

    function getPersonalTopSection($owner, $resumeformview) {
        $adminLogin = current_user_can('manage_options');
        $html = '<div class="resume-top-section">';
        if (isset(jsjobs::$_data[2][1]['photo'])) {
            $html .= '<div class="js-col-lg-4">';
            if (jsjobs::$_data[0]['personal_section']->photo != '') {
                $wpdir = wp_upload_dir();
                $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                $img = $wpdir['baseurl'] . '/' . $data_directory . '/data/jobseeker/resume_' . jsjobs::$_data[0]['personal_section']->id . '/photo/' . jsjobs::$_data[0]['personal_section']->photo;
            } else {
                $img = jsjobs::$_pluginpath . 'includes/images/users.png';
            }
            $html .= '<img src="' . $img . '" />';
            $html .= '</div>';
            $html .= '<div class="js-col-lg-8">';
        } else {
            $html .= '<div class="js-col-lg-12">';
        }
        if (isset(jsjobs::$_data[2][1]['first_name']) || isset(jsjobs::$_data[2][1]['middle_name']) || isset(jsjobs::$_data[2][1]['last_name'])) {
            $html .= '<span class="resume-tp-name">' . jsjobs::$_data[0]['personal_section']->first_name . ' ' . jsjobs::$_data[0]['personal_section']->middle_name . ' ' . jsjobs::$_data[0]['personal_section']->last_name;
            $layout = JSJOBSrequest::getVar('layout');
            $editsocialclass = '';
            // if ($resumeformview == 0 && ($layout == 'addresume' || $owner == 1)) {
            //     $html .= '<a class="personal_section_edit" href="#"><img src="' . jsjobs::$_pluginpath . 'includes/images/edit-resume.png" /></a>';
            //     $editsocialclass = 'editform';
            // }elseif($adminLogin || (!is_user_logged_in() && isset($_SESSION['wp-jsjobs']))) {
            //     $html .= '<a class="personal_section_edit" href="#"><img src="' . jsjobs::$_pluginpath . 'includes/images/edit-resume.png" /></a>';
            //     $editsocialclass = 'editform';
            // }
            $html .= '<div id="job-info-sociallink" class="' . $editsocialclass . '">';
            if (!empty(jsjobs::$_data[0]['personal_section']->facebook)) {
                if(strstr(jsjobs::$_data[0]['personal_section']->facebook, 'http') ){
                    $facebook = jsjobs::$_data[0]['personal_section']->facebook ;
                }else{
                    $facebook = 'http://'.jsjobs::$_data[0]['personal_section']->facebook;
                }
                $html .= '<a href="' . $facebook . '" target="_blank"><img src="' . jsjobs::$_pluginpath . 'includes/images/scround/fb.png"/></a>';
            }
            if (!empty(jsjobs::$_data[0]['personal_section']->twitter)) {
                if(strstr(jsjobs::$_data[0]['personal_section']->twitter, 'http') ){
                    $twitter = jsjobs::$_data[0]['personal_section']->twitter;
                }else{
                    $twitter = 'http://'.jsjobs::$_data[0]['personal_section']->twitter;
                }
                $html .= '<a href="' . $twitter . '" target="_blank"><img src="' . jsjobs::$_pluginpath . 'includes/images/scround/twitter.png"/></a>';
            }
            if (!empty(jsjobs::$_data[0]['personal_section']->googleplus)) {
                if(strstr(jsjobs::$_data[0]['personal_section']->googleplus, 'http') ){
                    $googleplus = jsjobs::$_data[0]['personal_section']->googleplus;
                }else{
                    $googleplus = 'http://'.jsjobs::$_data[0]['personal_section']->googleplus;
                }
                $html .= '<a href="' . $googleplus . '" target="_blank"><img src="' . jsjobs::$_pluginpath . 'includes/images/scround/gmail.png"/></a>';
            }
            if (!empty(jsjobs::$_data[0]['personal_section']->linkedin)) {
                if(strstr(jsjobs::$_data[0]['personal_section']->linkedin, 'http') ){
                    $linkedin = jsjobs::$_data[0]['personal_section']->linkedin;
                }else{
                    $linkedin = 'http://'.jsjobs::$_data[0]['personal_section']->linkedin;
                }
                $html .= '<a href="' . $linkedin . '" target="_blank"><img src="' . jsjobs::$_pluginpath . 'includes/images/scround/in.png"/></a>';
            }
            $html .= '</div>';

            $html .= '</span>';
        }
        if (isset(jsjobs::$_data[2][1]['application_title'])) {
            $html .= '<span class="resume-tp-apptitle">' . jsjobs::$_data[0]['personal_section']->application_title . '</span>';
        }
        if (jsjobs::$_data['resumecontactdetail'] == true || $adminLogin) {
            if (isset(jsjobs::$_data[2][1]['email_address'])) {
                $html .= '<span class="resume-tp-apptitle">' . jsjobs::$_data[0]['personal_section']->email_address . '</span>';
            }
        }
        $layout = JSJOBSrequest::getVar('jsjobslt');
        if ($layout != 'printresume') {
            if ($owner != 1) { // Current user is not owner and (Consider as employer)
                if (isset(jsjobs::$_data['coverletter']) && !empty(jsjobs::$_data['coverletter'])) {
                    // View cover letter icon 
                    $html .= '<a href="#" onclick="showPopupAndSetValues();"><img src="' . jsjobs::$_pluginpath . 'includes/images/resume/coverletter.png"/></a>';
                }
            }
            if (jsjobs::$_data['resumecontactdetail'] == true || $adminLogin) {
                $html .= '<a target="_blank" href="' . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'pdf', 'jsjobsid'=>jsjobs::$_data[0]['personal_section']->id,'jsjobspageid'=>jsjobs::getPageid())) . '"><img src="' . jsjobs::$_pluginpath . '/includes/images/pdf.png" /></a>';
                $html .= '<a target="_blank" href="' . jsjobs::makeUrl(array('jsjobsme'=>'export', 'task'=>'exportresume', 'action'=>'jsjobtask', 'jsjobsid'=>jsjobs::$_data[0]['personal_section']->id,'jsjobspageid'=>jsjobs::getPageid())) . '"><img src="' . jsjobs::$_pluginpath . '/includes/images/export.png" /></a>';
            }
            $html .= '<a href="#" id="print-link" data-resumeid="' . jsjobs::$_data[0]['personal_section']->id . '" ><img src="' . jsjobs::$_pluginpath . '/includes/images/print.png" /></a>';
            if(!empty(jsjobs::$_data[0]['file_section']) && (jsjobs::$_data['resumecontactdetail'] == true || $adminLogin)){
                $html .= '<a class="downloadall" target="_blank" href="' . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'action'=>'jsjobtask', 'task'=>'getallresumefiles', 'resumeid'=>jsjobs::$_data[0]['personal_section']->id, 'jsjobspageid'=>JSJOBSRequest::getVar('jsjobspageid'))) . '" ><img src="' . jsjobs::$_pluginpath . '/includes/images/download-all.png" />' . __('Resume files download', 'js-jobs') . '</a>';
            }
        } elseif ($layout == 'printresume') {
            $html .= '<a href="javascript:window.print();" class="grayBtn">' . __('Print', 'js-jobs') . '</a>';
        }

        $html .= '</div>'; // close for the inner section
        $html .= '</div>'; // closing div of resume-top-section
        return $html;
    }

    function getFieldTitleByField($field){

        return __(jsjobs::$_data['fieldtitles'][$field],'js-jobs');
    }
    function getRowForView($text, $value, &$i,$themecall=null,$full=0) {
        $html = '';
        if(null != $themecall){
            if(1!=$full){
                if ($i == 0 || $i % 2 == 0) {
                    $html .= '<div class="resume-row-wrapper-wrapper jsjb-jm-resumedetail-sec-value">';
                }
            }
        }else{
            if ($i == 0 || $i % 2 == 0) {
                $html .= '<div class="resume-row-wrapper-wrapper jsjb-jm-resumedetail-sec-value">';
            }
        }
        if(null != $themecall){
            if(0==$full){
                $html .= '<div class="jsjb-jm-resumedetail-sec-value-left jsjb-jm-bigfont">
                            <span class="jsjb-jm-resumedetail-title">' . $text . ':</span>
                            <span class="jsjb-jm-resumedetail-value">' . __($value,'js-jobs') . '</span>
                        </div>';
            }else if(1==$full){
                $html .='<div class="jsjb-jm-resumedetail-sec-value jsjb-jm-bigfont">
                            <span class="jsjb-jm-resumedetail-sec-title">' . $text . ':</span>
                            <span class="jsjb-jm-resumedetail-sec-value">' . __($value,'js-jobs') . '</span>
                        </div>';
            }
        }else{
            $html .= '<div class="resume-row-wrapper">
                        <div class="row-title">' . $text . ':</div>
                        <div class="row-value">' . __($value,'js-jobs') . '</div>
                    </div>';
        }
        $i++;
        if(null != $themecall){
            if(1!=$full){
                if ($i % 2 == 0) {
                    $html .= '</div>';
                }
            }
        }else{
            if ($i % 2 == 0) {
                $html .= '</div>';
            }
        }
        return $html;
    }

    function getRowForForm($text, $value) {
        $html = '<div class="resume-row-wrapper form">
                    <div class="row-title">' . $text . ':</div>
                    <div class="row-value">' . $value . '</div>
                </div>';
        return $html;
    }
    function getHeadingRowForView($value,$themecall=null) {
        if(null != $themecall){
            $html='<div class="jsjb-jm-resumedetail-sec-title1">
                <h6 class="jsjb-jm-resumedetail-sec-title1-txt">'.$value.'</h6>
            </div>';            
        }else{
            $html = '<div class="resume-heading-row">' . $value . '</div>';
        }
        return $html;
    }
    function makeanchorfortags($tags,$themecall=null) {

        if (empty($tags)) {
            if(null != $themecall) return;
            $anchor = '<div id="jsresume-tags-wrapper"></div>';
            return $anchor;
        }
        $array = explode(',', $tags);
        $anchor="";
        if(null != $themecall){
            for ($i = 0; $i < count($array); $i++) {
                $with_spaces = jsjobs::tagfillin($array[$i]);
                $anchor .= '<a title="tags" class="jsjb-jm-resume-tag-icon" href="' . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'resumes', 'tags'=>$with_spaces)) . '"><i class="fa fa-tag tag" aria-hidden="true"></i>' . __($array[$i], 'js-jobs') . '</a>';
            }
        }else{
            $anchor .= '<div id="jsresume-tags-wrapper">';
            $anchor .= '<span class="jsresume-tags-title">' . __('Tags', 'js-jobs') . '</span>';
            $anchor .= '<div class="tags-wrapper-border">';
            for ($i = 0; $i < count($array); $i++) {
                $with_spaces = jsjobs::tagfillin($array[$i]);
                $anchor .= '<a class="jsjob_tags_a" href="' . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'resumes', 'tags'=>$with_spaces)) . '">' . __($array[$i], 'js-jobs') . '</a>';
            }
            $anchor .= '</div>';
            $anchor .= '</div>';
        }
        return $anchor;
    }

}

?>
