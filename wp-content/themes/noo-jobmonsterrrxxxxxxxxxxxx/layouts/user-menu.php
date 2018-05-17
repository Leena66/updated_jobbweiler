<?php if(Noo_Member::is_logged_in()):?>
	<li class="menu-item-has-children nav-item-member-profile login-link align-right">
		<a id="thumb-info" href="<?php echo Noo_Member::get_member_page_url(); ?>">
			<span class="profile-name"><?php echo Noo_Member::get_display_name(); ?></span>
			<span class="profile-avatar"><?php echo noo_get_avatar( get_current_user_id(), 40 ); ?></span>
			<?php echo user_notifications_number(); ?>
		</a>
		<ul class="sub-menu">
			<?php if(Noo_Member::is_employer()):?>
				<li class="menu-item" ><a href="<?php echo Noo_Member::get_post_job_url()?>"><i class="fa fa-edit"></i> <?php _e('Post a Job','noo')?></a></li>
				<li class="menu-item" ><a href="<?php echo Noo_Member::get_endpoint_url('manage-job')?>"><i class="fa fa-file-text-o"></i> <?php _e('Manage Jobs','noo')?></a></li>
				<li class="menu-item" ><a href="<?php echo Noo_Member::get_endpoint_url('manage-application')?>" style="white-space: nowrap;"><i class="fa fa-newspaper-o"></i> <?php _e('Manage Applications','noo')?></a></li>
				<?php do_action( 'noo-member-employer-menu' ); ?>
				<li class="divider" role="presentation"></li>
				<?php //if(jm_is_woo_job_posting()) : ?>
					<li class="menu-item" ><a href="<?php echo Noo_Member::get_endpoint_url('manage-plan')?>"><i class="fa fa-file-text-o"></i> <?php _e('Manage Plan','noo')?></a></li>
				<?php //endif; ?>
				<li class="menu-item" ><a href="<?php echo Noo_Member::get_company_profile_url()?>"><i class="fa fa-users"></i> <?php _e('Company Profile','noo')?></a></li>
			<?php elseif(Noo_Member::is_candidate()):?>
				<?php if( jm_resume_enabled() ) : ?>
					<li class="menu-item" ><a href="<?php echo Noo_Member::get_post_resume_url()?>"><i class="fa fa-edit"></i> <?php _e('Post a Resume','noo')?></a></li>
					<li class="menu-item" ><a href="<?php echo Noo_Member::get_endpoint_url('manage-resume')?>" style="white-space: nowrap;"><i class="fa fa-file-text-o"></i> <?php _e('Manage Resumes','noo')?></a></li>
				<?php endif; ?>
				<li class="menu-item" ><a href="<?php echo Noo_Member::get_endpoint_url('manage-job-applied')?>" style="white-space: nowrap;"><i class="fa fa-newspaper-o"></i> <?php _e('Manage Applications','noo')?></a></li>
				

				<li class="menu-item" ><a href="<?php echo Noo_Member::get_endpoint_url('shop')?>" style="white-space: nowrap;"><i class="fa fa-newspaper-o"></i> <?php _e('Assessments','noo')?></a></li>
			

<?php


global $current_user;
                                $second_website_url = 'http://assessments.jobbweiler.com'; // put your second website url
                                $user_email = $current_user->user_email;
                                $user_login = $current_user->user_login;
                                if($user_email != ''){
 
                                    $email_encoded = rtrim(strtr(base64_encode($user_email), '+/', '-_'), '='); //email encryption
                                    $user_login_encoded = rtrim(strtr(base64_encode($user_login), '+/', '-_'), '='); //username encryption
                                    echo '<li class="menu-item" > <a href="'.$second_website_url.'/sso.php?key='.$email_encoded.'&detail='.$user_login_encoded.'" target="_blank" style="white-space: nowrap;"><i class="fa fa-newspaper-o"></i>Click here to give assessment</a> ';
 
                        }


?>


				<?php if( Noo_Job_Alert::enable_job_alert() ) : ?>
					<li class="menu-item" ><a href="<?php echo Noo_Member::get_endpoint_url('job-alert')?>"><i class="fa fa-bell-o"></i> <?php _e('Jobs Alert','noo')?></a></li>
				<?php endif; ?>
				<?php do_action( 'noo-member-candidate-menu' ); ?>
				<li class="divider" role="presentation"></li>
				<?php if(jm_is_woo_resume_posting()) : ?>
					<li class="menu-item" ><a href="<?php echo Noo_Member::get_endpoint_url('manage-plan')?>"><i class="fa fa-file-text-o"></i> <?php _e('Manage Plan','noo')?></a></li>
				<?php endif; ?>
<?php  $user_id = get_current_user_id();
$balance = mycred_get_users_balance( $user_id ); ?>

				<li class="menu-item" ><a href="#"><i class="fa fa-user"></i> <?php echo "wibbss : $balance "  ?></a></li>

                	<li class="menu-item" ><a href="<?php echo Noo_Member::get_candidate_profile_url()?>"><i class="fa fa-user"></i> <?php _e('My Profile','noo')?></a></li> 
                	
					<li class="menu-item" ><a href="<?php echo Noo_Member::get_candidate_profile_url()?>"><i class="fa fa-user"></i> <?php _e('Affiliate','noo')?></a></li>
		
			<?php endif; ?>
			<li class="menu-item" ><a href="<?php echo Noo_Member::get_logout_url() ?>"><i class="fa fa-sign-out"></i> <?php _e('Sign Out','noo')?></a></li>

		</ul>
	</li>
<?php else:?>
	<li class="menu-item nav-item-member-profile login-link align-center">
		<a href="<?php echo Noo_Member::get_login_url(); ?>" class="member-links member-login-link"><i class="fa fa-sign-in"></i>&nbsp;<?php _e('Signin&#47;Signup', 'noo')?></a>
		<?php do_action( 'noo_user_menu_login_dropdown' ); ?>
	</li>
	<?php if( Noo_Member::can_register() ) : ?>
		<li class="menu-item nav-item-member-profile register-link">
			<a href="<?php echo get_bloginfo('url') ?>/employee-registration/"><i class="fa fa-key"></i>&nbsp;<?php _e('Recruiter Login', 'noo')?></a>
			<?php do_action( 'noo_user_menu_register_dropdown' ); ?>
		</li>
	<?php endif; ?>
<?php endif;?>