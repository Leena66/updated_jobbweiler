<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

$host = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];
$url = "http://$host$self";
?>
<script>
    function opendiv() {
        document.getElementById('jsjob_installer_waiting_div').style.display = 'block';
        document.getElementById('jsjob_installer_waiting_span').style.display = 'block';
    }
</script>
<table width="100%">
    <tr>
        <td align="left" width="175" valign="top">
            <table width="100%"><tr><td style="vertical-align:top;">
                        <?php
                        include_once('components/com_jsjobs/views/menu.php');
                        ?>
                    </td>
                </tr></table>
        </td>
        <td width="100%" valign="top">
            <div id="jsjobs_info_heading"><?php echo __('JS_ACTIVATE_UPDATES'); ?></div>	
            <div id="jsjob_installer_msg">
                <?php echo __('JS_JOBS_INSTALLER'); ?>
            </div>
            <form action="index.php" method="POST" name="adminForm" id="adminForm" >
                <div id="jsjob_installer_waiting_div" style="display:none;"></div>
                <span id="jsjob_installer_waiting_span" style="display:none;"><?php echo __('PLEASE_WAIT_INSTALLATION_IN_PROGRESS'); ?></span>
                <div id="jsjob_installer_outerwrap">
                    <div id="jsjob_installer_leftimage">
                        <span id="jsjob_installer_leftimage_logo"></span>
                    </div>
                    <div id="jsjob_installer_wrap">
                        <span id="installer_text">
                            <?php echo __('JS_PLEASE_FILL_THE_FORM_AND_PRESS_UPDATE'); ?>
                        </span>
                        <?php if (in_array('curl', get_loaded_extensions())) { ?>
                            <div id="jsjob_installer_formlabel">
                                <label id="transactionkeymsg" for="transactionkey"><?php echo __('ACTIVATION_KEY'); ?></label>
                            </div>
                            <div id="jsjob_installer_forminput">
                                <input id="transactionkey" name="transactionkey" class="inputbox required" value="" />
                            </div>
                            <div id="jsjob_installer_formsubmitbutton">
                                <input type="submit" class="button" name="submit_app" id="jsjob_instbutton" onclick="return confirmcall();" value="<?php echo __('Start'); ?>" />
                            </div>
                        <?php } else { ?>
                            <div id="jsjob_installer_warning"><?php echo __('WARNING'); ?>!</div>
                            <div id="jsjob_installer_warningmsg"><?php echo __('CURL_IS_NOT_ENABLE_PLEASE_ENABLE_CURL'); ?></div>
                        <?php } ?>
                    </div>
                </div>
                <div id="jsjob_installer_lowerbar">
                    <?php if (!in_array('curl', get_loaded_extensions())) { ?>
                        <span id="jsjob_installer_arrow"><?php echo __('REFRENCE_LINK'); ?></span>
                        <span id="jsjob_installer_link"><a href="http://devilsworkshop.org/tutorial/enabling-curl-on-windowsphpapache-machine/702/"><?php echo __('http://devilsworkshop.org/...'); ?></a></span>
                        <span id="jsjob_installer_link"><a href="http://www.tomjepson.co.uk/enabling-curl-in-php-php-ini-wamp-xamp-ubuntu/"><?php echo __('http://www.tomjepson.co.uk/...'); ?></a></span>
                        <span id="jsjob_installer_link"><a href="http://www.joomlashine.com/blog/how-to-enable-curl-in-php.html"><?php echo __('http://www.joomlashine.com/...'); ?></a></span>
                    <?php } else { ?>
                        <span id="jsjob_installer_mintmsg"><?php echo __('IT_MAY_TAKE_FEW_MINUTES...'); ?></span>
                    <?php } ?>
                </div>

                <input type="hidden" name="check" value="" />
                <input type="hidden" name="domain" value="<?php echo site_url(); ?>" />
                <input type="hidden" name="producttype" value="pro" />
                <input type="hidden" name="count_config" value="<?php echo $this->count_config; ?>" />
                <input type="hidden" name="productcode" value="jsjobs" />
                <input type="hidden" name="productversion" value="<?php echo $this->configur[1]; ?>" />
                <input type="hidden" name="task" value="startupdate" />
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
            </form>
        </td>
    </tr>
</table>
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
    function confirmcall() {
        var result = confirm("<?php echo __('ALL_FILES_OVERRIDE_ARE_YOU_SURE_TO_CONTINUE'); ?>");
        if (result == true) {
            var r = validate_form(document.adminForm);
            return r;
        } else
            return false;
    }
    function validate_form(f)
    {
        if (document.formvalidator.isValid(f)) {
            f.check.value = '<?php if (JVERSION < 3)
                        echo JUtility::getToken();
                    else
                        echo JSession::getFormToken();
                    ?>';//send token
        }
        else {
            alert("<?php echo __('Some values are not acceptable. Please retry.','js-jobs'); ?>");
            return false;
        }
        opendiv();
        return true;
    }
</script>





























<?php
/*
if (!defined('ABSPATH')) die('Restricted Access');

 
 
 $document->addStyleSheet('components/com_jsjobs/css/jsjobs01.css');

$host = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];
$url = "http://$host$self";
?>

<script type="text/javascript">
function validateActivate() {
	var f = document.formAdmin;
	if (f.activationkey.value == ""){
		alert("Please enter Activation Key");
		f.activationkey.focus()
		return false;
	}
	return true;
}

function validateActivateKey() {
	var f = document.activateForm;
	if (f.acemailadd.value == ""){
		alert("Please enter email address");
		f.acemailadd.focus()
		return false;
	}
	if (echeck(f.acemailadd.value)==false){
		f.acemailadd.focus()
		return false
	}
	if (f.actransactionid.value == ""){
		alert("Please enter transaction id");
		f.actransactionid.focus()
		return false;
	}
	return true;
}

function validateUpdates() {
	var f = document.updateForm;
	if (f.upemailadd.value == ""){
		alert("Please enter email address");
		f.upemailadd.focus()
		return false;
	}
	if (echeck(f.upemailadd.value)==false){
		f.upemailadd.focus()
		return false
	}
	if (f.uptransactionid.value == ""){
		alert("Please enter transaction id");
		f.uptransactionid.focus()
		return false;
	}
	return true;
}

function echeck(str) {

		var at="@"
		var dot="."
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		if (str.indexOf(at)==-1){
		   alert("Invalid E-mail ID")
		   return false
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		   alert("Invalid E-mail ID")
		   return false
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		    alert("Invalid E-mail ID")
		    return false
		}

		 if (str.indexOf(at,(lat+1))!=-1){
		    alert("Invalid E-mail ID")
		    return false
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		    alert("Invalid E-mail ID")
		    return false
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    alert("Invalid E-mail ID")
		    return false
		 }

		 if (str.indexOf(" ")!=-1){
		    alert("Invalid E-mail ID")
		    return false
		 }

 		 return true
}

</script>
<table width="100%" class="admintable">
	<tr>
		<td align="left" width="175"  valign="top">
			<table width="100%" ><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="100%" valign="top" align="left">
				<?php if ($this->configur != '0'){ ?>
				<form action="http://www.joomsky.com/jsjobssys/checkactivate.php" method="POST" name="activateForm" id="activeForm" target="_blank">
                                <fieldset class="adminform">
                                   <legend><?php echo __('JS_GET_ACTIVATION_KEY'); ?></legend>

                                       <table cellpadding="0" cellspacing="0" border="0" width="100%" class="admintable">
                                        <tr><td height="10" colspan="3"></td></tr>

                                         <tr>
                                        <td class="key" width="35%"><?php echo __('JS_PAYMENT_EMAIL_ADDRESS'); ?> :</td>
                                                <td width="50%"><input class="inputbox required" type="text" name="acemailadd" size="50" maxlength="255" value="" />   </td>
                                                <td width="15%"></td>
                                      </tr>
                                         <tr>
                                        <td class="key"><?php echo __('JS_TRANSACTION_NO'); ?> / <?php echo __('JS_REFERENCE_NO'); ?> : </td>
                                                  <td><input class="inputbox required" type="text" name="actransactionid" size="50" maxlength="255" value="" />
                                        </td>
                                                <td></td>
                                      </tr>
                                        <tr><td height="10" colspan="3"></td></tr>
                                          <tr>
                                                <td colspan="3" align="center" nowrap style="text-align:center;">
                                                        <input class="button" onclick="return validateActivateKey();" type="submit" name="submit_app" value="<?php echo __('JS_GET_ACTIVATION_KEY'); ?>" />
                                                </td>
                                        </tr>
                                    </table>
                                   </fieldset>

						<input type="hidden" name="activateipo" value="activate" />
						<input type="hidden" name="refercode" value="<?php echo $this->configur[0]; ?>" />
						<input type="hidden" name="siteaddress" value="<?php echo $url; ?>" />
						<input type="hidden" name="vcode" value="<?php echo $this->configur[1]; ?>" />
						<input type="hidden" name="vtype" value="<?php echo $this->configur[2]; ?>" />
			  </form>

			  <br><br>
			  <form action="index.php" method="POST" name="formAdmin"  >
                                <fieldset class="adminform">
                                   <legend><?php echo __('JS_ACTIVATE_JSJOBS'); ?></legend>
                                        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="admintable">
                                        <tr><td height="10" colspan="3"></td></tr>

                                         <tr>
                                        <td class="key" width="35%"><?php echo __('JS_ACTIVATION_KEY'); ?> :</td>
                                                <td class="textbox" width="50%"><input class="inputbox required" type="text" name="activationkey" id="activationkey" size="50" maxlength="255" value="" />   </td>
                                                <td width="15%"></td>
                                      </tr>
                                        <tr><td height="10" colspan="3"></td></tr>
                                          <tr>
                                                <td align="center" colspan="3" nowrap style="text-align:center;">
                                                        <input class="button" onclick="return validateActivate();" type="submit" name="submit_app" value="<?php echo __('JS_ACTIVATE_JSJOBS'); ?>" />
                                                </td>
                                        </tr>
                                    </table>
                                   </fieldset>
					<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
					<input type="hidden" name="task" value="saveactivate" />
			  </form>
				<br><br>
			  <form action="http://www.joomsky.com/jsjobssys/checkupdate.php" method="POST" name="updateForm" target="_blank" >
                                <fieldset class="adminform">
                                   <legend><?php echo __('JS_UPDATE_JSJOBS'); ?></legend>
                                        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="admintable">
                                        <tr><td height="10" colspan="3"></td></tr>

                                         <tr>
                                        <td class="key" width="35%"><?php echo __('JS_PAYMENT_EMAIL_ADDRESS'); ?> :</td>
                                                <td width="50%"><input class="inputbox required" type="text" name="upemailadd" size="50" maxlength="255" value="" />   </td>
                                                <td width="15%"></td>
                                      </tr>
                                         <tr>
                                        <td class="key"><?php echo __('JS_TRANSACTION_NO'); ?> / <?php echo __('JS_REFERENCE_NO'); ?> : </td>
                                                  <td><input class="inputbox required" type="text" name="uptransactionid" size="50" maxlength="255" value="" />
                                        </td>
                                                <td></td>
                                      </tr>
                                        <tr><td height="10" colspan="3"></td></tr>
                                          <tr>
                                                <td colspan="3" align="center" nowrap style="text-align:center;">
                                                        <input class="button" type="submit" onclick="return validateUpdates();" name="submit_app" value="<?php echo __('JS_CHECK_UPDATES'); ?>" />
                                                </td>
                                        </tr>
                                    </table>
                                   </fieldset>
				<input type="hidden" name="updatejsjpo" value="update" />
						<input type="hidden" name="refercode" value="<?php echo $this->configur[0]; ?>" />
						<input type="hidden" name="siteaddress" value="<?php echo $url; ?>" />
						<input type="hidden" name="vcode" value="<?php echo $this->configur[1]; ?>" />
						<input type="hidden" name="vtype" value="<?php echo $this->configur[2]; ?>" />
			  </form>
			 <?php } else {
				echo __('JS_PROBLEM_NOT_JS_JOBS_ACTIVATE');

			 } ?>

		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			<table width="100%" style="table-layout:fixed;"><tr><td style="vertical-align:top;"><?php echo eval(base64_decode('CQkJZWNobyAnPHRhYmxlIHdpZHRoPSIxMDAlIiBzdHlsZT0idGFibGUtbGF5b3V0OmZpeGVkOyI+DQo8dHI+PHRkIGhlaWdodD0iMTUiPjwvdGQ+PC90cj4NCjx0cj4NCjx0ZCBzdHlsZT0idmVydGljYWwtYWxpZ246bWlkZGxlOyIgYWxpZ249ImNlbnRlciI+DQo8YSBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIiB0YXJnZXQ9Il9ibGFuayI+PGltZyBzcmM9Imh0dHA6Ly93d3cuam9vbXNreS5jb20vbG9nby9qc2pvYnNjcmxvZ28ucG5nIiA+PC9hPg0KPGJyPg0KQ29weXJpZ2h0ICZjb3B5OyAyMDA4IC0gJy4gZGF0ZSgnWScpIC4nLCA8YSBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSIgdGFyZ2V0PSJfYmxhbmsiPkJ1cnVqIFNvbHV0aW9uczwvYT4gDQo8L3RkPg0KPC90cj4NCjwvdGFibGU+JzsNCg=='));	?>	</td></tr></table>
		</td>
	</tr>

</table>
*/ 
