<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

if (jsjobs::$_error_flag == null) {
    $msgkey = JSJOBSincluder::getJSModel('jsjobs')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey);
    $msgkey = JSJOBSincluder::getJSModel('user')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey);

    JSJOBSbreadcrumbs::getBreadcrumbs();
    include_once(jsjobs::$_path . 'includes/header.php');
    ?>
    <div id="jsjobs-wrapper">
        <div class="page_heading"><?php echo __('Login', 'js-jobs'); ?></div>

        <div class="js-login-wrapper">
            <div  class="js-ourlogin">
                <div class="login-heading"><?php echo __('Login into your account', 'js-jobs'); ?></div>
                <?php
                if (!is_user_logged_in()) { // Display WordPress login form:
                    $args = array(
                        'redirect' => jsjobs::$_data[0]['redirect_url'],
                        'form_id' => 'loginform-custom',
                        'label_username' => __('Username', 'js-jobs'),
                        'label_password' => __('Password', 'js-jobs'),
                        'label_remember' => __('keep me login', 'js-jobs'),
                        'label_log_in' => __('Login', 'js-jobs'),
                        'remember' => true
                    );
                    wp_login_form($args);
                } /* else { // If logged in:
                  wp_loginout( home_url() ); // Display "Log Out" link.
                  echo " | ";
                  wp_register('', ''); // Display "Site Admin" link.
                  } */
                ?>
            </div>
        </div>
    </div>
<?php 
} ?>