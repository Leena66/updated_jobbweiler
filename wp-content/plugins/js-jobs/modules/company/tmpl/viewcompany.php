<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

$msgkey = JSJOBSincluder::getJSModel('company')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(jsjobs::$_path . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    function getDataRow($title, $value) {
        $html = '<div class="data-row">
                    <span class="title">' . $title . ':&nbsp;</span>
                    ' . $value . '
                </div>';
        return $html;
    }

    $data_class = (isset(jsjobs::$_data[2]['logo'])) ? 'two_column' : 'one_column';
    $config_array = jsjobs::$_data['config'];
    ?>
    <div id="jsjobs-wrapper">
        <div class="page_heading"><?php echo __('Company Information', 'js-jobs'); ?></div>
        <div class="viewcompany-upper-wrapper">
            <?php if (isset(jsjobs::$_data[2]['name']) && $config_array['comp_name'] == 1) { ?>
                <div class="viewcompnay-name"><?php echo jsjobs::$_data[0]->name; ?></div>
            <?php } ?>
            <?php if ($data_class == 'one_column') { ?>
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
            <?php } ?>
            <?php if (isset(jsjobs::$_data[2]['url']) && jsjobs::$_data['companycontactdetail'] == true && $config_array['comp_show_url'] == 1) { ?>
                <div class="viewcompnay-url"><a href="<?php echo jsjobs::$_data[0]->url; ?>" target="_blank"><?php echo jsjobs::$_data[0]->url; ?></a></div>
            <?php } ?>
            <?php if (isset(jsjobs::$_data[2]['city']) && $config_array['comp_city'] == 1) { ?>
                <div class="viewcompnay-city">
                    <?php echo jsjobs::$_data[0]->location; ?>
                </div>
            <?php } ?>
            <?php if (isset(jsjobs::$_data[2]['description'])) { ?>
                <div class="viewcompany-description"><?php echo jsjobs::$_data[0]->description; ?></div>
            <?php } ?>
        </div>
        <div class="viewcompany-lower-wrapper">
            <?php if (isset(jsjobs::$_data[2]['logo'])) { ?>
                <div class="viewcompany-logo">
                    <?php
                    $logopath = jsjobs::$_pluginpath . "includes/images/default_logo.png";
                    if (jsjobs::$_data[0]->logofilename) {
                        $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                        $wpdir = wp_upload_dir();
                        $logopath = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . jsjobs::$_data[0]->id . '/logo/' . jsjobs::$_data[0]->logofilename;
                    }
                    ?>
                    <img src="<?php echo $logopath; ?>" class="viewcompany-logo" />
                </div>
                <?php if ($data_class == 'two_column') { ?>
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
                <?php } ?>
            <?php } ?>
            <div class="viewcompany-data <?php echo $data_class; ?>">
                <?php
                $dateformat = jsjobs::$_configuration['date_format'];
                foreach (jsjobs::$_data[2] AS $key => $val) {
                    switch ($key) {
                        case 'contactname':
                            if (jsjobs::$_data['companycontactdetail'] == true)
                                if ($config_array['comp_name'] == 1)
                                    echo getDataRow(__($val, 'js-jobs'), jsjobs::$_data[0]->contactname);
                            break;
                        case 'contactemail':
                            if (jsjobs::$_data['companycontactdetail'] == true)
                                if ($config_array['comp_email_address'] == 1)
                                    echo getDataRow(__($val, 'js-jobs'), jsjobs::$_data[0]->contactemail);
                            break;
                        case 'contactphone':
                            if (jsjobs::$_data['companycontactdetail'] == true)
                                echo getDataRow(__($val, 'js-jobs'), jsjobs::$_data[0]->contactphone);
                            break;
                        case 'contactfax':
                            if (jsjobs::$_data['companycontactdetail'] == true)
                                echo getDataRow(__($val, 'js-jobs'), jsjobs::$_data[0]->companyfax);
                            break;
                        case 'category':
                            echo getDataRow(__($val, 'js-jobs'), __(jsjobs::$_data[0]->cat_title,'js-jobs'));
                            break;
                        case 'income':
                            echo getDataRow(__($val, 'js-jobs'), jsjobs::$_data[0]->income);
                            break;
                        case 'since':
                            $sincedate = date_i18n($dateformat, strtotime(jsjobs::$_data[0]->since));
                            if(strpos($sincedate , '1970') !== false){
								$sincedate = "";
							}
							echo getDataRow(__($val, 'js-jobs'), $sincedate);
                            break;
                        case 'companysize':
                            echo getDataRow(__($val, 'js-jobs'), jsjobs::$_data[0]->companysize);
                            break;
                        case 'address1':
                            if (jsjobs::$_data['companycontactdetail'] == true)
                                echo getDataRow(__($val, 'js-jobs'), jsjobs::$_data[0]->address1);
                            break;
                        case 'zipcode':
                            if (jsjobs::$_data['companycontactdetail'] == true && $config_array['comp_zipcode'] == 1)
                                echo getDataRow(__($val, 'js-jobs'), jsjobs::$_data[0]->zipcode);
                            break;
                        case 'address2':
                            if (jsjobs::$_data['companycontactdetail'] == true)
                                echo getDataRow(__($val, 'js-jobs'), jsjobs::$_data[0]->address2);
                            break;
                        default: // handle the user fields data
                            $customfields = JSJOBSincluder::getObjectClass('customfields')->userFieldsData(1);
                            foreach($customfields AS $field){                                
                                if($key == $field->field){
                                    echo JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 5,jsjobs::$_data[0]->params);
                                }
                            }
                            
                            break;
                    }
                }
                ?>
            </div>
        </div>
        <?php if (JSJOBSincluder::getObjectClass('user')->isemployer() == 0) { ?>
            <div class="bottombutton">
            <?php 
                $compalias = jsjobs::$_data[0]->alias.'-'.jsjobs::$_data[0]->id;
            ?>
                <a href="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'jobs', 'company'=>$compalias)); ?>"><?php echo __('View all jobs', 'js-jobs'); ?></a>
            </div>
        <?php } ?>
    </div>
<?php 
}else{
    echo jsjobs::$_error_flag_message;
}
?>