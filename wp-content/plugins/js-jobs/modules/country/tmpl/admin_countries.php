<script	type="text/javascript">
    function resetFrom() {
        jQuery("input#countryname").val('');
        jQuery("select#status").val('');
        jQuery("#states1").prop('checked', false);
        jQuery("#city1").prop('checked', false);
        jQuery("form#jsjobsform").submit();
    }
</script>
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
    $msgkey = JSJOBSincluder::getJSModel('country')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey); 
    ?>
    <span class="js-admin-title">
        <a href="<?php echo admin_url('admin.php?page=jsjobs'); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Countries', 'js-jobs') ?>
        <a class="js-button-link button" href="<?php echo admin_url('admin.php?page=jsjobs_country&jsjobslt=formcountry'); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/add_icon.png" /><?php echo __('Add New','js-jobs') .'&nbsp;'. __('Country', 'js-jobs') ?></a>
    </span>
    <div class="page-actions js-row no-margin">
        <a class="js-bulk-link button multioperation" message="<?php echo JSJOBSMessages::getMSelectionEMessage(); ?>" data-for="publish" href="#"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/publish-icon.png" /><?php echo __('Publish', 'js-jobs') ?></a>
        <a class="js-bulk-link button multioperation" message="<?php echo JSJOBSMessages::getMSelectionEMessage(); ?>" data-for="unpublish" href="#"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/unbuplish.png" /><?php echo __('Unpublish', 'js-jobs') ?></a>
        <a class="js-bulk-link button multioperation" message="<?php echo JSJOBSMessages::getMSelectionEMessage(); ?>" data-for="remove" confirmmessage="<?php echo __('Are you sure to delete', 'js-jobs') .' ?'; ?>"  href="#"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/delete-icon.png" /><?php echo __('Delete', 'js-jobs') ?></a>
    </div>
    <form class="js-filter-form" name="jsjobsform" id="jsjobsform" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_country"); ?>">
        <?php echo JSJOBSformfield::text('countryname', jsjobs::$_data['filter']['countryname'], array('class' => 'inputbox', 'placeholder' => __('Name', 'js-jobs'))); ?>
        <?php echo JSJOBSformfield::select('status', JSJOBSincluder::getJSModel('common')->getstatus(), is_numeric(jsjobs::$_data['filter']['status']) ? jsjobs::$_data['filter']['status'] : '', __('Select Status', 'js-jobs'), array('class' => 'inputbox')); ?>
        <?php echo JSJOBSformfield::hidden('JSJOBS_form_search', 'JSJOBS_SEARCH'); ?>
        <div class="checkbox">
            <?php echo JSJOBSformfield::checkbox('states', array('1' => __('Has states', 'js-jobs')), isset(jsjobs::$_data['filter']['states']) ? jsjobs::$_data['filter']['states'] : 0, array('class' => 'checkbox')); ?>
        </div>
        <div class="checkbox">        
            <?php echo JSJOBSformfield::checkbox('city', array('1' => __('Has cities', 'js-jobs')), isset(jsjobs::$_data['filter']['city']) ? jsjobs::$_data['filter']['city'] : 0, array('class' => 'checkbox')); ?>
        </div>
        <div class="filter-bottom-button">
            <?php echo JSJOBSformfield::submitbutton('btnsubmit', __('Search', 'js-jobs'), array('class' => 'button')); ?>
            <?php echo JSJOBSformfield::button('reset', __('Reset', 'js-jobs'), array('class' => 'button', 'onclick' => 'resetFrom();')); ?>
        </div>
    </form>
    <?php
    if (!empty(jsjobs::$_data[0])) {
        ?>
        <form id="jsjobs-list-form" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_country"); ?>">
            <table id="js-table">
                <thead>
                    <tr>
                        <th class="grid"><input type="checkbox" name="selectall" id="selectall" value=""></th>
                        <th class="left-row"><?php echo __('Name', 'js-jobs'); ?></th>
                        <th class="centered"><?php echo __('Published', 'js-jobs'); ?></th>
                        <th class="centered"><?php echo __('States', 'js-jobs'); ?></th>
                        <th class="centered"><?php echo __('Cities', 'js-jobs'); ?></th>
                        <th class="action"><?php echo __('Action', 'js-jobs'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $pagenum = JSJOBSrequest::getVar('pagenum', 'get', 1);
                    $pageid = ($pagenum > 1) ? '&pagenum=' . $pagenum : '';
                    foreach (jsjobs::$_data[0] AS $row) {
                        $published = ($row->enabled == 1) ? 'yes.png' : 'no.png';
                        ?>			
                        <tr>
                            <td class="grid-rows">
                                <input type="checkbox" class="jsjobs-cb" id="jsjobs-cb" name="jsjobs-cb[]" value="<?php echo $row->id; ?>" />
                            </td>
                            <td class="left-row"><a href="<?php echo admin_url('admin.php?page=jsjobs_country&jsjobslt=formcountry&jsjobsid='.$row->id); ?>"><?php echo __($row->name,'js-jobs'); ?></a></td>
                            <td>
                                <?php if ($row->enabled == 1) { ?> 
                                    <a href="<?php echo admin_url('admin.php?page=jsjobs_country&task=unpublish&action=jsjobtask&jsjobs-cb[]='.$row->id.$pageid); ?>">
                                        <img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/yes.png" alt="<?php echo __('Published', 'js-jobs'); ?>" />
                                    </a>
                                <?php } else { ?>
                                    <a href="<?php echo admin_url('admin.php?page=jsjobs_country&task=publish&action=jsjobtask&jsjobs-cb[]='.$row->id.$pageid); ?>">
                                        <img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/no.png" alt="<?php echo __('Not Published', 'js-jobs'); ?>" />
                                    </a>
                                <?php } ?>	
                            </td>
                            <td><a href="<?php echo admin_url('admin.php?page=jsjobs_state&countryid='.$row->id); ?>"> <?php echo __('States', 'js-jobs'); ?></a></td>
                            <td><a href="<?php echo admin_url('admin.php?page=jsjobs_city&countryid='.$row->id); ?>"> <?php echo __('Cities', 'js-jobs'); ?></a></td>
                            <td class="action">
                                <a href="<?php echo admin_url('admin.php?page=jsjobs_country&jsjobslt=formcountry&jsjobsid='.$row->id); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/edit.png" title="<?php echo __('Edit', 'js-jobs'); ?>"></a>
                                <a href="<?php echo admin_url('admin.php?page=jsjobs_country&task=remove&action=jsjobtask&jsjobs-cb[]='.$row->id); ?>" onclick="return confirmdelete('<?php echo __('Are you sure to delete', 'js-jobs').' ?'; ?>');"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/remove.png" title="<?php echo __('Delete', 'js-jobs'); ?>"></a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php echo JSJOBSformfield::hidden('action', 'country_removecountry'); ?>
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
                    'link' => 'admin.php?page=jsjobs_country&jsjobslt=formcountry',
                    'text' => __('Add New','js-jobs') .'&nbsp;'. __('Country','js-jobs')
                );
        echo JSJOBSlayout::getNoRecordFound($msg,$link);
    }
    ?>
</div>
</div>