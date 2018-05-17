<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jsjob-res-tables', jsjobs::$_pluginpath . 'includes/js/responsivetable.js');
?>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <?php 
    $msgkey = JSJOBSincluder::getJSModel('systemerror')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey);
    ?>
    <span class="js-admin-title">
        <a href="<?php echo admin_url('admin.php?page=jsjobs'); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Error Log', 'js-jobs'); ?>
    </span>
    <?php
    if (!empty(jsjobs::$_data[0])) {
        ?>
        <table id="js-table">
            <thead>
                <tr>
                    <th class="left-row"><?php echo __('Error', 'js-jobs'); ?></th>
                    <th ><?php echo __('View', 'js-jobs'); ?></th>
                    <th ><?php echo __('Date', 'js-jobs'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach (jsjobs::$_data[0] AS $systemerror) {
                    $isview = ($systemerror->isview == 1) ? 'no.png' : 'yes.png';
                    ?>
                    <tr valign="top">
                        <td class="left-row">
                            <?php echo $systemerror->error; ?>
                        </td>
                        <td>
                            <img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/<?php echo $isview; ?>" />
                        </td>
                        <td>
                            <?php 
                                echo date_i18n(jsjobs::$_configuration['date_format'], strtotime($systemerror->created)); ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>

        <?php
        if (jsjobs::$_data[1]) {
            echo '<div class="tablenav"><div class="tablenav-pages">' . jsjobs::$_data[1] . '</div></div>';
        }
    } else {
        $msg = __('No record found','js-jobs');
        echo JSJOBSlayout::getNoRecordFound($msg);
    }
    ?>
</div>
</div>

