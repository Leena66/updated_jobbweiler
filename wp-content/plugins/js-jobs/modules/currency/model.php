<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBScurrencyModel {

    function getCurrencybyId($id) {
        if (is_numeric($id) == false)
            return false;

        $query = "SELECT * FROM " . jsjobs::$_db->prefix . "js_job_currencies WHERE id = " . $id;
        jsjobs::$_data[0] = jsjobsdb::get_row($query);
        return;
    }

    function getCurrencyForCombo() {

        $query = "SELECT id, symbol AS text FROM `" . jsjobs::$_db->prefix . "js_job_currencies` WHERE status = 1 ORDER BY ordering ASC";
        $allcurrency = jsjobsdb::get_results($query);
        return $allcurrency;
    }

    function getDefaultCurrency() {

        $query = "SELECT currency.id FROM `" . jsjobs::$_db->prefix . "js_job_currencies` currency WHERE currency.default = 1 AND currency.status=1 ";
        $defaultValue = jsjobsdb::get_row($query);
        if (!$defaultValue) {
            $query = "SELECT id FROM `" . jsjobs::$_db->prefix . "js_job_currencies` WHERE status=1";
            $defaultValue = jsjobsdb::get_results($query);
        }
        return $defaultValue;
    }

    function getAllCurrencies() {
        // Filter
        $title = JSJOBSrequest::getVar('title');
        $status = JSJOBSrequest::getVar('status');
        $code = JSJOBSrequest::getVar('code');
        $formsearch = JSJOBSrequest::getVar('JSJOBS_form_search', 'post');
        if ($formsearch == 'JSJOBS_SEARCH') {
            $_SESSION['JSJOBS_SEARCH']['title'] = $title;
            $_SESSION['JSJOBS_SEARCH']['status'] = $status;
            $_SESSION['JSJOBS_SEARCH']['code'] = $code;
        }
        if (JSJOBSrequest::getVar('pagenum', 'get', null) != null) {
            $title = (isset($_SESSION['JSJOBS_SEARCH']['title']) && $_SESSION['JSJOBS_SEARCH']['title'] != '') ? $_SESSION['JSJOBS_SEARCH']['title'] : null;
            $status = (isset($_SESSION['JSJOBS_SEARCH']['status']) && $_SESSION['JSJOBS_SEARCH']['status'] != '') ? $_SESSION['JSJOBS_SEARCH']['status'] : null;
            $code = (isset($_SESSION['JSJOBS_SEARCH']['code']) && $_SESSION['JSJOBS_SEARCH']['code'] != '') ? $_SESSION['JSJOBS_SEARCH']['code'] : null;
        } elseif ($formsearch !== 'JSJOBS_SEARCH') {
            unset($_SESSION['JSJOBS_SEARCH']);
        }
        $inquery = '';
        $clause = ' WHERE ';
        if ($title != null) {
            $inquery .= $clause . "title LIKE '%" . $title . "%'";
            $clause = ' AND ';
        }
        if (is_numeric($status))
            $inquery .=$clause . " status = " . $status;
        if ($code != null)
            $inquery .=$clause . " code LIKE '%" . $code . "%'";

        jsjobs::$_data['filter']['title'] = $title;
        jsjobs::$_data['filter']['status'] = $status;
        jsjobs::$_data['filter']['code'] = $code;
        //Pagination
        $query = "SELECT count(id) FROM `" . jsjobs::$_db->prefix . "js_job_currencies` ";
        $query .= $inquery;
        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);
        //Data
        $query = "SELECT * FROM `" . jsjobs::$_db->prefix . "js_job_currencies` $inquery ORDER BY ordering ASC ";
        $query .= " LIMIT " . JSJOBSpagination::$_offset . ", " . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);

        return;
    }

    function updateIsDefault($id) {
        if (!is_numeric($id))
            return false;
        //DB class limitations
        $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_currencies` AS cur SET cur.default = 0 WHERE cur.id != " . $id;
        jsjobsdb::query($query);
    }

    function validateFormData(&$data) {
        $canupdate = false;
        if ($data['id'] == '') {
            $result = $this->isCurrencyExist($data['title']);
            if ($result == true) {
                return ALREADY_EXIST;
            } else {
                $query = "SELECT max(ordering)+1 AS maxordering FROM " . jsjobs::$_db->prefix . "js_job_currencies";
                $data['ordering'] = jsjobsdb::get_var($query);
            }

            if ($data['status'] == 0) {
                $data['default'] = 0;
            } else {
                if ($data['default'] == 1) {
                    $canupdate = true;
                }
            }
        } else {
            if ($data['status'] == 0) {
                $data['default'] = 0;
            } else {
                if ($data['default'] == 1) {
                    $canupdate = true;
                }
            }
        }
        return $canupdate;
    }

    function storeCurrency($data) {
        if (empty($data))
            return false;

        $canupdate = $this->validateFormData($data);
        if ($canupdate === ALREADY_EXIST)
            return ALREADY_EXIST;

        $row = JSJOBSincluder::getJSTable('currency');
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        if (!$row->bind($data)) {
            return SAVE_ERROR;
        }
        if (!$row->store()) {
            return SAVE_ERROR;
        }
        if ($row->default == 1) {
            $this->updateIsDefault($row->id);
        }
        return SAVED;
    }

    function isCurrencyExist($title) {
        $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_currencies WHERE title = '" . $title . "'";
        $result = jsjobsdb::get_var($query);
        if ($result > 0)
            return true;
        else
            return false;
    }

    function deleteCurrencies($ids) {
        if (empty($ids))
            return false;
        $row = JSJOBSincluder::getJSTable('currency');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if ($this->currencyCanDelete($id) == true) {
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

        $row = JSJOBSincluder::getJSTable('currency');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if (!$row->update(array('id' => $id, 'status' => $status))) {
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if ($this->currencyCanUnpulish($id)) {
                    if (!$row->update(array('id' => $id, 'status' => $status))) {
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

    function currencyCanUnpulish($currencyid) {
        $query = " SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_currencies` AS cur WHERE cur.id = " . $currencyid . " AND cur.default = 1 ";
        $total = jsjobsdb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function currencyCanDelete($currencyid) {
        if (is_numeric($currencyid) == false)
            return false;

        $query = " SELECT
                    ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE currencyid = " . $currencyid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE currencyid = " . $currencyid . " OR dcurrencyid = " . $currencyid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_currencies` AS cur WHERE cur.id = " . $currencyid . " AND cur.default =1)
                    AS total ";
        $total = jsjobsdb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function getCurrencyResumeAppliedForCombo() {
        $query = "SELECT id, symbol AS text FROM `" . jsjobs::$_db->prefix . "js_job_currencies` WHERE status = 1";
        $allcurrency = jsjobsdb::get_results($query);
        return $allcurrency;
    }

    function getDefaultCurrencyId() {
        $query = "SELECT id FROM " . jsjobs::$_db->prefix . "js_job_currencies WHERE `default` = 1";
        $id = jsjobsdb::get_var($query);
        return $id;
    }
    function getMessagekey(){
        $key = 'currency';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }


}

?>
