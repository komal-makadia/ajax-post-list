jQuery(document).ready(function(){
jQuery('.resource_post').find('.bdt-gallery-item').each(function() {
var url = jQuery(this).find('.bdt-post-gallery-excerpt').text().trim();
jQuery(this).find('.bdt-post-gallery-skin-abetis-desc').find('a').attr("href",url).attr('target','_blank');
jQuery(this).find('.bdt-post-gallery-inner').wrap( "<a target='_blank' href='"+url+"'></a>" );
});


var ajaxurl = "https://ocds.org/wp-admin/admin-ajax.php";
   function cvf_load_all_posts(page){
      //  debugger;
        
        jQuery('.loader_main').toggleClass('loading ');
                jQuery(".cvf_pag_loading").fadeIn();
        
        var selected_alphabets = jQuery(".alphabet-filter").val();
        var selected_category = jQuery(".directory-categories").val();
        var selected_location = jQuery(".directory-locations").val();
        var search_result = jQuery(".directory-search-filetr").val();
        var data = {
          selected_alphabets:selected_alphabets,
          selected_category:selected_category,
          selected_location:selected_location,
          search_result:search_result,
          page:page,
          action:"member_filter_result",
         
        };
        jQuery.post(ajaxurl, data, function(response) {
            //alert(response);
            jQuery(".cvf_universal_container").html(response);
            
            jQuery(".cvf_pag_loading").css({"background":"none", "transition":"all 1s ease-out"});
            jQuery(".membership_directory_demo").empty();
             jQuery(".membership_directory_demo").hide();
             jQuery(".cvf_pag_loading").show();
            jQuery('.loader_main').toggleClass('loading ');
             jQuery(".cvf_pag_loading").fadeIn();
             
             if (jQuery(window).width() <= 767) {
                  jQuery.fn.chunk = function(size) {
                  var arr = [];
                  for (var i = 0; i < this.length; i += size) {
                    arr.push(this.slice(i, i + size));
                  }
                  return this.pushStack(arr, "chunk", size);
                };
                
                jQuery(".member_list").chunk(2).wrap('<div class="column"></div>');
                
            } if(jQuery(window).width() >= 768) {
                
                    jQuery.fn.chunk = function(size) {
                  var arr = [];
                  for (var i = 0; i < this.length; i += size) {
                    arr.push(this.slice(i, i + size));
                  }
                  return this.pushStack(arr, "chunk", size);
                };
                
                jQuery(".member_list").chunk(3).wrap('<div class="column"></div>');
            }  
                
            jQuery('.column').each(function(){  
                // Cache the highest
                  var highestBox = 0;
                  
                  // Select and loop the elements you want to equalise
                  jQuery('.member_list', this).each(function(){
                     // alert("hi");
                    
                    // If this box is higher than the cached highest then store it
                    if(jQuery(this).height() > highestBox) {
                      highestBox =jQuery(this).height(); 
                    }
                  
                  });  
                        
                  // Set the height of all those children to whichever was highest 
                  jQuery('.member_list',this).height(highestBox);
                                
                });
           
        });
    }
    
    jQuery(document).on("click",".cvf_universal_container .cvf-universal-pagination li.active",function(){
     //debugger;
        var page = jQuery(this).attr("p");
        cvf_load_all_posts(page);
        
    });
    
    jQuery(document).on("change",".directory-categories",function(){
      
       jQuery(".directory-search-filetr").val("");
         cvf_load_all_posts(1);
    });
    
     $('.directory-category-filter .select2').on('select2:select', function(e) {
     jQuery(".directory-search-filetr").val("");   
     cvf_load_all_posts(1);
     
  });
  
jQuery(document).on("click",".search-btn, .alpha",function(event){
        event.preventDefault();
       // debugger;
        //alert("hi");
        if(jQuery(".directory-search-filetr").val()){
            jQuery('.active').removeClass('active').removeAttr('style');
            jQuery(".directory-categories").val("");
            jQuery("#select2-directory-locations-container").text("Filter By Location");
            jQuery(".directory-locations").val("");
        }
       
        var  alpha = jQuery(this).attr("alpha");
        if (alpha){
            jQuery(".directory-search-filetr").val("");
        if (jQuery('.active').hasClass('active')) { 
            
            jQuery('.active').removeClass('active').removeAttr('style');
            
        }
        
       jQuery(this).addClass('active');
       
       
        jQuery('.active').css({"background-color":"#fff", "border":"1px solid #F28033", "color":"#f28033"});
        }
        if (alpha==11) {location.reload();}
        else{
        jQuery("input[type=hidden][name=alphabet-filter]").val(alpha);
        }
         //jQuery(".membership_directory_demo").empty();
         //jQuery(".membership_directory_demo").hide();
         //jQuery(".cvf_pag_loading").show();
             cvf_load_all_posts(1);
    });
    
if (jQuery(window).width() <= 767) {
      jQuery.fn.chunk = function(size) {
      var arr = [];
      for (var i = 0; i < this.length; i += size) {
        arr.push(this.slice(i, i + size));
      }
      return this.pushStack(arr, "chunk", size);
    };
    
    jQuery(".member_list").chunk(2).wrap('<div class="column"></div>');
    
    
} if(jQuery(window).width() >= 768) {
    
        jQuery.fn.chunk = function(size) {
      var arr = [];
      for (var i = 0; i < this.length; i += size) {
        arr.push(this.slice(i, i + size));
      }
      return this.pushStack(arr, "chunk", size);
    };
    
    jQuery(".member_list").chunk(3).wrap('<div class="column"></div>');
}  

jQuery('.column').each(function(){  
    // Cache the highest
      var highestBox = 0;
      
      // Select and loop the elements you want to equalise
      jQuery('.member_list', this).each(function(){
         // alert("hi");
        
        // If this box is higher than the cached highest then store it
        if(jQuery(this).height() > highestBox) {
          highestBox =jQuery(this).height(); 
        }
      
      });  
            
      // Set the height of all those children to whichever was highest 
      jQuery('.member_list',this).height(highestBox);
      
                    
    });
    

/** specialty page filter **/

 function specialty_load_all_posts(page){
        //debugger;
        
        jQuery('.loader_main').toggleClass('loading ');
                jQuery(".specialty_pag_loading").fadeIn();
        
       
        var specialty_category = jQuery(".specialty-categories").val();
        var specialty_location = jQuery(".specialty-locations").val();
       
        var data = {
          
          specialty_category:specialty_category,
          specialty_location:specialty_location,
         
          page:page,
          action:"specialty_member_filter_result",
         
        };
        jQuery.post(ajaxurl, data, function(response) {
            //alert(response);
            jQuery(".specialty_universal_container").html(response);
            jQuery(".specialty_pag_loading").css({"background":"none", "transition":"all 1s ease-out"});
            jQuery('.loader_main').toggleClass('loading ');
             jQuery(".specialty_pag_loading").fadeIn();
             //jQuery(".specialty-memeber-list").empty();
             jQuery(".specialty-memeber-list").hide();
             if(jQuery(".specialty-memeber-list-result ul li").length === 0){
                 jQuery(".specialty-memeber-list-result").html('<h3>Sorry, no member found!</h3>').show();
             }
             else{
                jQuery(".specialty_pag_loading").show();
            }
        });    
    }
    
    jQuery(document).on("change",".specialty-categories",function(){
       // alert("hi");
      if(jQuery(".specialty-categories").val() == jQuery(this).val()){
          jQuery("#select2-specialty-locations-container").text("Filter By Location");
      } 
       
         specialty_load_all_posts(1); 
    });
    
    $('.specialty-filter .select2').on('select2:select', function(e) {
     jQuery(".specialty-categories").val("");
     specialty_load_all_posts(1) ; 
     
  });
     
/** specialty page filter **/

/** general page filter **/
 function general_load_all_posts(page){
        //debugger;
        
        jQuery('.loader_main').toggleClass('loading ');
                jQuery(".general_pag_loading").fadeIn();
        
       
        
        var general_location = jQuery("#select2-general-locations-container").text();
       
        var data = {
          
       
          general_location:general_location,
         
          page:page,
          action:"general_member_filter_result",
         
        };
        jQuery.post(ajaxurl, data, function(response) {
            //alert(response);

            jQuery(".general_universal_container").html(response);
            
            jQuery(".general_pag_loading").css({"background":"none", "transition":"all 1s ease-out"});
            jQuery('.loader_main').toggleClass('loading ');
             jQuery(".general_pag_loading").fadeIn();
             jQuery(".general-memeber-list").empty();
             jQuery(".general-memeber-list").hide();
             if(jQuery(".general-memeber-list-result ul li").length === 0){
                 jQuery(".general-memeber-list-result ul").html('<h3>Sorry, no member found!</h3>').show();
             }
             else{
                jQuery(".general_pag_loading").show();
            }
        });    
    }
    
    
   
    jQuery(document).on("click", ".reset_btn",function(){
         window.location.reload();
     });

 
 
 $(".select2").select2();
 $('.general-filter .select2').on('select2:select', function(e) {
     general_load_all_posts(1); 
  });
 
 /** general page filter **/
 
});


    
