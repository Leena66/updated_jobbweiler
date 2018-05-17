<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSJobtypeModel {

    function getJobTypebyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;

        $query = "SELECT * FROM " . jsjobs::$_db->prefix . "js_job_jobtypes WHERE id = " . $c_id;
        jsjobs::$_data[0] = jsjobsdb::get_row($query);
        return;
    }

    function getAllJobTypes() {
        // Filter
        $title = JSJOBSrequest::getVar('title');
        $status = JSJOBSrequest::getVar('status');
        $formsearch = JSJOBSrequest::getVar('JSJOBS_form_search', 'post');
        if ($formsearch == 'JSJOBS_SEARCH') {
            $_SESSION['JSJOBS_SEARCH']['title'] = $title;
            $_SESSION['JSJOBS_SEARCH']['status'] = $status;
        }
        if (JSJOBSrequest::getVar('pagenum', 'get', null) != null) {
            $title = (isset($_SESSION['JSJOBS_SEARCH']['title']) && $_SESSION['JSJOBS_SEARCH']['title'] != '') ? $_SESSION['JSJOBS_SEARCH']['title'] : null;
            $status = (isset($_SESSION['JSJOBS_SEARCH']['status']) && $_SESSION['JSJOBS_SEARCH']['status'] != '') ? $_SESSION['JSJOBS_SEARCH']['status'] : null;
        } else if ($formsearch !== 'JSJOBS_SEARCH') {
            unset($_SESSION['JSJOBS_SEARCH']);
        }
        $inquery = '';
        $clause = ' WHERE ';
        if ($title != null) {
            $inquery .= $clause . "title LIKE '%" . $title . "%'";
            $clause = ' AND ';
        }
        if ($status != null)
            $inquery .=$clause . " isactive = '" . $status . "'";

        jsjobs::$_data['filter']['title'] = $title;
        jsjobs::$_data['filter']['status'] = $status;

        //Pagination
        $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_jobtypes";
        $query .= $inquery;
        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT * FROM " . jsjobs::$_db->prefix . "js_job_jobtypes $inquery ORDER BY ordering ASC";
        $query .=" LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);

        return;
    }

    function updateIsDefault($id) {
        if (!is_numeric($id))
            return false;
        // DB class limiations
        $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_jobtypes` SET isdefault = 0 WHERE id != " . $id;
        jsjobsdb::query($query);
    }

    function validateFormData(&$data) {
        $canupdate = false;

        $alias = JSJOBSincluder::getJSModel('common')->removeSpecialCharacter($data['title']);
        $alias = strtolower(str_replace(' ', '-', $alias));
        $data['alias'] = $alias;

        if ($data['id'] == '') {
            $result = $this->isJobTypesExist($data['title']);
            if ($result == true) {
                return ALREADY_EXIST;
            } else {
                $query = "SELECT max(ordering)+1 AS maxordering FROM " . jsjobs::$_db->prefix . "js_job_jobtypes";
                $data['ordering'] = jsjobsdb::get_var($query);
            }

            if ($data['isactive'] == 0) {
                $data['isdefault'] = 0;
            } else {
                if ($data['isdefault'] == 1) {
                    $canupdate = true;
                }
            }
        } else {
            if ($data['jsjobs_isdefault'] == 1) {
                $data['isdefault'] = 1;
                $data['isactive'] = 1;
            } else {
                if ($data['isactive'] == 0) {
                    $data['isdefault'] = 0;
                } else {
                    if ($data['isdefault'] == 1) {
                        $canupdate = true;
                    }
                }
            }
        }
        return $canupdate;
    }

    function storeJobType($data) {
        if (empty($data))
            return false;

        $canupdate = $this->validateFormData($data);
        if ($canupdate === ALREADY_EXIST)
            return ALREADY_EXIST;

        $row = JSJOBSincluder::getJSTable('jobtype');
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        if (!$row->bind($data)) {
            return SAVE_ERROR;
        }
        if (!$row->store()) {
            return SAVE_ERROR;
        }
        if ($canupdate) {
            $this->updateIsDefault($row->id);
        }

        return SAVED;
    }

    function deleteJobsType($ids) {
        if (empty($ids))
            return false;
        $row = JSJOBSincluder::getJSTable('jobtype');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if ($this->jobTypeCanDelete($id) == true) {
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

    function publishUnpublish($ids, $status) {
        if (empty($ids))
            return false;
        if (!is_numeric($status))
            return false;

        $row = JSJOBSincluder::getJSTable('jobtype');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if (!$row->update(array('id' => $id, 'isactive' => $status))) {
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if ($this->jobTypeCanUnpublish($id)) {
                    if (!$row->update(array('id' => $id, 'isactive' => $status))) {
                        $total += 1;
                    }
                } else {
                    $total += 1;
                }
            }
        }
        if ($total == 0) {
            JSJOBSMessages::$counter = false;
            if ($status == 1)
                return PUBLISHED;
            else
                return UN_PUBLISHED;
        }else {
            JSJOBSMessages::$counter = $total;
            if ($status == 1)
                return PUBLISH_ERROR;
            else
                return UN_PUBLISH_ERROR;
        }
    }

    function jobTypeCanUnpublish($typeid) {
        if (!is_numeric($typeid))
            return false;
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobtypes` WHERE id = " . $typeid . " AND isdefault = 1)
                    AS total ";
        $total = jsjobsdb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function jobTypeCanDelete($typeid) {
        if (!is_numeric($typeid))
            return false;
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE jobtype = " . $typeid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE jobtype = " . $typeid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobtypes` WHERE id = " . $typeid . " AND isdefault = 1)
                    AS total ";
        $total = jsjobsdb::get_var($query);

        if ($total > 0)
            return false;
        else
            return true;
    }

    function getJobTypeForCombo() {

        $query = "SELECT id, title AS text FROM `" . jsjobs::$_db->prefix . "js_job_jobtypes` WHERE isactive = 1";
        $query.= " ORDER BY ordering ASC ";

        $rows = jsjobsdb::get_results($query);
        if (jsjobs::$_db->last_error != null) {

            return false;
        }
        return $rows;
    }

    function isJobTypesExist($title) {
        if (!$title)
            return false;
        $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_jobtypes WHERE title = '" . $title . "'";
        $result = jsjobsdb::get_var($query);
        if ($result > 0)
            return true;
        else
            return false;
    }

    function getDefaultJobTypeId() {
        $query = "SELECT id FROM " . jsjobs::$_db->prefix . "js_job_jobtypes WHERE isdefault = 1";
        $id = jsjobsdb::get_var($query);

        return $id;
    }

    function getTitleByid($id) {
        if(!is_numeric($id)) return false;
        $query = "SELECT title FROM " . jsjobs::$_db->prefix . "js_job_jobtypes WHERE id = " . $id;
        $title = jsjobsdb::get_var($query);
        return $title;
    }
    function getMessagekey(){
        $key = 'jobtype';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }

}

?>
