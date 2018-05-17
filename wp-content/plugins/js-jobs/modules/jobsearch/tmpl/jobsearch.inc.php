<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

$mapfield = null;
if(isset(jsjobs::$_data[2]))
foreach(jsjobs::$_data[2] AS $key => $value){
    $value = (array) $value;
    if(in_array('map', $value)){
        $mapfield = $key;
        break;
    }
}

?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $(".jsjob-multiselect").chosen({
            placeholder_text_multiple: "<?php echo __('Select some options', 'js-jobs'); ?>"
        });
        <?php if(isset(jsjobs::$_data[2][$mapfield]) && jsjobs::$_data[2][$mapfield]->published == 1){ ?>
        loadMap();
        <?php } ?>
        //Token Input
        var multicities = <?php echo isset(jsjobs::$_data[0]->multicity) ? jsjobs::$_data[0]->multicity : "''" ?>;
        getTokenInput(multicities);

        //Validation
        $.validate();
    });
    var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
</script>
<style type="text/css">
    div#map{
        width: 100%;
        height: 100%;
    }    
    div#map_container{
        height:<?php echo jsjobs::$_configuration['mapheight'] . 'px'; ?>;
        width:100%;
    }
</style>
<?php 
if(isset(jsjobs::$_data[2][$mapfield]) && jsjobs::$_data[2][$mapfield]->published == 1){ ?>
<?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; ?>
<script type="text/javascript" src="<?php echo $protocol; ?>maps.googleapis.com/maps/api/js?key=<?php echo jsjobs::$_configuration['google_map_api_key']; ?>"></script>
<script type="text/javascript">
    function loadMap() {
        var default_latitude = document.getElementById('default_latitude').value;
        var default_longitude = document.getElementById('default_longitude').value;

        var latitude = document.getElementById('latitude').value;
        var longitude = document.getElementById('longitude').value;

        if (latitude != '' && longitude != '') {
            default_latitude = latitude;
            default_longitude = longitude;
        }
        var latlng = new google.maps.LatLng(default_latitude, default_longitude);
        zoom = 10;
        var myOptions = {
            zoom: zoom,
            center: latlng,
            scrollwheel: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map_container"), myOptions);
        var lastmarker = new google.maps.Marker({
            postiion: latlng,
            map: map,
        });
        

        google.maps.event.addListener(map, "click", function (e) {
            var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
            geocoder = new google.maps.Geocoder();
            geocoder.geocode({'latLng': latLng}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (lastmarker != '')
                        lastmarker.setMap(null);
                    var marker = new google.maps.Marker({
                        position: results[0].geometry.location,
                        map: map,
                    });
                    lastmarker = marker;
                    document.getElementById('longitude').value = marker.position.lng();
                    document.getElementById('latitude').value = marker.position.lat();
                } else {
                    alert("<?php echo __('Geocode was not successful for the following reason', 'js-jobs'); ?>:" + status);
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
</script>
<?php } ?>
<script type="text/javascript" >
    //Token in put
    function getTokenInput(multicities) {
        var cityArray = '<?php echo admin_url("admin.php?page=jsjobs_city&action=jsjobtask&task=getaddressdatabycityname"); ?>';

        jQuery("#city").tokenInput(cityArray, {
            theme: "jsjobs",
            preventDuplicates: true,
            hintText: "<?php echo __('Type In A Search Term', 'js-jobs'); ?>",
            noResultsText: "<?php echo __('No Results', 'js-jobs'); ?>",
            searchingText: "<?php echo __('Searching', 'js-jobs'); ?>"
        });
    }

    function getTokenInputtags() {
        var tagarray = '<?php echo admin_url("admin.php?page=jsjobs_tag&tagfor=1&action=jsjobtask&task=gettagsbytagname"); ?>';
        jQuery("#tags").tokenInput(tagarray, {
            theme: "jsjobs",
            preventDuplicates: true,
            tokenLimit: 5,
            hintText: "<?php echo __('Type In A Search Term', 'js-jobs'); ?>",
            noResultsText: "<?php echo __('No Results', 'js-jobs'); ?>",
            searchingText: "<?php echo __('Searching', 'js-jobs'); ?>"
        });
    }

</script>