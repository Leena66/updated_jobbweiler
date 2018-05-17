<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

$msgkey = JSJOBSincluder::getJSModel('company')->getMessagekey();
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(jsjobs::$_path . 'includes/header.php');
JSJOBSMessages::getLayoutMessage($msgkey);
if (jsjobs::$_error_flag == null) {
        $msg = isset(jsjobs::$_data[0]) ? __('Edit', 'js-jobs') : __('Add New', 'js-jobs');
        ?>
        <div id="jsjobs-wrapper">
            <div class="page_heading"><?php echo $msg . '&nbsp;' . __("Company", 'js-jobs'); ?></div>
            <form class="js-ticket-form" id="company_form" method="post" enctype="multipart/form-data" action="<?php echo jsjobs::makeUrl(array('jsjobsme'=>'company', 'task'=>'savecompany')); ?>">
                <?php
            function printFormField($title, $field) {
                $html = '<div class="js-col-md-12 js-form-wrapper">
                                    <div class="js-col-md-12 js-form-title">' . $title . '</div>
                                    <div class="js-col-md-12 js-form-value">' . $field . '</div>
                                </div>';
                return $html;
            }
            $i = 0;
            foreach (jsjobs::$_data[2] AS $field) {
                switch ($field->field) {
                    case 'url':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('url', isset(jsjobs::$_data[0]->url) ? jsjobs::$_data[0]->url : '', array('class' => 'inputbox', 'data-validation' => $req, 'onblur' => 'checkUrl(this);'));
                        echo printFormField($title, $formfield);
                        break;
                    case 'income':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('income', isset(jsjobs::$_data[0]->income) ? jsjobs::$_data[0]->income : '', array('class' => 'inputbox', 'data-validation' => $req));
                        echo printFormField($title, $formfield);
                        break;
                    case 'category':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::select('category', JSJOBSincluder::getJSModel('category')->getCategoryForCombobox(), isset(jsjobs::$_data[0]->category) ? jsjobs::$_data[0]->category : JSJOBSincluder::getJSModel('category')->getDefaultCategoryId(), '', array('class' => 'inputbox', 'data-validation' => $req));
                        echo printFormField($title, $formfield);
                        break;
                    case 'contactname':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('contactname', isset(jsjobs::$_data[0]->contactname) ? jsjobs::$_data[0]->contactname : '', array('class' => 'inputbox', 'data-validation' => $req));
                        echo printFormField($title, $formfield);
                        break;
                    case 'name':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('name', isset(jsjobs::$_data[0]->name) ? jsjobs::$_data[0]->name : '', array('class' => 'inputbox', 'data-validation' => $req));
                        echo printFormField($title, $formfield);
                        break;
                    case 'contactemail':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('contactemail', isset(jsjobs::$_data[0]->contactemail) ? jsjobs::$_data[0]->contactemail : '', array('class' => 'inputbox', 'data-validation' => 'email ' . $req));
                        echo printFormField($title, $formfield);
                        break;
                    case 'contactphone':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('contactphone', isset(jsjobs::$_data[0]->contactphone) ? jsjobs::$_data[0]->contactphone : '', array('class' => 'inputbox', 'data-validation' => $req));
                        echo printFormField($title, $formfield);
                        break;
                    case 'contactfax':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('companyfax', isset(jsjobs::$_data[0]->companyfax) ? jsjobs::$_data[0]->companyfax : '', array('class' => 'inputbox', 'data-validation' => $req));
                        echo printFormField($title, $formfield);
                        break;
                    case 'since':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('since', isset(jsjobs::$_data[0]->since) ? jsjobs::$_data[0]->since : '', array('class' => 'inputbox custom_date', 'data-validation' => $req));
                        echo printFormField($title, $formfield);
                        break;
                    case 'description':
                        $req = '';
                        $titlereq = '';
                        if ($field->required == 1) {
                            $req = 'required';
                            $titlereq = '<font color="red">*</font>';
                        }
                        ?>
                         <div class="js-col-md-12 js-form-wrapper">
                            <div class="js-col-md-12 js-form-title"><?php echo __('Description', 'js-jobs') . $titlereq; ?></div>
                            <div class="js-col-md-12 js-form-value"><?php echo wp_editor(isset(jsjobs::$_data[0]->description) ? jsjobs::$_data[0]->description : '', 'description', array('media_buttons' => false, 'data-validation' => $req)); ?></div>
                        </div>                                
                        <?php
                        break;
                    case 'companysize':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('companysize', isset(jsjobs::$_data[0]->companysize) ? jsjobs::$_data[0]->companysize : '', array('class' => 'inputbox', 'data-validation' => $req));
                        echo printFormField($title, $formfield);
                        break;
                    case 'city':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('city', isset(jsjobs::$_data[0]->city) ? jsjobs::$_data[0]->city : '', array('class' => 'inputbox', 'data-validation' => $req));
                        echo printFormField($title, $formfield);
                        break;
                    case 'zipcode':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('zipcode', isset(jsjobs::$_data[0]->zipcode) ? jsjobs::$_data[0]->zipcode : '', array('class' => 'inputbox', 'data-validation' => $req));
                        echo printFormField($title, $formfield);
                        break;
                    case 'facebook':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('facebook', isset(jsjobs::$_data[0]->facebook) ? jsjobs::$_data[0]->facebook : '', array('class' => 'inputbox', 'data-validation' => $req));
                        echo printFormField($title, $formfield);
                        break;
                    case 'googleplus':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('googleplus', isset(jsjobs::$_data[0]->googleplus) ? jsjobs::$_data[0]->googleplus : '', array('class' => 'inputbox', 'data-validation' => $req));
                        echo printFormField($title, $formfield);
                        break;
                    case 'twitter':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('twitter', isset(jsjobs::$_data[0]->twitter) ? jsjobs::$_data[0]->twitter : '', array('class' => 'inputbox', 'data-validation' => $req));
                        echo printFormField($title, $formfield);
                        break;
                    case 'linkedin':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('linkedin', isset(jsjobs::$_data[0]->linkedin) ? jsjobs::$_data[0]->linkedin : '', array('class' => 'inputbox', 'data-validation' => $req));
                        echo printFormField($title, $formfield);
                        break;
                    case 'address1':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('address1', isset(jsjobs::$_data[0]->address1) ? jsjobs::$_data[0]->address1 : '', array('class' => 'inputbox', 'data-validation' => $req));
                        echo printFormField($title, $formfield);
                        break;
                    case 'address2':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('address2', isset(jsjobs::$_data[0]->address2) ? jsjobs::$_data[0]->address2 : '', array('class' => 'inputbox', 'data-validation' => $req));
                        echo printFormField($title, $formfield);
                        break;
                    case 'logo': ?>
                        <div class="js-col-md-12 js-form-wrapper">
                        <div class="js-col-md-12 js-form-title"><?php echo __('Logo', 'js-jobs') ?></div>
                        <div class="js-col-md-12 js-form-value">
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
                            <input class="inputbox" id="logo" name="logo" type="file">
                            <?php
                                $logoformat = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('image_file_type');
                                $maxsize = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('company_logofilezize');
                            echo '('.$logoformat.')<br>';
                            echo '('.__("Maximum file size","js-jobs").' '.$maxsize.' Kb)'; ?>
                            </div>
                        </div>

                        <?php
                    break;
                    default:
                        JSJOBSincluder::getObjectClass('customfields')->formCustomFields($field);
                        break;
                }
            }
            ?> 
            <div class="js-col-md-12 js-form-wrapper">
                <?php echo JSJOBSformfield::hidden('id', isset(jsjobs::$_data[0]->id) ? jsjobs::$_data[0]->id : '' ); ?>
                <?php echo JSJOBSformfield::hidden('uid', JSJOBSincluder::getObjectClass('user')->uid()); ?>
                <?php echo JSJOBSformfield::hidden('created', isset(jsjobs::$_data[0]->created) ? jsjobs::$_data[0]->created : date('Y-m-d H:i:s')); ?>
                <?php echo JSJOBSformfield::hidden('action', 'company_savecompany'); ?>
                <?php echo JSJOBSformfield::hidden('jsjobspageid', get_the_ID()); ?>
                <?php echo JSJOBSformfield::hidden('creditid', ''); ?>
                <?php echo JSJOBSformfield::hidden('form_request', 'jsjobs'); ?>
                <div class="js-col-md-12 bottombutton js-form" id="save-button">			    	
                    <?php
                    echo JSJOBSformfield::submitbutton('save', __('Save','js-jobs') .' '. __('Company', 'js-jobs'), array('class' => 'button', 'onClick' => 'return validate_url();'));
                    ?>
                </div>
            </div>
        </form>
    </div>
<?php 
}else{
    echo jsjobs::$_error_flag_message;
}
?>
