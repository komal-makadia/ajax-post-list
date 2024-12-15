/* Add your JavaScript code here.

If you are using the jQuery library, then don't forget to wrap your code inside jQuery.ready() as follows:

jQuery(document).ready(function( $ ){
    // Your code in here
});

--

If you want to link a JavaScript file that resides on another server (similar to
<script src="https://example.com/your-js-file.js"></script>), then please use
the "Add HTML Code" page, as this is a HTML code that links a JavaScript file.

End of comment */ 

jQuery(document).ready(function( $ ){
    var ajaxurl = "https://blancgroup.co/staging/fairpricegroup/wp-admin/admin-ajax.php";
       function cvf_load_all_posts(page){
            var selected_tag = jQuery("#tag-dropdown").val();
            var selected_category = jQuery("#category-dropdown").val();
            var search_result = jQuery("#search-bar input").val();
             var data = {
              selected_tag:selected_tag,
              selected_category:selected_category,
              search_result:search_result,
              page:page,
              action:"reports_post_result",
             
            };
             jQuery.post(ajaxurl, data, function(response) {
                //alert(response);
                jQuery(".cvf_universal_container").html(response);
                
                jQuery(".cvf_pag_loading").css({"background":"none", "transition":"all 1s ease-out"});
                jQuery("#report-posts-list").empty();
                 jQuery("#report-posts-list").hide();
                 jQuery(".cvf_pag_loading").show();
                 jQuery(".cvf_pag_loading").fadeIn();
                jQuery('html, body').animate({
                    scrollTop: jQuery('.main-header-group .filter-header-dropdown').offset().top
                }, 500); // 800ms for smooth scrolling, adjust as needed
             });
       }
    
          jQuery(document).on("change","#tag-dropdown, #category-dropdown",function(){
         //debugger;
            //var page = jQuery(this).attr("p");
            cvf_load_all_posts(1);
            
        });
        jQuery('#search-bar input').on('input', function() {
            cvf_load_all_posts(1);
        });
        jQuery(document).on("click",".cvf_universal_container .cvf-universal-pagination li",function(){
         //debugger;
            var page = jQuery(this).attr("p");
            cvf_load_all_posts(page);
            
        });
        jQuery(document).on("click",".post-list-nav .cvf-universal-pagination li.active",function(){
         //debugger;
            
            var page = jQuery(this).attr("p");
            cvf_load_all_posts(page);
            
        });
    
    // news room filter
    
    function news_load_all_posts(page){
            var selected_tag = jQuery("#news-tag-dropdown").val();
            var selected_category = jQuery("#news-category-dropdown").val();
            var search_result = jQuery("#news-search-bar input").val();
             var data = {
              selected_tag:selected_tag,
              selected_category:selected_category,
              search_result:search_result,
              page:page,
              action:"news_post_result",
             
            };
             jQuery.post(ajaxurl, data, function(response) {
                //alert(response);
                jQuery(".newsroom_universal_container").html(response);
                
                jQuery(".newsroom_pag_loading").css({"background":"none", "transition":"all 1s ease-out"});
                jQuery("#newsroom-post-listing").empty();
                 jQuery("#newsroom-post-listing").hide();
                 jQuery(".newsroom_pag_loading").show();
                 jQuery(".newsroom_pag_loading").fadeIn();
                 jQuery('html, body').animate({
                    scrollTop: jQuery('.newsroom-main .wp-block-group').offset().top
                }, 500); // 800ms for smooth scrolling, adjust as needed
             });
       }
    
          jQuery(document).on("change","#news-tag-dropdown, #news-category-dropdown",function(){
         //debugger;
            //var page = jQuery(this).attr("p");
            news_load_all_posts(1);
            
        });
        jQuery(document).on('input', '#news-search-bar input', function() {
            news_load_all_posts(1);
        });
        jQuery(document).on("click",".newsroom_universal_container .newsroom-universal-pagination li",function(){
         //debugger;
            var page = jQuery(this).attr("p");
            news_load_all_posts(page);
            
        });
        jQuery(document).on("click",".newsroom-pagination-nav .newsroom-universal-pagination li.active",function(){
         //debugger;
            
            var page = jQuery(this).attr("p");
           news_load_all_posts(page);
            
        });
    
    
    });
    
    