<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<script type="text/javascript">
    function resetFrom() {
        document.getElementById('departmentname').value = '';
        document.getElementById('companyname').value = '';
        document.getElementById('status').value = '';
        document.getElementById('jsjobsform').submit();
    }
</script>
<?php wp_enqueue_script('jsjob-res-tables', jsjobs::$_pluginpath . 'includes/js/responsivetable.js'); ?>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <?php 
    $msgkey = JSJOBSincluder::getJSModel('departments')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey); 
    ?>
    <span class="js-admin-title">
        <a href="<?php echo admin_url('admin.php?page=jsjobs'); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Departments', 'js-jobs') ?>
        <a class="js-button-link button" href="<?php echo admin_url('admin.php?page=jsjobs_departments&jsjobslt=formdepartment'); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/add_icon.png" /><?php echo __('Add New','js-jobs') .'&nbsp;'. __('Department', 'js-jobs') ?></a>
    </span>
    <div class="page-actions js-row no-margin">
        <a class="js-bulk-link button multioperation" message="<?php echo JSJOBSMessages::getMSelectionEMessage(); ?>" confirmmessage="<?php echo __('Are you sure to delete', 'js-jobs') .' ?'; ?>"  data-for="remove" href="#"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/delete-icon.png" /><?php echo __('Delete', 'js-jobs') ?></a>
    </div>
    <?php
    $statuscombo = array();
    $statuscombo[] = (object) array('id' => '1', 'text' => __('Approved', 'js-jobs'));
    $statuscombo[] = (object) array('id' => '-1', 'text' => __('Rejected', 'js-jobs'));
    $cid = '';
    $check = '';
    if (isset($_SESSION['cid_departments'])) {
        $cid = $_SESSION['cid_departments'];
        $name = jsjobs::$_data[0]['companyname'];
        $check = "readonly value='$name'";
    }
    ?>
    <form class="js-filter-form" name="jsjobsform" id="jsjobsform" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_departments&jsjobslt=departments&companyid=" . $cid); ?>">
        <input name="departmentname" id="departmentname" type="text" placeholder="<?php echo __('Department', 'js-jobs'); ?>" value="<?php echo jsjobs::$_data['filter']['departmentname']; ?>" />
        <input name="companyname" id="companyname" type="text" <?php echo $check; ?> placeholder="<?php echo __('Company', 'js-jobs'); ?>" value="<?php echo jsjobs::$_data['filter']['companyname']; ?>" />
        <?php echo JSJOBSformfield::select('status', $statuscombo, is_numeric(jsjobs::$_data['filter']['status']) ? jsjobs::$_data['filter']['status'] : '', __('Select Status', 'js-jobs'), array('class' => 'inputbox')); ?>
        <?php echo JSJOBSformfield::hidden('JSJOBS_form_search', 'JSJOBS_SEARCH'); ?>
        <div class="filter-bottom-button">
            <input type="submit" class="button" value="<?php echo __('Search', 'js-jobs') ?>" name="btnsubmit"/>
            <input type="button" class="button" value="<?php echo __('Reset', 'js-jobs') ?>" onclick="resetFrom();" />
        </div>
    </form>
    <?php
    if (!empty(jsjobs::$_data[0]['department'])) {
        ?>  		
        <form id="jsjobs-list-form" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_departments"); ?>">
            <table id="js-table">
                <thead>
                    <tr>
                        <th class="grid"><input type="checkbox" name="selectall" id="selectall" value=""></th>
                        <th class="left-row"><?php echo __('Department', 'js-jobs'); ?></th>
                        <th ><?php echo __('Company', 'js-jobs'); ?></th>
                        <th><?php echo __('Created', 'js-jobs'); ?></th>
                        <th><?php echo __('Status', 'js-jobs'); ?></th>
                        <th class="action"><?php echo __('Action', 'js-jobs'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach (jsjobs::$_data[0]['department'] AS $department) {
                        ?>			
                        <tr>
                            <td class="grid-rows">
                                <input type="checkbox" class="jsjobs-cb" id="jsjobs-cb" name="jsjobs-cb[]" value="<?php echo $department->id; ?>" />
                            </td>
                            <td class="left-row">
                                <a href="<?php echo admin_url('admin.php?page=jsjobs_departments&jsjobslt=formdepartment&jsjobsid='.$department->id); ?>"><?php echo $department->name; ?></a>
                            </td>
                            <td><?php echo $department->companyname; ?></td>
                            <td><?php 
                                $dateformat = jsjobs::$_configuration['date_format'];
                                echo date_i18n($dateformat, strtotime($department->created)); ?></td>
                            <td><span class="status-text-bold">
                                    <?php
                                    if ($department->status == 1) {
                                        echo "<font color='green'>" . __('Approved', 'js-jobs') . "</font>";
                                    } elseif ($department->status == -1) {
                                        echo "<font color='red'>" . __('Rejected', 'js-jobs') . "</font>";
                                    } else {
                                        echo "<font color='orange'>" . __('Pending', 'js-jobs') . "</font>";
                                    }
                                    ?></span>
                            </td>
                            <td class="action">
                                <a href="<?php echo admin_url('admin.php?page=jsjobs_departments&jsjobslt=formdepartment&jsjobsid='.$department->id); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/edit.png" title="<?php echo __('Edit', 'js-jobs'); ?>"></a>
                                <a href="<?php echo admin_url('admin.php?page=jsjobs_departments&task=remove&action=jsjobtask&jsjobs-cb[]='.$department->id); ?>" onclick="return confirmdelete('<?php echo __('Are you sure to delete', 'js-jobs').' ?'; ?>');"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/remove.png" title="<?php echo __('Delete', 'js-jobs'); ?>"></a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php echo JSJOBSformfield::hidden('action', 'departments_remove'); ?>
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
                    'link' => 'admin.php?page=jsjobs_departments&jsjobslt=formdepartment',
                    'text' => __('Add New','js-jobs') .'&nbsp;'. __('Department','js-jobs')
                );
        echo JSJOBSlayout::getNoRecordFound($msg,$link);
    }
    ?>
</div>
</div>