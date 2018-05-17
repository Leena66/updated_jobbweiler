<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

$dateformat = jsjobs::$_configuration['date_format'];
if ($dateformat == 'm/d/Y' || $dateformat == 'd/m/y' || $dateformat == 'm/d/y' || $dateformat == 'd/m/Y') {
    $dash = '/';
} else {
    $dash = '-';
}
$firstdash = strpos($dateformat, $dash, 0);
$firstvalue = substr($dateformat, 0, $firstdash);
$firstdash = $firstdash + 1;
$seconddash = strpos($dateformat, $dash, $firstdash);
$secondvalue = substr($dateformat, $firstdash, $seconddash - $firstdash);
$seconddash = $seconddash + 1;
$thirdvalue = substr($dateformat, $seconddash, strlen($dateformat) - $seconddash);
$js_scriptdateformat = $firstvalue . $dash . $secondvalue . $dash . $thirdvalue;
$js_scriptdateformat = str_replace('Y', 'yy', $js_scriptdateformat);

?>
<style type="text/css"> 
.ui-datepicker{
    float: left;
}
</style>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        getTokenInputtagssearch();
        $.validate();
        $('.custom_date').datepicker({dateFormat: '<?php echo $js_scriptdateformat; ?>'});
    });
</script>
<script type="text/javascript">
    function getTokenInputtagssearch() {
        var tagarray = '<?php echo admin_url("admin.php?page=jsjobs_tag&tagfor=2&action=jsjobtask&task=gettagsbytagname"); ?>';
        jQuery("#tags").tokenInput(tagarray, {
            theme: "jsjobs",
            preventDuplicates: true,
            tokenLimit: 5,
            hintText: "<?php echo __('Type In A Search Term', 'js-jobs'); ?>",
            noResultsText: "<?php echo __('No Results', 'js-jobs'); ?>",
            searchingText: "<?php echo __('Searching', 'js-jobs'); ?>"
        });
    }
</script>