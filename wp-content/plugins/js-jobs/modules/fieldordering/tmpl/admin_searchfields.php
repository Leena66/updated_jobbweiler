<?php
   if(!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jsjob-res-tables', jsjobs::$_pluginpath . 'includes/js/responsivetable.js');
wp_enqueue_script('jquery-ui-sortable');
wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');// for sorting
  $searchable_combo = array(
        (object) array('id' => 1, 'text' => __('Enabled', 'js-jobs')),
        (object) array('id' => 0, 'text' => __('Disabled', 'js-jobs')));
?>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <div id="full_background" style="display:none;"></div>
    <div id="popup_main" style="display:none;">
        <span class="popup-top">
            <span id="popup_title" >
            </span> 
            <img id="popup_cross" onClick="closePopup();" src="<?php echo  jsjobs::$_pluginpath;?>includes/images/popup-close.png">
        </span>
        <form id="jsjobs-form" class="popup-field-from" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_fieldordering&task=savesearchfieldordering&action=jsjobtask");?>">
            <div class="popup-field-wrapper">
                <div class="popup-field-title"><?php echo  __('User Search', 'js-jobs');?></div>
                <div class="popup-field-obj"><?php echo  JSJOBSformfield::select('search_user', $searchable_combo, 0, '', array('class' => 'inputbox one'));?></div>
            </div>
            <div class="popup-field-wrapper">
                <div class="popup-field-title"><?php echo  __('Visitor Search', 'js-jobs');?></div>
                <div class="popup-field-obj"><?php echo  JSJOBSformfield::select('search_visitor', $searchable_combo, 0, '', array('class' => 'inputbox one'));?></div>
            </div>
            <?php echo JSJOBSformfield::hidden('form_request', 'jsjobs'); ?>
            <?php echo JSJOBSformfield::hidden('id',''); ?>
            <?php echo JSJOBSformfield::hidden('fieldfor',jsjobs::$_data['fieldfor']); ?>
            <div class="js-submit-container js-col-lg-10 js-col-md-10 js-col-md-offset-1 js-col-md-offset-1">
                <?php echo  JSJOBSformfield::submitbutton('save', __('Save', 'js-jobs'), array('class' => 'button')); ?>
            </div>
        </form>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery("div#full_background").click(function () {
                closePopup();
            });
            jQuery('table#js-table tbody').sortable({
                handle : ".grid-rows , .left-row",
                update  : function () {
                    var abc =  jQuery('table#js-table tbody').sortable('serialize');
                    jQuery('input#fields_ordering_new').val(abc);
                }
                
            });
        });

        function showPopupAndSetValues(id,title_string, search_user, search_visitor) {
            jQuery("select#search_user").val(search_user);
            jQuery("select#search_visitor").val(search_visitor);
            jQuery("input#id").val(id);
            jQuery("span#popup_title").html(title_string);
            jQuery("div#full_background").css("display", "block");
            jQuery("div#popup_main").slideDown('slow');
        }

        function closePopup() {
            jQuery("div#popup_main").slideUp('slow');
            setTimeout(function () {
                jQuery("div#full_background").hide();
            }, 700);
        }
    </script>
    <?php 
    $msgkey = JSJOBSincluder::getJSModel('fieldordering')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey); 
    $search_combo = array(
        (object) array('id' => 0, 'text' => __('Search Fields', 'js-jobs')),
        (object) array('id' => 1, 'text' => __('All Fields', 'js-jobs')));
    ?>
    <span class="js-admin-title">
        <a href="<?php echo admin_url('admin.php?page=jsjobs'); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/back-icon.png" /></a>
        <?php 
            if(jsjobs::$_data['fieldfor'] == 1){
                echo __('Company','js-jobs');
            }elseif(jsjobs::$_data['fieldfor'] == 2){
                echo __('Job','js-jobs');
            }elseif(jsjobs::$_data['fieldfor'] == 3){
                echo __('Resume','js-jobs');
            }
            echo ' '.__('Search Fields', 'js-jobs');
        ?>
    </span>
    <form class="js-filter-form" name="jsjobsform" id="jsjobsform" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_fieldordering&jsjobslt=searchfields"); ?>">
        <?php echo JSJOBSformfield::select('search', $search_combo, jsjobs::$_data['filter']['search'], '', array('class' => 'inputbox')); ?>
        <?php echo JSJOBSformfield::submitbutton('btnsubmit', __('Go', 'js-jobs'), array('class' => 'button')); ?>
        <?php echo JSJOBSformfield::hidden('fieldfor', jsjobs::$_data['fieldfor']); ?>
    </form>
    <?php
    if (!empty(jsjobs::$_data[0])) {
        ?>
        <form  id="jsjobs-form" class="search-fields-form" method="post" action="<?php echo admin_url("admin.php?page=jsjobs_fieldordering&action=jsjobtask&task=savesearchfieldorderingFromForm"); ?>">
            <table id="js-table">
                <thead>
                    <tr>
                        <th class="left-row"><?php echo __('Title', 'js-jobs'); ?></th>
                        <th class="search_combo"><?php echo __('User Search', 'js-jobs'); ?></th>
                        <th class="search_combo"><?php echo __('Visitor Search', 'js-jobs'); ?></th>
                        <th class="action"><?php echo __('Edit', 'js-jobs'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0, $n = count(jsjobs::$_data[0]); $i < $n; $i++) {
                        $row = jsjobs::$_data[0][$i];
                        ?>
                        <tr valign="top" id="id_<?php echo $row->id; ?>" >
                            <td class="left-row" style="cursor:grab;">
                                <?php echo __($row->fieldtitle,'js-jobs'); ?>
                            </td>
                            <td  >
                                 <?php if($row->search_user == 1){ ?>
                                    <img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/yes.png" title="<?php echo __('Cannot unpublished', 'js-jobs'); ?>" />
                                 <?php }else{ ?>
                                    <img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/no.png" title="<?php echo __('Cannot unpublished', 'js-jobs'); ?>" />
                                 <?php } ?>
                            </td>
                            <td  >
                                <?php if($row->search_visitor == 1){ ?>
                                    <img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/yes.png" title="<?php echo __('Cannot unpublished', 'js-jobs'); ?>" />
                                <?php }else{ ?>
                                    <img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/no.png" title="<?php echo __('Cannot unpublished', 'js-jobs'); ?>" />
                                <?php } ?>
                            </td>
                            <td class="action" >
                                <a href="#" onclick="showPopupAndSetValues(<?php echo $row->id; ?>,'<?php echo $row->fieldtitle;?>', <?php echo $row->search_user;?>, <?php echo $row->search_visitor;?>)" ><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/edit.png" title="<?php echo __('Edit', 'js-jobs'); ?>"></a>
                            </td>
                        </tr>
                <?php
                    }
                ?>
                </tbody>
            </table>
            <?php echo JSJOBSformfield::hidden('form_request', 'jsjobs'); ?>
            <?php echo JSJOBSformfield::hidden('fieldfor', jsjobs::$_data['fieldfor']); ?>
            <?php echo JSJOBSformfield::hidden('fields_ordering_new',''); ?>
            <div class="js-submit-container js-col-lg-8 js-col-md-8 js-col-md-offset-2 js-col-md-offset-2">
                <a id="form-cancel-button" href="<?php echo admin_url('admin.php?page=jsjobs_fieldordering&ff='.jsjobs::$_data['fieldfor']); ?>" ><?php echo __('Cancel', 'js-jobs'); ?></a>
                <?php echo JSJOBSformfield::submitbutton('save', __('Save','js-jobs') .' '. __('Ordering', 'js-jobs'), array('class' => 'button')); ?>
            </div>
        </form>
        <?php
    }
    ?>
</div>
</div>
