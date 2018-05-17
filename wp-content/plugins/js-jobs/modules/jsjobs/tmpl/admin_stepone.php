<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <?php 
    $msgkey = JSJOBSincluder::getJSModel('jsjobs')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey);
    ?>
    <span class="js-admin-title">
        <a href="<?php echo admin_url('admin.php?page=jsjobs'); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Update', 'js-jobs'); ?>
    </span>
    <div id="jsjobs-content">
        <form action="?page=jsjobs&jsjobslt=steptwo" method="POST" name="adminForm" id="adminForm" >
            <div class="js_installer_wrapper">
                <div class="update-header-img step-1">
                    <div class="header-parts first-part">
                        <img class="start" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/header/orange.png" />
                        <span class="text"><?php echo __('Configuration', 'js-jobs'); ?></span>
                        <span class="text-no">1</span>
                        <img class="end" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/header/orange-2.png" />
                    </div>
                    <div class="header-parts second-part">
                        <img class="start" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/header/blue.png" />
                        <span class="text"><?php echo __('Permissions', 'js-jobs'); ?></span>
                        <span class="text-no">2</span>
                        <img class="end" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/header/grey-2.png" />
                    </div>
                    <div class="header-parts third-part">
                        <img class="start" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/header/blue.png" />
                        <span class="text"><?php echo __('Install', 'js-jobs'); ?></span>
                        <span class="text-no">3</span>
                        <img class="end" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/header/grey-2.png" />
                    </div>
                    <div class="header-parts fourth-part">
                        <img class="start" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/header/blue.png" />
                        <span class="text"><?php echo __('Finish', 'js-jobs'); ?></span>
                        <span class="text-no">4</span>
                        <img class="end" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/header/grey-2.png" />
                    </div>
                </div>
                <div class="installer-bottom-part">
                    <div class="js_header_bar"><?php echo __('Quick Configuration', 'js-jobs'); ?></div>
                    <div class="js_heading_bar">
                        <span class="title"><?php echo __('Settings', 'js-jobs'); ?></span>
                        <span class="recommended"><?php echo __('Recommended', 'js-jobs'); ?></span>
                        <span class="current"><?php echo __('Current', 'js-jobs'); ?></span>
                    </div>
                    <div class="js_data_bar">
                        <span class="title"><?php echo __('PHP Version', 'js-jobs'); ?></span>
                        <span class="recommended"><?php echo __('5.0', 'js-jobs'); ?></span>
                        <span class="current "><?php echo jsjobs::$_data[0]['phpversion']; ?></span>
                    </div>
                    <?php if (jsjobs::$_data[0]['phpversion'] < 5.0) { ?>
                        <span class="error-span"><?php echo __('PHP version lower then recommended', 'js-jobs') ?>.</span>
                    <?php } ?>
                    <div class="js_data_bar">
                        <span class="title"><?php echo __('ZIP Library', 'js-jobs'); ?></span>
                        <span class="recommended"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/hired.png" /></span>
                        <span class="current <?php echo (jsjobs::$_data[0]['zip_lib'] == 0) ? 'invalid' : 'valid'; ?>"><?php $src = (jsjobs::$_data[0]['zip_lib'] == 1) ? 'hired.png' : 'reject-s.png'; ?><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/<?php echo $src; ?>"/></span>
                    </div>
                    <?php if ($src == 'reject-s.png') { ?>
                        <span class="error-span"><?php echo __('ZIP library does not exist', 'js-jobs') ?>.</span>
                    <?php } ?>
                    <div class="js_data_bar">
                        <span class="title"><?php echo __('GD Library', 'js-jobs'); ?></span>
                        <span class="recommended"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/hired.png" /></span>
                        <span class="current <?php echo (jsjobs::$_data[0]['gd_lib'] == 0) ? 'invalid' : 'valid'; ?>"><?php $src = (jsjobs::$_data[0]['gd_lib'] == 1) ? 'hired.png' : 'reject-s.png'; ?><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/<?php echo $src; ?>" /></span>
                    </div>
                    <?php if ($src == 'reject-s.png') { ?>
                        <span class="error-span"><?php echo __('GD library does not exist', 'js-jobs') ?>.</span>
                    <?php } ?>
                    <div class="js_data_bar">
                        <span class="title"><?php echo __('CURL Library', 'js-jobs'); ?></span>
                        <span class="recommended"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/hired.png" /></span>
                        <span class="current <?php echo (jsjobs::$_data[0]['curlexist'] == 0) ? 'invalid' : 'valid'; ?>"><?php $src = (jsjobs::$_data[0]['curlexist'] == 1) ? 'hired.png' : 'reject-s.png'; ?><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/<?php echo $src; ?>"/></span>
                    </div>
                    <?php if ($src == 'reject-s.png') { ?>
                        <span class="error-span"><?php echo __('CURL does not exist', 'js-jobs') ?>.</span>
                    <?php } ?>
                    <?php if (jsjobs::$_data[0]['phpversion'] > 5.0 && jsjobs::$_data[0]['zip_lib'] == 1 && jsjobs::$_data[0]['gd_lib'] == 1 && jsjobs::$_data[0]['curlexist'] == 1) { ?>
                        <div class="js_button_wrapper">
                            <input class="js_next_button" type="submit" value="<?php echo __('Next', 'js-jobs'); ?>"  />
                        </div>
                    <?php } ?>
                </div>
            </div>
        </form>
    </div>
    <table width="100%" style="table-layout:fixed;"><tr><td style="vertical-align:top;"><?php echo eval(base64_decode('CQkJZWNobyAnPHRhYmxlIHdpZHRoPSIxMDAlIiBzdHlsZT0idGFibGUtbGF5b3V0OmZpeGVkOyI+DQo8dHI+PHRkIGhlaWdodD0iMTUiPjwvdGQ+PC90cj4NCjx0cj4NCjx0ZCBzdHlsZT0idmVydGljYWwtYWxpZ246bWlkZGxlOyIgYWxpZ249ImNlbnRlciI+DQo8YSBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIiB0YXJnZXQ9Il9ibGFuayI+PGltZyBzcmM9Imh0dHA6Ly93d3cuam9vbXNreS5jb20vbG9nby9qc2pvYnNjcmxvZ28ucG5nIiA+PC9hPg0KPGJyPg0KQ29weXJpZ2h0ICZjb3B5OyAyMDA4IC0gJy4gZGF0ZSgnWScpIC4nLCA8YSBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSIgdGFyZ2V0PSJfYmxhbmsiPkJ1cnVqIFNvbHV0aW9uczwvYT4gDQo8L3RkPg0KPC90cj4NCjwvdGFibGU+JzsNCg==')); ?></td></tr></table>
</div>
</div>
