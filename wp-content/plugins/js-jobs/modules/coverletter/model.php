<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSCoverLetterModel {

    function getCoverLetterbyId($id) {
        if (is_numeric($id) == false)
            return false;
        $query = "SELECT * FROM " . jsjobs::$_db->prefix . "js_job_coverletters WHERE id = " . $id;
        jsjobs::$_data[0] = jsjobsdb::get_row($query);

        return;
    }

    function getViewCoverLetter($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT cl.title,cl.description FROM " . jsjobs::$_db->prefix . "js_job_coverletters AS cl WHERE cl.id = " . $id;
        jsjobs::$_data[0] = jsjobsdb::get_row($query);
    }

    function getMyCoverLettersbyUid($u_id) {
        if ((is_numeric($u_id) == false))
            return false;

        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_coverletters` WHERE uid = " . $u_id;
        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        $query = "SELECT letter.id, letter.title,letter.created,letter.serverid,CONCAT(letter.alias,'-',letter.id) aliasid
                FROM `" . jsjobs::$_db->prefix . "js_job_coverletters` AS letter
                WHERE letter.uid =" . $u_id;
        $query.=" LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);
        return;
    }

    function getAllCoverletters() {
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
            //$title = esc_sql($title);
            $inquery .= $clause . "title LIKE '%" . $title . "%'";
            $clause = ' AND ';
        }
        if (is_numeric($status))
            $inquery .= $clause . " status = " . $status;

        jsjobs::$_data['filter']['title'] = $title;
        jsjobs::$_data['filter']['status'] = $status;

        //pagination
        $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_coverletters ";
        $query .= $inquery;
        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //data
        $query = "SELECT * FROM " . jsjobs::$_db->prefix . "js_job_coverletters ";
        $query .= $inquery;
        $query .= " ORDER BY created DESC LIMIT " . JSJOBSpagination::$_offset . ", " . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);

        return;
    }

    function deleteCoverLetter($coverletterid, $uid = '') {
        if (is_numeric($coverletterid) == false)
            return false;
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;

        $row = JSJOBSincluder::getJSTable('coverletter');
        $query = "SELECT COUNT(letter.id) FROM `" . jsjobs::$_db->prefix . "js_job_coverletters` AS letter WHERE letter.id = " . $coverletterid . " AND letter.uid = " . $uid;
        $total = jsjobsdb::get_var($query);
        if ($total > 0) { // this search is same user
            $query = "SELECT COUNT(jobapply.id) FROM `" . jsjobs::$_db->prefix . "js_job_jobapply` AS jobapply WHERE jobapply.coverletterid = " . $coverletterid;
            $cvtotal = jsjobsdb::get_var($query);
            if ($cvtotal > 0) { // Cover letter in use
                return IN_USE;
            }
            if (!$row->delete($coverletterid)) {
                return DELETE_ERROR;
            }
        } else {
            return DELETE_ERROR;
        }
        return DELETED;
    }

    function deleteCoverLetterAdmin($ids) {
        foreach($ids AS $id){
            if(!is_numeric($id)) return false;
            $query = "SELECT COUNT(jobapply.id) FROM `" . jsjobs::$_db->prefix . "js_job_jobapply` AS jobapply WHERE jobapply.coverletterid = " . $id;
            $total = jsjobsdb::get_var($query);
            if ($total > 0) { // Cover letter in use
                return IN_USE;
            }
            $row = JSJOBSincluder::getJSTable('coverletter');
            if (!$row->delete($id)) {
                return DELETE_ERROR;
            }
        }
        return DELETED;
    }

    function publishUnpublish($ids, $status) {
        if (empty($ids))
            return false;
        if (!is_numeric($status))
            return false;

        $row = JSJOBSincluder::getJSTable('coverletter');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if (!$row->update(array('id' => $id, 'status' => $status))) {
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if ($this->coverletterCanUnpublish($id)) {
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
    
    function coverletterCanUnpublish($id){
        return true;
    }

    function storeCoverLetter($data) {
        if (empty($data))
            return false;

        if (!empty($data['alias']))
            $c_l_alias = JSJOBSincluder::getJSModel('common')->removeSpecialCharacter($data['alias']);
        else
            $c_l_alias = JSJOBSincluder::getJSModel('common')->removeSpecialCharacter($data['title']);

        $c_l_alias = strtolower(str_replace(' ', '-', $c_l_alias));
        $data['alias'] = $c_l_alias;
        if(!is_admin())
            $data['status'] = 1;
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        $data['description'] = wpautop(wptexturize(stripslashes($_POST['description'])));
        $row = JSJOBSincluder::getJSTable('coverletter');
        if (!$row->bind($data)) {
            return SAVE_ERROR;
        }
        if (!$row->store()) {
            return SAVE_ERROR;
        }

        return SAVED;
    }

    function canAddCoverLetter($uid) {
        if (!is_numeric($uid))
            return false;
        return true;
    }

    function getCoverLetterByResumeAndJobID($resumeid, $jobid) {
        if (!is_numeric($resumeid))
            return false;
        if (!is_numeric($jobid))
            return false;
        $query = "SELECT c.title AS ctitle, c.description AS cdescription
                    FROM `" . jsjobs::$_db->prefix . "js_job_jobapply` AS ja 
                    JOIN `" . jsjobs::$_db->prefix . "js_job_coverletters` AS c ON c.id = ja.coverletterid
                    WHERE ja.cvid = " . $resumeid . " AND ja.jobid = " . $jobid;
        $result = jsjobs::$_db->get_row($query);
        jsjobs::$_data['coverletter'] = $result;
        return;
    }


    function getIfCoverLetterOwner($coverletterid) {
        if (!is_numeric($coverletterid))
            return false;
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        $query = "SELECT cletter.id 
        FROM `" . jsjobs::$_db->prefix . "js_job_coverletters` AS cletter 
        WHERE cletter.uid = " . $uid . " 
        AND cletter.id =" . $coverletterid;
        $result = jsjobs::$_db->get_var($query);
        if ($result == null) {
            return false;
        } else {
            return true;
        }
    }
    
    function getMessagekey(){
        $key = 'coverletter';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }

}
?>