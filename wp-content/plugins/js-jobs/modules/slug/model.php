<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSslugModel {

    private $_params_flag;
    private $_params_string;

    function __construct() {
        $this->_params_flag = 0;
    }

    function getSlug() {
    // Filter
        $slug = JSJOBSrequest::getVar('slug');
        $formsearch = JSJOBSrequest::getVar('JSJOBS_form_search', 'post');
        if ($formsearch == 'JSJOBS_SEARCH') {
            $_SESSION['JSJOBS_SEARCH']['slug'] = $slug;
        }
        if(JSJOBSrequest::getVar('pagenum', 'get', null) != null) {
            $slug = (isset($_SESSION['JSJOBS_SEARCH']['slug']) && $_SESSION['JSJOBS_SEARCH']['slug'] != '') ? $_SESSION['JSJOBS_SEARCH']['slug'] : null;
        }elseif($formsearch !== 'JSJOBS_SEARCH') {
            unset($_SESSION['JSJOBS_SEARCH']);
        }
        
        $inquery = '';
        if ($slug != null){
            $inquery .= " AND slug.slug LIKE '%".$slug."%'";
        }
        jsjobs::$_data['slug'] = $slug;
        
        //pagination
        $query = "SELECT COUNT(id) FROM ".jsjobs::$_db->prefix."js_job_slug AS slug WHERE slug.status = 1 ";
        $query .= $inquery;
        $total = jsjobsdb::get_var($query);
        
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT *
                  FROM ".jsjobs::$_db->prefix ."js_job_slug AS slug WHERE slug.status = 1 ";
        $query .= $inquery;
        $query .= " LIMIT " . JSJOBSpagination::$_offset . " , " . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);
        
        return;
    }


    function storeSlug($data) {
        if (empty($data)) {
            return false;
        }
        $row = JSJOBSincluder::getJSTable('slug');
        foreach ($data as $id => $slug) {
            if($id != '' && is_numeric($id)){
                $slug = sanitize_title($slug);
                if($slug != ''){
                    $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_slug
                            WHERE slug = '" . $slug."' ";
                    $slug_flag = jsjobsdb::get_var($query);
                    if($slug_flag > 0){
                        continue;
                    }else{
                        $row->update(array('id' => $id, 'slug' => $slug));
                    }
                }
            }
        }


/*
        if(!is_numeric($data['id']))return false;
        $data['slug'] = sanitize_title($data['slug']);
        if ($data['id'] != 0) {
            if ($data['slug'] == ''){ // get default value
                $query = "SELECT defaultslug FROM " . jsjobs::$_db->prefix . "js_job_slug
                          WHERE id = " . $data['id'];
                $defaultslug = jsjobsdb::get_var($query);
                $data['slug'] = $defaultslug;
            }else{
                $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_slug
                        WHERE slug = '" . $data['slug']."' ";
                $slug_flag = jsjobsdb::get_var($query);
                if($slug_flag > 0){
                     update_option('rewrite_rules', '');
                    return ALREADY_EXIST;
                }
            }
            if (!$row->bind($data)){
                 update_option('rewrite_rules', '');
                return SAVE_ERROR;
            }
            if (!$row->store()){
                 update_option('rewrite_rules', '');
                return SAVE_ERROR;
            }
             update_option('rewrite_rules', '');
            return SAVED;
        }
        */
        update_option('rewrite_rules', '');
        return SAVED;
    }

    function savePrefix($data) {
        if (empty($data)) {
            return false;
        }
        $data['prefix'] = sanitize_title($data['prefix']);
        if($data['prefix'] == ''){
            return SAVE_ERROR;
        }
        $query = "UPDATE " . jsjobs::$_db->prefix . "js_job_config
                    SET configvalue = '".$data['prefix']."'
                    WHERE configname = 'slug_prefix'";
        if(jsjobsdb::query($query)){
             update_option('rewrite_rules', '');
            return SAVED;
        }else{
             update_option('rewrite_rules', '');
            return SAVE_ERROR;
        }
    }

    function saveHomePrefix($data) {
        if (empty($data)) {
            return false;
        }
        $data['prefix'] = sanitize_title($data['prefix']);
        if($data['prefix'] == ''){
            return SAVE_ERROR;
        }
        $query = "UPDATE " . jsjobs::$_db->prefix . "js_job_config
                    SET configvalue = '".$data['prefix']."'
                    WHERE configname = 'home_slug_prefix'";
        if(jsjobsdb::query($query)){
             update_option('rewrite_rules', '');
            return SAVED;
        }else{
             update_option('rewrite_rules', '');
            return SAVE_ERROR;
        }
    }

    function resetAllSlugs() {
        $query = "UPDATE " . jsjobs::$_db->prefix . "js_job_slug
                    SET slug = defaultslug ";
        if(jsjobsdb::query($query)){
             update_option('rewrite_rules', '');
            return SAVED;
        }else{
             update_option('rewrite_rules', '');
            return SAVE_ERROR;
        }
    }

    function getOptionsForEditSlug() {
        $slug = JSJOBSrequest::getVar('slug');
        $html = '<span class="popup-top">
                    <span id="popup_title" >' . __("Edit","js-jobs")."&nbsp;". __("Slug", "js-jobs") . '</span>
                        <img id="popup_cross" onClick="closePopup();" src="' . jsjobs::$_pluginpath . 'includes/images/popup-close.png"></span>';
        
        $html .= '<div class="popup-field-wrapper">
                    <div class="popup-field-title">' . __('Slug','js-jobs').'&nbsp;'. __('Name', 'js-jobs') . '<font class="jsjobs_required-notifier">*</font></div>
                         <div class="popup-field-obj">' . JSJOBSformfield::text('slugedit', isset($slug) ? $slug : 'text', '', array('class' => 'inputbox one', 'data-validation' => 'required')) . '</div>
                    </div>';
        $html .='<div class="js-submit-container js-col-lg-10 js-col-md-10 js-col-md-offset-1 js-col-md-offset-1">
                    ' . JSJOBSformfield::button('save', __('Save', 'jsjobs'), array('class' => 'button savebutton','onClick'=>'getFieldValue();'));
        $html .='</div>';
        return json_encode($html);
    }

    function getDefaultSlugFromSlug($layout) {
        $query = "SELECT  defaultslug FROM `".jsjobs::$_db->prefix."js_job_slug` WHERE slug = '".$layout."'";
        $val = jsjobs::$_db->get_var($query);
        return sanitize_title($val);
    }

    function getSlugFromFileName($layout,$module) {
        $where_query = '';
        if($layout == 'controlpanel'){
            if($module == 'jobseeker'){
                $where_query = " AND defaultslug = 'jobseeker-control-panel'";                            
            }elseif($module == 'employer'){
                $where_query = " AND defaultslug = 'employer-control-panel'";
            }
        }
        if($layout == 'mystats'){
            if($module == 'jobseeker'){
                $where_query = " AND defaultslug = 'jobseeker-my-stats'";                            
            }elseif($module == 'employer'){
                $where_query = " AND defaultslug = 'employer-my-stats'";
            }
        }
        $query = "SELECT slug FROM `".jsjobs::$_db->prefix."js_job_slug` WHERE filename = '".$layout."' ".$where_query;
        $val = jsjobs::$_db->get_var($query);
        return $val;
    }

    function getSlugString($home_page = 0) {
        
            //$query = "SELECT slug AS value, pkey AS akey FROM `".jsjobs::$_db->prefix."js_job_slug`";
            global $wp_rewrite;
            $rules = json_encode($wp_rewrite->rules);
            $query = "SELECT slug AS value FROM `".jsjobs::$_db->prefix."js_job_slug`";
            $val = jsjobs::$_db->get_results($query);
            $string = '';
            $bstring = '';
            //$rules = json_encode($rules);
            $prefix = JSJOBSincluder::getJSModel('configuration')->getConfigValue('slug_prefix');
            $homeprefix = JSJOBSincluder::getJSModel('configuration')->getConfigValue('home_slug_prefix');
            foreach ($val as $slug) {
                    if($home_page == 1){
                        $slug->value = $homeprefix.$slug->value;
                    }
                    if(strpos($rules,$slug->value) === false){
                        $string .= $bstring. $slug->value;
                    }else{
                        $string .= $bstring.$prefix. $slug->value;
                    }
                $bstring = '|';
            }
        return $string;
    }

    function getRedirectCanonicalArray() {
        global $wp_rewrite;
        $slug_prefix = JSJOBSincluder::getJSModel('configuration')->getConfigValue('slug_prefix');
        $homeprefix = JSJOBSincluder::getJSModel('configuration')->getConfigValue('home_slug_prefix');
        $rules = json_encode($wp_rewrite->rules);
        $query = "SELECT slug AS value FROM `".jsjobs::$_db->prefix."js_job_slug`";
        $val = jsjobs::$_db->get_results($query);
        $string = array();
        $bstring = '';
        foreach ($val as $slug) {
            $slug->value = $homeprefix.$slug->value;
            $string[] = $bstring.$slug->value;
            $bstring = '/';
        }
        return $string;
    }

    
    function getMessagekey(){
        $key = 'slug';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }


}

?>
