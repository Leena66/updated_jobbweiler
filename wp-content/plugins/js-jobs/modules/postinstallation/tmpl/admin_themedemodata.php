<div id="jsjobsadmin-wrapper">
	
	<div class="jsjobs-temp-sample-data-wrapper" >
		<div class="jsjobs-temp-sample-data-heading" >
			<h1> <?php 
					if(jsjobs::$_data['flag'] == 1){
						echo __('Demo data has been successfully imported','js-jobs').'&nbsp;.'; 
					}else{
						echo __('Please select the right demo data to import','js-jobs').'&nbsp;!';  
					}
				?>
			</h1>
		</div>
		<div class="jsjobs-temp-sample-data-links" >
			<div class="jsjobs-temp-sample-data-top-links" >
				<?php if(jsjobs::$_data['flag'] != 1){ ?>
						
						<div class="jsjobs-temp-sample-link-wrap" >
							<img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/free.png" />
							<div class="jsjobs-temp-sample-link-bottom-portion" >
								<span class="jsjobs-temp-sample-text" >
									<?php echo __('Free Version','js-jobs'); ?>
								</span>
								<a href="<?php echo admin_url('admin.php?page=jsjobs_postinstallation&action=jsjobtask&task=savetemplatesampledata&flag=f');?>" >
									<?php echo __('Import Data','js-jobs'); ?>
								</a>
							</div>
						</div>
						<div class="jsjobs-temp-sample-link-wrap" >
							<img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/pro.png" />
							<div class="jsjobs-temp-sample-link-bottom-portion" >
								<span class="jsjobs-temp-sample-text" >
									<?php echo __('Pro Version','js-jobs'); ?>
								</span>
								<a href="<?php echo admin_url('admin.php?page=jsjobs_postinstallation&action=jsjobtask&task=savetemplatesampledata&flag=p');?>" >
									<?php echo __('Import Data','js-jobs'); ?>
								</a>
							</div>
						</div>
						<div class="jsjobs-temp-sample-link-wrap" >
							<img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/postinstallation/freetopro.png" />
							<div class="jsjobs-temp-sample-link-bottom-portion" >
								<span class="jsjobs-temp-sample-text" >
									<?php echo __('Free To Pro Updated','js-jobs'); ?>
								</span>
								<a href="<?php echo admin_url('admin.php?page=jsjobs_postinstallation&action=jsjobtask&task=savetemplatesampledata&flag=ftp');?>" >
									<?php echo __('Import Data','js-jobs'); ?>
								</a>
							</div>
						</div>
				<?php } ?>
			</div>
			<div class="jsjobs-temp-sample-data-bottom-links" >
				<a href="?page=jsjobs" >
					<?php echo __('Click Here To Go Control Panel','js-jobs'); ?>
				</a>
				<a href="?page=job_manager_options" >
					<?php echo __('Click Here To Go Template Options','js-jobs'); ?>
				</a>
			</div>
		</div>
	</div>

</div>