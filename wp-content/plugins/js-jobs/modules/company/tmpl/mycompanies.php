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
        <div class="page_heading">
            <?php echo __('My Companies', 'js-jobs'); ?>
            <a class="additem" href="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'addcompany')); ?>"><?php echo __('Add New','js-jobs') .'&nbsp;'. __('Company', 'js-jobs'); ?></a>
        </div>
        <?php
        if (isset(jsjobs::$_data[0]) && !empty(jsjobs::$_data[0])) {
            foreach (jsjobs::$_data[0] AS $company) {
                if ($company->logofilename != "") {
                    $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                    $wpdir = wp_upload_dir();
                    $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $company->id . '/logo/' . $company->logofilename;
                } else {
                    $path = jsjobs::$_pluginpath . '/includes/images/default_logo.png';
                }
                ?>
                <div class="company-wrapper">
                    <div class="company-upper-wrapper object_<?php echo $company->id; ?>" data-boxid="company_<?php echo $company->id; ?>">
                        <div class="company-img">
                            <a href="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$company->aliasid)); ?>">
                                <img src="<?php echo $path; ?>">
                            </a>
                        </div>
                        <div class="company-detail">
                            <div class="company-detail-upper">
                                <div class="company-detail-upper-left  item-title"> 
                                <?php if ($config_array['comp_name']) { ?>
                                            <span class="company-title">
                                                <a href="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$company->aliasid)); ?>">
                                                    <?php echo $company->name; ?>
                                                 </a>
                                            </span><?php 
                                        } 
                                    $dateformat = jsjobs::$_configuration['date_format'];
                                    $curdate = date_i18n($dateformat);
                                    ?>
                                </div>
                                <div class="company-detail-upper-right">
                                    <span class="company-date"><?php echo __('Created', 'js-jobs') . ':&nbsp;' . date_i18n($dateformat, strtotime($company->created)); ?></span>
                                </div>
                            </div>
                            <div class="company-detail-lower">
                                <div class="js-col-xs-12 js-col-sm-6 js-col-md-4 company-detail-lower-left">
                                    <span class="js-text">
                                        <?php 
                                        if(!isset(jsjobs::$_data['fields']['category'])){
                                            jsjobs::$_data['fields']['category'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('category',1);
                                        }
                                        echo __(jsjobs::$_data['fields']['category'], 'js-jobs') . ": "; 
                                    ?>
                                    </span>
                                    <span class="js-value">
                                        <?php echo __($company->cat_title,'js-jobs'); ?>
                                    </span>
                                </div>
                                <div class="js-col-xs-12 js-col-sm-6 js-col-md-4 company-detail-lower-left">
                                    <span class="js-text"><?php echo __('Status', 'js-jobs') . ':&nbsp;'; ?></span>                                    
                                    <?php
                                    $color = ($company->status == 1) ? "green" : "red";
                                    if ($company->status == 1) {
                                        $statusCheck = __('Approved', 'js-jobs');
                                    } elseif ($company->status == 0) {
                                        $statusCheck = __('Waiting for approval', 'js-jobs');
                                    } else {
                                        $statusCheck = __('Rejected', 'js-jobs');
                                    }
                                    ?>
                                    <span class="js-value get-status<?php echo $color; ?>"><?php echo $statusCheck; ?></span>
                                </div>
                                <?php
                                $customfields = JSJOBSincluder::getObjectClass('customfields')->userFieldsData(1, 1);
                                foreach ($customfields as $field) {
                                    echo JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 8, $company->params);
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="company-lower-wrapper">
                        <div class="company-lower-wrapper-left"><?php 
                            if($config_array['comp_city']) { ?>
                                <span class="company-address"><img id=location-img  src="<?php echo jsjobs::$_pluginpath; ?>includes/images/location.png"><?php echo $company->location; ?></span>  <?php
                            } ?>
                        </div>
                        <div class="company-lower-wrapper-right">
                            <?php
                                if($company->status == 1){ ?>
                                    <div class="button edit-button">
                                        <a href="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'addcompany', 'jsjobsid'=>$company->id)); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/fe-edit.png" title="<?php echo __('Edit', 'js-jobs'); ?>"></a>
                                    </div>                                
                            <div class="button search-button">
                                        <a href="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$company->aliasid)); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/fe-view.png" title="<?php echo __('View', 'js-jobs'); ?>"></a>
                            </div>
                            <div class="button delete-button">
                                        <a href="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'company', 'task'=>'remove', 'jsjobs-cb[]'=>$company->id, 'action'=>'jsjobtask','jsjobspageid'=>jsjobs::getPageid())); ?>" onclick="return confirmdelete('<?php echo __('Are you sure to delete','js-jobs').' ?'; ?>');"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/fe-force-delete.png" title="<?php echo __('Delete', 'js-jobs'); ?>"></a>
                            </div>
                                <?php
                                }elseif($company->status == 0) { ?>
                                    <div class="big-lower-data-text pending"><img id="pending-img"  src="<?php echo jsjobs::$_pluginpath; ?>includes/images/pending-corner.png"/><span><?php echo __('Waiting for approval', 'js-jobs'); ?></span></div>
                                <?php
                                }elseif($company->status == -1){ ?>
                                    <div class="big-lower-data-text reject"><img id="pending-img"  src="<?php echo jsjobs::$_pluginpath; ?>includes/images/reject-cornor.png"/><span><?php echo __('Rejected', 'js-jobs'); ?></span></div>
                                <?php
                                }
                                ?>
                        </div>
                    </div>
                </div> 
                <?php
            }
            if (jsjobs::$_data[1]) {
                echo '<div id="jsjobs-pagination">' . jsjobs::$_data[1] . '</div>';
            }
        } else {
            $msg = __('No record found','js-jobs');
            $linkcompany[] = array(
                        'link' => jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'addcompany')),
                        'text' => __('Add New','js-jobs') .'&nbsp;'. __('Company', 'js-jobs')
                    );
            JSJOBSlayout::getNoRecordFound($msg,$linkcompany);
        }
        ?>
    </div>
<?php 
}else{
    echo jsjobs::$_error_flag_message;
} 
?>