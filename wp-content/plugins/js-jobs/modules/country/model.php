<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBScountryModel {

    function storeCountry($data) {
        if (empty($data))
            return false;

        if ($data['id'] == '') {
            $result = $this->isCountryExist($data['name']);
            if ($result == true) {
                return ALREADY_EXIST;
            }
        }

        $data['shortCountry'] = str_replace(' ', '-', $data['name']);
        $row = JSJOBSincluder::getJSTable('country');
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        if (!$row->bind($data)) {
            return SAVE_ERROR;
        }
        if (!$row->store()) {
            return SAVE_ERROR;
        }

        return SAVED;
    }

    function getCountrybyId($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT * FROM `" . jsjobs::$_db->prefix . "js_job_countries` WHERE id = " . $id;
        jsjobs::$_data[0] = jsjobsdb::get_row($query);

        return;
    }

    function getAllCountries() {

        $countryname = JSJOBSrequest::getVar("countryname");
        $Status = JSJOBSrequest::getVar("status");
        $states = JSJOBSrequest::getVar("states");
        $city = JSJOBSrequest::getVar("city");
        $formsearch = JSJOBSrequest::getVar('JSJOBS_form_search', 'post');
        if ($formsearch == 'JSJOBS_SEARCH') {
            $_SESSION['JSJOBS_SEARCH']['countryname'] = $countryname;
            $_SESSION['JSJOBS_SEARCH']['status'] = $Status;
            $_SESSION['JSJOBS_SEARCH']['states'] = $states;
            $_SESSION['JSJOBS_SEARCH']['city'] = $city;
        }
        if (JSJOBSrequest::getVar('pagenum', 'get', null) != null) {
            $countryname = (isset($_SESSION['JSJOBS_SEARCH']['countryname']) && $_SESSION['JSJOBS_SEARCH']['countryname'] != '') ? $_SESSION['JSJOBS_SEARCH']['countryname'] : null;
            $Status = (isset($_SESSION['JSJOBS_SEARCH']['status']) && $_SESSION['JSJOBS_SEARCH']['status'] != '') ? $_SESSION['JSJOBS_SEARCH']['status'] : null;
            $states = (isset($_SESSION['JSJOBS_SEARCH']['states']) && $_SESSION['JSJOBS_SEARCH']['states'] != '') ? $_SESSION['JSJOBS_SEARCH']['states'] : null;
            $city = (isset($_SESSION['JSJOBS_SEARCH']['city']) && $_SESSION['JSJOBS_SEARCH']['city'] != '') ? $_SESSION['JSJOBS_SEARCH']['city'] : null;
        } elseif ($formsearch !== 'JSJOBS_SEARCH') {
            unset($_SESSION['JSJOBS_SEARCH']);
        }
        $inquery = '';
        $clause = ' WHERE ';
        if ($countryname) {
            $inquery .= $clause . "  country.name LIKE '%" . $countryname . "%' ";
            $clause = " AND ";
        }
        if (is_numeric($Status)) {
            $inquery .= $clause . " country.enabled = " . $Status;
            $clause = " AND ";
        }

        if ($states == 1) {
            $inquery .= $clause . " (SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_states` AS state WHERE state.countryid = country.id) > 0 ";
            $clause = " AND ";
        }

        if ($city == 1) {
            $inquery .= $clause . " (SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_cities` AS city WHERE city.countryid = country.id) > 0 ";
            $clause = " AND ";
        }

        jsjobs::$_data['filter']['countryname'] = $countryname;
        jsjobs::$_data['filter']['status'] = $Status;
        jsjobs::$_data['filter']['states'] = $states;
        jsjobs::$_data['filter']['city'] = $city;

        // Pagination
        $query = "SELECT COUNT(country.id) 
                    FROM `" . jsjobs::$_db->prefix . "js_job_countries` AS country";
        $query .= $inquery;

        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        // Data
        $query = "SELECT country.* FROM `" . jsjobs::$_db->prefix . "js_job_countries` AS country";
        $query .= $inquery;

        $query .= " ORDER BY country.name ASC LIMIT " . JSJOBSpagination::$_offset . ", " . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);

        return;
    }

    function deleteCountries($ids) {
        if (empty($ids))
            return false;
        $row = JSJOBSincluder::getJSTable('country');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if ($this->countryCanDelete($id) == true) {
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

        $row = JSJOBSincluder::getJSTable('country');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if (!$row->update(array('id' => $id, 'enabled' => $status))) {
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if ($this->countryCanUnpublish($id)) {
                    if (!$row->update(array('id' => $id, 'enabled' => $status))) {
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

    function countryCanUnpublish($countryid) {
        return true;
    }

    function countryCanDelete($countryid) {
        if (!is_numeric($countryid))
            return false;
        $query = "SELECT
                    ( SELECT COUNT(jobcity.id) 
                        FROM `" . jsjobs::$_db->prefix . "js_job_jobcities` AS jobcity
                        JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = jobcity.cityid
                        WHERE city.countryid = " . $countryid . ")
                    + ( SELECT COUNT(companycity.id) 
                            FROM `" . jsjobs::$_db->prefix . "js_job_companycities` AS companycity
                            JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = companycity.cityid
                            WHERE city.countryid = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE nationality = " . $countryid . ")
                    + ( SELECT COUNT(resumecity.id) 
                            FROM `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` AS resumecity
                            JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = resumecity.address_city
                            WHERE city.countryid = " . $countryid . ")
                    + ( SELECT COUNT(institutecity.id) 
                            FROM `" . jsjobs::$_db->prefix . "js_job_resumeinstitutes` AS institutecity
                            JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = institutecity.institute_city
                            WHERE city.countryid = " . $countryid . ")
                    + ( SELECT COUNT(employeecity.id) 
                            FROM `" . jsjobs::$_db->prefix . "js_job_resumeemployers` AS employeecity
                            JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = employeecity.employer_city
                            WHERE city.countryid = " . $countryid . ")
                    + ( SELECT COUNT(referencecity.id) 
                            FROM `" . jsjobs::$_db->prefix . "js_job_resumereferences` AS referencecity
                            JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = referencecity.reference_city
                            WHERE city.countryid = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_states` WHERE countryid = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_cities` WHERE countryid = " . $countryid . ")
            AS total ";
        $total = jsjobsdb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function isCountryExist($country) {
        if (!$country)
            return;
        $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_countries WHERE name = '" . $country . "'";
        $total = jsjobsdb::get_var($query);
        if ($total > 0)
            return true;
        else
            return false;
    }

    function getCountriesForCombo() {
        $query = "SELECT id , name AS text FROM `" . jsjobs::$_db->prefix . "js_job_countries` WHERE enabled = 1 ORDER BY name ASC ";
        $rows = jsjobsdb::get_results($query);
        return $rows;
    }

    function getCountryIdByName($name) { // new function coded
        if (!$name)
            return;
        $query = "SELECT id FROM `" . jsjobs::$_db->prefix . "js_job_countries` WHERE REPLACE(LOWER(name), ' ', '') = REPLACE(LOWER('" . $name . "'), ' ', '') AND enabled = 1";
        $id = jsjobsdb::get_var($query);
        return $id;
    }
    function getMessagekey(){
        $key = 'country';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }


}

?>