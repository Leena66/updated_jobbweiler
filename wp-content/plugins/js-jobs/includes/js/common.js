jQuery(document).ready(function () {
    // Call block for all the #
    jQuery("body").delegate('a[href="#"]', "click", function (event) {
        event.preventDefault();
    });
    // Check boxess multi-selection
    jQuery('#selectall').click(function (event) {
        if (this.checked) {
            jQuery('.jsjobs-cb').each(function () {
                this.checked = true;
            });
        } else {
            jQuery('.jsjobs-cb').each(function () {
                this.checked = false;
            });
        }
    });
    //submit form with anchor
    jQuery("a.multioperation").click(function (e) {
        e.preventDefault();
        var total = jQuery('.jsjobs-cb:checked').size();
        if (total > 0) {
            var task = jQuery(this).attr('data-for');
            if (task.toLowerCase().indexOf("remove") >= 0) {
                if (confirmdelete(jQuery(this).attr('confirmmessage')) == true) {
                    jQuery("input#task").val(task);
                    jQuery("form#jsjobs-list-form").submit();
                }
            } else {
                jQuery("input#task").val(task);
                jQuery("form#jsjobs-list-form").submit();
            }
        } else {
            var message = jQuery(this).attr('message');
            alert(message);
        }
    });
    jsjobsPopupLink();
});

function jsjobsPopupLink(themecall=null) {
    var target_ancher="a.jsjobs-popup";
    if(null !=themecall){
        target_ancher="a.jsjb-jm-modal-credit-action-btn";
    }
    jQuery(target_ancher).click(function (e) {
  //      var link = jQuery(target_ancher).attr('href');

//        e.preventDefault();
        
    });
}

function confirmdelete(message) {
    if (confirm(message) == true) {
        return true;
    } else {
        return false;
    }
}

function jsjobsClosePopup(themecall=null) {
    var popup_div="";
    var bkpop_div="";
    if(null !=themecall){
        popup_div="div#jsjb-jm-popup";
        bkpop_div="div#jsjb-jm-popup-background";
    }else{
        popup_div="div#jsjobs-popup";
        bkpop_div="div#jsjobs-popup-background";
    }
    jQuery(popup_div).slideUp();
    jQuery(bkpop_div).hide();
    setTimeout(function () {
        jQuery(popup_div).html(' ');
    }, 350);
}

function getApplyNowByJobid(jobid,pageid,themecall=null) {

    if(null!=themecall){
        jQuery('div#jsjb-jm-popup-background').show();
    }else{
        jQuery("div#jsjob-popup-background").show();
    } 
    
    var permalink = jQuery('div#jsjobs_permalink').html();
    jQuery.post(common.ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'jobapply', task: 'getApplyNowByJobid', jobid: jobid, jobpermalink: permalink,jsjobs_pageid:pageid,themecall:themecall}, function (data) {
        if (data) {
            var d = jQuery.parseJSON(data);
            if(null!=themecall){
                jQuery("div#jsjb-jm-popup").html(d.content);
                jQuery("div#jsjb-jm-popup").slideDown();
            }else{
                jQuery("div#jsjobs-listpopup span.popup-title span.title").html(d.title);
                jQuery("div#jsjobs-listpopup div.jsjob-contentarea").html(d.content);
                jQuery("div#jsjobs-listpopup").slideDown();
            }
        }
    });
    return;
}

function jobApply(jobid,themecall=null) {
    task="jobapply";
    if(null!=themecall){
        jQuery('div#jsjb-jm-popup').prepend('<div class="jsjb-jm-loading"></div>');
        task="jobapplyjobmanager";
    }else{
        jQuery('div.jsjob-contentarea').find('div.quickviewrow').prepend('<div class="transparentbg loading"></div>');
    }
    var cvid = jQuery('select#cvid').val();
    var coverletterid = jQuery('select#coverletterid').val();
    jQuery.post(common.ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'jobapply', task: task, jobid: jobid, cvid: cvid, coverletterid: coverletterid,themecall:themecall}, function (data) {
        if (data) {
            if(null!=themecall){
                jQuery('div#jsjb-jm-popup').find("div.jsjb-jm-loading").remove();
                //jQuery("div.jsjb-jm-modal-wrp").find("div.jsjb-jm-modal-data-wrp").append(data);
                jQuery("div.jsjb-jm-modal-wrp").append(data);
            }else{
                jQuery("div.quickviewbutton").html(data); //retuen value
                jQuery("div.transparentbg").removeClass('loading');
                
            }
        }
    });
}

function getDataForDepandantFieldResume(parentf, childf, type,section = null, sectionid=null,themecall=null) {
    var val;
    if (type == 1) {
        if(1!=section){
            val = jQuery("select#" + parentf+sectionid).val();
        }else if(1==section){
            val = jQuery("select#" + parentf).val();
        }
    } else if (type == 2) {
        if(1!=section){ 
            val = jQuery("input[name=sec_"+section+"\\["+ parentf +"\\]\\["+ sectionid +"\\]]:checked").val();
        }else if(1==section){
            val = jQuery("input[name=sec_"+section+"\\["+ parentf +"\\]]:checked").val();
        }
    }
    jQuery.post(common.ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'fieldordering', task: 'DataForDepandantFieldResume', fvalue: val, child: childf,section:section,sectionid:sectionid,type:type,themecall:themecall}, function (data) {
        if (data) {
            
            var d = jQuery.parseJSON(data);
            /*console.log(d);
            console.log(section);*/
            if(1!=section){
                //console.log(childf+sectionid);
                jQuery("select#" + childf+sectionid).replaceWith(d);
            }else{
                jQuery("select#" + childf).replaceWith(d);

            }
        }
    });
} 


function getDataForDepandantField(parentf, childf, type,section = null, sectionid=null,themecall=null) {
    if (type == 1) {
        var val = jQuery("select#" + parentf).val();
    } else if (type == 2) {
            if(section == 1){
                var val = jQuery("input[name=sec_"+section+"\\["+ parentf +"\\]]:checked").val();
            }else{
                var val = jQuery("input[name=" + parentf + "]:checked").val();
            }

    }
    jQuery.post(common.ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'fieldordering', task: 'DataForDepandantField', fvalue: val, child: childf,themecall:themecall}, function (data) {
        if (data) {
            
            var d = jQuery.parseJSON(data);
            jQuery("select#" + childf).replaceWith(d);
        }
    });
} 
function draw() {
    var objects = document.getElementsByClassName('goldjob');
    for (var i = 0; i < objects.length; i++) {
        var canvas = objects[i];
        if (canvas.getContext) {
            var ctx = canvas.getContext('2d');
            ctx.fillStyle = "#FFFFFF";
            ctx.beginPath();
            ctx.moveTo(0, 0);
            ctx.lineTo(10, 10);
            ctx.lineTo(0, 20);
            ctx.fill();
        }
    }
}

window.onload = function () {
    draw();
}

function fillSpaces(string) {
    string = string.replace(" ", "%20");
    return string;
}

function showloginpopupjobmanager(){
    jQuery("a.jsjb-jm-tp-link").click();
    return;
}
