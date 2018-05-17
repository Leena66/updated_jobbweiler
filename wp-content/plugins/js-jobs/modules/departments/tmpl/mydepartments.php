<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
$msgkey = JSJOBSincluder::getJSModel('departments')->getMessagekey();

JSJOBSMessages::getLayoutMessage($msgkey);
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(jsjobs::$_path . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    ?>
    <div id="jsjobs-wrapper">
        <div class="page_heading">
            <?php echo __('My Departments', 'js-jobs'); ?>
            <a class="additem" href="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'departments', 'jsjobslt'=>'adddepartment')); ?>"><?php echo __('Add New','js-jobs') .'&nbsp;'. __('Department', 'js-jobs'); ?></a>
        </div>
        <?php
        if (isset(jsjobs::$_data[0]) && !empty(jsjobs::$_data[0])) {
            $dateformat = jsjobs::$_configuration['date_format'];
            foreach (jsjobs::$_data[0] AS $dept) {
                ?>
                <div class="department-content-data">
                    <div class="data-left">
                        <div class="data-upper">
                            <span class="upper-app-title"> <?php echo $dept->name; ?> </span><?php echo __('Created', 'js-jobs') . ':&nbsp;' . date_i18n($dateformat, strtotime($dept->created)); ?>
                        </div>
                        <div class="data-lower">
                            <span class="lower-text1">
                                <span class="title"><?php echo __('Company', 'js-jobs'); ?></span>:&nbsp;<a href="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$dept->companyid)); ?>"><?php echo $dept->companyname; ?></a>
                            </span>
                            <span class="lower-text1">
                                <span class="title">
                                    <?php
                                    if($dept->status == 1){
                                        $color = "green";                        
                                        $statusCheck = __('Approved','js-jobs');
                                    }elseif($dept->status == 0){
                                        $color = "orange";                        
                                        $statusCheck = __('Pending','js-jobs');
                                    }else{
                                        $color = "red";                        
                                        $statusCheck = __('Rejected','js-jobs');
                                    }
                                    echo __('Status', 'js-jobs').': ';
                                    ?>
                                </span>
                                    <span class="get-status <?php echo $color; ?>"><?php echo $statusCheck; ?></span>
                            </span>
                        </div>
                    </div>
                    <div class="data-icons">
                        <a href="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'departments', 'jsjobslt'=>'adddepartment', 'jsjobsid'=>$dept->id)); ?>"><img class="icon-img" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/fe-edit.png" alt="<?php echo __('Edit', 'js-jobs'); ?>" title="<?php echo __('Edit', 'js-jobs'); ?>"/></a>
                        <a href="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'departments', 'jsjobslt'=>'viewdepartment', 'jsjobsid'=>$dept->id)); ?>"><img class="icon-img" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/fe-view.png" alt="<?php echo __('View', 'js-jobs'); ?>" title="<?php echo __('View', 'js-jobs'); ?>"/></a>
                        <a href="<?php echo jsjobs::makeUrl(array('jsjobspageid'=>jsjobs::getPageid(),'jsjobsme'=>'departments', 'task'=>'remove', 'action'=>'jsjobtask', 'jsjobs-cb[]'=>$dept->id)); ?>"onclick="return confirmdelete('<?php echo __('Are you sure to delete','js-jobs').' ?'; ?>');"><img class="icon-img" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/fe-force-delete.png" alt="<?php echo __('Delete', 'js-jobs'); ?>" title="<?php echo __('Delete', 'js-jobs'); ?>"/></a>
                    </div>
                </div>
                <?php
            }
            if (jsjobs::$_data[1]) {
                echo '<div id="jsjobs-pagination">' . jsjobs::$_data[1] . '</div>';
            }
        } else {
            $msg = __('No record found','js-jobs');
            $link[] = array(
                        'link' => jsjobs::makeUrl(array('jsjobsme'=>'departments', 'jsjobslt'=>'adddepartment')),
                        'text' => __('Add New','js-jobs') .'&nbsp;'. __('Department', 'js-jobs')
                    );
            JSJOBSlayout::getNoRecordFound();
        }
        ?>
    </div>
<?php 
}else{
    echo jsjobs::$_error_flag_message;
}
?>