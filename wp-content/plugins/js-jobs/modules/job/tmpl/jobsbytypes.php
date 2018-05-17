<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
$msgkey = JSJOBSincluder::getJSModel('job')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(jsjobs::$_path . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    ?>
    <div id="jsjobs-wrapper">
        <div class="page_heading"><?php echo __('Jobs By Types', 'js-jobs'); ?></div>
        <?php
        $number = jsjobs::$_data['config']['jobtype_per_row'];
        if ($number < 1 || $number > 100) {
            $number = 3; // by default set to 3
        }
        $width = 100 / $number;
        $count = 0;
        if (isset(jsjobs::$_data[0]) && !empty(jsjobs::$_data[0])) {
            foreach (jsjobs::$_data[0] AS $jobsBytype) {
                if (($count % $number) == 0) {
                    if ($count == 0)
                        echo '<div class="type-row-wrapper">';
                    else
                        echo '</div><div class="type-row-wrapper">';
                }
                ?>
                <div class="type-wrapper" style="width:<?php echo $width; ?>%;">
                    <a href="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'jobs', 'jobtype'=>$jobsBytype->alias)); ?>">
                        <div class="jobs-by-type-wrapper">
                            <span class="title"><?php echo __($jobsBytype->title,'js-jobs'); ?></span>
                        <?php if(jsjobs::$_data['config']['jobtype_numberofjobs']){ ?>
                            <span class="totat-jobs"><?php echo $jobsBytype->totaljobs; ?></span>
                        <?php } ?>
                        </div> 
                    </a>
                </div>
                <?php
                $count++;
            }
            echo '</div>';
        }
        else {
            echo JSJOBSlayout::getNoRecordFound();
            ?><?php }
        ?>
    </div>	
<?php 
}else{
    echo jsjobs::$_error_flag_message;
}
?>