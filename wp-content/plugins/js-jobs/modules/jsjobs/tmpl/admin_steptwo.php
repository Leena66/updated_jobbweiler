<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <span class="js-admin-title">
        <a href="<?php echo admin_url('admin.php?page=jsjobs&jsjobslt=stepone'); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Update', 'js-jobs'); ?>
    </span>
    <div id="jsjobs-content">
        <form action="?page=jsjobs&jsjobslt=stepthree" method="POST" name="adminForm" id="adminForm" >
            <div class="js_installer_wrapper">
                <div class="update-header-img step-2">
                    <div class="header-parts first-part">
                        <img class="start" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/header/green.png" />
                        <span class="text"><?php echo __('Configuration', 'js-jobs'); ?></span>
                        <span class="text-no">1</span>
                        <img class="end" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/header/green-2.png" />
                    </div>
                    <div class="header-parts second-part">
                        <img class="start" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/header/orange.png" />
                        <span class="text"><?php echo __('Permissions', 'js-jobs'); ?></span>
                        <span class="text-no">2</span>
                        <img class="end" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/header/orange-2.png" />
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
                        <span class="title"><?php echo __('Main Directory', 'js-jobs'); ?></span>
                        <span class="recommended"><?php echo __('Writable', 'js-jobs'); ?></span>
                        <span class="current <?php echo (jsjobs::$_data['dir'] < 755) ? 'invalid' : 'valid'; ?>"><?php $msg = (jsjobs::$_data['dir'] < 755) ? __('Not Writable', 'js-jobs') : __('Writable', 'js-jobs'); ?><?php echo $msg; ?></span>
                    </div>
                    <?php if ($msg == 'Not Writable') { ?>
                        <span class="error-span"><?php echo __('Directory permissions error', 'js-jobs') . '&nbsp;(&nbsp' . jsjobs::$_path . '&nbsp)&nbsp;' . __('directory is not writable', 'js-jobs') ?>.</span>
                    <?php } ?>
                    <div class="js_data_bar">
                        <span class="title"><?php echo __('Temp Directory', 'js-jobs'); ?></span>
                        <span class="recommended"><?php echo __('Writable', 'js-jobs'); ?></span>
                        <span class="current <?php echo (jsjobs::$_data['tmp_dir'] < 755) ? 'invalid' : 'valid'; ?>"><?php $msg = (jsjobs::$_data['tmp_dir'] < 755) ? __('Not Writable', 'js-jobs') : __('Writable', 'js-jobs'); ?><?php echo $msg; ?></span>
                    </div>
                    <?php
                    $tempflag = 1;
                    if ($msg == 'Not Writable') {
                        $tempfalg = 0;
                        ?>

                        <span class="error-span"><?php echo __('Directory permissions error', 'js-jobs') . '&nbsp;(&nbsp' . ABSPATH . '/tmp' . '/tmp' . '&nbsp)&nbsp;' . __('directory is not writable', 'js-jobs') ?>.</span>
                    <?php } ?>
                    <div class="js_data_bar">
                        <span class="title"><?php echo __('Create Database Table', 'js-jobs'); ?></span>
                        <span class="recommended"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/hired.png" /></span>
                        <span class="current <?php echo (jsjobs::$_data['create_table'] == 0) ? 'invalid' : 'valid'; ?>"><?php $src = (jsjobs::$_data['create_table'] == 1) ? 'hired.png' : 'reject-s.png'; ?><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/<?php echo $src ?>" /></span>
                    </div>
                    <?php if ($src == 'reject-s.png') { ?>
                        <span class="error-span"><?php echo __('System unable to create database table', 'js-jobs') . '. ' . __('Please make sure your database user have create table permissions', 'js-jobs'); ?>.</span>
                    <?php } ?>
                    <div class="js_data_bar">
                        <span class="title"><?php echo __('Insert Record Into Table', 'js-jobs'); ?></span>
                        <span class="recommended"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/hired.png" /></span>
                        <span class="current <?php echo (jsjobs::$_data['insert_record'] == 0) ? 'invalid' : 'valid'; ?>"><?php $src = (jsjobs::$_data['insert_record'] == 1) ? 'hired.png' : 'reject-s.png'; ?><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/<?php echo $src ?>" /></span>
                    </div>
                    <?php if ($src == 'reject-s.png') { ?>
                        <span class="error-span"><?php echo __('System unable to insert record into table', 'js-jobs') . '. ' . __('Please make sure your database user have inserting permissions', 'js-jobs'); ?>.</span>
                    <?php } ?>
                    <div class="js_data_bar">
                        <span class="title"><?php echo __('Update Record In Table', 'js-jobs'); ?></span>
                        <span class="recommended"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/hired.png" /></span>
                        <span class="current <?php echo (jsjobs::$_data['update Record'] == 0) ? 'invalid' : 'valid'; ?>"><?php $src = (jsjobs::$_data['update_record'] == 1) ? 'hired.png' : 'reject-s.png'; ?><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/<?php echo $src ?>" /></span>
                    </div>
                    <?php if ($src == 'reject-s.png') { ?>
                        <span class="error-span"><?php echo __('System unable to update record into table', 'js-jobs') . '. ' . __('Please make sure your database user have update permissions', 'js-jobs'); ?>.</span>
                    <?php } ?>
                    <div class="js_data_bar">
                        <span class="title"><?php echo __('Delete Record In Table', 'js-jobs'); ?></span>
                        <span class="recommended"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/hired.png" /></span>
                        <span class="current <?php echo (jsjobs::$_data['delete Record'] == 0) ? 'invalid' : 'valid'; ?>"><?php $src = (jsjobs::$_data['delete_record'] == 1) ? 'hired.png' : 'reject-s.png'; ?><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/<?php echo $src ?>" /></span>
                    </div>
                    <?php if ($src == 'reject-s.png') { ?>
                        <span class="error-span"><?php echo __('System unable to delete record into table', 'js-jobs') . '. ' . __('Please make sure your database user have delete permissions', 'js-jobs'); ?>.</span>
                    <?php } ?>
                    <div class="js_data_bar">
                        <span class="title"><?php echo __('Drop Database Table', 'js-jobs'); ?></span>
                        <span class="recommended"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/hired.png" /></span>
                        <span class="current <?php echo (jsjobs::$_data['drop_table'] == 0) ? 'invalid' : 'valid'; ?>"><?php $src = (jsjobs::$_data['drop_table'] == 1) ? 'hired.png' : 'reject-s.png'; ?><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/<?php echo $src ?>" /></span>
                    </div>
                    <?php if ($src == 'reject-s.png') { ?>
                        <span class="error-span"><?php echo __('System unable to drop database table', 'js-jobs') . '. ' . __('Please make sure your database user have drop table permissions', 'js-jobs'); ?>.</span>
                    <?php } ?>
                    <div class="js_data_bar">
                        <span class="title"><?php echo __('File Download Using CURL', 'js-jobs'); ?></span>
                        <span class="recommended"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/hired.png" /></span>
                        <span class="current <?php echo (jsjobs::$_data['file_downloaded'] == 0) ? 'invalid' : 'valid'; ?>"><?php $src = (jsjobs::$_data['file_downloaded'] == 1) ? 'hired.png' : 'reject-s.png'; ?><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/<?php echo $src ?>" /></span>
                    </div>
                    <?php if ($src == 'reject-s.png') { ?>
                        <span class="error-span"><?php
                            if ($tempflag != 1) {
                                echo __('Directory permissions error', 'js-jobs') . '&nbsp;(&nbsp' . jsjobs::$_path . '/tmp' . '&nbsp)&nbsp;' . __('directory is not writable', 'js-jobs');
                            } else {
                                echo __('CURL in not enabled', 'js-jobs') . '. ' . __('System unable to download file', 'js-jobs') . '. ' . __('Please make sure you have enabled CURL library', 'js-jobs');
                            }
                            ?>.</span>
                    </div>

                <?php }

                if(jsjobs::$_data['dir'] >= 755  && jsjobs::$_data['tmp_dir'] >= 755 && jsjobs::$_data['file_downloaded'] == 1 && jsjobs::$_data['create_table'] == 1 && jsjobs::$_data['insert_record'] == 1 && jsjobs::$_data['update_record'] == 1 && jsjobs::$_data['delete_record'] == 1 && jsjobs::$_data['drop_table'] == 1){
                    ?>
                    <div class="js_button_wrapper">
                        <input class="js_next_button" type="submit" value="<?php echo __('Next', 'js-jobs'); ?>"  />
                    </div>
                    <?php
                }
                ?>
            </div>

        </form>

    </div>
    <table width="100%" style="table-layout:fixed;"><tr><td style="vertical-align:top;"><?php echo eval(base64_decode('CQkJZWNobyAnPHRhYmxlIHdpZHRoPSIxMDAlIiBzdHlsZT0idGFibGUtbGF5b3V0OmZpeGVkOyI+DQo8dHI+PHRkIGhlaWdodD0iMTUiPjwvdGQ+PC90cj4NCjx0cj4NCjx0ZCBzdHlsZT0idmVydGljYWwtYWxpZ246bWlkZGxlOyIgYWxpZ249ImNlbnRlciI+DQo8YSBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIiB0YXJnZXQ9Il9ibGFuayI+PGltZyBzcmM9Imh0dHA6Ly93d3cuam9vbXNreS5jb20vbG9nby9qc2pvYnNjcmxvZ28ucG5nIiA+PC9hPg0KPGJyPg0KQ29weXJpZ2h0ICZjb3B5OyAyMDA4IC0gJy4gZGF0ZSgnWScpIC4nLCA8YSBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSIgdGFyZ2V0PSJfYmxhbmsiPkJ1cnVqIFNvbHV0aW9uczwvYT4gDQo8L3RkPg0KPC90cj4NCjwvdGFibGU+JzsNCg==')); ?></td></tr></table>
</div>
</div>

