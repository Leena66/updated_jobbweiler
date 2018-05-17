 <?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jsjob-res-tables', jsjobs::$_pluginpath . 'includes/js/responsivetable.js');
?>
<script type="text/javascript">
    function resetFrom() {
        document.getElementById('rangestart').value = '';
        document.getElementById('rangeend').value = '';
        document.getElementById('status').value = '';
        document.getElementById('jsjobsform').submit();
    }
</script>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <?php 
    $msgkey = JSJOBSincluder::getJSModel('salaryrange')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey);
    ?>
    <span class="js-admin-title">
        <a href="<?php echo admin_url('admin.php?page=jsjobs'); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Salary Range', 'js-jobs') ?>
        <a class="js-button-link button" href="<?php echo admin_url('admin.php?page=jsjobs_salaryrange&jsjobslt=formsalaryrange'); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/add_icon.png" /><?php echo __('Add New','js-jobs') .'&nbsp;'. __('Salary Range', 'js-jobs') ?></a>
    </span>
    <div class="page-actions js-row no-margin">
        <a class="js-bulk-link button multioperation" message="<?php echo JSJOBSMessages::getMSelectionEMessage(); ?>" data-for="publish" href="#"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/publish-icon.png" /><?php echo __('Publish', 'js-jobs') ?></a>
        <a class="js-bulk-link button multioperation" message="<?php echo JSJOBSMessages::getMSelectionEMessage(); ?>" data-for="unpublish" href="#"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/unbuplish.png" /><?php echo __('Unpublish', 'js-jobs') ?></a>
        <a class="js-bulk-link button multioperation" message="<?php echo JSJOBSMessages::getMSelectionEMessage(); ?>" confirmmessage="<?php echo __('Are you sure to delete', 'js-jobs') .' ?'; ?>" data-for="remove" href="#"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/delete-icon.png" /><?php echo __('Delete', 'js-jobs') ?></a>
    </div>
    <form class="js-filter-form" name="jsjobsform" id="jsjobsform" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_salaryrange"); ?>">
        <?php echo JSJOBSformfield::text('rangestart', jsjobs::$_data['filter']['rangestart'], array('class' => 'inputbox', 'placeholder' => __('Salary Range Start', 'js-jobs'))); ?>
        <?php echo JSJOBSformfield::text('rangeend', jsjobs::$_data['filter']['rangeend'], array('class' => 'inputbox', 'placeholder' => __('Salary Range End', 'js-jobs'))); ?>
        <?php echo JSJOBSformfield::select('status', JSJOBSincluder::getJSModel('common')->getstatus(), is_numeric(jsjobs::$_data['filter']['status']) ? jsjobs::$_data['filter']['status'] : '', __('Select Status', 'js-jobs'), array('class' => 'inputbox')); ?>
        <?php echo JSJOBSformfield::hidden('JSJOBS_form_search', 'JSJOBS_SEARCH'); ?>
        <div class="filter-bottom-button">
            <?php echo JSJOBSformfield::submitbutton('btnsubmit', __('Search', 'js-jobs'), array('class' => 'button')); ?>
            <?php echo JSJOBSformfield::button('reset', __('Reset', 'js-jobs'), array('class' => 'button', 'onclick' => 'resetFrom();')); ?>
        </div>
    </form>
    <?php
    if (!empty(jsjobs::$_data[0])) {
        ?>
        <form id="jsjobs-list-form" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_salaryrange"); ?>">
            <table id="js-table">
                <thead>
                    <tr>
                        <th class="grid"><input type="checkbox" name="selectall" id="selectall" value=""></th>
                        <th class="left-row"><?php echo __('Salary Range', 'js-jobs'); ?></th>
                        <th class="centered"><?php echo __('Default', 'js-jobs'); ?></th>
                        <th class="centered"><?php echo __('Published', 'js-jobs'); ?></th>
                        <th class="centered"><?php echo __('Ordering', 'js-jobs'); ?></th>
                        <th class="action"><?php echo __('Action', 'js-jobs'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $pagenum = JSJOBSrequest::getVar('pagenum', 'get', 1);
                    $pageid = ($pagenum > 1) ? '&pagenum=' . $pagenum : '';
                    $islastordershow = JSJOBSpagination::isLastOrdering(jsjobs::$_data['total'], $pagenum);
                    for ($i = 0, $n = count(jsjobs::$_data[0]); $i < $n; $i++) {
                        $row = jsjobs::$_data[0][$i];
                        ?>
                        <tr valign="top">
                            <td class="grid-rows">
                                <input type="checkbox" class="jsjobs-cb" id="jsjobs-cb" name="jsjobs-cb[]" value="<?php echo $row->id; ?>" />
                            </td>
                            <td class="left-row">
                                <a href="<?php echo admin_url('admin.php?page=jsjobs_salaryrange&jsjobslt=formsalaryrange&jsjobsid='.$row->id); ?>">
                                    <?php echo $row->rangestart . ' - ' . $row->rangeend; ?>
                                </a>
                            </td>
                            <td>
                                <?php if ($row->isdefault == 1) { ?> 
                                    <img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/default.png" alt="Default" />
                                <?php } else { ?>
                                    <a href="<?php echo admin_url('admin.php?page=jsjobs_common&task=makedefault&action=jsjobtask&for=salaryrange&id='.$row->id.$pageid); ?>">
                                        <img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/notdefault.png" alt="Not Default" />
                                    </a>
        <?php } ?>	
                            </td>
                            <td>
                                   <?php if ($row->status == 1) { ?> 
                                    <a href="<?php echo admin_url('admin.php?page=jsjobs_salaryrange&task=unpublish&action=jsjobtask&jsjobs-cb[]='.$row->id.$pageid); ?>">
                                        <img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/yes.png" border="0" alt="<?php echo __('Published'); ?>" /></a>
                                   <?php } else { ?>
                                    <a href="<?php echo admin_url('admin.php?page=jsjobs_salaryrange&task=publish&action=jsjobtask&jsjobs-cb[]='.$row->id.$pageid); ?>">
                                        <img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/no.png" border="0" alt="<?php echo __('Not Published'); ?>" /></a>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if ($i != 0 || $pagenum > 1) { ?>
                                    <a href="<?php echo admin_url('admin.php?page=jsjobs_common&task=defaultorderingup&action=jsjobtask&for=salaryrange&id='.$row->id.$pageid); ?>">
                                        <img class="ordering-up" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/uparrow.png" border="0" alt="Order Up" /></a>
                                   <?php } else echo ''; ?>
                                <?php echo $row->ordering; ?>
                                <?php if ($i < $n - 1 || $islastordershow) { ?>
                                    <a href="<?php echo admin_url('admin.php?page=jsjobs_common&task=defaultorderingdown&action=jsjobtask&for=salaryrange&id='.$row->id.$pageid); ?>">
                                        <img class="ordering-down" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/downarrow.png" alt="Order Down" /></a>
        <?php } ?>	
                            </td>
                            <td class="action">
                                <a href="<?php echo admin_url('admin.php?page=jsjobs_salaryrange&jsjobslt=formsalaryrange&jsjobsid='.$row->id); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/edit.png" title="<?php echo __('Edit', 'js-jobs'); ?>"></a>
                                <a href="<?php echo admin_url('admin.php?page=jsjobs_salaryrange&task=remove&action=jsjobtask&jsjobs-cb[]='.$row->id); ?>" onclick="return confirmdelete('<?php echo __('Are you sure to delete', 'js-jobs').' ?'; ?>');"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/remove.png" title="<?php echo __('Delete', 'js-jobs'); ?>"></a>
                            </td>
                        </tr>
                <?php
            }
            ?>
                </tbody>
            </table>
        <?php echo JSJOBSformfield::hidden('action', 'salaryrange_remove'); ?>
        <?php echo JSJOBSformfield::hidden('pagenum', ($pagenum > 1) ? $pagenum : ''); ?>
        <?php echo JSJOBSformfield::hidden('task', ''); ?>
        <?php echo JSJOBSformfield::hidden('form_request', 'jsjobs'); ?>
        </form>
        <?php
        if (jsjobs::$_data[1]) {
            echo '<div class="tablenav"><div class="tablenav-pages">' . jsjobs::$_data[1] . '</div></div>';
        }
    } else {
        $msg = __('No record found','js-jobs');
        $link[] = array(
                    'link' => 'admin.php?page=jsjobs_salaryrange&jsjobslt=formsalaryrange',
                    'text' => __('Add New','js-jobs') .'&nbsp;'. __('Salary Range','js-jobs')
                );
        echo JSJOBSlayout::getNoRecordFound($msg,$link);
    }
    ?>
</div>
</div>