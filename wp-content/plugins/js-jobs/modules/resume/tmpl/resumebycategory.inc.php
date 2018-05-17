<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<script type="text/javascript">
    var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
    function addTouchEvent(){
        jQuery('div.category-wrapper').on("touchstart", function (e) {
            'use strict'; //satisfy code inspectors
            var link = jQuery(this); //preselect the link
            if (link.hasClass('touch')) {
                return true;
            }else {
                link.addClass('touch');
                jQuery('div.category-wrapper').not(this).removeClass('touch');
                e.preventDefault();
                return false; //extra, and to make sure the function has consistent return points
            }
        });
        jQuery('div.category-wrapper').hover(function(e){
            e.preventDefault();
            jQuery(this).find('div.jsjobs-subcategory-wrapper').slideDown("slow");
            //jQuery(this).find('div.jsjobs-subcategory-wrapper').slideToggle();
        },function(e){
            e.preventDefault();    
            jQuery(this).find('div.jsjobs-subcategory-wrapper').slideUp("slow");
            //jQuery(this).find('div.jsjobs-subcategory-wrapper').slideToggle();
        });
    }
    function attachClosePopup() {
        jQuery('img#popup_cross, div#jsjob-popup-background').click(function(){
            jQuery("div#jsjob-search-popup,div#jsjobs-listpopup").slideUp('slow');
            setTimeout(function () {
                jQuery("div#jsjob-popup-background").hide();
            }, 700);
        });
    }
    function getPopupAjax(category,categorytitle){
        jQuery('div.jsjobs-subcategory-wrapper').slideUp("slow");
        var page_id = '<?php echo jsjobs::getPageid(); ?>';
        jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'category', task: 'getsubcategorypopup', category: category, resume:1, page_id:page_id}, function (data) {
            if (data) {
                jQuery('div#jsjobs-listpopup span.popup-title span.title').html(categorytitle);
                jQuery('div#jsjob-popup-background').show();
                jQuery('div#jsjobs-listpopup div.jsjob-contentarea').html(data);
                jQuery('div#jsjobs-listpopup').show();
                addTouchEvent();
                attachClosePopup();
            }
        });
    }
    jQuery(document).ready(function(){
        addTouchEvent();
    });
</script>       
