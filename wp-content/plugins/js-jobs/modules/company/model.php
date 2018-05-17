<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSCompanyModel {

    function getCompanies_Widget($companytype, $noofcompanies) {
        if ((!is_numeric($companytype)) || ( !is_numeric($noofcompanies)))
            return false;

        if ($companytype == 1) {
            $inquery = ' AND company.isgoldcompany = 1 AND DATE(company.endgolddate) >= CURDATE() ';
        } elseif ($companytype == 2) {
            $inquery = ' AND company.isfeaturedcompany = 1 AND DATE(company.endfeatureddate) >= CURDATE() ';
        } else {
            return '';
        }

        $query = "SELECT  company.*,cat.cat_title , CONCAT(company.alias,'-',company.id) AS companyaliasid ,company.id AS companyid,company.logofilename AS companylogo
            FROM `" . jsjobs::$_db->prefix . "js_job_companies` AS company 
            LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON company.category = cat.id 
            WHERE company.status = 1 AND company.isgoldcompany = 1 AND DATE(company.endgolddate) >= CURDATE() ";
        $query .= $inquery . " ORDER BY company.created DESC ";
        if ($noofcompanies != -1)
            $query .=" LIMIT " . $noofcompanies;
        $results = jsjobsdb::get_results($query);

        $results = jsjobsdb::get_results($query);
        foreach ($results AS $d) {
            $d->location = JSJOBSincluder::getJSModel('city')->getLocationDataForView($d->city);
        }
        return $results;
    }

    function getAllCompaniesForSearchForCombo() {
        $query = "SELECT id, name AS text FROM `" . jsjobs::$_db->prefix . "js_job_companies` ORDER BY name ASC ";
        $rows = jsjobsdb::get_results($query);
        return $rows;
    }

    function getCompanybyIdForView($companyid) {
        if (is_numeric($companyid) == false)
            return false;

        $query = "SELECT company.*, cat.cat_title, country.name AS countryname, state.name AS statename ,city.cityName AS cityname
                    FROM `" . jsjobs::$_db->prefix . "js_job_companies` AS company
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON company.category = cat.id
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS country ON company.country = country.id
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_states` AS state ON company.state = state.id
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON company.city = city.id
                    WHERE  company.id = " . $companyid;
        jsjobs::$_data[0] = jsjobsdb::get_row($query);
        jsjobs::$_data[0]->location = JSJOBSincluder::getJSModel('city')->getLocationDataForView(jsjobs::$_data[0]->city);
        jsjobs::$_data[2] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforView(1);
        jsjobs::$_data[3] = jsjobs::$_data[0]->params;
        jsjobs::$_data['companycontactdetail'] = true;
        //update the company view counter
        //DB class limitations
        $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_companies` SET hits = hits + 1 WHERE id = " . $companyid;
        jsjobs::$_db->query($query);
        jsjobs::$_data['config'] = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('company');
        return;
    }

    function getCompanybyId($c_id) {
        if ($c_id)
            if (!is_numeric($c_id))
                return false;
        if ($c_id) {
            $query = "SELECT * FROM `" . jsjobs::$_db->prefix . "js_job_companies` WHERE id =" . $c_id;
            jsjobs::$_data[0] = jsjobsdb::get_row($query);
            if(jsjobs::$_data[0] != ''){
                jsjobs::$_data[0]->multicity = JSJOBSincluder::getJSModel('common')->getMultiSelectEdit($c_id, 2);
            }
        }
        jsjobs::$_data[2] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(1); // company fields
        return;
    }

    function sorting() {

        $pagenum = JSJOBSrequest::getVar('pagenum');
        jsjobs::$_data['sorton'] = JSJOBSrequest::getVar('sorton', 'post', 3);
        jsjobs::$_data['sortby'] = JSJOBSrequest::getVar('sortby', 'post', 2);
        if($pagenum > 1 && isset($_SESSION['companies'])){
            jsjobs::$_data['sorton'] = $_SESSION['companies']['sorton'];
            jsjobs::$_data['sortby'] = $_SESSION['companies']['sortby'];
        }else{
            $_SESSION['companies']['sorton'] = jsjobs::$_data['sorton'];
            $_SESSION['companies']['sortby'] = jsjobs::$_data['sortby'];
        }
        switch (jsjobs::$_data['sorton']) {
            case 3: // created
                jsjobs::$_data['sorting'] = ' company.created ';
                break;
            case 1: // company title
                jsjobs::$_data['sorting'] = ' company.name ';
                break;
            case 2: // category
                jsjobs::$_data['sorting'] = ' cat.cat_title ';
                break;
            case 4: // location
                jsjobs::$_data['sorting'] = ' city.cityName ';
                break;
            case 5: // status
                jsjobs::$_data['sorting'] = ' company.status ';
                break;
        }
        if (jsjobs::$_data['sortby'] == 1) {
            jsjobs::$_data['sorting'] .= ' ASC ';
        } else {
            jsjobs::$_data['sorting'] .= ' DESC ';
        }
        jsjobs::$_data['combosort'] = jsjobs::$_data['sorton'];
    }

    function getAllCompanies() {
        $this->sorting();

        //Filters
        $searchcompany = JSJOBSrequest::getVar('searchcompany');
        $searchjobcategory = JSJOBSrequest::getVar('searchjobcategory');
        $status = JSJOBSrequest::getVar('status');
        $datestart = JSJOBSrequest::getVar('datestart');
        $dateend = JSJOBSrequest::getVar('dateend');
        //Front end search var
        $jsjobs_company = JSJOBSrequest::getVar('jsjobs-company');
        $jsjobs_company = jsjobs::parseSpaces($jsjobs_company);
        $jsjobs_city = JSJOBSrequest::getVar('jsjobs-city');
        $formsearch = JSJOBSrequest::getVar('JSJOBS_form_search', 'post');
        if ($formsearch == 'JSJOBS_SEARCH') {
            $_SESSION['JSJOBS_SEARCH']['searchcompany'] = $searchcompany;
            $_SESSION['JSJOBS_SEARCH']['searchjobcategory'] = $searchjobcategory;
            $_SESSION['JSJOBS_SEARCH']['status'] = $status;
            $_SESSION['JSJOBS_SEARCH']['datestart'] = $datestart;
            $_SESSION['JSJOBS_SEARCH']['dateend'] = $dateend;
        }
        if (JSJOBSrequest::getVar('pagenum', 'get', null) != null) {
            $searchcompany = (isset($_SESSION['JSJOBS_SEARCH']['searchcompany']) && $_SESSION['JSJOBS_SEARCH']['searchcompany'] != '') ? $_SESSION['JSJOBS_SEARCH']['searchcompany'] : null;
            $searchjobcategory = (isset($_SESSION['JSJOBS_SEARCH']['searchjobcategory']) && $_SESSION['JSJOBS_SEARCH']['searchjobcategory'] != '') ? $_SESSION['JSJOBS_SEARCH']['searchjobcategory'] : null;
            $status = (isset($_SESSION['JSJOBS_SEARCH']['status']) && $_SESSION['JSJOBS_SEARCH']['status'] != '') ? $_SESSION['JSJOBS_SEARCH']['status'] : null;
            $datestart = (isset($_SESSION['JSJOBS_SEARCH']['datestart']) && $_SESSION['JSJOBS_SEARCH']['datestart'] != '') ? $_SESSION['JSJOBS_SEARCH']['datestart'] : null;
            $dateend = (isset($_SESSION['JSJOBS_SEARCH']['dateend']) && $_SESSION['JSJOBS_SEARCH']['dateend'] != '') ? $_SESSION['JSJOBS_SEARCH']['dateend'] : null;
        } elseif ($formsearch !== 'JSJOBS_SEARCH') {
            unset($_SESSION['JSJOBS_SEARCH']);
        }
        if ($searchjobcategory)
            if (is_numeric($searchjobcategory) == false)
                return false;
        $inquery = '';
        if ($searchcompany) {
            $inquery = " AND LOWER(company.name) LIKE '%$searchcompany%'";
        }
        if ($jsjobs_company) {
            $inquery = " AND LOWER(company.name) LIKE '%$jsjobs_company%'";
        }
        if ($jsjobs_city) {
			if(is_numeric($jsjobs_city)){
				$inquery .= " AND company.city = $jsjobs_city ";
			}else{
				$arr = explode( ',' , $jsjobs_city);
				$cityQuery = false;
				foreach($arr as $i){
					if($cityQuery){
						$cityQuery .= " OR company.city = $i ";
					}else{
						$cityQuery = " company.city = $i ";
					}
				}
				$inquery .= " AND ( $cityQuery ) ";
			}
        }
        if ($searchjobcategory) {
            $inquery .= " AND company.category = " . $searchjobcategory;
        }
        if (is_numeric($status)) {
            $inquery .= " AND company.status = " . $status;
        }

        if ($datestart != null) {
            $datestart = date('Y-m-d',strtotime($datestart));
            $inquery .= " AND DATE(company.created) >= '" . $datestart . "'";
        }

        if ($dateend != null) {
            $dateend = date('Y-m-d',strtotime($dateend));
            $inquery .= " AND DATE(company.created) <= '" . $dateend . "'";
        }
        $curdate = date('Y-m-d');
        jsjobs::$_data['filter']['jsjobs-company'] = $jsjobs_company;
        jsjobs::$_data['filter']['jsjobs-city'] = JSJOBSincluder::getJSModel('common')->getCitiesForFilter($jsjobs_city);
        jsjobs::$_data['filter']['searchcompany'] = $searchcompany;
        jsjobs::$_data['filter']['searchjobcategory'] = $searchjobcategory;
        jsjobs::$_data['filter']['status'] = $status;
        jsjobs::$_data['filter']['datestart'] = $datestart;
        jsjobs::$_data['filter']['dateend'] = $dateend;
        //Pagination
        $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_companies AS company WHERE company.status != 0";
        $query .=$inquery;

        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT company.uid,company.name,CONCAT(company.alias,'-',company.id) AS aliasid,
                company.isfeaturedcompany, company.city, company.created,company.logofilename,
                company.status,company.url,company.id, cat.cat_title,company.params
                FROM " . jsjobs::$_db->prefix . "js_job_companies AS company  
                LEFT JOIN " . jsjobs::$_db->prefix . "js_job_categories AS cat ON company.category = cat.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = (SELECT cityid FROM `" . jsjobs::$_db->prefix . "js_job_companycities` WHERE companyid = company.id ORDER BY id DESC LIMIT 1) 
                WHERE company.status != 0";

        $query .=$inquery;

        $query .= " ORDER BY " . jsjobs::$_data['sorting'] . " LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;
        $results = jsjobsdb::get_results($query);
        $data = array();
        foreach ($results AS $d) {
            $d->location = JSJOBSincluder::getJSModel('city')->getLocationDataForView($d->city);
            $data[] = $d;
        }
        jsjobs::$_data[0] = $data;
        jsjobs::$_data['fields'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforView(1);
        jsjobs::$_data['config'] = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('company');
        return;
    }

    function getAllUnapprovedCompanies() {
        $this->sorting();
        //Filters
        $searchcompany = JSJOBSrequest::getVar('searchcompany');
        $categoryid = JSJOBSrequest::getVar('searchjobcategory');
        $datestart = JSJOBSrequest::getVar('datestart');
        $dateend = JSJOBSrequest::getVar('dateend');

        $formsearch = JSJOBSrequest::getVar('JSJOBS_form_search', 'post');
        if ($formsearch == 'JSJOBS_SEARCH') {
            $_SESSION['JSJOBS_SEARCH']['searchcompany'] = $searchcompany;
            $_SESSION['JSJOBS_SEARCH']['searchjobcategory'] = $categoryid;
            $_SESSION['JSJOBS_SEARCH']['datestart'] = $datestart;
            $_SESSION['JSJOBS_SEARCH']['dateend'] = $dateend;
        }
        if (JSJOBSrequest::getVar('pagenum', 'get', null) != null) {
            $searchcompany = (isset($_SESSION['JSJOBS_SEARCH']['searchcompany']) && $_SESSION['JSJOBS_SEARCH']['searchcompany'] != '') ? $_SESSION['JSJOBS_SEARCH']['searchcompany'] : null;
            $categoryid = (isset($_SESSION['JSJOBS_SEARCH']['searchjobcategory']) && $_SESSION['JSJOBS_SEARCH']['searchjobcategory'] != '') ? $_SESSION['JSJOBS_SEARCH']['searchjobcategory'] : null;
            $datestart = (isset($_SESSION['JSJOBS_SEARCH']['datestart']) && $_SESSION['JSJOBS_SEARCH']['datestart'] != '') ? $_SESSION['JSJOBS_SEARCH']['datestart'] : null;
            $dateend = (isset($_SESSION['JSJOBS_SEARCH']['dateend']) && $_SESSION['JSJOBS_SEARCH']['dateend'] != '') ? $_SESSION['JSJOBS_SEARCH']['dateend'] : null;
        } elseif ($formsearch !== 'JSJOBS_SEARCH') {
            unset($_SESSION['JSJOBS_SEARCH']);
        }

        jsjobs::$_data['filter']['searchcompany'] = $searchcompany;
        jsjobs::$_data['filter']['searchjobcategory'] = $categoryid;
        jsjobs::$_data['filter']['datestart'] = $datestart;
        jsjobs::$_data['filter']['dateend'] = $dateend;

        $inquery = '';
        if ($searchcompany)
            $inquery = " AND LOWER(company.name) LIKE '%$searchcompany%'";
        if (is_numeric($categoryid))
            $inquery .= " AND company.category =" . $categoryid;

        if ($datestart != null) {
            $datestart = date('Y-m-d',strtotime($datestart));
            $inquery .= " AND DATE(company.created) >= '" . $datestart . "'";
        }

        if ($dateend != null) {
            $dateend = date('Y-m-d',strtotime($dateend));
            $inquery .= " AND DATE(company.created) <= '" . $dateend . "'";
        }

        //Pagination
        $query = "SELECT COUNT(company.id) 
                    FROM " . jsjobs::$_db->prefix . "js_job_companies AS company 
                    LEFT JOIN " . jsjobs::$_db->prefix . "js_job_categories AS cat ON company.category = cat.id
                    WHERE (company.status = 0 )";
        $query .=$inquery;

        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT company.*, cat.cat_title  
                FROM " . jsjobs::$_db->prefix . "js_job_companies AS company  
                LEFT JOIN " . jsjobs::$_db->prefix . "js_job_categories AS cat ON company.category = cat.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = (SELECT cityid FROM `" . jsjobs::$_db->prefix . "js_job_companycities` WHERE companyid = company.id ORDER BY id DESC LIMIT 1) 
                WHERE (company.status = 0 )";
        $query .=$inquery;
        $query .= " ORDER BY " . jsjobs::$_data['sorting'] . " LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;

        jsjobs::$_data[0] = jsjobsdb::get_results($query);
        jsjobs::$_data['fields'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforView(1);
        // print_r(jsjobs::$_data[0]);       
        return;
    }

    function storeCompany($data) {
        if (empty($data))
            return false;
        $row = JSJOBSincluder::getJSTable('company');
        $filerealpath = "";
        
        $dateformat = jsjobs::$_configuration['date_format'];
        if (isset($data['since']))
            $data['since'] = date('Y-m-d H:i:s', strtotime($data['since']));

        if (isset($data['company_logo_deleted'])) {
            $data['logoisfile'] = '';
            $data['logofilename'] = '';
        }

        $returnvalue = 1;
        if (!empty($data['alias']))
            $companyalias = JSJOBSincluder::getJSModel('common')->removeSpecialCharacter($data['alias']);
        else
            $companyalias = JSJOBSincluder::getJSModel('common')->removeSpecialCharacter($data['name']);

        $companyalias = strtolower(str_replace(' ', '-', $companyalias));
        $data['alias'] = $companyalias;
        if ($data['id'] == '') {
            if (!is_admin()) {
                $data['status'] = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('companyautoapprove');
            }
        }
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        
        $job_editor = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('job_editor');
        if ($job_editor == 1) {
            $data['description'] = wpautop(wptexturize(stripslashes($_POST['description'])));
        }
//custom field code start
        $userfieldforcompany = JSJOBSincluder::getJSModel('fieldordering')->getUserfieldsfor(1);
        $params = array();
        foreach ($userfieldforcompany AS $ufobj) {
            $vardata = isset($data[$ufobj->field]) ? $data[$ufobj->field] : '';
            if($vardata != ''){
                // if($ufobj->userfieldtype == 'multiple'){ // multiple field change behave
                //     $vardata = implode(', ', $vardata); // fixed index
                // }
                if(is_array($vardata)){
                    $vardata = implode(', ', $vardata);
                }
                $params[$ufobj->field] = htmlspecialchars($vardata);
            }
        }
        $params = json_encode($params);
        $data['params'] = $params;

//custom field code end
        if (!$row->bind($data)) {
            return SAVE_ERROR;
        }
        if (!$row->check()) {
            return SAVE_ERROR;
        }
        if (!$row->store()) {
            return SAVE_ERROR;
        }

        // For file upload
        $companyid = $row->id;
        $actionid = 0;
        if ($data['id'] == '') {
        } else {
            $uid = JSJOBSincluder::getObjectClass('user')->uid();
        }
        if (isset($data['city']))
            $storemulticity = $this->storeMultiCitiesCompany($data['city'], $row->id);
        if (isset($storemulticity) && $storemulticity == false)
            return false;
        if ($_FILES['logo']['size'] > 0) { // logo
            $res = $this->uploadFile($companyid);
            if ($res == 6){
                $msg = JSJOBSMessages::getMessage(FILE_TYPE_ERROR, '');
                JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->getMessagekey());
            }
            if($res == 5){
                $msg = JSJOBSMessages::getMessage(FILE_SIZE_ERROR, '');
                JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->getMessagekey());
            }
        }
        //Sending email only new case
        if ($data['id'] == '') {
            JSJOBSincluder::getJSModel('emailtemplate')->sendMail(1, 1, $row->id); // 1 for company,1 for add new company
        }
        return SAVED;
    }

    function storeMultiCitiesCompany($city_id, $companyid) { // city id comma seprated
        if (!is_numeric($companyid))
            return false;


        $query = "SELECT cityid FROM " . jsjobs::$_db->prefix . "js_job_companycities WHERE companyid = " . $companyid;
        $old_cities = jsjobsdb::get_results($query);

        $id_array = explode(",", $city_id);
        $row = JSJOBSincluder::getJSTable('companycities');
        $error = array();

        foreach ($old_cities AS $oldcityid) {
            $match = false;
            foreach ($id_array AS $cityid) {
                if ($oldcityid->cityid == $cityid) {
                    $match = true;
                    break;
                }
            }
            if ($match == false) {
                $query = "DELETE FROM " . jsjobs::$_db->prefix . "js_job_companycities WHERE companyid = " . $companyid . " AND cityid=" . $oldcityid->cityid;

                if (!jsjobsdb::query($query)) {
                    $err = jsjobs::$_db->last_error;
                    $error[] = $err;
                }
            }
        }
        foreach ($id_array AS $cityid) {
            $insert = true;
            foreach ($old_cities AS $oldcityid) {
                if ($oldcityid->cityid == $cityid) {
                    $insert = false;
                    break;
                }
            }
            if ($insert) {
                $cols = array();
                $cols['id'] = "";
                $cols['companyid'] = $companyid;
                $cols['cityid'] = $cityid;
                if (!$row->bind($cols)) {
                    $err = jsjobs::$_db->last_error;
                    $error[] = $err;
                }
                if (!$row->store()) {
                    $err = jsjobs::$_db->last_error;
                    $error[] = $err;
                }
            }
        }
        if (empty($error))
            return true;
        return false;
    }

    function getUidByCompanyId($companyid) {
        if (!is_numeric($companyid))
            return false;
        $query = "SELECT uid FROM `" . jsjobs::$_db->prefix . "js_job_companies` WHERE id = " . $companyid;
        $uid = jsjobsdb::get_var($query);
        return $uid;
    }

    function deleteCompanies($ids) {
        if (empty($ids))
            return false;
        $row = JSJOBSincluder::getJSTable('company');
        $notdeleted = 0;
        foreach ($ids as $id) {
            $query = "SELECT company.name,company.contactemail AS contactemail,company.contactname FROM `" . jsjobs::$_db->prefix . "js_job_companies` AS company  WHERE company.id = " . $id;
            $companyinfo = jsjobsdb::get_row($query);
            $_SESSION['companyname'] = $companyinfo->name;
            $_SESSION['contactname'] = $companyinfo->contactname;
            $_SESSION['contactemail'] = $companyinfo->contactemail;
            if ($this->companyCanDelete($id) == true) {
                if (!$row->delete($id)) {
                    $notdeleted += 1;
                } else {
                    $query = "DELETE FROM `" . jsjobs::$_db->prefix . "js_job_companycities` WHERE companyid = " . $id;
                    jsjobsdb::query($query);
                    JSJOBSincluder::getJSModel('emailtemplate')->sendMail(1, 2, $id); // 1 for company,2 for delete company

                    $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                    $wpdir = wp_upload_dir();
                    array_map('unlink', glob($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$id."/logo/*.*"));//deleting files
                    if(is_dir($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$id."/logo")){
                        rmdir($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$id."/logo");
                        rmdir($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$id);
                    }
                }
            } else {
                $notdeleted += 1;
            }
        }
        if ($notdeleted == 0) {
            JSJOBSMessages::$counter = false;
            return DELETED;
        } else {
            JSJOBSMessages::$counter = $notdeleted;
            return DELETE_ERROR;
        }
    }

    function companyCanDelete($companyid) {
        if (!is_numeric($companyid))
            return false;
        if(!is_admin()){
            if(!$this->getIfCompanyOwner($companyid)){
                return false;
            }
        }
        $query = "SELECT 
                    ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE companyid = " . $companyid . ") 
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_departments` WHERE companyid = " . $companyid . ")
                    AS total ";
        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function companyEnforceDeletes($companyid) {
        if (empty($companyid))
            return false;
        $row = JSJOBSincluder::getJSTable('company');
        $query1 = "SELECT company.name,company.contactemail AS contactemail,company.contactname FROM `" . jsjobs::$_db->prefix . "js_job_companies` AS company  WHERE company.id = " . $companyid;
        $companyinfo = jsjobsdb::get_row($query1);
        $_SESSION['companyname'] = $companyinfo->name;
        $_SESSION['contactname'] = $companyinfo->contactname;
        $_SESSION['contactemail'] = $companyinfo->contactemail;
        $query = "DELETE  company,job,department,companycity
                    FROM `" . jsjobs::$_db->prefix . "js_job_companies` AS company
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_companycities` AS companycity ON company.id=companycity.companyid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_departments` AS department ON company.id=department.companyid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobs` AS job ON company.id=job.companyid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobapply` AS apply ON job.id=apply.jobid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobcities` AS jobcity ON job.id=jobcity.jobid
                    WHERE company.id =" . $companyid;
        if (!jsjobsdb::query($query)) {
            return DELETE_ERROR;
        }
        JSJOBSincluder::getJSModel('emailtemplate')->sendMail(1, 2, $companyid); // 1 for company,2 for delete company
        
        $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        $wpdir = wp_upload_dir();
        $file = $wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$companyid."/logo/*.*";
        $files = glob($file);
        array_map('unlink', $files);//deleting files
        rmdir($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$companyid."/logo");
        rmdir($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$companyid);
        
        return DELETED;
    }

    function getCompanyForCombo($uid = null) {
        $query = "SELECT id, name AS text FROM `" . jsjobs::$_db->prefix . "js_job_companies` WHERE status = 1 ";
        if ($uid != null) {
            if (!is_numeric($uid))
                return false;
            $query .= " AND uid = " . $uid;
        }
        $query .= " ORDER BY id ASC ";
        $companies = jsjobsdb::get_results($query);
        if (jsjobs::$_db->last_error != null) {
            return false;
        }
        return $companies;
    }

    function deletecompanylogo() {
        $cid = JSJOBSrequest::getVar('companyid');
        if (!is_numeric($cid))
            return false;
        $row = JSJOBSincluder::getJSTable('company');
        $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        $wpdir = wp_upload_dir();
        $path = $wpdir['basedir'] . '/' . $data_directory . '/data/employer/comp_' . $cid . '/logo';
        $files = glob($path . '/*.*');
        array_map('unlink', $files);    // delete all file in the direcoty 
        $query = "UPDATE `".jsjobs::$_db->prefix."js_job_companies` SET logofilename = '', logoisfile = -1 WHERE id = ".$cid;
        jsjobs::$_db->query($query);
        return true;
    }

    function uploadFile($id) {
        $result =  JSJOBSincluder::getObjectClass('uploads')->uploadCompanyLogo($id);
        return $result;
    }

    function approveQueueCompanyModel($id) {
        if (is_numeric($id) == false)
            return false;
        $row = JSJOBSincluder::getJSTable('company');
        if($row->load($id)){
            $row->columns['status'] = 1;
            if(!$row->store()){
                return APPROVE_ERROR;
            }
        }else{
            return APPROVE_ERROR;
        }
        //send email
        $company_queue_approve_email = JSJOBSincluder::getJSModel('emailtemplate')->sendMail(1, 3, $id); // 1 for company, 3 for company approve
        return APPROVED;
    }

    function rejectQueueCompanyModel($id) {
        if (is_numeric($id) == false)
            return false;
        $row = JSJOBSincluder::getJSTable('company');
        if (!$row->update(array('id' => $id, 'status' => -1))) {
            return APPROVE_ERROR;
        }
        //send email
        $company_approve_email = JSJOBSincluder::getJSModel('emailtemplate')->sendMail(1, 3, $id); // 1 for company, 3 for company reject
        return APPROVED;
    }


    function approveQueueAllCompaniesModel($id, $actionid) {
        /*
         * *  4 for All
         */
        if (!is_numeric($id))
            return false;

        $result = $this->approveQueueCompanyModel($id);
        return $result;
    }

    function rejectQueueAllCompaniesModel($id, $actionid) {
        /*
         * *  4 for All
         */
        if (!is_numeric($id))
            return false;

        $result = $this->rejectQueueCompanyModel($id);
        return $result;
    }

    function getCompaniesForCombo() {
        $query = "SELECT id, name AS text FROM `" . jsjobs::$_db->prefix . "js_job_companies` WHERE status = 1 ORDER BY name ASC ";
        $rows = jsjobsdb::get_results($query);
        return $rows;
    }

    function getUserCompaniesForCombo() {
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        if(!is_numeric($uid)) return false;
        $query = "SELECT id, name AS text FROM `" . jsjobs::$_db->prefix . "js_job_companies` WHERE uid = " . $uid . " AND status = 1 ORDER BY name ASC ";
        $rows = jsjobsdb::get_results($query);
        return $rows;
    }

    function getMyCompanies($uid) {
        if (!is_numeric($uid)) return false;
        //Filters
        $searchcompany = JSJOBSrequest::getVar('searchcompany');
        $searchcompcategory = JSJOBSrequest::getVar('searchcompcategory');
        //Front end search var
        $jsjobs_city = JSJOBSrequest::getVar('jsjobs-city');
        $formsearch = JSJOBSrequest::getVar('JSJOBS_form_search', 'post');
        if ($formsearch == 'JSJOBS_SEARCH') {
            $_SESSION['JSJOBS_SEARCH']['searchcompany'] = $searchcompany;
            $_SESSION['JSJOBS_SEARCH']['searchcompcategory'] = $searchcompcategory;
            $_SESSION['JSJOBS_SEARCH']['jsjobs_city'] = $jsjobs_city;
        }
        if (JSJOBSrequest::getVar('pagenum', 'get', null) != null) {
            $searchcompany = (isset($_SESSION['JSJOBS_SEARCH']['searchcompany']) && $_SESSION['JSJOBS_SEARCH']['searchcompany'] != '') ? $_SESSION['JSJOBS_SEARCH']['searchcompany'] : null;
            $searchcompcategory = (isset($_SESSION['JSJOBS_SEARCH']['searchcompcategory']) && $_SESSION['JSJOBS_SEARCH']['searchcompcategory'] != '') ? $_SESSION['JSJOBS_SEARCH']['searchcompcategory'] : null;
            $jsjobs_city = (isset($_SESSION['JSJOBS_SEARCH']['jsjobs_city']) && $_SESSION['JSJOBS_SEARCH']['jsjobs_city'] != '') ? $_SESSION['JSJOBS_SEARCH']['jsjobs_city'] : null;
        } elseif ($formsearch !== 'JSJOBS_SEARCH') {
            unset($_SESSION['JSJOBS_SEARCH']);
        }
        if ($searchcompcategory)
            if (is_numeric($searchcompcategory) == false)
                return false;
        $inquery = '';
        if ($searchcompany) {
            $inquery = " AND LOWER(company.name) LIKE '%$searchcompany%'";
        }
        if ($jsjobs_city) {
            if(is_numeric($jsjobs_city)){
                $inquery .= " AND LOWER(company.city) LIKE '%$jsjobs_city%'";
            }else{
                $arr = explode( ',' , $jsjobs_city);
                $cityQuery = false;
                foreach($arr as $i){
                    if($cityQuery){
                        $cityQuery .= " OR LOWER(company.city) LIKE '%$i%' ";
                    }else{
                        $cityQuery = " LOWER(company.city) LIKE '%$i%' ";
                    }
                }
                $inquery .= " AND ( $cityQuery ) ";
            }
        }
        if ($searchcompcategory) {
            $inquery .= " AND company.category = " . $searchcompcategory;
        }

        jsjobs::$_data['filter']['jsjobs-city'] = JSJOBSincluder::getJSModel('common')->getCitiesForFilter($jsjobs_city);
        jsjobs::$_data['filter']['searchcompany'] = $searchcompany;
        jsjobs::$_data['filter']['searchcompcategory'] = $searchcompcategory;

        //Pagination
        $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_companies AS company WHERE uid = " . $uid;
        $query .=$inquery;

        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);
        //Data
        $query = "SELECT company.id,company.name,company.logofilename,CONCAT(company.alias,'-',company.id) AS aliasid,company.created,company.serverid,company.city,company.status,company.isgoldcompany,company.isfeaturedcompany
                 ,cat.cat_title,company.params,company.url
                FROM " . jsjobs::$_db->prefix . "js_job_companies AS company  
                LEFT JOIN " . jsjobs::$_db->prefix . "js_job_categories AS cat ON cat.id = company.category
                WHERE company.uid = " . $uid;
        $query .=$inquery;
        $query .= " ORDER BY company.created DESC LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);
        $data = array();
        foreach (jsjobs::$_data[0] AS $d) {
            $d->location = JSJOBSincluder::getJSModel('city')->getLocationDataForView($d->city);
            $data[] = $d;
        }
        jsjobs::$_data[0] = $data;
        jsjobs::$_data['fields'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforView(1);
        jsjobs::$_data['config'] = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('company');
        return;
    }

    function getCompanynameById($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT company.name FROM `" . jsjobs::$_db->prefix . "js_job_companies` AS company WHERE company.id = " . $id;
        $companyname = jsjobs::$_db->get_var($query);
        return $companyname;
    }

    function addViewContactDetail($companyid, $uid) {
        if (!is_numeric($companyid))
            return false;
        if (!is_numeric($uid))
            return false;
        
        $data = array();
        $data['uid'] = $uid;
        $data['companyid'] = $companyid;
        $data['status'] = 1;
        $data['created'] = $curdate;

        $row = JSJOBSincluder::getJSTable('jobseekerviewcompany');
        if (!$row->bind($data)) {
            return false;
        }

        if ($row->store()) {
            return true;
        }else{
            return false;
        }
    }

    function canAddCompany($uid) {
        if (!is_numeric($uid))
            return false;
        return true;
    }

    function employerHaveCompany($uid) {
        if (!is_numeric($uid))
            return false;
        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_companies` WHERE uid = " . $uid;
        $result = jsjobs::$_db->get_var($query);
        if ($result == 0) {
            return false;
        } else {
            return true;
        }
    }
    
    function makeCompanySeo($company_seo , $jsjobid){
        if(empty($company_seo))
            return '';

        $common = JSJOBSincluder::getJSModel('common');
        $id = $common->parseID($jsjobid);
        if(! is_numeric($id))
            return '';        
        $result = '';
        $company_seo = str_replace( ' ', '', $company_seo);
        $company_seo = str_replace( '[', '', $company_seo);
        $array = explode(']', $company_seo);

        $total = count($array);
        if($total > 3)
            $total = 3;

        for ($i=0; $i < $total; $i++) { 
            $query = '';
            switch ($array[$i]) {
                case 'name':
                    $query = "SELECT name AS col FROM `" . jsjobs::$_db->prefix . "js_job_companies` WHERE id = " . $id;
                break;
                case 'category':
                    $query = "SELECT category.cat_title AS col 
                        FROM `" . jsjobs::$_db->prefix . "js_job_companies` AS company 
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS category ON category.id = company.category 
                        WHERE company.id = " . $id;
                break;
                case 'location':
                    $query = "SELECT company.city AS col 
                        FROM `" . jsjobs::$_db->prefix . "js_job_companies` AS company WHERE company.id = " . $id;
                break;
            }
            if($query){
                $data = jsjobsdb::get_row($query);
                if(isset($data->col)){
                    if($array[$i] == 'location'){
                        $cityids = explode(',', $data->col);
                        $location = '';
                        for ($j=0; $j < count($cityids); $j++) { 
                            if(is_numeric($cityids[$j])){
                                $query = "SELECT name FROM `" . jsjobs::$_db->prefix . "js_job_cities` WHERE id = ". $cityids[$j];
                                $cityname = jsjobsdb::get_row($query);
                                if(isset($cityname->name)){
                                    if($location == '')
                                        $location .= $cityname->name;
                                    else
                                        $location .= ' '.$cityname->name;

                                }
                            }
                        }
                        $location = $common->removeSpecialCharacter($location);
                        if($location != ''){
                            if($result == '')
                                $result .= str_replace(' ', '-', $location);
                            else
                                $result .= '-'.str_replace(' ', '-', $location);                            
                        }
                    }else{
                        $val = $common->removeSpecialCharacter($data->col);
                        if($result == '')
                            $result .= str_replace(' ', '-', $val);
                        else
                            $result .= '-'.str_replace(' ', '-', $val);
                    }
                }
            }
        }
        return $result;
    }    

    function getCompanyExpiryStatus($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT company.id 
        FROM `" . jsjobs::$_db->prefix . "js_job_companies` AS company 
        WHERE company.status = 1
        AND company.id =" . $id;
        $result = jsjobs::$_db->get_var($query);
        if ($result == null) {
            return false;
        } else {
            return true;
        }
    }

    function getIfCompanyOwner($id) {
        if (!is_numeric($id))
            return false;
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        $query = "SELECT company.id 
        FROM `" . jsjobs::$_db->prefix . "js_job_companies` AS company 
        WHERE company.uid = " . $uid . " 
        AND company.id =" . $id;
        $result = jsjobs::$_db->get_var($query);
        if ($result == null) {
            return false;
        } else {
            return true;
        }
    }
    function getMessagekey(){
        $key = 'company';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }


}
?>
