<?php
if (!defined('ABSPATH'))    die('Restricted Access');
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');?>
<style type="text/css"> 
.ui-datepicker{
    float: left;
}
</style>
<?php
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
    var map = null;
</script>
<?php

$mapfield = null;
if(isset(jsjobs::$_data[2]))
foreach(jsjobs::$_data[2] AS $key => $value){
    $value = (array) $value;
    if(in_array('map', $value)){
        $mapfield = $key;
        break;
    }
}
if($mapfield):
    $mapfield = jsjobs::$_data[2][$mapfield];
    if($mapfield->published == 1){ ?>
        <style type="text/css">
            div#map{width: 100%;
                height: 100%;
            }
            div#map_container{
                height:<?php echo jsjobs::$_configuration['mapheight'] . 'px'; ?>;
                width:100%;}
        </style>
        <?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; ?>
        <script type="text/javascript" src="<?php echo $protocol; ?>maps.googleapis.com/maps/api/js?key=<?php echo jsjobs::$_configuration['google_map_api_key']; ?>"></script>
        <script type="text/javascript">
                    var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
                    function loadMap() {
                        var default_latitude = document.getElementById('default_latitude').value;
                        var default_longitude = document.getElementById('default_longitude').value;
                        var latitude = document.getElementById('latitude').value;
                        var longitude = document.getElementById('longitude').value;
                        var isdefaultvalue = true;
                        if (latitude != '' && longitude != '') {
                            default_latitude = latitude;
                            default_longitude = longitude;
                            isdefaultvalue = false;
                        }

                        var latlng = new google.maps.LatLng(document.getElementById('default_latitude').value, document.getElementById('default_longitude').value);
                        zoom = 10;
                        var myOptions = {
                            zoom: zoom,
                            center: latlng,
                            scrollwheel: false,
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        };
                        map = new google.maps.Map(document.getElementById("map_container"), myOptions);
                        default_latitude = default_latitude.split(',');
                        if(default_latitude instanceof Array){
                            default_longitude = default_longitude.split(',');
                            for (i = 0; i < default_latitude.length; i++) {
                                var latlng = new google.maps.LatLng(default_latitude[i], default_longitude[i]);
                                if(isdefaultvalue == false)
                                    addMarker(latlng);
                            }                            
                        }else{
                            var latlng = new google.maps.LatLng(default_latitude, default_longitude);
                            if(isdefaultvalue == false)
                                addMarker(latlng);
                        }
                        google.maps.event.addListener(map, "click", function (e) {
                            var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
                            geocoder = new google.maps.Geocoder();
                            geocoder.geocode({'latLng': latLng}, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                addMarker(results[0].geometry.location);
                            } else {
                                alert("<?php echo __('Geocode was not successful for the following reason', 'js-jobs'); ?>: " + status);
                            }
                            });
                        });
                    }

            function checkmapcooridnate() {
            var latitude = document.getElementById('latitude').value;
                    var longitude = document.getElementById('longitude').value;
                    var radius = document.getElementById('radius').value;
                    if (latitude != '' && longitude != '') {
            if (radius != '') {
            this.form.submit();
            } else {
            alert("<?php echo __('Please enter the coordinate radius', 'js-jobs'); ?>");
                    return false;
            }
            }
            }
            function addMarker(latlang){
                var marker = new google.maps.Marker({
                    position: latlang,
                    map: map,
                    draggable: true,
                });
                marker.setMap(map);
                map.setCenter(latlang);
                marker.addListener("dblclick", function() {
                    var latitude = document.getElementById('latitude').value;
                    latitude = latitude.replace(','+marker.position.lat(), "");
                    latitude = latitude.replace(marker.position.lat()+',', "");
                    latitude = latitude.replace(marker.position.lat(), "");
                    document.getElementById('latitude').value = latitude;
                    var longitude = document.getElementById('longitude').value;
                    longitude = longitude.replace(','+marker.position.lng(), "");
                    longitude = longitude.replace(marker.position.lng()+',', "");
                    longitude = longitude.replace(marker.position.lng(), "");
                    document.getElementById('longitude').value = longitude;
                    marker.setMap(null);
                });
                if(document.getElementById('latitude').value == ''){
                    document.getElementById('latitude').value = marker.position.lat();
                }else{
                    document.getElementById('latitude').value += ',' + marker.position.lat();
                }
                if(document.getElementById('longitude').value == ''){
                    document.getElementById('longitude').value = marker.position.lng();
                }else{
                    document.getElementById('longitude').value += ',' + marker.position.lng();
                }
            }
        </script>
    <?php } ?>
<?php endif; ?>
<script type="text/javascript">
    function getdepartments(src, val,themecall=null){
    var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
            jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'departments', task: 'listdepartments', val: val,themecall:themecall}, function(data){
            if (data){
                jQuery("#" + src).html(data); //retuen value                
            }
            });
    }
    function addMarkerOnMap(location){
        var geocoder =  new google.maps.Geocoder();
        geocoder.geocode( { 'address': location}, function(results, status) {
            var latlng = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
            if (status == google.maps.GeocoderStatus.OK) {
                if(map != null){
                    addMarker(latlng);
                }
            } else {
                //alert("<?php echo __('Something got wrong','js-jobs');?>:"+status);
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

            onResult: function(item) {
            if (jQuery.isEmptyObject(item)){
            return [{id:0, name: jQuery("tester").text()}];
            } else {
            //add the item at the top of the dropdown
            item.unshift({id:0, name: jQuery("tester").text()});
                    return item;
            }
            },
            onAdd: function(item) {
            if (item.id > 0){
<?php                 
if($mapfield):
    if($mapfield->published == 1){ ?>                
                addMarkerOnMap(item.name);
    <?php } ?>
<?php endif; ?>
                return; 
            }
            if (item.name.search(",") == - 1) {
            var input = jQuery("tester").text();
                    alert ("<?php echo __('Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name', 'js-jobs'); ?>");
                    jQuery("#city").tokenInput("remove", item);
                    return false;
            } else{
            var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
                    jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'city', task: 'savetokeninputcity', citydata: jQuery("tester").text()}, function(data){
                    if (data){
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
            
            onResult: function(item) {
            if (jQuery.isEmptyObject(item)){
            return [{id:0, name: jQuery("tester").text()}];
            } else {
            //add the item at the top of the dropdown
            item.unshift({id:0, name: jQuery("tester").text()});
                    return item;
            }
            },
            onAdd: function(item) {
            if (item.id > 0){
<?php
if($mapfield):
    if($mapfield->published == 1){ ?>
                addMarkerOnMap(item.name);
    <?php } ?>
<?php endif; ?>
                return; 
            }
            if (item.name.search(",") == - 1) {
            var input = jQuery("tester").text();
                    alert ("<?php echo __('Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name', 'js-jobs'); ?>");
                    jQuery("#city").tokenInput("remove", item);
                    return false;
            } else{
            var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
                    jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'city', task: 'savetokeninputcity', citydata: jQuery("tester").text()}, function(data){
                    if (data){
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



    jQuery(document).ready(function ($) {
        /*job apply link start*/
        if (jQuery("input#jobapplylink1").is(":checked")){
        jQuery("div#input-text-joblink").show();
        }
        jQuery("input#jobapplylink1").click(function(){
            if (jQuery(this).is(":checked")){
                jQuery("div#input-text-joblink").show();
            } else{
                jQuery("div#input-text-joblink").hide();
            }
        });
        /*job apply link end*/
        $('.custom_date').datepicker({dateFormat: '<?php echo $js_scriptdateformat; ?>'});
        $.validate();
<?php
if($mapfield):
    if($mapfield->published == 1){ ?>
        loadMap();
    <?php } ?>
<?php endif; ?>
        //Token Input
        var multicities = <?php echo isset(jsjobs::$_data[0]->multicity) ? jsjobs::$_data[0]->multicity : "''" ?>;
        getTokenInput(multicities);
    });
    function getotherexp(id){
        if (id == 1){
            jQuery("span#experience").show();
            jQuery("span#experienceid").hide();
            jQuery("span#range-one").show();
            jQuery("span#range-exp").hide();
            jQuery("input#isexperienceminimax").val(0);
        }
        if (id == 2){
            jQuery("span#experience").hide();
            jQuery("span#experienceid").show();
            jQuery("span#range-exp").show();
            jQuery("span#range-one").hide();
            jQuery("input#isexperienceminimax").val(1);
        }
    }
    function getotheredu(id){
        if (id == 1){
            jQuery("span#education").show();
            jQuery("span#educationid").hide();
            jQuery("span#range-edu-one").show();
            jQuery("span#range-edu").hide();
            jQuery("input#iseducationminimax").val(0);
        }
        if (id == 2){
            jQuery("span#education").hide();
            jQuery("span#educationid").show();
            jQuery("span#range-edu").show();
            jQuery("span#range-edu-one").hide();
            jQuery("input#iseducationminimax").val(1);
        }
    }
</script>
