<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jsjob-res-tables', jsjobs::$_pluginpath . 'includes/js/responsivetable.js');
?>
<div id="jsjobsadmin-wrapper">
    <div id="full_background" style="display:none;"></div>
    <div id="popup_main" style="display:none;"></div>
    <div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <?php 
    $msgkey = JSJOBSincluder::getJSModel('slug')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey); 
    ?>
    <span class="js-admin-title">
        <a href="<?php echo admin_url('admin.php?page=jsjobs'); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/back-icon.png" /></a>
    <?php 
        echo __('Slug', 'js-jobs');
    ?>
    <a class="js-button-link button reset-all-slug" href="<?php echo admin_url('admin.php?page=jsjobs_slug&task=resetallslugs&action=jsjobtask'); ?>"><?php echo __('Resest All', 'js-jobs') ?></a>
    </span>
<script type="text/javascript">/*Function to Show popUp,Reset*/
        var slug_for_edit = 0;
        jQuery(document).ready(function () {
        jQuery("div#full_background").click(function () {
          closePopup();
           });
       });
             
    function resetFrom() {// Resest Form
        jQuery("input#slug").val('');
        jQuery("form#jsjobsform").submit();
    }

    function showPopupAndSetValues(id,slug) {//Showing PopUp
        slug = jQuery('td#td_'+id).html();
        slug_for_edit = id;
        jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'slug', task: 'getOptionsForEditSlug',id:id ,slug:slug }, function (data) {
            if (data) {
                var d = jQuery.parseJSON(data);
                jQuery("div#full_background").css("display", "block");
                jQuery("div#popup_main").html(d);
                jQuery("div#popup_main").slideDown('slow');
            }
        });
    }
    function closePopup() {// Close PopUp
        jQuery("div#popup_main").slideUp('slow');
        setTimeout(function () {
        jQuery("div#full_background").hide();
        jQuery("div#popup_main").html('');
        }, 700);
    }
    function getFieldValue() {
        var slugvalue = jQuery("#slugedit").val();
        jQuery('input#'+slug_for_edit).val(slugvalue);
        jQuery('td#td_'+slug_for_edit).html(slugvalue);
        closePopup();
    }
</script>
    <form class="js-filter-form slug-configform" name="jsjobsform" id="conjsjobsform" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_slug&task=savehomeprefix"); ?>">
     <?php echo JSJOBSformfield::text('prefix', jsjobs::$_configuration['home_slug_prefix'], array('class' => 'inputbox', 'placeholder' => __('Home Slug','js-jobs').'&nbsp;'. __('Prefix', 'js-jobs'))); ?> 
        <?php echo JSJOBSformfield::submitbutton('btnsubmit', __('Save', 'js-jobs'), array('class' => 'button')); ?>
        <?php echo JSJOBSformfield::hidden('form_request', 'jsjobs'); ?>
        <span class="slug-prefix-msg" ><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/view-job-information.png" /><?php echo __('This prefix will be added to slug incase of homepage links','js-jobs')?></span>
     </form>

    <form class="js-filter-form slug-configform" name="jsjobsform" id="conjsjobsform" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_slug&task=saveprefix"); ?>">
     <?php echo JSJOBSformfield::text('prefix', jsjobs::$_configuration['slug_prefix'], array('class' => 'inputbox', 'placeholder' => __('Slug','js-jobs').'&nbsp;'. __('Prefix', 'js-jobs'))); ?> 
        <?php echo JSJOBSformfield::submitbutton('btnsubmit', __('Save', 'js-jobs'), array('class' => 'button')); ?>
        <?php echo JSJOBSformfield::hidden('form_request', 'jsjobs'); ?>
        <span class="slug-prefix-msg" ><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/view-job-information.png" /><?php echo __('This prefix will be added to slug incase of conflict','js-jobs')?></span>
     </form>
    <form class="js-filter-form" name="jsjobsform" id="jsjobsform" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_slug"); ?>">
        <?php echo JSJOBSformfield::text('slug', jsjobs::$_data['slug'], array('class' => 'inputbox', 'placeholder' => __('Search By Slug', 'js-jobs'))); ?>
        <?php echo JSJOBSformfield::submitbutton('btnsubmit', __('Search', 'js-jobs'), array('class' => 'button')); ?>
        <?php echo JSJOBSformfield::button('reset', __('Reset', 'js-jobs'), array('class' => 'button', 'onclick' => 'resetFrom();')); ?>
        <?php echo JSJOBSformfield::hidden('JSJOBS_form_search', 'JSJOBS_SEARCH'); ?>
    </form>
    <?php
    if (!empty(jsjobs::$_data[0])) {
        ?>
        <form id="jsjobs-list-form" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_slug&task=saveSlug"); ?>">
            <table id="js-table">
                <thead>
                    <tr>
                        <th class="left-row"><?php echo __('Slug List', 'js-jobs'); ?></th>
                        <th class="left-row"><?php echo __('Description', 'js-jobs'); ?></th>
                        <th class="action"><?php echo __('Action', 'js-jobs'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $pagenum = JSJOBSrequest::getVar('pagenum', 'get', 1);
                    $pageid = ($pagenum > 1) ? '&pagenum=' . $pagenum : '';
                    foreach (jsjobs::$_data[0] as $row){
                        ?>
                        <tr valign="top">
                            <td class="left-row" id="<?php echo 'td_'.$row->id;?>"><?php echo $row->slug;?></td>
                            <td class="left-row"><?php echo __($row->description,'jsjobs');?></td>
                            <td class="action">
                            <a href="#" onclick="showPopupAndSetValues(<?php echo $row->id; ?>)">
                                <img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/edit.png" title="<?php echo __('Edit','js-jobs'); ?>"> </a></td>
                        </tr>
                            <?php echo JSJOBSformfield::hidden($row->id, $row->slug);?>
                        <?php
                         }
                        ?>
                 </tbody>
            </table>
                <!-- Hidden Fields -->
                <div class="submit-button-slug-form-wrap" >
                    <?php echo JSJOBSformfield::submitbutton('btnsubmit', __('Save', 'js-jobs'), array('class' => 'button savebutton')); ?>
                    <span class="slug-save-msg" > <?php echo  __('This button will only save slugs on current page','js-jobs'); ?> !</span>
                </div>
                
                <?php echo JSJOBSformfield::hidden('task', ''); ?>
                <?php echo JSJOBSformfield::hidden('pagenum', ($pagenum > 1) ? $pagenum : ''); ?>
                <?php echo JSJOBSformfield::hidden('form_request', 'jsjobs'); ?>
        </form>
     <?php
        if (jsjobs::$_data[1]) {
            echo '<div class="tablenav"><div class="tablenav-pages">' . jsjobs::$_data[1] . '</div></div>';
        }
    } else {
        $msg = __('No record found','js-jobs');
        $link[] = array(
                    'link' => 'admin.php?page=jsjobs_slug&jsjobslt=formcareerlevels',
                );
        echo JSJOBSlayout::getNoRecordFound($msg, $link);
    }
    ?>
</div>
</div>
