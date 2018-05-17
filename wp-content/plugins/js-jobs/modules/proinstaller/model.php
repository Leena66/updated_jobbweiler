<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSproinstallerModel {

    function getServerValidate() {
        $array = explode('.', phpversion());
        $phpversion = $array[0] . '.' . $array[1];
        $curlexist = function_exists('curl_version');
        //$curlversion = curl_version()['version'];
        $curlversion = '';
        if (extension_loaded('gd') && function_exists('gd_info')) {
            $gd_lib = 1;
        } else {
            $gd_lib = 0;
        }
        $zip_lib = 0;

        if (file_exists(jsjobs::$_path . 'includes/lib/pclzip.lib.php')) {
            $zip_lib = 1;
        }
        jsjobs::$_data['phpversion'] = $phpversion;
        jsjobs::$_data['curlexist'] = $curlexist;
        jsjobs::$_data['curlversion'] = $curlversion;
        jsjobs::$_data['gdlib'] = $gd_lib;
        jsjobs::$_data['ziplib'] = $zip_lib;
    }

    function getConfiguration() {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        // check for plugin using plugin name
        if (is_plugin_active('js-jobs/js-jobs.php')) {
            //plugin is activated
            $query = "SELECT config.* FROM `" . jsjobs::$_db->prefix . "js_job_config` AS config";
            $config = jsjobs::$_db->get_results($query);
            foreach ($config as $conf) {
                jsjobs::$_configuration[$conf->configname] = $conf->configvalue;
            }
            jsjobs::$_configuration['config_count'] = COUNT($config);
        }
    }

    function makeDir($path) {
        if (!file_exists($path)) { // create directory
            mkdir($path, 0755);
            $ourFileName = $path . '/index.html';
            $ourFileHandle = fopen($ourFileName, 'w') or die("$path  can't create. Please create directory with 0755 permissions");
            fclose($ourFileHandle);
        }
    }


    function getStepTwoValidate() {
        $basepath = ABSPATH;
        if(!is_writable($basepath)){
            $return['tmpdir'] = 0;
        }else{
            $this->makeDir($basepath.'/tmp');
        }        
        $return['dir'] = substr(sprintf('%o', fileperms(jsjobs::$_path)), -3);
        if(!is_writable(jsjobs::$_path)){
            $return['dir'] = 0;
        }        
        $return['tmpdir'] = substr(sprintf('%o', fileperms($basepath.'/tmp')), -3);
        if(!is_writable($basepath.'/tmp')){
            $return['tmpdir'] = 0;
        }        
        $query = 'CREATE TABLE js_test_table(
                    id int,
                    name varchar(255)
                );';
        jsjobs::$_db->query($query);
        $return['create_table'] = 1;

        if (jsjobs::$_db->last_error != null) {
            $return['create_table'] = 0;
        }

        $query = 'INSERT INTO js_test_table(id,name) VALUES (1,\'Naeem\'),(2,\'Saad\');';
        jsjobs::$_db->query($query);
        $return['insert_record'] = 1;
        if (jsjobs::$_db->last_error != null) {
            $return['insert_record'] = 0;
        }
        $query = 'UPDATE js_test_table SET name = \'Abduallah\' WHERE id = 1;';
        jsjobs::$_db->query($query);
        $return['update_record'] = 1;

        if (jsjobs::$_db->last_error != null) {
            $return['update_record'] = 0;
        }
        $query = 'DELETE FROM js_test_table;';
        jsjobs::$_db->query($query);
        $return['delete_record'] = 1;
        if (jsjobs::$_db->last_error != null) {
            $return['delete_record'] = 0;
        }
        $query = 'DROP TABLE js_test_table;';
        jsjobs::$_db->query($query);
        $return['drop_table'] = 1;
        if (jsjobs::$_db->last_error != null) {
            $return['drop_table'] = 0;
        }
        if ($return['dir'] >= 755 && $return['tmpdir'] >= 755) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_URL, 'http://test.setup.joomsky.com/logo.png');
            $fp = fopen(jsjobs::$_path . 'logo.png', 'w+');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
            $return['file_downloaded'] = 0;
            if (file_exists(jsjobs::$_path . 'logo.png')) {
                $return['file_downloaded'] = 1;
            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_URL, 'http://test.setup.joomsky.com/logo.png');
            $fp = fopen($basepath . '/tmp/logo.png', 'w+');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
            $return['file_downloaded'] = 0;
            if (file_exists(jsjobs::$_path . 'logo.png')) {
                $return['file_downloaded'] = 1;
            }
        } else
            $return['file_downloaded'] = 0;
        jsjobs::$_data['step2'] = $return;
    }

    function getmyversionlist() {
        $post_data['transactionkey'] = JSJOBSrequest::getVar('transactionkey');
        $post_data['serialnumber'] = JSJOBSrequest::getVar('serialnumber');
        $post_data['domain'] = JSJOBSrequest::getVar('domain');
        $post_data['producttype'] = JSJOBSrequest::getVar('producttype', null, 'pro');
        $post_data['productcode'] = JSJOBSrequest::getVar('productcode');
        $post_data['productversion'] = JSJOBSrequest::getVar('productversion');
        $post_data['JVERSION'] = JSJOBSrequest::getVar('JVERSION');
        $post_data['count'] = jsjobs::$_configuration['config_count'];
        $post_data['installerversion'] = JSJOBSrequest::getVar('installerversion');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, JCONSTV);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $response = curl_exec($ch);
        curl_close($ch);
        print_r($response);
        return;
    }
    function getMessagekey(){
        $key = 'proinstaller';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }


}

?>
