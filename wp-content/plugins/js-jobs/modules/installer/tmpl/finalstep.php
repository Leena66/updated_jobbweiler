<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<script type="text/javascript">
// for joomla 1.6
    Joomla.submitbutton = function (task) {
        if (task == '') {
            return false;
        } else {
            if (task == 'startinstallation') {
                returnvalue = validate_form(document.adminForm);
            } else
                returnvalue = true;
            if (returnvalue) {
                Joomla.submitform(task);
                return true;
            } else
                return false;
        }
    }

    function validate_form(f)
    {
        if (document.formvalidator.isValid(f)) {
            f.check.value = '<?php
if (JVERSION < 3)
    echo JUtility::getToken();
else
    echo JSession::getFormToken();
?>';//send token
        }else {
            alert("<?php echo __('Some values are not acceptable. Please retry.','js-jobs'); ?>");
            return false;
        }
        opendiv();
        return true;
    }
</script>
<form action="index.php" method="POST" name="adminForm" id="adminForm" >
    <div class="js_installer_wrapper">
        <div class="js_header_bar"><?php echo __('JS jobs installation', 'js-jobs'); ?></div>
        <img class="js_progress" src="components/com_jsjobs/include/images/p4.png" />
        <div class="js_message_wrapper">
            <?php
            if (!empty($this->data))
                foreach ($this->data AS $data) {
                    ?>
                    <div class="js_final_step <?php echo ($data[1] == 1) ? 'green' : 'red'; ?>"><img src="components/com_jsjobs/include/images/<?php echo ($data[1] == 1) ? 'tick.png' : 'cross.png'; ?>"/><?php echo $data[0]; ?></div>
                <?php }
            ?>
        </div>
        <div class="js_button_wrapper">
            <input class="js_next_button" type="submit" value="<?php echo __('JS Finish', 'js-jobs'); ?>" onclick="return validate_form(document.adminForm);" />
        </div>
    </div>
    <input type="hidden" name="check" value="" />
    <input type="hidden" name="view" value="installer" />
    <input type="hidden" name="layout" value="sampledata" />
    <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
</form>
<table width="100%" style="table-layout:fixed;"><tr><td style="vertical-align:top;"><?php echo eval(base64_decode('CQkJZWNobyAnPHRhYmxlIHdpZHRoPSIxMDAlIiBzdHlsZT0idGFibGUtbGF5b3V0OmZpeGVkOyI+DQo8dHI+PHRkIGhlaWdodD0iMTUiPjwvdGQ+PC90cj4NCjx0cj4NCjx0ZCBzdHlsZT0idmVydGljYWwtYWxpZ246bWlkZGxlOyIgYWxpZ249ImNlbnRlciI+DQo8YSBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIiB0YXJnZXQ9Il9ibGFuayI+PGltZyBzcmM9Imh0dHA6Ly93d3cuam9vbXNreS5jb20vbG9nby9qc2pvYnNjcmxvZ28ucG5nIiA+PC9hPg0KPGJyPg0KQ29weXJpZ2h0ICZjb3B5OyAyMDA4IC0gJy4gZGF0ZSgnWScpIC4nLCA8YSBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSIgdGFyZ2V0PSJfYmxhbmsiPkJ1cnVqIFNvbHV0aW9uczwvYT4gDQo8L3RkPg0KPC90cj4NCjwvdGFibGU+JzsNCg==')); ?></td></tr></table>
