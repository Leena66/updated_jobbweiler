<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSdepartmentsModel {

    function getDepartmentById($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $query = "SELECT department.* FROM `" . jsjobs::$_db->prefix . "js_job_departments` AS department WHERE department.id=" . $c_id;
        jsjobs::$_data[0] = jsjobsdb::get_row($query);
        return;
    }

    function getViewDepartment($id) {
        if (is_numeric($id) == false)
            return false;
        $query = "SELECT department.name,department.description,company.name AS companyname
            		FROM `" . jsjobs::$_db->prefix . "js_job_departments` AS department
                    JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company
                    ON company.id = department.companyid
                    WHERE department.id = " . $id;
        jsjobs::$_data[0] = jsjobsdb::get_row($query);
        return;
    }

    function getDepartments($companyid) {
        //Filters
        $departmentname = JSJOBSrequest::getVar('departmentname');
        $companyname = JSJOBSrequest::getVar('companyname');
        $status = JSJOBSrequest::getVar('status');
        $formsearch = JSJOBSrequest::getVar('JSJOBS_form_search', 'post');
        if ($formsearch == 'JSJOBS_SEARCH') {
            $_SESSION['JSJOBS_SEARCH']['departmentname'] = $departmentname;
            $_SESSION['JSJOBS_SEARCH']['companyname'] = $companyname;
            $_SESSION['JSJOBS_SEARCH']['status'] = $status;
        }
        if (JSJOBSrequest::getVar('pagenum', 'get', null) != null) {
            $departmentname = (isset($_SESSION['JSJOBS_SEARCH']['departmentname']) && $_SESSION['JSJOBS_SEARCH']['departmentname'] != '') ? $_SESSION['JSJOBS_SEARCH']['departmentname'] : null;
            $companyname = (isset($_SESSION['JSJOBS_SEARCH']['companyname']) && $_SESSION['JSJOBS_SEARCH']['companyname'] != '') ? $_SESSION['JSJOBS_SEARCH']['companyname'] : null;
            $status = (isset($_SESSION['JSJOBS_SEARCH']['status']) && $_SESSION['JSJOBS_SEARCH']['status'] != '') ? $_SESSION['JSJOBS_SEARCH']['status'] : null;
        } elseif ($formsearch !== 'JSJOBS_SEARCH') {
            unset($_SESSION['JSJOBS_SEARCH']);
        }
        $inquery = " WHERE department.status != 0 ";
        if ($departmentname) {
            $inquery .= " AND department.name LIKE '%" . $departmentname . "%' ";
        }
        if ($companyname) {
            $inquery .= " AND company.name LIKE '%" . $companyname . "%' ";
        }if (is_numeric($status)) {
            $inquery .= " AND department.status = " . $status;
        }
        if (is_numeric($companyid)) {
            $inquery .= " AND company.id = " . $companyid;
            $query = "SELECT name FROM `" . jsjobs::$_db->prefix . "js_job_companies` WHERE id = " . $companyid;
            jsjobs::$_data[0]['companyname'] = jsjobsdb::get_var($query);
        }
        jsjobs::$_data['filter']['departmentname'] = $departmentname;
        jsjobs::$_data['filter']['companyname'] = $companyname;
        jsjobs::$_data['filter']['status'] = $status;

        //pagination
        $query = "SELECT COUNT(department.id)
            FROM `" . jsjobs::$_db->prefix . "js_job_departments` AS department
            JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON company.id = department.companyid";
        $query .= $inquery;

        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT department.*, company.name as companyname
            FROM `" . jsjobs::$_db->prefix . "js_job_departments` AS department
            JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON company.id = department.companyid";
        $query .= $inquery;
        $query.=" LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;

        jsjobs::$_data[0]['department'] = jsjobsdb::get_results($query);

        return;
    }

    function getMyDepartments($uid, $companyid) {
        if (!is_numeric($uid))
            return false;
        $departmentname = JSJOBSrequest::getVar('departmentname');
        $companyname = JSJOBSrequest::getVar('companyname');
        $status = JSJOBSrequest::getVar('status');
        $formsearch = JSJOBSrequest::getVar('JSJOBS_form_search', 'post');
        if ($formsearch == 'JSJOBS_SEARCH') {
            $_SESSION['JSJOBS_SEARCH']['departmentname'] = $departmentname;
            $_SESSION['JSJOBS_SEARCH']['companyname'] = $companyname;
            $_SESSION['JSJOBS_SEARCH']['status'] = $status;
        }
        if (JSJOBSrequest::getVar('pagenum', 'get', null) != null) {
            $departmentname = (isset($_SESSION['JSJOBS_SEARCH']['departmentname']) && $_SESSION['JSJOBS_SEARCH']['departmentname'] != '') ? $_SESSION['JSJOBS_SEARCH']['departmentname'] : null;
            $companyname = (isset($_SESSION['JSJOBS_SEARCH']['companyname']) && $_SESSION['JSJOBS_SEARCH']['companyname'] != '') ? $_SESSION['JSJOBS_SEARCH']['companyname'] : null;
            $status = (isset($_SESSION['JSJOBS_SEARCH']['status']) && $_SESSION['JSJOBS_SEARCH']['status'] != '') ? $_SESSION['JSJOBS_SEARCH']['status'] : null;
        } elseif ($formsearch !== 'JSJOBS_SEARCH') {
            unset($_SESSION['JSJOBS_SEARCH']);
        }
        //$inquery = " WHERE department.status != 0 ";
        $inquery = "";
        if ($departmentname) {
            $inquery .= " AND department.name LIKE '%" . $departmentname . "%' ";
        }
        if ($companyname) {
            $inquery .= " AND company.name LIKE '%" . $companyname . "%' ";
        }if (is_numeric($status)) {
            $inquery .= " AND department.status = " . $status;
        }
        if (is_numeric($companyid)) {
            $inquery .= " AND company.id = " . $companyid;
            $query = "SELECT name FROM `" . jsjobs::$_db->prefix . "js_job_companies` WHERE id = " . $companyid;
            jsjobs::$_data[0]['companyname'] = jsjobsdb::get_var($query);
        }
        jsjobs::$_data['filter']['departmentname'] = $departmentname;
        jsjobs::$_data['filter']['companyname'] = $companyname;
        jsjobs::$_data['filter']['status'] = $status;

        /*//Filters
        $departmentname = JSJOBSrequest::getVar('departmentname');
        $companyname = JSJOBSrequest::getVar('companyname');

        jsjobs::$_data['filter']['departmentname'] = $departmentname;
        jsjobs::$_data['filter']['companyname'] = $companyname;

        $inquery = "";
        if ($departmentname) {
            $inquery = " AND department.name LIKE '%" . $departmentname . "%' ";
        }
        if ($companyname) {
            $inquery .= " AND company.name LIKE '%" . $companyname . "%' ";
        }

        if (is_numeric($companyid)) {
            $inquery .= " AND company.id = " . $companyid;
        }*/

        //pagination
        $query = "SELECT COUNT(department.id)
			FROM `" . jsjobs::$_db->prefix . "js_job_departments` AS department
			JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON company.id = department.companyid
            WHERE department.uid = " . $uid;
        $query .= $inquery;

        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT department.id,department.uid,department.name,department.status,department.created,company.name as companyname,department.companyid,CONCAT(company.alias,'-',company.id) AS companyalias
			FROM `" . jsjobs::$_db->prefix . "js_job_departments` AS department
			JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON company.id = department.companyid
            WHERE department.uid = " . $uid;
        $query .= $inquery;
        $query.=" LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);

        return;
    }

    function getAllUnapprovedDepartments() {

        //Filters
        $searchcompany = JSJOBSrequest::getVar('companyname');
        $searchdepartment = JSJOBSrequest::getVar('departmentname');
        $formsearch = JSJOBSrequest::getVar('JSJOBS_form_search', 'post');
        if ($formsearch == 'JSJOBS_SEARCH') {
            $_SESSION['JSJOBS_SEARCH']['departmentname'] = $searchdepartment;
            $_SESSION['JSJOBS_SEARCH']['companyname'] = $searchcompany;
        }
        if (JSJOBSrequest::getVar('pagenum', 'get', null) != null) {
            $searchdepartment = (isset($_SESSION['JSJOBS_SEARCH']['departmentname']) && $_SESSION['JSJOBS_SEARCH']['departmentname'] != '') ? $_SESSION['JSJOBS_SEARCH']['departmentname'] : null;
            $searchcompany = (isset($_SESSION['JSJOBS_SEARCH']['companyname']) && $_SESSION['JSJOBS_SEARCH']['companyname'] != '') ? $_SESSION['JSJOBS_SEARCH']['companyname'] : null;
        } elseif ($formsearch !== 'JSJOBS_SEARCH') {
            unset($_SESSION['JSJOBS_SEARCH']);
        }
        jsjobs::$_data['filter']['companyname'] = $searchcompany;
        jsjobs::$_data['filter']['departmentname'] = $searchdepartment;

        $inquery = "";
        if ($searchcompany)
            $inquery .= " AND LOWER(company.name) LIKE '%" . $searchcompany . "%'";
        if ($searchdepartment)
            $inquery .= " AND LOWER(department.name) LIKE '%" . $searchdepartment . "%'";

        //Pagination
        $query = "SELECT COUNT(department.id)
			FROM `" . jsjobs::$_db->prefix . "js_job_departments` AS department
			JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON company.id = department.companyid
			WHERE department.status = 0";
        $query.=$inquery;

        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT department.*, company.name as companyname
			FROM `" . jsjobs::$_db->prefix . "js_job_departments` AS department
			JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON company.id = department.companyid
			WHERE department.status = 0";
        $query.=$inquery;
        $query.=" LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);
        return;
    }

    function storeDepartment($data) {
        if (empty($data))
            return false;

        $row = JSJOBSincluder::getJSTable('department');
        if (!empty($data['alias']))
            $departmentalias = JSJOBSincluder::getJSModel('common')->removeSpecialCharacter($data['alias']);
        else
            $departmentalias = JSJOBSincluder::getJSModel('common')->removeSpecialCharacter($data['name']);

        $departmentalias = strtolower(str_replace(' ', '-', $departmentalias));
        $data['alias'] = $departmentalias;
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        $data['description'] = wpautop(wptexturize(stripslashes($_POST['description'])));

        $data['uid'] = JSJOBSincluder::getJSModel('company')->getUidByCompanyId($data['companyid']); // Uid must be the same as the company owner id

        if ($data['id'] == ''){
            $data['created'] = date("Y-m-d H:i:s");
            if (!is_admin()) {
                $data['status'] = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('department_auto_approve');
            }
        }
        if (!$row->bind($data)) {
            return SAVE_ERROR;
        }
        if (!$row->check()) {
            return SAVE_ERROR;
        }
        if (!$row->store()) {
            return SAVE_ERROR;
        }

        return SAVED;
    }

    function deleteDepartments($ids) {
        if (empty($ids))
            return false;
        $row = JSJOBSincluder::getJSTable('department');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if ($this->departmentCanDelete($id) == true) {
                if (!$row->delete($id)) {
                    $notdeleted += 1;
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

    function departmentCanDelete($departmentid) {
        if (!is_numeric($departmentid))
            return false;
        if(!is_admin()){
            if(!$this->getIfDepartmentOwner($departmentid)){
                return false;
            }
        }
        $query = "SELECT
            ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE departmentid = " . $departmentid . ")
            AS total ";

        $total = jsjobsdb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function getDepartmentForCombo($uid = null) {
        if ($uid != null) {
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        }else {
            $uid = JSJOBSincluder::getObjectClass('user')->uid();
        }
        if(!$uid) return false;
        $query = "SELECT id, name as text FROM `" . jsjobs::$_db->prefix . "js_job_departments` WHERE uid = " . $uid . " AND status = 1  ORDER BY name ASC ";
        $rows = jsjobsdb::get_results($query);
        if (jsjobs::$_db->last_error != null) {

            return false;
        }
        return $rows;
    }

    function listDepartments() {
        $return_value = '';
        $val = JSJOBSrequest::getVar('val');
        $themecall = JSJOBSrequest::getVar('themecall');
        $themeclass="";
        if($themecall){
            if(function_exists("getJobManagerThemeClass")){
                $themeclass = getJobManagerThemeClass("select");
            }
        }
        if (is_numeric($val) === false)
            return false;
        $query = "SELECT published,isvisitorpublished,required FROM " . jsjobs::$_db->prefix . "js_job_fieldsordering WHERE  field='department' AND fieldfor = 2";
        $authentication = jsjobsdb::get_row($query);
        if (JSJOBSincluder::getObjectClass('user')->isguest() == true) {
            $published = $authentication->isvisitorpublished;
        } else {
            $published = $authentication->published;
        }
        if ($published == 1) {
            $query = "SELECT id, name FROM " . jsjobs::$_db->prefix . "js_job_departments  WHERE status = 1 AND companyid = " . $val . " ORDER BY name ASC";
            $result = jsjobsdb::get_results($query);
            $required = ($authentication->required == 1) ? 'data-validation="required"' : '';
            $return_value = "<select name='departmentid' class='inputbox one $themeclass' $required >\n";
            foreach ($result as $row) {
                $return_value .= "<option value=\"$row->id\" >$row->name</option> \n";
            }
            $return_value .= "</select>\n";
        }
        return $return_value;
    }

    function departmentsApprove($ids) {
        if (empty($ids))
            return false;

        $row = JSJOBSincluder::getJSTable('department');
        $total = 0;
        $status = 1;
        foreach ($ids as $id) {
            if (!is_numeric($id))
                $total +=1;

            if (!$row->update(array('id' => $id, 'status' => $status))) {
                $total += 1;
            }
        }

        if ($total != 0) {
            JSJOBSMessages::$counter = $total;
            return APPROVE_ERROR;
        } else {
            return APPROVED;
        }
    }

    function canAddDepartment($uid) {
        if (!is_numeric($uid))
            return false;
        return true;
    }

    function departmentsReject($ids) {
        if (empty($ids))
            return false;

        $total = 0;
        $row = JSJOBSincluder::getJSTable('department');
        $status = -1;
        foreach ($ids as $id) {
            if (!is_numeric($id))
                $total +=1;
            if (!$row->update(array('id' => $id, 'status' => $status))) {
                $total += 1;
            }
        }

        if ($total != 0) {
            JSJOBSMessages::$counter = $total;
            return REJECT_ERROR;
        } else {
            return REJECTED;
        }
    }

    function getIfDepartmentOwner($departentid) {
        if (!is_numeric($departentid))
            return false;
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        $query = "SELECT departent.id 
        FROM `" . jsjobs::$_db->prefix . "js_job_departments` AS departent 
        WHERE departent.uid = " . $uid . " 
        AND departent.id =" . $departentid;
        $result = jsjobs::$_db->get_var($query);
        if ($result == null) {
            return false;
        } else {
            return true;
        }
    }

    function getMessagekey(){
        $key = 'departments';if(is_admin()){$key = 'admin_'.$key;} return $key;
    }


}

?>