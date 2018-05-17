<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css'); ?>
<style type="text/css"> 
.ui-datepicker{
    float: left;
}
</style>
<?php 

$config = jsjobs::$_configuration;
if ($config['date_format'] == 'm/d/Y' || $config['date_format'] == 'd/m/y' || $config['date_format'] == 'm/d/y' || $config['date_format'] == 'd/m/Y') {
    $dash = '/';
} else {
    $dash = '-';
}
$dateformat = $config['date_format'];
$firstdash = strpos($dateformat, $dash, 0);
$firstvalue = substr($dateformat, 0, $firstdash);
$firstdash = $firstdash + 1;
$seconddash = strpos($dateformat, $dash, $firstdash);
$secondvalue = substr($dateformat, $firstdash, $seconddash - $firstdash);
$seconddash = $seconddash + 1;
$thirdvalue = substr($dateformat, $seconddash, strlen($dateformat) - $seconddash);
$js_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;
$js_scriptdateformat = $firstvalue . $dash . $secondvalue . $dash . $thirdvalue;
$js_scriptdateformat = str_replace('Y', 'yy', $js_scriptdateformat);
?>
<script type="text/javascript">
    function removeLogo(id) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
        jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'company', task: 'deletecompanylogo', companyid: id}, function (data) {
            if (data) {
                jQuery("img#comp_logo").css("display", "none");
                jQuery("span.remove-file").css("display", "none");
                jQuery("form#jsjobs-form").append('<input type="hidden" name="company_logo_deleted">');
            } else {
                jQuery("div.logo-container").append('<span style="color:Red;"><?php echo __("Error Deleting Logo", "js-jobs"); ?>');
            }
        });
    }
    jQuery(document).ready(function ($) {
        $('.custom_date').datepicker({dateFormat: '<?php echo $js_scriptdateformat; ?>'});
        $.validate();
        //Token Input
        var multicities = <?php echo isset(jsjobs::$_data[0]->multicity) ? jsjobs::$_data[0]->multicity : "''" ?>;
        getTokenInput(multicities);
    });
    function checkUrl(obj) {
        if (!obj.value.match(/^http[s]?\:\/\//))
            obj.value = 'http://' + obj.value;
    }
    function validate_url() {
        var value = jQuery("#url").val();
        if (typeof value != 'undefined') {
            if (value != '' && value != 'http://' ) {
                if (value.match(/^(http|https|ftp)\:\/\/\w+([\.\-]\w+)*\.\w{2,4}(\:\d+)*([\/\.\-\?\&\%\#]\w+)*\/?$/i) ||
                        value.match(/^mailto\:\w+([\.\-]\w+)*\@\w+([\.\-]\w+)*\.\w{2,4}$/i))
                {
                    return true;
                }
                else {
                    jQuery("#url").addClass("invalid");
                    alert("<?php echo __('Enter Correct Company Site', 'js-jobs'); ?>");
                    return false;
                }
            }
        }
        return true;
    }
    var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
    function getTokenInput(multicities) {
        var cityArray = '<?php echo admin_url("admin.php?page=jsjobs_city&action=jsjobtask&task=getaddressdatabycityname"); ?>';
        var city = jQuery("#cityforedit").val();
        if (city != "") {
            jQuery("#city").tokenInput(cityArray, {
                theme: "jsjobs",
                preventDuplicates: true,
                hintText: "<?php echo __('Type In A Search Term', 'js-jobs'); ?>",
                noResultsText: "<?php echo __('No Results', 'js-jobs'); ?>",
                searchingText: "<?php echo __('Searching', 'js-jobs'); ?>",
                // tokenLimit: 1,
                prePopulate: multicities,
<?php $newtyped_cities = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('newtyped_cities');
 if ($newtyped_cities == 1) { ?>

                onResult: function (item) {
                    if (jQuery.isEmptyObject(item)) {
                        return [{id: 0, name: jQuery("tester").text()}];
                    } else {
                        //add the item at the top of the dropdown
                        item.unshift({id: 0, name: jQuery("tester").text()});
                        return item;
                    }
                },
                onAdd: function (item) {
                    if (item.id > 0) {
                        return;
                    }
                    if (item.name.search(",") == -1) {
                        var input = jQuery("tester").text();
                        alert("<?php echo __('Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name', 'js-jobs'); ?>");
                        jQuery("#city").tokenInput("remove", item);
                        return false;
                    } else {
                        var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
                        jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'city', task: 'savetokeninputcity', citydata: jQuery("tester").text()}, function (data) {
                            if (data) {
                                try {
                                    var value = jQuery.parseJSON(data);
                                    jQuery('#city').tokenInput('remove', item);
                                    jQuery('#city').tokenInput('add', {id: value.id, name: value.name});
                                }
                                catch (err) {
                                    jQuery("#city").tokenInput("remove", item);
                                    alert(data);
                                }
                            }
                        });
                    }
                }
        <?php } ?>
            });
        } else {
            jQuery("#city").tokenInput(cityArray, {
                theme: "jsjobs",
                preventDuplicates: true,
                hintText: "<?php echo __('Type In A Search Term', 'js-jobs'); ?>",
                noResultsText: "<?php echo __('No Results', 'js-jobs'); ?>",
                searchingText: "<?php echo  __('Searching', 'js-jobs'); ?>",
                // tokenLimit: 1,
<?php $newtyped_cities = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('newtyped_cities');
 if ($newtyped_cities == 1) { ?>

                onResult: function (item) {
                    if (jQuery.isEmptyObject(item)) {
                        return [{id: 0, name: jQuery("tester").text()}];
                    } else {
                        //add the item at the top of the dropdown
                        item.unshift({id: 0, name: jQuery("tester").text()});
                        return item;
                    }
                },
                onAdd: function (item) {
                    if (item.id > 0) {
                        return;
                    }
                    if (item.name.search(",") == -1) {
                        var input = jQuery("tester").text();
                        alert("<?php echo __('Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name', 'js-jobs'); ?>");
                        jQuery("#city").tokenInput("remove", item);
                        return false;
                    } else {
                        var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
                        jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'city', task: 'savetokeninputcity', citydata: jQuery("tester").text()}, function (data) {
                            if (data) {
                                try {
                                    var value = jQuery.parseJSON(data);
                                    jQuery('#city').tokenInput('remove', item);
                                    jQuery('#city').tokenInput('add', {id: value.id, name: value.name});
                                }
                                catch (err) {
                                    jQuery("#city").tokenInput("remove", item);
                                    alert(data);
                                }
                            }
                        });
                    }
                }
                <?php } ?>
            });
        }
    }
</script>
