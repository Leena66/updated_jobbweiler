<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<script type="text/javascript">

    function closeSection() {
        jQuery("div#comments").html(' ');
        jQuery("div#comments").css("display", "none");
    }
    

    function showPopupAndSetValues(name, title, id, themecall = null) {
        if(null != themecall){
            var desc = jQuery("input#cover-letter-text_" + id).val();
            jQuery("h2#jsjb-jm-modal-ar-title").html(name);
            jQuery("div#jsjb-jm-popup-background").hide();
            jQuery("div.jsjb-jm-sendmessage-modal-data-wrp").hide();
            jQuery("div.jsjb-jm-viewcover-modal-data-wrp").show();
            jQuery("div.jsjb-jm-viewcover-title").html(title);
            jQuery("div.jsjb-jm-viewcover-desc").html(desc);
            jQuery("div.js-field-wrapper js-row no-margin center").show();
            jQuery("div#jsjb-jm-popup").slideDown('slow');
            jQuery("div#jsjb-jm-popup-background").click(function () {
                closePopupJobManager();
            });
        }else{
            var desc = jQuery("input#cover-letter-text_" + id).val();
            jQuery("div#full_background").css("display", "block");
            jQuery("div#popup-main-outer.coverletter").show();
            jQuery("div#popup-main.coverletter").slideDown('slow');
            jQuery("div#full_background").click(function () {
                closePopup();
            });
            jQuery("img#popup_cross").click(function () {
                closePopup();
            });
            jQuery("span#popup_title.coverletter").html(name);
            jQuery("span#popup_coverletter_title.coverletter").html(title);
            jQuery("span#popup_coverletter_desc.coverletter").html(desc);
        }
    }

    function setRating(src,newrating){
        setRating_ja_front(src,newrating);
    }

    function setRating_ja_front(jobapplyid, newrating) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
            jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'jobapply', task: 'setResumeRatting', jobapplyid:jobapplyid, rate: newrating}, function (data) {
                if (data) {
                    jQuery("#rating_" + jobapplyid).width(parseInt(newrating * 20) + '%');
                }
            });        

        // jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'jobapply', task: 'setJobApplyRating', jobapplyid: jobapplyid, newrating: newrating}, function (data) {
        //     if (data == 1) {
        //         jQuery("#rating_" + jobapplyid).width(parseInt(newrating * 20) + '%');
        //     }
        // })
    }



    function closePopup() {
        jQuery("div#popup-main-outer").slideUp('slow');
        setTimeout(function () {
            jQuery("div#full_background").hide();
            jQuery("span#popup_title").html('');
            jQuery("div#popup-main").css("display", "none");
            jQuery("span#popup_coverletter_title.coverletter").html('');
            jQuery("span#popup_coverletter_desc.coverletter").html('');
        }, 700);

    }
    function closePopupJobManager() {
        jQuery("div#jsjb-jm-popup").slideUp('slow');
        setTimeout(function () {
            jQuery("div#jsjb-jm-popup-background").hide();
            jQuery("h2#jsjb-jm-modal-ar-title").html('');
            jQuery("div#jsjb-jm-popup").css("display", "none");
            /*jQuery("span#popup_coverletter_title.coverletter").html('');
            jQuery("span#popup_coverletter_desc.coverletter").html('');*/
        }, 700);

    }


    function getResumeDetails(resumeid, salary, exp, inisi, study, available,themecall=null) {
        task="getResumeDetail";
        if(null != themecall){
            task="getResumeDetailJobManager";
            jQuery("div#comments").css({
               'display' : 'block',
               'width' : '100%',
               'float' : 'left'
            });
        }else{
            jQuery("div#comments").css("display", "inline-block");
        }
        var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
        jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'jobapply', task: task, sal: salary, expe: exp, institue: inisi, stud: study, ava: available}, function (data) {
            if (data) {
                jQuery("div." + resumeid).html(data);
            }
        });

    }

    function getEmailFields(emailid, resumeid,themecall=null) {
        task="getEmailFields";
        if(null != themecall){
            task="getEmailFieldsJobManager";
            jQuery("div#comments").css({
               'display' : 'block',
               'width' : '100%',
               'float' : 'left'
            });
        }else{
            jQuery("div#comments").css("display", "inline-block");
        }
        var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
        jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'jobapply', task: task, em: emailid,resumeid:resumeid}, function (data) {
            if (data) {
                jQuery("div." + resumeid).html(data);
            }
        });

    }
    jQuery(document).ready(function () {
        jQuery('a#print-link').click(function (e) {
            e.preventDefault();
            var printurl = jQuery(this).attr('data-print-url');
            print = window.open(printurl, 'print_win', 'width=1024, height=800, scrollbars=yes');
        });
    });
</script>
