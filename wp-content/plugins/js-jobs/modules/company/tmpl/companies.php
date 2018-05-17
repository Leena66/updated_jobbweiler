<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

$msgkey = JSJOBSincluder::getJSModel('company')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(jsjobs::$_path . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    $config_array = jsjobs::$_data['config'];
    ?>
    <div id="jsjobs-wrapper">
        <div class="page_heading"><?php echo __('Companies', 'js-jobs'); ?></div>
        <div class="companies filterwrapper">
            <form method="POST" action="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'companies')); ?>">
                <?php echo JSJOBSformfield::text('jsjobs-company', jsjobs::$_data['filter']['jsjobs-company'], array('class' => 'filter-buttons', 'placeholder' => __('Company name', 'js-jobs'))); ?>
                <span class="filterlocation">
                    <img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/location-icon.png" />
                    <input type="text" class="filter-buttons" id="jsjobs-city" name="jsjobs-city">                  
                </span>
                <?php echo JSJOBSformfield::submitbutton('jsjobs-go', __('Search', 'js-jobs'), array('class' => 'button', 'onclick' => 'return addSpaces();')); ?>
                <?php echo JSJOBSformfield::submitbutton('jsjobs-reset', __('Reset', 'js-jobs'), array('class' => 'button', 'onclick' => 'return resetFrom();')); ?>
                <?php echo "<input type='hidden' name='page_id' value='" . jsjobs::getPageId() . "'/>"; ?>
                <?php echo "<input type='hidden' name='jsjobsme' value='company'/>"; ?>
                <?php echo "<input type='hidden' name='jsjobslt' value='companies'/>"; ?>
                <?php echo "<input type='hidden' name='JSJOBS_form_search' value='JSJOBS_SEARCH'/>"; ?>
            </form>
        </div>
        <div id="companies-wrapper">
            <?php
            if (isset(jsjobs::$_data[0]) && !empty(jsjobs::$_data[0])) {
                foreach (jsjobs::$_data[0] AS $companies) {
                    if ($companies->logofilename != "") {
                        $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                        $wpdir = wp_upload_dir();
                        $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $companies->id . '/logo/' . $companies->logofilename;
                    } else {
                        $path = jsjobs::$_pluginpath . '/includes/images/default_logo.png';
                    }
                    ?>
                    <div class="view-companies-wrapper">
                        <div class="company-upper-wrapper">
                            <div class="company-img">
                                <a href="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$companies->aliasid)); ?>">
                                    <img src="<?php echo $path; ?>">
                                </a>
                            </div>
                            <div class="company-detail">
                                <div class="company-detail-upper">  <?php
                                    if($config_array['comp_name'] == 1){ ?>
                                        <span class="company-title"><a href="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$companies->aliasid)); ?>"><?php echo $companies->name; ?></a></span><?php
                                    }
                                    $dateformat = jsjobs::$_configuration['date_format'];
                                    $curdate = date_i18n($dateformat);
                                    ?>
                                </div>
                                <div class="js-col-xs-12 js-col-sm-6 js-col-md-4 company-detail-lower"><?php
                                    if($config_array['comp_show_url'] == 1){ ?>
                                        <span class="js-get-title">
                                        <?php 
                                            if(!isset(jsjobs::$_data['fields']['url'])){
                                                jsjobs::$_data['fields']['url'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('url',1);
                                            }
                                            echo __(jsjobs::$_data['fields']['url'], 'js-jobs') . ':&nbsp;'; ?>
                                        </span>
                                        <a class="get-website-url" target="_blank" href="<?php echo $companies->url; ?>"><?php echo $companies->url; ?></a><?php
                                    } ?>                                            
                                </div>
                                <?php
                                $customfields = JSJOBSincluder::getObjectClass('customfields')->userFieldsData(1, 1);
                                foreach ($customfields as $field) {
                                    echo JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 4, $companies->params);
                                }
                                ?>
                            </div>
                        </div>
                        <div class="company-lower-wrapper">
                            <div class="company-lower-wrapper-left">
                                <?php if ($companies->location != '' && $config_array['comp_city'] == 1) { ?>
                                    <span class="company-address"><img id=location-img  src="<?php echo jsjobs::$_pluginpath; ?>includes/images/location.png"/><?php echo $companies->location; ?></span>
                                <?php } ?>
                            </div>
                            <div class="company-lower-wrapper-right">   
                                <a class="viewall-jobs" href="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'jobs', 'company'=>$companies->aliasid)); ?>"><?php echo __('View all jobs', 'js-jobs'); ?> </a>
                            </div>
                        </div>
                    </div>              
                    <?php
                }if (jsjobs::$_data[1]) {
                    echo '<div id="jsjobs-pagination">' . jsjobs::$_data[1] . '</div>';
                }
            } else {
                $msg = __('No record found','js-jobs');
                JSJOBSlayout::getNoRecordFound($msg);
            }
            ?>
        </div>      
    </div>
<?php 
}else{
    echo jsjobs::$_error_flag_message;
} 
?>
