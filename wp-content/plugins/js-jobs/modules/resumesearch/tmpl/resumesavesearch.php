<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
$msgkey = JSJOBSincluder::getJSModel('resumesearch')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(jsjobs::$_path . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    ?>
    <div id="jsjobs-wrapper">
        <div class="page_heading"><?php echo __('Saved Searches', 'js-jobs'); ?></div>
        <?php
        if (!empty(jsjobs::$_data[0])) {
            $dateformat = jsjobs::$_configuration['date_format'];
            foreach (jsjobs::$_data[0] AS $jobsearch) {
                ?>
                <div class="search-wrapper-content-data">
                    <div class="data-left">
                        <div class="data-upper">
                            <span class="upper-app-title"> <?php echo $jobsearch->searchname ?> </span><span class="datecreated"><?php echo __('Created', 'js-jobs') . ':&nbsp;' . date_i18n($dateformat, strtotime($jobsearch->created)); ?></span>
                        </div>
                    </div>
                    <div class="data-icons">
                        <a href="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'resumes', 'search'=>str_replace(' ', '-', $jobsearch->searchname) . '-' . $jobsearch->id, 'jsjobspageid'=>jsjobs::getPageid())); ?>"><img class="icon-img" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/fe-view.png" alt="<?php echo __('View', 'js-jobs'); ?>" title="<?php echo __('View', 'js-jobs'); ?>"/></a>
                        <a href="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'resumesearch', 'task'=>'removeSavedSearch', 'action'=>'jsjobtask', 'jsjobsid'=>$jobsearch->id, 'jsjobspageid'=>jsjobs::getPageid())); ?>" onclick="return confirmdelete('<?php echo __('Are you sure to delete','js-jobs').' ?'; ?>');"><img class="icon-img" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/fe-force-delete.png" alt="<?php echo __('Delete', 'js-jobs'); ?>" title="<?php echo __('Delete', 'js-jobs'); ?>"/></a>
                    </div>
                </div>
                <?php
            }
            if (jsjobs::$_data[1]) {
                echo '<div id="jsjobs-pagination">' . jsjobs::$_data[1] . '</div>';
            }
            ?>
        </div>
        <?php
    } else {
        $msg = __('No record found','js-jobs');
        echo JSJOBSlayout::getNoRecordFound($msg);
    }
}else{
    echo jsjobs::$_error_flag_message;
}
?>
