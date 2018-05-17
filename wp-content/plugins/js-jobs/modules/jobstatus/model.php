<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSJobstatusModel {

    function getJobStatusbyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $query = "SELECT * FROM " . jsjobs::$_db->prefix . "js_job_jobstatus WHERE id = " . $c_id;
        jsjobs::$_data[0] = jsjobsdb::get_row($query);

        return;
    }

    function getAllJobStatus() {
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
        } elseif ($formsearch !== 'JSJOBS_SEARCH') {
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
        $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_jobstatus ";
        $query .= $inquery;
        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT * FROM " . jsjobs::$_db->prefix . "js_job_jobstatus $inquery ORDER BY ordering ASC";
        $query .=" LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);

        return;
    }

    function updateIsDefault($id) {
        if (!is_numeric($id))
            return false;
        // DB class limitations
        $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_jobstatus` SET isdefault = 0 WHERE id != " . $id;
        jsjobsdb::query($query);
    }

    function validateFormData(&$data) {
        $canupdate = false;
        if ($data['id'] == '') {
            $result = $this->isJobStatusExist($data['title']);
            if ($result == true) {
                return ALREADY_EXIST;
            } else {
                $query = "SELECT max(ordering)+1 AS maxordering FROM " . jsjobs::$_db->prefix . "js_job_jobstatus";
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

    function storeJobStatus($data) {
        if (empty($data))
            return false;

        $canupdate = $this->validateFormData($data);
        if ($canupdate === ALREADY_EXIST)
            return ALREADY_EXIST;

        $row = JSJOBSincluder::getJSTable('jobstatus');
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

    function deleteJobsStatus($ids) {
        if (empty($ids))
            return false;
        $row = JSJOBSincluder::getJSTable('jobstatus');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if ($this->jobStatusCanDelete($id) == true) {
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

        $row = JSJOBSincluder::getJSTable('jobstatus');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if (!$row->update(array('id' => $id, 'isactive' => $status))) {
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if ($this->jobStatusCanUnpublish($id)) {
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

    function jobStatusCanUnpublish($statusid) {
        if (!is_numeric($statusid))
            return false;
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobstatus` WHERE id = " . $statusid . " AND isdefault = 1)
                    AS total ";
        $total = jsjobsdb::get_var($query);

        if ($total > 0)
            return false;
        else
            return true;
    }

    function jobStatusCanDelete($statusid) {
        if (!is_numeric($statusid))
            return false;
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE jobstatus = " . $statusid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobstatus` WHERE id = " . $statusid . " AND isdefault = 1)
                    AS total ";
        $total = jsjobsdb::get_var($query);

        if ($total > 0)
            return false;
        else
            return true;
    }

    function getJobStatusForCombo() {

        $query = "SELECT id, title AS text FROM `" . jsjobs::$_db->prefix . "js_job_jobstatus` WHERE isactive = 1";
        $query.= " ORDER BY ordering ASC ";

        $rows = jsjobsdb::get_results($query);
        if (jsjobs::$_db->last_error != null) {

            return false;
        }
        return $rows;
    }

    function isJobStatusExist($title) {
        $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_jobstatus WHERE title = '" . $title . "'";
        $result = jsjobsdb::get_var($query);
        if ($result > 0)
            return true;
        else
            return false;
    }

    function getDefaultJobStatusId() {
        $query = "SELECT id FROM " . jsjobs::$_db->prefix . "js_job_jobstatus WHERE isdefault = 1";
        $id = jsjobsdb::get_var($query);

        return $id;
    }
    function getMessagekey(){
        $key = 'jobstatus';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }

}

?>
