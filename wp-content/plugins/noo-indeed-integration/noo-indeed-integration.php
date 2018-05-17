<?php
/*
Plugin Name: Noo Indeed Integration
Plugin URI: https://www.nootheme.com
Description: This plugin help integrates your site with the jobs data from Indeed.com
Version: 1.3.0.1
Author: NooTheme Team
Author URI: https://www.nootheme.com/
*/

// === << Check class exits

if ( !class_exists( 'Noo_Import_Indeed' ) ) :

	class Noo_Import_Indeed {

		public static $current_index = 0;
		public static $trunk = 25;

		function __construct() {
			add_action('init', array($this,'init'));
			if(is_admin()){

				add_action('admin_init', array($this,'admin_init'));
				add_filter('noo_job_settings_tabs_array', array( $this, 'add_seting_import_indeed_tab' ));
				add_filter('noo-jobs-shortcode-params', array( $this, 'noo_jobs_shortcode_params' ));
				add_action('noo_job_setting_import_indeed', array( $this, 'setting_page' ));

				$plugin = plugin_basename( __FILE__ );
				add_filter( "plugin_action_links_$plugin", array( $this, 'plugin_add_settings_link' ) );

				if( !class_exists('Noo_Check_Version_Child') ) {
					require_once( 'includes/noo-check-version-child.php' );
				}
		 
	            $license_manager = new Noo_Check_Version_Child(
	                'noo-import-indeed',
	                'Noo Indeed Integration',
	                'noo-jobmonster',
	                'http://update.nootheme.com/api/license-manager/v1',
	                'plugin',
	                __FILE__
	            );
			}
			add_filter('noo-job-loop-paginate-data', array( $this, 'show_indeed_params_in_loop_paginate' ), 1, 2);

			// -- Load script
				add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_script' ) );
				add_action( 'wp_enqueue_scripts', array( $this, 'load_enqueue_script' ) );


			add_action( 'job_list_before', array( $this, 'list_item_indeed_before' ), 10, 2 );
			add_action( 'job_list_after', array( $this, 'list_item_indeed_after' ), 10, 2 );
			add_action( 'job_list_single_after', array( $this, 'list_item_indeed_randrom' ), 10, 2 );

			// ===== <<< checking plugin visual composer
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if ( is_plugin_active( 'js_composer/js_composer.php' ) ) :
				
				add_action( 'admin_init', array( $this, 'noo_indeed_vc_map' ), 20, 0 );
			
			endif;
			
			add_shortcode( 'noo_indeed', array( $this, 'noo_indeed_shortcode' ) );

			// === <<< register ajax
				add_action( 'wp_ajax_load_job_item', array( $this, 'load_job_item' ) );
				add_action( 'wp_ajax_nopriv_load_job_item', array( $this, 'load_job_item' ) );

				add_action( 'wp_ajax_load_xml_indedd', array( $this, 'load_xml' ) );
		}

		public static function is_indeed_enabled() {
			$setting = self::get_setting('public_id') != '' && self::get_setting('enable_backfill');
			if( !$setting ) return false;

			$where_to_show = self::get_setting('where_to_show', array('index', 'type', 'location'));

			if( is_tax( 'job_type' ) ) {
				return in_array( 'type', $where_to_show);
			}

			if( is_tax( 'job_category' ) ) {
				return in_array( 'category', $where_to_show);
			}

			if( is_tax( 'job_location' ) ) {
				return  in_array( 'location', $where_to_show);
			}

 			if( is_search() ) {
				return in_array( 'search', $where_to_show);
 			}

			if( is_post_type_archive( 'noo_job' ) ) {
				return in_array( 'index', $where_to_show);
			}

			if( is_singular( 'noo_company' ) ) {
				return false;
			}

			return false;
		}

		public function init(){
			$this->registerRSS();
		}

		public function admin_init(){

			register_setting( 'import_indeed' , 'import_indeed' );
		}

		public function plugin_add_settings_link( $links ) {
			if( is_plugin_active( 'noo-indeed-integration/noo-indeed-integration.php' ) ) {
			    $settings_link = '<a href="' . jm_setting_page_url('import_indeed') . '">' . __( 'Settings' ) . '</a>';
			    array_unshift( $links, $settings_link );
			}
		  	return $links;
		}

		public function load_admin_script() {

			wp_register_style( 'noo-import-indeed', plugin_dir_url( __FILE__ ) . 'assets/css/noo_import_indeed.css' );
			wp_enqueue_style( 'noo-import-indeed' );

			wp_register_script( 'noo-import-indeed', plugin_dir_url( __FILE__ ) . 'assets/js/min/noo_import_indeed.min.js', array( 'jquery'), null, true );
			
			wp_enqueue_script( 'noo-import-indeed' );

			// -- Load ajax
				wp_localize_script( 'noo-import-indeed', 'ImportIndeed',
		            array( 
		            	'ajax_url' => admin_url( 'admin-ajax.php' ),
		            	'loadmore' => __( 'Load More', 'noo' ),
		            	'xml_nonce' => wp_create_nonce( 'load_xml' ),
		            	'loadmore_nonce' => wp_create_nonce( 'loadmore' ),
		            )
		        );
		}

		public function load_enqueue_script() {

			wp_register_style( 'noo-indeed', plugin_dir_url( __FILE__ ) . 'assets/css/indeed.css' );
			wp_enqueue_style( 'noo-indeed' );

			wp_register_script( 'noo-import-indeed', plugin_dir_url( __FILE__ ) . 'assets/js/min/noo_import_indeed.min.js', array( 'jquery'), null, true );
			
			wp_enqueue_script( 'noo-import-indeed' );

			// -- Load ajax
				wp_localize_script( 'noo-import-indeed', 'ImportIndeed',
		            array( 
		            	'ajax_url' => admin_url( 'admin-ajax.php' ),
		            	'loadmore' => __( 'Load More', 'noo' ),
		            	'xml_nonce' => wp_create_nonce( 'load_xml' ),
		            	'loadmore_nonce' => wp_create_nonce( 'loadmore' ),
		            )
		        );
		}

		public function noo_jobs_shortcode_params($params = array()) {
			if ( !self::get_setting('public_id') || !self::get_setting('enable_backfill') ) {
				return $params;
			}

			$temp1 = array_slice($params, 0, 4);
			$temp2 = array_slice($params, 4);
			
			$new   = array(
						array(
							'param_name' => 'show_job_indeed',
							'heading' => __( 'Show Indeed Jobs', 'noo' ),
							'type' => 'dropdown',
							'admin_label' => true,
							'value' => array(
								__( 'Hide', 'noo' ) => 'hide',
								__( 'Show', 'noo' ) => 'show',
							) 
						),

						array(
							'param_name' => 'show_job_indeed_mode',
							'heading' => __( 'Integrate mode', 'noo' ),
							'type' => 'dropdown',
							'dependency' => array( 'element' => 'show_job_indeed', 'value' => array( 'show' ) ),
							'value' => array(
								__( 'Before and After', 'noo' ) => 'default',
								__( 'Random', 'noo' ) => 'rand',
							),
							'std' => self::get_setting('show_job', 'default'),
						),

						array(
							'param_name' => 'show_job_indeed_before',
							'heading' => __( 'Job Indeed Before', 'noo' ),
							'type' => 'textfield',
							'dependency' => array( 'element' => 'show_job_indeed_mode', 'value' => array( 'default' ) ),
							'value' => self::get_setting('indeed_backfill_before')
						),

						array(
							'param_name' => 'show_job_indeed_after',
							'heading' => __( 'Job Indeed After', 'noo' ),
							'type' => 'textfield',
							'dependency' => array( 'element' => 'show_job_indeed_mode', 'value' => array( 'default' ) ),
							'value' => self::get_setting('indeed_backfill_after')
						)
					);

			return array_merge($temp1, $new, $temp2);
		}

		public function show_indeed_params_in_loop_paginate($paginate_data = array(), $args = array() ) {
			if( isset( $args['show_job_indeed'] ) && !empty( $args['show_job_indeed'] ) )
				$paginate_data['show_job_indeed'] = $args['show_job_indeed'];
			if( isset( $args['show_job_indeed_mode'] ) && !empty( $args['show_job_indeed_mode'] ) )
				$paginate_data['show_job_indeed_mode'] = $args['show_job_indeed_mode'];
			if( isset( $args['show_job_indeed_before'] ) && !empty( $args['show_job_indeed_before'] ) )
				$paginate_data['show_job_indeed_before'] = $args['show_job_indeed_before'];
			if( isset( $args['show_job_indeed_after'] ) && !empty( $args['show_job_indeed_after'] ) )
				$paginate_data['show_job_indeed_after'] = $args['show_job_indeed_after'];

			return $paginate_data;
		}

		public function add_seting_import_indeed_tab($tabs) {

			$tabs['import_indeed'] = __( 'Indeed', 'noo' );
			return $tabs;
		}

		public static function get_setting($id = null ,$default = null){
			$job_package_setting = get_option('import_indeed');
			if(isset($job_package_setting[$id]))
				return $job_package_setting[$id];
			return $default;
		}

		public function setting_page(){
			?>
				<?php settings_fields('import_indeed'); ?>
				<h3><?php echo __('Indeed Options','noo')?></h3>
				<table class="form-table" cellspacing="0">
					<tbody>

						<tr>
							<th>
								<?php esc_html_e('Publisher ID','noo')?>
							</th>
							<td>
								<?php $public_id = self::get_setting('public_id') ? self::get_setting('public_id') : ''; ?>
								<input type="text" name="import_indeed[public_id]" size="50" placeholder="<?php _e( 'Please enter your public id', 'noo' ); ?>" value="<?php echo $public_id; ?>" />
								<p><small><?php _e( 'To show search results from Indeed you will need a publisher account. Obtain this <a href="https://ads.indeed.com/jobroll/signup" title="" target="_blank">here</a>.', 'noo' ); ?></small></p>
							</td>
						</tr>

						<tr>
							<th>
								<?php esc_html_e('Indeed Job Feed','noo')?>
							</th>
							<td>
								<a href="<?php echo get_feed_link('indeed-job'); ?>" target="_blank" ><?php echo get_feed_link('indeed-job'); ?></a><br/><br/>
								<button id="noo_generate_xml" class="button button-primary">
									<?php _e( 'Download File', 'noo' ); ?>
								</button>
								<p><small><?php _e( 'An XML Feed helps you upload your jobs to Indeed automatically. Go to this link for more information.', 'noo' ); ?>
								<a href="http://www.indeed.com/intl/en/xmlinfo.html" target="_blank">http://www.indeed.com/intl/en/xmlinfo.html</a></small>
								</p>
							</td>
						</tr>

						<script type="text/javascript">
							jQuery(document).ready(function($) {
								$('#enable_backfill').change(function(event) {
									var $input = $( this );
									if ( $input.prop( "checked" ) ) {
										$('.enable_backfill').show().find(':input').change();
									} else {
										$('.enable_backfill').hide().find(':input').change();
									}
								}).change();

								$( "#select_type" ).change(function () {
								    $( "#select_type option:selected" ).each(function() {
								    	$('#indeed_type').val($( this ).text());
								    });
								}).change();

								$( "#select_show_job" ).change(function () {
									var $this = $( this );
									var opt = $this.find('option:selected').val();
							    	if ( $this.is(':visible') && opt === 'default' ) {
							    		$('.show_job').show();
							    	} else {
							    		$('.show_job').hide();
							    	}
								}).change();

							});
						</script>

						<tr>
							<th>
								<?php esc_html_e('Enable Indeed Integration','noo')?>
							</th>
							<td>
								<?php $enable_backfill = self::get_setting('enable_backfill') ? self::get_setting('enable_backfill') : ''; ?>
								<input type="checkbox" <?php checked( true, $enable_backfill ); ?> id="enable_backfill" name="import_indeed[enable_backfill]" value="1" /> <?php _e( 'Showing Indeed job on this site', 'noo' ); ?>
								<p><small><?php _e( 'Enabling this to allows you show Jobs from Indeed within your own job lists.', 'noo' ); ?></small></p>
							</td>
						</tr>

						<tr class="enable_backfill">
							<th>
								<?php esc_html_e('Job keywords','noo')?>
							</th>
							<td>
								<?php $default_query = self::get_setting('default_query') ? self::get_setting('default_query') : __( 'Design', 'noo' ); ?>
								<input type="text" name="import_indeed[default_query]" size="50" value="<?php echo $default_query; ?>" />
								<p><small><?php _e( 'Enter terms to search for Jobs from Indeed. By default terms are ANDed. Search for multiple terms at once by using the "or" keyword between each keyword.', 'noo' ); ?></small></p>
							</td>
						</tr>

						<tr class="enable_backfill">
							<th>
								<?php esc_html_e('Job location','noo')?>
							</th>
							<td>
								<?php $default_localtion = self::get_setting('default_localtion') ? self::get_setting('default_localtion') : ''; ?>
								<input type="text" name="import_indeed[default_localtion]" size="50" value="<?php echo $default_localtion; ?>" />
								<p><small><?php _e( 'The location to search for Jobs from Indeed.', 'noo' ); ?></small></p>
							</td>
						</tr>

						<tr class="enable_backfill">
							<th>
								<?php esc_html_e('Job type','noo')?>
							</th>
							<td>
								<?php $default_job_type = self::get_setting('default_job_type') ? self::get_setting('default_job_type') : ''; ?>
								<select id="select_type" name="import_indeed[default_job_type]">
									<option value="fulltime"<?php selected( $default_job_type, 'fulltime' ); ?>><?php _e( 'Full time', 'noo' ); ?></option>
									<option value="parttime"<?php selected( $default_job_type, 'parttime' ); ?>><?php _e( 'Part time', 'noo' ); ?></option>
									<option value="contract"<?php selected( $default_job_type, 'contract' ); ?>><?php _e( 'Contract', 'noo' ); ?></option>
									<option value="internship"<?php selected( $default_job_type, 'internship' ); ?>><?php _e( 'Internship', 'noo' ); ?></option>
									<option value="temporary"<?php selected( $default_job_type, 'temporary' ); ?>><?php _e( 'Temporary', 'noo' ); ?></option></select>
								</select>
							</td>
						</tr>

						<tr class="enable_backfill">
							<th>
								<?php esc_html_e('Job country','noo')?>
							</th>
							<td>
								<?php $default_country = self::get_setting('default_country') ? self::get_setting('default_country') : 'us'; ?>
								<input type="text" name="import_indeed[default_country]" size="50" value="<?php echo $default_country; ?>" />
								<p><small><?php _e( 'Choose a default country to show jobs from ( us equal to United States ). See https://ads.indeed.com/jobroll/xmlfeed for the full list of supported country codes.', 'noo' ); ?></small></p>
							</td>
						</tr>

						<tr class="enable_backfill">
							<th>
								<?php esc_html_e('Page to Show Indeed Jobs','noo')?>
							</th>
							<td>
								<?php $where_to_show = self::get_setting('where_to_show', array('index', 'type', 'location')); ?>
								<fieldset>
									<input name="import_indeed[where_to_show][]" type="hidden" value="" />
									<label class="checkbox" for="import_indeed_where_to_show_archive">
										<input id="import_indeed_where_to_show_archive" name="import_indeed[where_to_show][]" type="checkbox" <?php checked( in_array('index', $where_to_show), true ); ?> value="index" />
										<?php echo __('Job Archive ( Index page )', 'noo'); ?>
									</label><br/>
									<label class="checkbox" for="import_indeed_where_to_show_type">
										<input id="import_indeed_where_to_show_type" name="import_indeed[where_to_show][]" type="checkbox" <?php checked( in_array('type', $where_to_show), true ); ?> value="type" />
										<?php echo __('Job Type page', 'noo'); ?>
									</label><br/>
									<label class="checkbox" for="import_indeed_where_to_show_category">
										<input id="import_indeed_where_to_show_category" name="import_indeed[where_to_show][]" type="checkbox" <?php checked( in_array('category', $where_to_show), true ); ?> value="category" />
										<?php echo __('Job Category page', 'noo'); ?>
									</label><br/>
									<label class="checkbox" for="import_indeed_where_to_show_location">
										<input id="import_indeed_where_to_show_location" name="import_indeed[where_to_show][]" type="checkbox" <?php checked( in_array('location', $where_to_show), true ); ?> value="location" />
										<?php echo __('Job Location page', 'noo'); ?>
									</label><br/>
									<label class="checkbox" for="import_indeed_where_to_show_search">
										<input id="import_indeed_where_to_show_search" name="import_indeed[where_to_show][]" type="checkbox" <?php checked( in_array('search', $where_to_show), true ); ?> value="search" />
										<?php echo __('Search Result', 'noo'); ?>
									</label><br/>
								</fieldset>
							</td>
						</tr>

						<tr class="enable_backfill">
							<th>
								<?php esc_html_e('Show Company Link','noo')?>
							</th>
							<td>
								<?php $enable_url_company = self::get_setting('enable_url_company') ? self::get_setting('enable_url_company') : ''; ?>
								<input type="checkbox" <?php checked( true, $enable_url_company ); ?> id="enable_url_company" name="import_indeed[enable_url_company]" value="1" /> <?php _e( 'Add Link to Company URL for each job', 'noo' ); ?>
								<p><small><?php _e( 'Get the real company link for the jobs from Indeed. Jobs loading will take more time with this feature so please take it in consideration.', 'noo' ); ?></small></p>
							</td>
						</tr>

						<tr class="enable_backfill">
							<th>
								<?php esc_html_e('Integration mode','noo')?>
							</th>
							<td>
								<?php $show_job = self::get_setting('show_job') ? self::get_setting('show_job') : ''; ?>
								<select id="select_show_job" name="import_indeed[show_job]">
									<option value="default"<?php selected( $show_job, 'default' ); ?>><?php _e( 'Before and After', 'noo' ); ?></option>
									<option value="rand"<?php selected( $show_job, 'rand' ); ?>><?php _e( 'Random', 'noo' ); ?></option>
								</select>
							</td>
						</tr>

						<tr class="show_job">
							<th>
								<?php esc_html_e('No. of Indeed jobs before','noo')?>
							</th>
							<td>
								<?php $indeed_backfill_before = self::get_setting('indeed_backfill_before') ? self::get_setting('indeed_backfill_before') : '0'; ?>
								<input type="text" name="import_indeed[indeed_backfill_before]" size="50" value="<?php echo $indeed_backfill_before; ?>" />
								<p><small><?php _e( 'Show a maximum number of jobs from Indeed before each page of your job listings. Leave blank or set to 0 to disable.', 'noo' ); ?></small></p>
							</td>
						</tr>

						<tr class="show_job">
							<th>
								<?php esc_html_e('No. of Indeed jobs after','noo')?>
							</th>
							<td>
								<?php $indeed_backfill_after = self::get_setting('indeed_backfill_after') ? self::get_setting('indeed_backfill_after') : '0'; ?>
								<input type="text" name="import_indeed[indeed_backfill_after]" size="50" value="<?php echo $indeed_backfill_after; ?>" />
								<p><small><?php _e( 'Show a maximum of jobs from Indeed after each page of your job listings. Leave blank or set to 0 to disable.', 'noo' ); ?></small></p>
							</td>
						</tr>

						<?php
							$url_before = ($indeed_backfill_before > 0 ? "http://api.indeed.com/ads/apisearch?publisher={$public_id}&amp;q={$default_query}&amp;l={$default_localtion}&amp;sort=&amp;radius=&amp;st=&amp;jt={$default_job_type}&amp;start=0&amp;limit={$indeed_backfill_before}&amp;fromage=&amp;filter=&amp;latlong=1&amp;co={$default_localtion}&amp;v=2" : '' );
							$url_after = ($indeed_backfill_after > 0 ? "http://api.indeed.com/ads/apisearch?publisher={$public_id}&amp;q={$default_query}&amp;l={$default_localtion}&amp;sort=&amp;radius=&amp;st=&amp;jt={$default_job_type}&amp;start={$indeed_backfill_before}&amp;limit={$indeed_backfill_after}&amp;fromage=&amp;filter=&amp;latlong=1&amp;co={$default_localtion}&amp;v=2" : '' );
						?>
						<input type="hidden" name="import_indeed[indeed_url_before]" value="<?php echo $url_before ?>" />
						<input type="hidden" name="import_indeed[indeed_url_after]" value="<?php echo $url_after ?>" />

						<?php $indeed_type = self::get_setting('indeed_type') ? self::get_setting('indeed_type') : ''; ?>
						<input type="hidden" name="import_indeed[indeed_type]" id="indeed_type" value="<?php echo $indeed_type ?>" />
					</tbody>
				</table>
			<?php 
		}

		public function list_item_indeed_after( $args = array(), $query = null ) {
			$enable_backfill = self::is_indeed_enabled();
			$show_job = self::get_setting( 'show_job' );
			$indeed_backfill_before = self::get_setting('indeed_backfill_before', 0);
			$indeed_backfill_after = self::get_setting('indeed_backfill_after');
			if( isset( $args['is_shortcode'] ) && $args['is_shortcode'] ) {
				if( isset( $args['ajax_item'] ) && $args['ajax_item'] ) {
					$enable_backfill = isset( $_POST['show_job_indeed'] ) && $_POST['show_job_indeed'] == 'show';
					$show_job = isset( $_POST['show_job_indeed_mode'] ) && !empty( $_POST['show_job_indeed_mode'] ) ? $_POST['show_job_indeed_mode'] : $show_job;
					$indeed_backfill_before = ( isset( $_POST['show_job_indeed_before'] ) && !empty( $_POST['show_job_indeed_before'] ) ) ? $_POST['show_job_indeed_before'] : $indeed_backfill_before;
					$indeed_backfill_after = ( isset( $_POST['show_job_indeed_after'] ) && !empty( $_POST['show_job_indeed_after'] ) ) ? $_POST['show_job_indeed_after'] : $indeed_backfill_after;
				} else {
					$enable_backfill = isset( $args['show_job_indeed'] ) && $args['show_job_indeed'] == 'show';
					$show_job = isset( $args['show_job_indeed_mode'] ) && !empty( $args['show_job_indeed_mode'] ) ? $args['show_job_indeed_mode'] : $show_job;
					$indeed_backfill_before = isset( $args['show_job_indeed_before'] ) ? $args['show_job_indeed_before'] : $indeed_backfill_before;
					$indeed_backfill_after = isset( $args['show_job_indeed_after'] ) ? $args['show_job_indeed_after'] : $indeed_backfill_after;
				}
			}
			
			$current_page = 1;
			if( !empty( $query ) && isset( $query->query_vars['paged'] ) && !empty( $query->query_vars['paged'] ) ) {
				$current_page = $query->query_vars['paged'];
			} elseif( !empty( $query ) && isset( $query->query_vars['page'] ) && !empty( $query->query_vars['page'] ) ) {
				$current_page = $query->query_vars['page'];
			}
			if ( $enable_backfill && ( $show_job == 'default' ) && $indeed_backfill_after > 0 ) :
				$start = ( $current_page - 1 ) * ( $indeed_backfill_before + $indeed_backfill_after ) + $indeed_backfill_before;

				$list_job = $this->_get_indeed_jobs( array( 'start' => $start, 'limit' => $indeed_backfill_after ) );
				if ( !empty( $list_job ) ) :
					foreach ($list_job as $container) :
						$this->_indeed_one_job( $container, $this->_get_job_type( $query ) );
					endforeach;

				endif;
			endif;
		}

		public function list_item_indeed_before( $args = array(), $query = null ) {
			$enable_backfill = self::is_indeed_enabled();
			$show_job = self::get_setting( 'show_job' );
			$indeed_backfill_before = self::get_setting('indeed_backfill_before', 0);
			$indeed_backfill_after = self::get_setting('indeed_backfill_after');
			if( isset( $args['is_shortcode'] ) && $args['is_shortcode'] ) {
				if( isset( $args['ajax_item'] ) && $args['ajax_item'] ) {
					$enable_backfill = isset( $_POST['show_job_indeed'] ) && $_POST['show_job_indeed'] == 'show';
					$show_job = isset( $_POST['show_job_indeed_mode'] ) && !empty( $_POST['show_job_indeed_mode'] ) ? $_POST['show_job_indeed_mode'] : $show_job;
					$indeed_backfill_before = ( isset( $_POST['show_job_indeed_before'] ) && !empty( $_POST['show_job_indeed_before'] ) ) ? $_POST['show_job_indeed_before'] : $indeed_backfill_before;
					$indeed_backfill_after = ( isset( $_POST['show_job_indeed_after'] ) && !empty( $_POST['show_job_indeed_after'] ) ) ? $_POST['show_job_indeed_after'] : $indeed_backfill_after;
				} else {
					$enable_backfill = isset( $args['show_job_indeed'] ) && $args['show_job_indeed'] == 'show';
					$show_job = isset( $args['show_job_indeed_mode'] ) && !empty( $args['show_job_indeed_mode'] ) ? $args['show_job_indeed_mode'] : $show_job;
					$indeed_backfill_before = isset( $args['show_job_indeed_before'] ) ? $args['show_job_indeed_before'] : $indeed_backfill_before;
					$indeed_backfill_after = isset( $args['show_job_indeed_after'] ) ? $args['show_job_indeed_after'] : $indeed_backfill_after;
				}
			}

			$current_page = 1;
			if( !empty( $query ) && isset( $query->query_vars['paged'] ) && !empty( $query->query_vars['paged'] ) ) {
				$current_page = $query->query_vars['paged'];
			} elseif( !empty( $query ) && isset( $query->query_vars['page'] ) && !empty( $query->query_vars['page'] ) ) {
				$current_page = $query->query_vars['page'];
			}

			if ( $enable_backfill && ( $show_job == 'default' ) && $indeed_backfill_before > 0 ) :
				$start = ( $current_page - 1 ) * ( $indeed_backfill_before + $indeed_backfill_after );

				$list_job = $this->_get_indeed_jobs( array( 'start' => $start, 'limit' => $indeed_backfill_before ) );
				if ( !empty( $list_job ) ) :
					foreach ($list_job as $container) :
						$this->_indeed_one_job( $container, $this->_get_job_type( $query ) );
					endforeach;
				endif;
			endif;
		}

		public function list_item_indeed_randrom( $args  = array(), $query = null ) {
			$enable_backfill = self::is_indeed_enabled();
			$show_job = self::get_setting( 'show_job' );
			if( isset( $args['is_shortcode'] ) && $args['is_shortcode'] ) {
				if( isset( $args['ajax_item'] ) && $args['ajax_item'] ) {
					$enable_backfill = isset( $_POST['show_job_indeed'] ) && $_POST['show_job_indeed'] == 'show';
					$show_job = isset( $_POST['show_job_indeed_mode'] ) && !empty( $_POST['show_job_indeed_mode'] ) ? $_POST['show_job_indeed_mode'] : $show_job;
				} else {
					$enable_backfill = isset( $args['show_job_indeed'] ) && $args['show_job_indeed'] == 'show';
					$show_job = isset( $args['show_job_indeed_mode'] ) && !empty( $args['show_job_indeed_mode'] ) ? $args['show_job_indeed_mode'] : $show_job;
				}
			}

			if ( $enable_backfill && $show_job == 'rand' ) :
				$show_indeed = rand(1, 4);
				if( $show_indeed != 1 ) return;

				$rand = rand( 0, self::$trunk * 2 );

				$list_job = $this->_get_indeed_jobs( array( 'start' => $rand, 'limit' => 1 ) );
				if ( !empty( $list_job ) ) :
					foreach ($list_job as $container) :
						$this->_indeed_one_job( $container, $this->_get_job_type( $query ) );
					endforeach;
				endif;
			endif;
		}

		private function _get_default_args() {
			$query = self::get_setting( 'default_query', __( 'Design', 'noo' ) );
			$location = self::get_setting( 'default_localtion', '' );
			$type = self::get_setting( 'default_job_type', '' );
			if( isset($_REQUEST['post_type']) && $_REQUEST['post_type'] == 'noo_job' ) {
				if( isset($_REQUEST['s']) && !empty($_REQUEST['s']) ) {
					$query = $_REQUEST['s'];
				}

				if( isset($_REQUEST['location']) && !empty($_REQUEST['location']) ) {
					$location = $_REQUEST['location'];
				}

				// if( isset($args['query']->query_vars['term']) && !empty($args['query']->query_vars['term']) ) {
				// 	$type = $args['query']->query_vars['term'];
				// }

				if( isset($_REQUEST['type']) && !empty($_REQUEST['type']) ) {
					$type = $_REQUEST['type'];
				}
			}

			if( is_tax('job_location' ) ) {
				global $wp_query;
				if ( isset( $wp_query->query_vars['job_location'] ) ) :
					$location = $wp_query->query_vars['job_location'];
				// else :
				// 	$location = '';
				endif;
			}

			if( is_tax('job_type' ) ) {
				global $wp_query;
				if ( isset( $wp_query->query_vars['job_type'] ) ) :
					$type = $wp_query->query_vars['job_type'];
				// else :
				// 	$type = '';
				endif;
			}

			$default = array(
		 		'co' => self::get_setting( 'default_country', 'us' ),
 				'filter' => '',
 				'fromage' => '',
 				'jt' => $type,
				'l' => $location,
 				'latlong' => 1,
 				'limit' => 1,
				'publisher' => self::get_setting( 'public_id', '' ),
				'q'	=> $query,
 				'radius' => '',
 				'sort' => '',
 				'st' => '',
 				'start' => 0,
		 		'v' => 2
		 	);

		 	return apply_filters( 'jm_indeed_integration_default_args', $default );
		}

		private function _get_indeed_jobs( $args = array() ) {
			$default = $this->_get_default_args();

			$args = array_merge( $default, $args );
			$results = array();

			$start = isset( $args['start'] ) ? absint( $args['start'] ) : 0;
			$limit = isset( $args['limit'] ) ? absint( $args['limit'] ) : 1;

			$step = (int) ($start / self::$trunk);
			$step_start = $start % self::$trunk;
			$step_limit = ( $limit < self::$trunk ) ? $limit % self::$trunk : self::$trunk;

			do {
				$transient_key = $this->_get_transient_key( $args, $step );
				$jobs = get_transient( $transient_key );

				if ( false === $jobs ) {
					$args['start'] = $step * self::$trunk;
					$args['limit'] = self::$trunk;

					$url = $this->_get_indeed_url( $args );

					$get_list = file_get_contents($url);
					if ( !empty( $get_list ) && preg_match('#<result>(.*?)</result>#is', $get_list ) ) {
						preg_match_all('#<result>(.*?)</result>#is', $get_list, $jobs);
					}

					$jobs = isset( $jobs[1] ) ? $jobs[1] : array();

					set_transient( $transient_key, $jobs, HOUR_IN_SECONDS );
				}

				if( !empty( $jobs ) && count( $jobs ) ) {
					$results = array_merge( $results, array_slice( $jobs, $step_start, $step_limit ) );
				}

				$step++;
				$step_start = 0;
				$step_limit = ( $limit < self::$trunk ) ? $limit % self::$trunk : self::$trunk;
				$limit -= self::$trunk;
			} while( $limit > 0 );

			return $results;
		}

		private function _get_transient_key( $args, $step = 0 ) {
			unset( $args['start'] );
			unset( $args['limit'] );

			ksort($args);
			$key = 'indeed';

			foreach ($args as $k => $v) {
				$key .= "_{$k}:{$v}";
			}

			return $key . '_' . $step;
		}

		private function _get_indeed_url( $args = array() ) {
			foreach ($args as $key => $value) {
				if( is_array( $value ) ) {
					$value = reset( $value );
				}
				$args[$key] = urlencode( $value );
			}
		 	return add_query_arg( $args, 'http://api.indeed.com/ads/apisearch' );
		}

		private function _indeed_one_job( $container = '', $type = '' ) {

			if( empty( $container ) ) return;

			// === << precess title
				preg_match('#<jobtitle>(.*?)</jobtitle>#is', $container, $tit);
				$title = trim( $tit[1] );

			// === << precess url
				preg_match('#<url>(.*?)</url>#is', $container, $url);
				$url = trim( $url[1] );

			// ===== <<< [ Check enable show company url ] >>> ===== //
				if ( self::get_setting('enable_url_company') ) :

					$page_job = file_get_contents($url);
					if( !empty( $page_job ) ) {
						preg_match( '#<div class="cmp_title">(.*?)href="(.*?)"#is', $page_job, $url_company );
						if ( isset($url_company[2]) )
							$url_company = "http://www.indeed.com{$url_company[2]}";
						else 
							$url_company = $url;
					}
				endif;
			// === << precess company
				preg_match('#<company>(.*?)</company>#is', $container, $company);
				$company = trim( $company[1] );

			// === << precess formattedLocationFull
				preg_match('#<formattedLocationFull>(.*?)</formattedLocationFull>#is', $container, $formattedLocationFull);
				$formattedLocationFull = trim( $formattedLocationFull[1] );

			// === << precess date
				preg_match('#<date>(.*?)</date>#is', $container, $date);
				$date = explode( ' ', substr( trim( $date[1] ), 5, 11 ));

				$date_text = "{$date[1]} $date[0], $date[2]";
				$date_unix = strtotime("{$date[2]}-{$date[1]}-{$date[0]}");
				if( !empty( $date_unix ) ) {
					$date_text = date_i18n( get_option('date_format'), $date_unix );
				}
			?>
			<article class="noo_job type-noo_job hentry indeed-job loadmore-item">
				<div class="loop-item-wrap">
				    <div class="item-featured">
						<a href="<?php echo (isset( $url_company ) ? $url_company : $url )?>">
							<img src="<?php echo NOO_ASSETS_URI ?>/images/company-logo.png">
						</a>
					</div>
					
					<div class="loop-item-content" style="width: 60% !important;float: left">
						<h2 class="loop-item-title">
							<a href="<?php echo $url ?>" title="<?php echo $title; ?>" target="_blank"><?php echo $title; ?></a>
						</h2>
						<p class="content-meta">
							<span class="job-company">
								<a href="<?php echo (isset( $url_company ) ? $url_company : $url )?>" target="_blank"><?php echo $company ?></a>
							</span>
							<?php if( !empty( $type ) ) : ?>
								<span class="job-type">
									<a href="<?php echo $url ?>" style="color: <?php echo $type->color; ?>" target="_blank">
										<i class="fa fa-bookmark"></i><?php echo $type->name; ?>
									</a>
								</span>
							<?php endif; ?>
							<span class="job-location">
								<i class="fa fa-map-marker"></i>
								<a href="<?php echo $url ?>" target="_blank">
									<em><?php echo $formattedLocationFull; ?></em>
								</a>
							</span>
							<span>
								<time class="entry-date" datetime="<?php echo $date_text; ?>">
									<i class="fa fa-calendar"></i>
									<?php echo $date_text; ?>
								</time>
							</span>
						</p>
					</div>
					<div class="show-view-more" style="float: right;">
						<a class="btn btn-primary" title="<?php _e('View more', 'noo'); ?>" href="<?php echo $url ?>" target="_blank">
							<i class="indeed-icon"></i>
							<?php _e('View more', 'noo'); ?>
						</a>
					</div>
					
				</div>
			</article>
			<?php
		}

		private function _get_job_type( $query = null ) {
			if( !empty( $query ) && isset($query->query_vars['term']) && !empty($query->query_vars['term']) ) {
				$type = $query->query_vars['term'];
				$type = get_term_by( 'slug', $type, 'job_type' );
				if( $type ) {
					$noo_job_type_colors = get_option('noo_job_type_colors');
					$type->color = isset($noo_job_type_colors[$type->term_id]) ? $noo_job_type_colors[$type->term_id] : '';

					return $type;
				}
			}

			$type = new stdClass();
			$type->name = self::get_setting('indeed_type');
			$type->color = '#f14e3b';

			return $type;
		}

		public function noo_indeed_vc_map() {
			if ( defined( 'WPB_VC_VERSION' ) ) :
				vc_map( 
					array( 
						'base'                    => 'noo_indeed', 
						'name'                    => __( 'Noo Indeed', 'noo' ), 
						'weight'                  => 809, 
						'class'                   => 'noo-vc-element noo-indeed', 
						'icon'                    => '', 
						'category'                => __('JobMonster','noo'), 
						'description'             => '',
						'show_settings_on_create' => false,
						'params'                  => array(
							array( 
								'param_name' => 'title', 
								'heading'    => __( 'Title', 'noo' ), 
								'type'       => 'textfield', 
								'holder'     => 'div', 
								'value'      => __( 'Noo Indeed', 'noo' ) 
							),
							array( 
								'param_name'  => 'indeed_query', 
								'heading'     => __( 'Query', 'noo' ), 
								'type'        => 'textfield', 
								'holder'      => 'div', 
								'value'       => __( 'Design', 'noo' ),
								'description' => __( 'Enter terms to search for by default. By default terms are ANDed. Search for multiple terms at once by using the "or" keyword between each keyword.', 'noo' ),
							),
							array( 
								'param_name' => 'indeed_localtion', 
								'heading'    => __( 'Localtion', 'noo' ), 
								'type'       => 'textfield', 
								'holder'     => 'div', 
								'value'      => __( '', 'noo' ),
							),
							array(
								'param_name'  => 'indeed_job_type',
								'heading'     => __( 'Job Type', 'noo' ),
								'type'        => 'dropdown',
								'holder'      => 'div',
								'admin_label' =>true,
								'value'       => array(
									__( 'Full time', 'noo' )  => 'fulltime',
									__( 'Part time', 'noo' )  => 'parttime',
									__( 'Contract', 'noo' )   => 'contract',
									__( 'Internship', 'noo' ) => 'internship',
									__( 'Temporary', 'noo' )  => 'temporary',
								),
							),
							array( 
								'param_name' => 'indeed_country', 
								'heading'    => __( 'Country', 'noo' ), 
								'type'       => 'textfield', 
								'holder'     => 'div', 
								'value'      => __( 'us', 'noo' ),
							),
							array( 
								'param_name' => 'job_per_page', 
								'heading'    => __( 'Job Per Page', 'noo' ), 
								'type'       => 'textfield', 
								'holder'     => 'div', 
								'value'      => __( '10', 'noo' ),
							),
						)
					)
				);
			endif;
		}

		public function noo_indeed_shortcode( $atts, $content = null ) {

			extract(shortcode_atts(array(
				'title'            => __( 'Indeed Jobs', 'noo' ),
				'indeed_query'     => __( 'Design', 'noo' ),
				'indeed_localtion' => '',
				'indeed_job_type'  => 'fulltime',
				'indeed_country'   => 'us',
				'job_per_page'     => '10',
			), $atts));

			$type = get_term_by( 'slug', $indeed_job_type, 'job_type' );
			if( $type ) {
				$noo_job_type_colors = get_option('noo_job_type_colors');
				$type->color = isset($noo_job_type_colors[$type->term_id]) ? $noo_job_type_colors[$type->term_id] : '';
			} else {
				$type = new stdClass();
				$type->name = $indeed_job_type;
				$type->color = '#f14e3b';
			}

			$args = array(
				'q' => $indeed_query,
				'l' => $indeed_localtion,
				'jt' => $indeed_job_type,
				'start' => 0,
				'limit' => absint( $job_per_page ),
				'co' => $indeed_country
			);

			$public_id = self::get_setting('public_id') ? self::get_setting('public_id') : ''; 

			$list_job = $this->_get_indeed_jobs( $args );
			if ( !empty( $list_job ) ) :
			?>
			<div class="jobs posts-loop">
				<div class="posts-loop-title">
					<h3><?php echo esc_html($title); ?></h3>
				</div>
				<div class="posts-loop-content">
			<?php
				foreach ($list_job as $container) :
					$this->_indeed_one_job( $container, $type );
				endforeach;
				?>
				
					<div class="list_loadmore_job"></div>
					<div class="loadmore-action">
						<div class="btn btn-default btn-block btn-loadmore loadmore_job" data-public-id="<?php echo $public_id; ?>" data-query="<?php echo $indeed_query; ?>" data-localtion="<?php echo $indeed_localtion; ?>" data-job-type="<?php echo $indeed_job_type; ?>" data-country="<?php echo $indeed_country; ?>" data-limit="<?php echo $job_per_page; ?>" data-max="<?php echo $job_per_page; ?>"><?php _e( 'Load More', 'noo' ); ?></div>
						<div class="noo-loader loadmore-loading"><span></span><span></span><span></span><span></span><span></span></div>
					</div>
				</div>
			</div>
				<?php
			endif;
		}

		public function load_xml() {
			check_ajax_referer( 'load_xml', 'nonce', true );

			error_reporting(0);
			$xml = $this->build_xml();

			$upload_dir = wp_upload_dir();
			$time = time();
			$file = $upload_dir['path'] . '/' . basename($_POST['post_type'] . "_{$time}.xml" );
			$file_redirect = $upload_dir['url'] . '/' . basename($_POST['post_type'] . "_{$time}.xml" );
			$xml->save($file) or die("Error");

			echo $file_redirect;

			wp_die();
		}

		public function xml_feed() {
			error_reporting(0);
			$xml = $this->build_xml();
			header('Content-Type: '.feed_content_type('rss-http').'; charset='.get_option('blog_charset'), true);
			print $xml->saveXML();
		}

		public function registerRSS() {
			add_feed( 'indeed-job', array( $this, 'xml_feed' ) );
			// global $wp_rewrite;
			// $wp_rewrite->flush_rules();
		}

		private function build_xml() {

			$args_list = array(
				'post_type'		 => 'noo_job',
				'orderby'        => 'date',
				'order'          => 'DESC',
				'post_status'    => 'publish',
				'posts_per_page' => -1

			);

			$info_post = get_posts( $args_list );

			$xml = new DOMDocument("1.0");
			$root = $xml->createElement("source");
			$xml->appendChild($root);

			// -- Publisher
				$publisher         = $xml->createElement("publisher");
				$publisher_content = $xml->createTextNode( get_bloginfo( 'name' ) );
				$publisher->appendChild( $publisher_content );
				$root->appendChild($publisher);

			// -- Publisherurl
				$publisherurl         = $xml->createElement("publisherurl");
				$publisherurl_content = $xml->createTextNode( get_bloginfo( 'url' ) );
				$publisherurl->appendChild( $publisherurl_content );
				$root->appendChild($publisherurl);
				

			foreach ($info_post as $item) :
				setup_postdata( $item ); 
				$job = $xml->createElement("job");
				$root->appendChild($job);

					// -- title
						$title         = $xml->createElement("title");
						$title_content = $xml->createCDATASection( get_the_title( $item->ID ) );
						$title->appendChild( $title_content );
						$job->appendChild($title);

					// -- date
						$date         = $xml->createElement("date");
						$date_content = $xml->createCDATASection( get_the_date( 'D, j M Y g:i:s', $item->ID ) .' GMT' );
						$date->appendChild( $date_content );
						$job->appendChild($date);

					// -- date
						$url         = $xml->createElement("url");
						$url_content = $xml->createCDATASection( get_the_permalink( $item->ID ) );
						$url->appendChild( $url_content );
						$job->appendChild($url);

					// -- company
						$company         = $xml->createElement("company");
						$id_company      = $this->get_info_author( $item->post_author, 'employer_company' );
						$company_content = $xml->createCDATASection( get_the_title($id_company) );
						$company->appendChild( $company_content );
						$job->appendChild($company);

					// -- city
						$city         = $xml->createElement("city");
						$city_content = $xml->createCDATASection( $_POST['city'] );
						$city->appendChild( $city_content );
						$job->appendChild($city);

					// -- state
						$state         = $xml->createElement("state");
						$state_content = $xml->createCDATASection( $_POST['state'] );
						$state->appendChild( $state_content );
						$job->appendChild($state);

					// -- country
						$country         = $xml->createElement("country");
						$country_content = $xml->createCDATASection( $_POST['country'] );
						$country->appendChild( $country_content );
						$job->appendChild($country);

					// -- postalcode
						$postalcode         = $xml->createElement("postalcode");
						$postalcode_content = $xml->createCDATASection( $_POST['postalcode'] );
						$postalcode->appendChild( $postalcode_content );
						$job->appendChild($postalcode);

					// -- description
						$description         = $xml->createElement("description");
						$description_content = $xml->createCDATASection( strip_tags(get_the_content()) );
						$description->appendChild( $description_content );
						$job->appendChild($description);

					// -- salary
						$salary         = $xml->createElement("salary");
						$salary_content = $xml->createCDATASection( $_POST['salary'] );
						$salary->appendChild( $salary_content );
						$job->appendChild($salary);

					// -- jobtype
						$jobtype         = $xml->createElement("jobtype");
						$jobtype_list    = wp_get_post_terms($item->ID, 'job_type');
						// print_r($jobtype_list);
						$jobtype_content = $xml->createCDATASection( str_replace( '-', '', $jobtype_list[0]->slug ) );
						$jobtype->appendChild( $jobtype_content );
						$job->appendChild($jobtype);

					// -- experience
						$experience         = $xml->createElement("experience");
						$experience_content = $xml->createCDATASection( $_POST['experience'] );
						$experience->appendChild( $experience_content );
						$job->appendChild($experience);

				// $content_xml .= "</job>\n";

			endforeach;
			wp_reset_postdata();

			return $xml;
		}

		public function get_info_author( $user_id, $key ) {

			return get_user_meta( $user_id, $key, true ); 
		}

		public function load_job_item() {
			check_ajax_referer( 'loadmore', 'nonce', true );

			$args = array();
			if( isset( $_POST['public_id'] ) ) $args['publisher'] = esc_html( $_POST['public_id'] );
			if( isset( $_POST['indeed_query'] ) ) $args['q'] = esc_html( $_POST['indeed_query'] );
			if( isset( $_POST['indeed_localtion'] ) ) $args['l'] = esc_html( $_POST['indeed_localtion'] );
			if( isset( $_POST['indeed_job_type'] ) ) $args['jt'] = esc_html( $_POST['indeed_job_type'] );
			if( isset( $_POST['indeed_country'] ) ) $args['co'] = esc_html( $_POST['indeed_country'] );
			if( isset( $_POST['start'] ) ) $args['start'] = absint( $_POST['start'] );
			if( isset( $_POST['limit'] ) ) $args['limit'] = absint( $_POST['limit'] );

			$type = get_term_by( 'slug', $args['jt'], 'job_type' );
			if( $type ) {
				$noo_job_type_colors = get_option('noo_job_type_colors');
				$type->color = isset($noo_job_type_colors[$type->term_id]) ? $noo_job_type_colors[$type->term_id] : '';
			} else {
				$type = new stdClass();
				$type->name = $args['jt'];
				$type->color = '#f14e3b';
			}

			$list_job = $this->_get_indeed_jobs( $args );
			if ( !empty( $list_job ) ) :
				foreach ($list_job as $container) :
					$this->_indeed_one_job( $container, $type );
				endforeach;
			endif;
			wp_die();
		}

	}
	new Noo_Import_Indeed();
endif;