<?php $searchjobtag = array((object) array('id' => 1, 'text' => __('Top left', 'js-jobs'))
                    , (object) array('id' => 2, 'text' => __('Top right', 'js-jobs'))
                    , (object) array('id' => 3, 'text' => __('Middle left', 'js-jobs'))
                    , (object) array('id' => 4, 'text' => __('Middle right', 'js-jobs'))
                    , (object) array('id' => 5, 'text' => __('Bottom left', 'js-jobs'))
                    , (object) array('id' => 6, 'text' => __('Bottom right', 'js-jobs')));
$yesno = array((object) array('id' => 1, 'text' => __('Yes', 'js-jobs'))
                    , (object) array('id' => 0, 'text' => __('No', 'js-jobs')));
wp_enqueue_script('jsjob-commonjs', jsjobs::$_pluginpath . 'includes/js/radio.js');
if (!defined('ABSPATH')) die('Restricted Access'); ?>
<div id="jsjobsadmin-wrapper" class="post-installation">
    <div class="js-admin-title-installtion">
        <img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/setting-icon.png" />
        <?php echo __('JS Jobs Settings','js-jobs'); ?>
    </div>
    <div class="post-installtion-content-header">
        <div class="update-header-img step-4">
            <div class="header-parts first-part">
                <img class="start" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/header/green.png" />
                <span class="text"><?php echo __('General', 'js-jobs'); ?></span>
                <span class="text-no">1</span>
                <img class="end" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/header/green-2.png" />
            </div>
            <div class="header-parts second-part">
                <img class="start" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/header/green.png" />
                <span class="text"><?php echo __('Employer', 'js-jobs'); ?></span>
                <span class="text-no">2</span>
                <img class="end" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/header/green-2.png" />
            </div>
            <div class="header-parts third-part">
                <img class="start" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/header/green.png" />
                <span class="text"><?php echo __('Job seeker', 'js-jobs'); ?></span>
                <span class="text-no">3</span>
                <img class="end" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/header/green-2.png" />
            </div>
            <div class="header-parts fourth-part">
                <img class="start" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/header/orange.png" />
                <span class="text"><?php echo __('Sample data', 'js-jobs'); ?></span>
                <span class="text-no">4</span>
                <img class="end" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/header/orange-2.png" />
            </div>
        </div>
    </div>
    
    <span class="heading-post-ins"><?php echo __('Sample Data','js-jobs');?></span>
    <div class="post-installtion-content">
        <form id="jsjobs-form-ins" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_postinstallation&task=savesampledata"); ?>">
            <div class="pic-config">
                <div class="title"> 
                    <?php echo __('Insert Sample Data','js-jobs');?>: &nbsp;
                </div>
                <div class="field"> 
                    <?php echo JSJOBSformfield::select('sampledata', $yesno,1,'',array('class' => 'inputbox')); ?>
                </div>
                <div class="sample-data-heading">
                   <?php echo  __('Job Seeker','js-jobs'); ?>
                </div>
                <div class="sample-data-text">
                    <div class="name-part">
                        <?php echo __('User Name','js-jobs'); ?>:&nbsp; jsjobs_jobseeker
                    </div>
                    <?php echo __('Password','js-jobs').':&nbsp;demo';                   ?>
                </div>
                <div class="sample-data-heading">
                   <?php echo  __('Employer','js-jobs'); ?>
                </div>
                <div class="sample-data-text bottom-border">
                    <div class="name-part">
                        <?php echo __('User Name','js-jobs'); ?>:&nbsp; jsjobs_employer
                    </div>
                    <?php echo __('Password','js-jobs').': &nbsp;demo';?>
                </div>
            </div>
        <?php
            $theme = wp_get_theme();
            if($theme != 'Job Manager'){ 
                ?>
            <div class="pic-config">
                <div class="title"> 
                    <?php echo __('Job Seeker Menu','js-jobs');?>: &nbsp;
                </div>
                <div class="field"> 
                    <?php echo JSJOBSformfield::select('jsmenu', $yesno,1,'',array('class' => 'inputbox')); ?>
                </div>
            </div>
            <div class="pic-config">
                <div class="title"> 
                    <?php echo __('Employer Menu','js-jobs');?>: &nbsp;
                </div>
                <div class="field"> 
                    <?php echo JSJOBSformfield::select('empmenu', $yesno,1,'',array('class' => 'inputbox')); ?>
                </div>
            </div>
            <?php }else{ ?>
                <div class="pic-config temp-demo-data">
                    <div class="title"> 
                        <?php echo __('Job Manager Sample Data','js-jobs');?>: &nbsp;
                    </div>
                    <div class="field"> 
                        <?php echo JSJOBSformfield::select('temp_data', $yesno,1,'',array('class' => 'inputbox')); ?>
                    </div>
                    <div class="desc"><?php echo __('if yes is selected then pages and menus of job manager template will be cretaed and published.','js-jobs');?>. </div>
                </div>
            <?php }?>
           <div class="pic-button-part">
                <a class="next-step finish" href="#" onclick="document.getElementById('jsjobs-form-ins').submit();">
                    <?php echo __('Finish','js-jobs'); ?>
                    <img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/finsh-icon.png" />
                </a>
                <a class="back" href="<?php echo admin_url('admin.php?page=jsjobs_postinstallation&jsjobslt=stepthree'); ?>"> 
                    <img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/back-arrow.png" />
                    <?php echo __('Back','js-jobs'); ?>
                </a>
            </div>
            <?php echo JSJOBSformfield::hidden('form_request', 'jsjobs'); ?>
            <?php echo JSJOBSformfield::hidden('step', 3); ?>
        </form>
    </div>
    <div class="close-button-bottom">
        <a href="<?php echo admin_url('admin.php?page=jsjobs'); ?>" class="close-button">
            <img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/close-icon.png" />
            <?php echo __('Close','js-jobs'); ?>
        </a>
    </div>
</div>
