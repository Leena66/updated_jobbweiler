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

    $msgkey = JSJOBSincluder::getJSModel('customfield')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey); 

    ?>
    <span class="js-admin-title">
        <a href="<?php echo admin_url('admin.php?page=jsjobs'); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/back-icon.png" /></a>
        <?php echo __('User Fields', 'js-jobs') ?>
        <a class="js-button-link button" href="<?php echo admin_url('admin.php?page=jsjobs_fieldordering&jsjobslt=formuserfield'); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/add_icon.png" /><?php echo __('Add User Field', 'js-jobs') ?></a>
    </span>
    <div class="page-actions js-row no-margin">
        <a class="js-bulk-link button multioperation" message="<?php echo JSJOBSMessages::getMSelectionEMessage(); ?>" data-for="remove" confirmmessage="<?php echo __('Are you sure to delete', 'js-jobs') .' ?'; ?>"  href="#"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/delete-icon.png" /><?php echo __('Delete', 'js-jobs') ?></a>
    </div>
    <script	type="text/javascript">
        function resetFrom() {
            jQuery("input#title").val('');
            jQuery("select#type").val('');
            jQuery("select#required").val('');
            jQuery("form#jsjobsform").submit();
        }
    </script>
    <form class="js-filter-form" name="jsjobsform" id="jsjobsform" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_customfield&ff=" . jsjobs::$_data['fieldfor']); ?>">
        <?php echo JSJOBSformfield::text('title', jsjobs::$_data['filter']['title'], array('class' => 'inputbox', 'placeholder' => __('Title', 'js-jobs'))); ?>
        <?php echo JSJOBSformfield::select('type', JSJOBSincluder::getJSModel('common')->getFeilds(), is_numeric(jsjobs::$_data['filter']['type']) ? jsjobs::$_data['filter']['type'] : '', __('Select','js-jobs') .'&nbsp;'. __('Field Type', 'js-jobs'), array('class' => 'inputbox')); ?>
        <?php echo JSJOBSformfield::select('required', JSJOBSincluder::getJSModel('common')->getYesNo(), is_numeric(jsjobs::$_data['filter']['required']) ? jsjobs::$_data['filter']['required'] : '', __('Required', 'js-jobs'), array('class' => 'inputbox')); ?>
        <?php echo JSJOBSformfield::hidden('JSJOBS_form_search', 'JSJOBS_SEARCH'); ?>
        <div class="filter-bottom-button">
            <?php echo JSJOBSformfield::submitbutton('btnsubmit', __('Search', 'js-jobs'), array('class' => 'button')); ?>
            <?php echo JSJOBSformfield::button('reset', __('Reset', 'js-jobs'), array('class' => 'button', 'onclick' => 'resetFrom();')); ?>
        </div>
    </form>
    <?php
    if (!empty(jsjobs::$_data[0])) {
        ?>
        <form id="jsjobs-list-form" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_customfield"); ?>">
            <table id="js-table">
                <thead>
                    <tr>
                        <th class="grid"><input type="checkbox" name="selectall" id="selectall" value=""></th>
                        <th class="left-row"><?php echo __('Field Name', 'js-jobs'); ?></th>
                        <th><?php echo __('Field Title', 'js-jobs'); ?></th>
                        <th><?php echo __('Field Type', 'js-jobs'); ?></th>
                        <th><?php echo __('Required', 'js-jobs'); ?></th>
                        <th><?php echo __('Read Only', 'js-jobs'); ?></th>
                        <th class="action"><?php echo __('Action', 'js-jobs'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $k = 0;
                    for ($i = 0, $n = count(jsjobs::$_data[0]); $i < $n; $i++) {
                        $row = jsjobs::$_data[0][$i];
                        $required = ($row->required == 1) ? 'yes' : 'no';
                        $requiredalt = ($row->required == 1) ? __('Required', 'js-jobs') : __('Not Required', 'js-jobs');
                        $readonly = ($row->readonly == 1) ? 'yes' : 'no';
                        $readonlyalt = ($row->readonly == 1) ? __('Required', 'js-jobs') : __('Not Required', 'js-jobs');
                        ?>
                        <tr valign="top">
                            <td class="grid-rows">
                                <input type="checkbox" class="jsjobs-cb" id="jsjobs-cb" name="jsjobs-cb[]" value="<?php echo $row->id; ?>" />
                            </td>
                            <td class="left-row"><a href="<?php echo admin_url('admin.php?page=jsjobs_customfield&jsjobslt=formuserfield&jsjobsid='.$row->id); ?>"><?php echo $row->name; ?></a></td>
                            <td><?php echo $row->title; ?></td>
                            <td><?php echo $row->type; ?></td>
                            <td><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/<?php echo $required; ?>.png" alt="<?php echo $requiredalt; ?>" /></td>
                            <td><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/<?php echo $readonly; ?>.png" alt="<?php echo $readonlyalt; ?>" /></td>
                            <td class="action">
                                <a href="<?php echo admin_url('admin.php?page=jsjobs_customfield&jsjobslt=formuserfield&jsjobsid='.$row->id); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/edit.png" title="<?php echo __('Edit', 'js-jobs'); ?>"></a>
                                <a href="<?php echo admin_url('admin.php?page=jsjobs_customfield&task=remove&action=jsjobtask&jsjobs-cb[]='.$row->id); ?>" onclick="return confirmdelete('<?php echo __('Are you sure to delete', 'js-jobs').' ?'; ?>');"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/remove.png" title="<?php echo __('Delete', 'js-jobs'); ?>"></a>
                            </td>
                        </tr>
                        <?php
                        $k = 1 - $k;
                    }
                    ?>
                </tbody>
            </table>
            <?php echo JSJOBSformfield::hidden('action', 'customfield_remove'); ?>
            <?php echo JSJOBSformfield::hidden('task', ''); ?>
            <?php echo JSJOBSformfield::hidden('form_request', 'jsjobs'); ?>
        </form>
        <?php
        if (jsjobs::$_data[1]) {
            echo '<div class="tablenav"><div class="tablenav-pages">' . jsjobs::$_data[1] . '</div></div>';
        }
    } else {
        echo JSJOBSlayout::getNoRecordFound();
    }
    ?>
</div>
</div>
