<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

$dateformat = jsjobs::$_configuration['date_format'];
if ($dateformat == 'm/d/Y' || $dateformat == 'd/m/y' || $dateformat == 'm/d/y' || $dateformat == 'd/m/Y') {
    $dash = '/';
} else {
    $dash = '-';
}
$firstdash = strpos($dateformat, $dash, 0);
$firstvalue = substr($dateformat, 0, $firstdash);
$firstdash = $firstdash + 1;
$seconddash = strpos($dateformat, $dash, $firstdash);
$secondvalue = substr($dateformat, $firstdash, $seconddash - $firstdash);
$seconddash = $seconddash + 1;
$thirdvalue = substr($dateformat, $seconddash, strlen($dateformat) - $seconddash);
$js_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;
$js_scriptdateformat = $firstvalue . $dash . $secondvalue . $dash . $thirdvalue;
$js_scriptdateformat = str_replace('Y', 'yy', $js_scriptdateformat);
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        //Date Format
        $('.custom_date').datepicker({dateFormat: '<?php echo $js_scriptdateformat; ?>'});
        $.validate();
        //Token Input
        var multicities = <?php echo isset(jsjobs::$_data[0]->multicity) ? jsjobs::$_data[0]->multicity : "''" ?>;
        getTokenInput(multicities);
    });
    function checkUrl(obj) {
        if (!obj.value.match(/^http[s]?\:\/\//))
            obj.value = 'http://' + obj.value;
    }
    function validate_url() {
        var value = jQuery("#url").val();
        if (typeof value != 'undefined') {
            if (value != '') {
                if (value.match(/^(http|https|ftp)\:\/\/\w+([\.\-]\w+)*\.\w{2,4}(\:\d+)*([\/\.\-\?\&\%\#]\w+)*\/?$/i) ||
                        value.match(/^mailto\:\w+([\.\-]\w+)*\@\w+([\.\-]\w+)*\.\w{2,4}$/i))
                {
                    return true;
                }
                else {
                    jQuery("#url").addClass("invalid");
                    alert("<?php echo __('Enter Correct Company Site', 'js-jobs'); ?>");
                    return false;
                }
            }
        }
        return true;
    }

    function updateuserlist(pagenum) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
        jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'user', task: 'getuserlistajax', userlimit: pagenum}, function (data) {
            if (data) {
                jQuery("div#popup-record-data").html("");
                jQuery("span#popup_title").html(jQuery("input#user-popup-title-text").val());
                jQuery("div#popup-record-data").html(data);
                setUserLink();
            }
        });
    }

    function setUserLink() {
        jQuery("a.js-userpopup-link").each(function () {
            var anchor = jQuery(this);
            jQuery(anchor).click(function (e) {
                var name = jQuery(this).attr('data-name');
                jQuery("label#uname").html(name);
                var id = jQuery(this).attr('data-id');
                jQuery("input#uid").val(id);
                jQuery("div#popup_main").slideUp('slow', function () {
                    jQuery("div#full_background").hide();
                });
            });
        });
    }
    jQuery(document).ready(function () {
        jQuery("a#userpopup").click(function (e) {
            e.preventDefault();
            jQuery("div#popup-new-company").css("display", "none");
            jQuery("img.icon").css("display", "none");
            jQuery("div#popup-record-data").css("display", "block");
            jQuery("div#full_background").show();
            var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
            jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'user', task: 'getuserlistajax'}, function (data) {
                if (data) {
                    jQuery("div#popup-record-data").html("");
                    jQuery("span#popup_title").html(jQuery("input#user-popup-title-text").val());
                    jQuery("div#popup-record-data").html(data);
                    setUserLink();
                }
            });
            jQuery("div#popup_main").slideDown('slow');
        });
        //jQuery("form#userpopupsearch").submit(function (e) {
        jQuery(document).delegate('form#userpopupsearch', 'submit', function (e) {
            e.preventDefault();
            e.preventDefault();
            var username = jQuery("input#uname").val();
            var name = jQuery("input#name").val();
            var emailaddress = jQuery("input#email").val();
            var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
            jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'user', task: 'getuserlistajax', name: name, uname: username, email: emailaddress}, function (data) {
                if (data) {
                    console.log(data);
                    jQuery("span#popup_title").html(jQuery("input#user-popup-title-text").val());
                    jQuery("div#popup-record-data").html(data);
                    setUserLink();
                }
            });//jquery closed
        });
        jQuery("span.close, div#full_background,img#popup_cross").click(function (e) {
            jQuery("div#popup_main").slideUp('slow', function () {
                jQuery("div#full_background").hide();
            });

        });

    });

</script>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <div id="full_background" style="display:none;"></div>
    <div id="popup_main" style="display:none;">
        <img class="icon" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/popup-coin-icon.png"/>
        <span class="popup-top"><span id="popup_title" ></span><img id="popup_cross" src="<?php echo jsjobs::$_pluginpath; ?>includes/images/popup-close.png">
        </span>
            <div style="display:inline-block;width:100%;float:left;">
                <form id="userpopupsearch">
                    <div class="search-center">
                        <div class="js-col-md-12">
                            <div class="js-col-xs-12 js-col-md-3 search-value">
                                <input type="text" name="uname" id="uname" placeholder="<?php echo __('Username', 'js-jobs');?>" />
                            </div>
                            <div class="js-col-xs-12 js-col-md-3 search-value">
                                <input type="text" name="name" id="name" placeholder="<?php echo __('Name', 'js-jobs');?>" />
                            </div>
                            <div class="js-col-xs-12 js-col-md-3 search-value">
                                <input type="text" name="email" id="email" placeholder="<?php echo __('Email Address', 'js-jobs');?>"/>
                            </div>
                            <div class="js-col-xs-12 js-col-md-3 search-value-button">
                                <div class="js-button ">
                                    <input type="submit" class="submit-button" value="<?php echo __('Search', 'js-jobs');?>" />
                                </div>
                                <div class="js-button">
                                    <input type="submit" onclick="document.getElementById('name').value = '';document.getElementById('uname').value = ''; document.getElementById('email').value = '';" value="<?php echo __('Reset', 'js-jobs');?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>            
        <div id="popup-record-data" style="display:inline-block;width:100%;">
        </div>
        
    </div>
        <?php 
        $msgkey = JSJOBSincluder::getJSModel('company')->getMessagekey();
        JSJOBSMessages::getLayoutMessage($msgkey); 
        ?>
        <span class="js-admin-title">
            <a href="<?php echo admin_url('admin.php?page=jsjobs_company'); ?>"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/back-icon.png" /></a>
            <?php
            $heading = isset(jsjobs::$_data[0]) ? __('Edit', 'js-jobs') : __('Add New', 'js-jobs');
            echo $heading . '&nbsp' . __('Company', 'js-jobs'); ?>
        </span>
        <form id="company_form" class="jsjobs-form" method="post" enctype="multipart/form-data" action="<?php echo admin_url("admin.php?page=jsjobs_company&task=savecompany"); ?>">
            <?php
            $status = array((object) array('id' => 0, 'text' => __('Pending', "js-jobs")), (object) array('id' => 1, 'text' => __('Approved', "js-jobs")), (object) array('id' => -1, 'text' => __('Rejected', "js-jobs")));

            function printFormField($title, $field) {
                $html = '<div class="js-field-wrapper js-row no-margin">
                           <div class="js-field-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding">' . $title . '</div>
                           <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding">' . $field . '</div>
                       </div>';
                return $html;
            }

            $k = 0;
            foreach (jsjobs::$_data[2] AS $field) {
                switch ($field->field) {
                    case 'name':
                        if ($field->published == 1) {
                            $req = '';
                            $titlereq = '';

                            if ($field->required == 1) {
                                $req = 'required';
                                $titlereq = '<font class="required-notifier">*</font>';
                            }
                            ?> 
                            <div class="js-field-wrapper js-row no-margin">
                                <div class="js-field-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding"><?php echo __($field->fieldtitle, 'js-jobs') . $titlereq; ?></div>
                                <div class="js-field-obj js-col-lg-9 js-col-md-9 js-col-xs-12 no-padding"><?php echo JSJOBSformfield::text('name', isset(jsjobs::$_data[0]->name) ? jsjobs::$_data[0]->name : '', array('class' => 'inputbox one', 'data-validation' => $req)); ?></div>
                            </div>
                            <?php
                        }
                        break;
                    case 'category':
                        if ($field->published == 1) {
                            $req = '';
                            $title = __($field->fieldtitle, 'js-jobs');
                            if ($field->required == 1) {
                                $req = 'required';
                                $title .= '<font class="required-notifier">*</font>';
                            }
                            $field = JSJOBSformfield::select('category', JSJOBSincluder::getJSModel('category')->getCategoryForCombobox(), isset(jsjobs::$_data[0]->category) ? jsjobs::$_data[0]->category : JSJOBSincluder::getJSModel('category')->getDefaultCategoryId() , __('Select','js-jobs') .'&nbsp;'. __('Category', 'js-jobs'), array('class' => 'inputbox one', 'data-validation' => $req));
                            echo printFormField($title, $field);
                        }
                        break;
                    case 'uid':
                        if (!isset(jsjobs::$_data[0]->uid)) {
                            if ($field->published == 1) {
                                $req = '';
                                $titlereq = '';

                                // if ($field->required == 1) {
                                //     $req = 'required';
                                //     $titlereq = '<font class="required-notifier">*</font>';
                                // }
                                ?> 
                                <div class="js-field-wrapper js-row no-margin">
                                    <div class="js-field-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding"><?php echo __($field->fieldtitle, 'js-jobs') . $titlereq; ?></div>
                                    <div class="js-field-obj js-col-lg-9 js-col-md-9 js-col-xs-12 no-padding"><label id="uname"></label></div>

                                    <a href="#" id="userpopup"><?php echo __('Select','js-jobs') .'&nbsp;'. __('User', 'js-jobs'); ?></a><div id="username-div"></div>
                            <?php } ?>               
                            </div>
                            <?php
                        }
                        break;
                    case 'url':
                        if ($field->published == 1) {
                            $req = '';
                            $title = __($field->fieldtitle, 'js-jobs');
                            if ($field->required == 1) {
                                $req = 'required';
                                $title .= '<font class="required-notifier">*</font>';
                            }
                            $field = JSJOBSformfield::text('url', isset(jsjobs::$_data[0]->url) ? jsjobs::$_data[0]->url : '', array('class' => 'inputbox one', 'data-validation' => $req, 'onblur' => 'checkUrl(this);'));
                            echo printFormField($title, $field);
                        }
                        break;
                    case 'contactname':
                        if ($field->published == 1) {
                            $req = '';
                            $title = __($field->fieldtitle, 'js-jobs');
                            if ($field->required == 1) {
                                $req = 'required';
                                $title .= '<font class="required-notifier">*</font>';
                            }
                            $field = JSJOBSformfield::text('contactname', isset(jsjobs::$_data[0]->contactname) ? jsjobs::$_data[0]->contactname : '', array('class' => 'inputbox one', 'data-validation' => $req));
                            echo printFormField($title, $field);
                        }
                        break;
                    case 'contactphone':
                        if ($field->published == 1) {
                            $req = '';
                            $title = __($field->fieldtitle, 'js-jobs');
                            if ($field->required == 1) {
                                $req = 'required';
                                $title .= '<font class="required-notifier">*</font>';
                            }
                            $field = JSJOBSformfield::text('contactphone', isset(jsjobs::$_data[0]->contactphone) ? jsjobs::$_data[0]->contactphone : '', array('class' => 'inputbox one', 'data-validation' => $req));
                            echo printFormField($title, $field);
                        }
                        break;
                    case 'contactemail':
                        if ($field->published == 1) {
                            $req = '';
                            $title = __($field->fieldtitle, 'js-jobs');
                            if ($field->required == 1) {
                                $req = 'required';
                                $title .= '<font class="required-notifier">*</font>';
                            }
                            $field = JSJOBSformfield::text('contactemail', isset(jsjobs::$_data[0]->contactemail) ? jsjobs::$_data[0]->contactemail : '', array('class' => 'inputbox one', 'data-validation' => 'email'));
                            echo printFormField($title, $field);
                        }
                        break;
                    case 'since':
                        if ($field->published == 1) {
                            $req = '';
                            $title = __($field->fieldtitle, 'js-jobs');
                            if ($field->required == 1) {
                                $req = 'required';
                                $title .= '<font class="required-notifier">*</font>';
                            }
                            $field = JSJOBSformfield::text('since', isset(jsjobs::$_data[0]->since) ? jsjobs::$_data[0]->since : '', array('class' => 'custom_date one', 'data-validation' => $req));
                            echo printFormField($title, $field);
                        }
                        break;
                    case 'companysize':
                        if ($field->published == 1) {
                            $req = '';
                            $title = __($field->fieldtitle, 'js-jobs');
                            if ($field->required == 1) {
                                $req = 'required';
                                $title .= '<font class="required-notifier">*</font>';
                            }
                            $field = JSJOBSformfield::text('companysize', isset(jsjobs::$_data[0]->companysize) ? jsjobs::$_data[0]->companysize : '', array('class' => 'inputbox one', 'data-validation' => $req));
                            echo printFormField($title, $field);
                        }
                        break;
                    case 'income':
                        if ($field->published == 1) {
                            $req = '';
                            $title = __($field->fieldtitle, 'js-jobs');
                            if ($field->required == 1) {
                                $req = 'required';
                                $title .= '<font class="required-notifier">*</font>';
                            }
                            $field = JSJOBSformfield::text('income', isset(jsjobs::$_data[0]->income) ? jsjobs::$_data[0]->income : '', array('class' => 'inputbox one', 'data-validation' => $req));
                            echo printFormField($title, $field);
                        }
                        break;
                    case 'description':
                        if ($field->published == 1) {
                            $req = '';
                            $titlereq = '';
                            if ($field->required == 1) {
                                $req = 'required';
                                $titlereq = '<font class="required-notifier">*</font>';
                            }
                            ?>
                            <div class="js-field-wrapper js-row no-margin">
                                <div class="js-field-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding"><?php echo __('Description', 'js-jobs') . $titlereq; ?></div>
                                <div class="js-field-obj js-col-lg-9 js-col-md-9 js-col-xs-12 no-padding"><?php echo wp_editor(isset(jsjobs::$_data[0]->description) ? jsjobs::$_data[0]->description : '', 'description', array('media_buttons' => false, 'data-validation' => $req)); ?></div>
                            </div>
                            <?php
                        }
                        break;
                    case 'city':
                        if ($field->published == 1) {
                            $req = '';
                            $title = __($field->fieldtitle, 'js-jobs');
                            if ($field->required == 1) {
                                $req = 'required';
                                $title .= '<font class="required-notifier">*</font>';
                            }
                            $field = JSJOBSformfield::text('city', '', array('class' => 'inputbox', 'data-validation' => $req));
                            $field .= JSJOBSformfield::hidden('cityforedit', isset(jsjobs::$_data[0]->multicity) ? jsjobs::$_data[0]->multicity : '', array('class' => 'inputbox one'));
                            echo printFormField($title, $field);
                        }
                        break;
                    case 'zipcode':
                        if ($field->published == 1) {
                            $req = '';
                            $title = __($field->fieldtitle, 'js-jobs');
                            if ($field->required == 1) {
                                $req = 'required';
                                $title .= '<font class="required-notifier">*</font>';
                            }
                            $field = JSJOBSformfield::text('zipcode', isset(jsjobs::$_data[0]->zipcode) ? jsjobs::$_data[0]->zipcode : '', array('class' => 'inputbox one', 'data-validation' => $req));
                            echo printFormField($title, $field);
                        }
                        break;
                    case 'contactfax':
                        if ($field->published == 1) {
                            $req = '';
                            $title = __($field->fieldtitle, 'js-jobs');
                            if ($field->required == 1) {
                                $req = 'required';
                                $title .= '<font class="required-notifier">*</font>';
                            }
                            $field = JSJOBSformfield::text('companyfax', isset(jsjobs::$_data[0]->companyfax) ? jsjobs::$_data[0]->companyfax : '', array('class' => 'inputbox one', 'data-validation' => $req));
                            echo printFormField($title, $field);
                        }
                        break;
                    case 'facebook':
                        if ($field->published == 1) {
                            $req = '';
                            $title = __($field->fieldtitle, 'js-jobs');
                            if ($field->required == 1) {
                                $req = 'required';
                                $title .= '<font color="red">*</font>';
                            }
                            $formfield = JSJOBSformfield::text('facebook', isset(jsjobs::$_data[0]->facebook) ? jsjobs::$_data[0]->facebook : '', array('class' => 'inputbox', 'data-validation' => $req));
                            echo printFormField($title, $formfield);
                        }
                        break;
                    case 'googleplus':
                        if ($field->published == 1) {
                            $req = '';
                            $title = __($field->fieldtitle, 'js-jobs');
                            if ($field->required == 1) {
                                $req = 'required';
                                $title .= '<font color="red">*</font>';
                            }
                            $formfield = JSJOBSformfield::text('googleplus', isset(jsjobs::$_data[0]->googleplus) ? jsjobs::$_data[0]->googleplus : '', array('class' => 'inputbox', 'data-validation' => $req));
                            echo printFormField($title, $formfield);
                        }
                        break;
                    case 'twitter':
                        if ($field->published == 1) {
                            $req = '';
                            $title = __($field->fieldtitle, 'js-jobs');
                            if ($field->required == 1) {
                                $req = 'required';
                                $title .= '<font color="red">*</font>';
                            }
                            $formfield = JSJOBSformfield::text('twitter', isset(jsjobs::$_data[0]->twitter) ? jsjobs::$_data[0]->twitter : '', array('class' => 'inputbox', 'data-validation' => $req));
                            echo printFormField($title, $formfield);
                        }
                        break;
                    case 'linkedin':
                        if ($field->published == 1) {
                            $req = '';
                            $title = __($field->fieldtitle, 'js-jobs');
                            if ($field->required == 1) {
                                $req = 'required';
                                $title .= '<font color="red">*</font>';
                            }
                            $formfield = JSJOBSformfield::text('linkedin', isset(jsjobs::$_data[0]->linkedin) ? jsjobs::$_data[0]->linkedin : '', array('class' => 'inputbox', 'data-validation' => $req));
                            echo printFormField($title, $formfield);
                        }
                        break;

                    case 'address1':
                        if ($field->published == 1) {
                            $req = '';
                            $title = __($field->fieldtitle, 'js-jobs');
                            if ($field->required == 1) {
                                $req = 'required';
                                $title .= '<font class="required-notifier">*</font>';
                            }
                            $field = JSJOBSformfield::text('address1', isset(jsjobs::$_data[0]->address1) ? jsjobs::$_data[0]->address1 : '', array('class' => 'inputbox one', 'data-validation' => $req));
                            echo printFormField($title, $field);
                        }
                        break;
                    case 'address2':
                        if ($field->published == 1) {
                            $req = '';
                            $title = __($field->fieldtitle, 'js-jobs');
                            if ($field->required == 1) {
                                $req = 'required';
                                $title .= '<font class="required-notifier">*</font>';
                            }
                            $field = JSJOBSformfield::text('address2', isset(jsjobs::$_data[0]->address2) ? jsjobs::$_data[0]->address2 : '', array('class' => 'inputbox one', 'data-validation' => $req));
                            echo printFormField($title, $field);
                        }
                        break;
                    case 'status':
                        if ($field->published == 1) {
                            $req = '';
                            $title = __($field->fieldtitle, 'js-jobs');
                            if ($field->required == 1) {
                                $req = 'required';
                                $title .= '<font class="required-notifier">*</font>';
                            }
                            $field = JSJOBSformfield::select('status', $status, isset(jsjobs::$_data[0]->status) ? jsjobs::$_data[0]->status : 1, __('Select Status', 'js-jobs'), array('class' => 'inputbox one', 'data-validation' => $req));
                            echo printFormField($title, $field);
                        }
                        break;
                    case 'logo':
                        if ($field->published == 1) {
                            ?>

                            <div class="js-field-wrapper logo-container js-row no-margin">
                                <div class="js-field-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding"><?php echo __('Company logo', 'js-jobs'); ?></div>
                                <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding">
                                    <input class="inputbox" type="file" id="logo" name="logo" />
                                    <?php
                                    if (isset(jsjobs::$_data[0]->logofilename) && jsjobs::$_data[0]->logofilename != "") {
                                        $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                                        $wpdir = wp_upload_dir();
                                        $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . jsjobs::$_data[0]->id . '/logo/' . jsjobs::$_data[0]->logofilename;
                                        ?><img id="comp_logo" style="display:inline;width:60px;height:auto;"  src="<?php echo $path; ?>">
                                                <!-- <span id="logo-name" class="logo-name"></span> -->
                                        <span class="remove-file" onClick="return removeLogo(<?php echo jsjobs::$_data[0]->id; ?>);"><img src="<?php echo jsjobs::$_pluginpath; ?>includes/images/no.png"></span>
                                    <?php                             
                                    }
                                    ?>
                                    <br><small><?php echo __('Maximum width','js-jobs'); ?> : 200px)</small>
                                    <br><small><?php echo __('Maximum file size','js-jobs') . ' (' . JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('company_logofilezize'); ?>KB)</small>
                                </div>
                            </div>
                            <?php
                        }
                        break;
                    default:
                        JSJOBSincluder::getObjectClass('customfields')->formCustomFields($field);
                        break;
                }
            }
            ?>
            <?php echo JSJOBSformfield::hidden('isqueue', isset($_GET['isqueue']) ? 1 : 0); ?>
            <?php echo JSJOBSformfield::hidden('id', isset(jsjobs::$_data[0]->id) ? jsjobs::$_data[0]->id : ''); ?>
            <?php echo JSJOBSformfield::hidden('action', 'company_savecompany'); ?>
            <?php echo JSJOBSformfield::hidden('form_request', 'jsjobs'); ?>
            <?php echo JSJOBSformfield::hidden('uid', isset(jsjobs::$_data[0]->uid) ? jsjobs::$_data[0]->uid : ''); ?>
            <?php echo JSJOBSformfield::hidden('alias', isset(jsjobs::$_data[0]->alias) ? jsjobs::$_data[0]->alias : ''); ?>
            <?php echo JSJOBSformfield::hidden('logofilename', isset(jsjobs::$_data[0]->logofilename) ? jsjobs::$_data[0]->logofilename : ''); ?>
            <?php echo JSJOBSformfield::hidden('logoisfile', isset(jsjobs::$_data[0]->logoisfile) ? jsjobs::$_data[0]->logoisfile : ''); ?>
            <?php echo JSJOBSformfield::hidden('created', isset(jsjobs::$_data[0]->created) ? jsjobs::$_data[0]->created : date("Y-m-d H:i:s")); ?>
            <?php echo JSJOBSformfield::hidden('serverstatus', isset(jsjobs::$_data[0]->serverstatus) ? jsjobs::$_data[0]->serverstatus : ''); ?>
            <?php echo JSJOBSformfield::hidden('serverid', isset(jsjobs::$_data[0]->serverid) ? jsjobs::$_data[0]->serverid : ''); ?>
            <?php echo JSJOBSformfield::hidden('user-popup-title-text', __('Select','js-jobs') .'&nbsp;'. __('User', 'js-jobs')); ?>
            <?php echo JSJOBSformfield::hidden('isadmin', '1'); ?>
            <div class="js-submit-container js-col-lg-10 js-col-md-10 js-col-md-offset-2 js-col-md-offset-2">
                <a id="form-cancel-button" href="<?php echo admin_url('admin.php?page=jsjobs_company'); ?>" ><?php echo __('Cancel', 'js-jobs'); ?></a>
                <?php
                    echo JSJOBSformfield::submitbutton('save', __('Save','js-jobs') .' '. __('Company', 'js-jobs'), array('class' => 'button', 'onClick' => 'return validate_url();'));
                ?>
            </div>
        </form>
    </div>
</div>
        <script type="text/javascript">
            function removeLogo(id) {
                var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
                jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'company', task: 'deletecompanylogo', companyid: id}, function (data) {
                    if (data) {
                        jQuery("img#comp_logo").css("display", "none");
                        jQuery("form#company_form").append('<input type="hidden" name="company_logo_deleted" value="1"/>');
                    } else {
                        jQuery("div.logo-container").append('<span style="color:Red;"><?php echo __("Error Deleting Logo", "js-jobs"); ?>');
                    }
                });
            }
            function getTokenInput(multicities) {
                var cityArray = '<?php echo admin_url("admin.php?page=jsjobs_city&action=jsjobtask&task=getaddressdatabycityname"); ?>';
                var city = jQuery("#cityforedit").val();
                if (city != "") {
                    jQuery("#city").tokenInput(cityArray, {
                        theme: "jsjobs",
                        preventDuplicates: true,
                        hintText: "<?php echo __('Type In A Search Term', 'js-jobs'); ?>",
                        noResultsText: "<?php echo __('No Results', 'js-jobs'); ?>",
                        searchingText: "<?php echo __('Searching', 'js-jobs'); ?>",
                        // tokenLimit: 1,
                        prePopulate: multicities,
<?php $newtyped_cities = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('newtyped_cities');
 if ($newtyped_cities == 1) { ?>

                        onResult: function (item) {
                            if (jQuery.isEmptyObject(item)) {
                                return [{id: 0, name: jQuery("tester").text()}];
                            } else {
                                //add the item at the top of the dropdown
                                item.unshift({id: 0, name: jQuery("tester").text()});
                                return item;
                            }
                        },
                        onAdd: function (item) {
                            if (item.id > 0) {
                                return;
                            }
                            if (item.name.search(",") == -1) {
                                var input = jQuery("tester").text();
                                alert("<?php echo __('Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name', 'js-jobs'); ?>");
                                jQuery("#city").tokenInput("remove", item);
                                return false;
                            } else {
                                var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
                                jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'city', task: 'savetokeninputcity', citydata: jQuery("tester").text()}, function (data) {
                                    if (data) {
                                        try {
                                            var value = jQuery.parseJSON(data);
                                            jQuery('#city').tokenInput('remove', item);
                                            jQuery('#city').tokenInput('add', {id: value.id, name: value.name});
                                        }
                                        catch (err) {
                                            jQuery("#city").tokenInput("remove", item);
                                            alert(data);
                                        }
                                    }
                                });
                            }
                        }
                        <?php } ?>
                    });
                } else {
                    jQuery("#city").tokenInput(cityArray, {
                        theme: "jsjobs",
                        preventDuplicates: true,
                        hintText: "<?php echo __('Type In A Search Term', 'js-jobs'); ?>",
                        noResultsText: "<?php echo __('No Results', 'js-jobs'); ?>",
                        searchingText: "<?php echo __('Searching', 'js-jobs'); ?>",
                        // tokenLimit: 1,
<?php $newtyped_cities = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('newtyped_cities');
 if ($newtyped_cities == 1) { ?>

                        onResult: function (item) {
                            if (jQuery.isEmptyObject(item)) {
                                return [{id: 0, name: jQuery("tester").text()}];
                            } else {
                                //add the item at the top of the dropdown
                                item.unshift({id: 0, name: jQuery("tester").text()});
                                return item;
                            }
                        },
                        onAdd: function (item) {
                            if (item.id > 0) {
                                return;
                            }
                            if (item.name.search(",") == -1) {
                                var input = jQuery("tester").text();
                                alert("<?php echo __('Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name', 'js-jobs'); ?>");
                                jQuery("#city").tokenInput("remove", item);
                                return false;
                            } else {
                                var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
                                jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'city', task: 'savetokeninputcity', citydata: jQuery("tester").text()}, function (data) {
                                    if (data) {
                                        try {
                                            var value = jQuery.parseJSON(data);
                                            jQuery('#city').tokenInput('remove', item);
                                            jQuery('#city').tokenInput('add', {id: value.id, name: value.name});
                                        }
                                        catch (err) {
                                            jQuery("#city").tokenInput("remove", item);
                                            alert(data);
                                        }
                                    }
                                });
                            }
                        }
                        <?php } ?>
                    });
                }
            }
        </script>
