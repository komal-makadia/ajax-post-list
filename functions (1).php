<?php
/**
 * GeneratePress child theme functions and definitions.
 *
 * Add your custom PHP in this file. 
 * Only edit this file if you have direct access to it on your server (to fix errors if they happen).
 */


function gpg_adding_scripts() {
 
wp_register_script('utm_forms', get_template_directory_uri() . '_child/utm_form-1.0.4-gpg.js', array('jquery'),'1.1', true);
wp_enqueue_script('custom_js', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'),'', true); ?>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<?php
 
wp_enqueue_script('utm_forms');
}
  
add_action( 'wp_enqueue_scripts', 'gpg_adding_scripts' );  


function generatepress_child_enqueue_scripts() {
	if ( is_rtl() ) {
		wp_enqueue_style( 'generatepress-rtl', trailingslashit( get_template_directory_uri() ) . 'rtl.css' );
	} ?>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
	<?php
	
}
add_action( 'wp_enqueue_scripts', 'generatepress_child_enqueue_scripts', 100 );

function custom_post_type() { 
    // Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Resources', 'Post Type General Name', 'twentynineteen' ),
        'singular_name'       => _x( 'Resource', 'Post Type Singular Name', 'twentynineteen' ),
        'menu_name'           => __( 'Resources', 'twentynineteen' ),
        'parent_item_colon'   => __( 'Parent Resources', 'twentynineteen' ),
        'all_items'           => __( 'All Resources', 'twentynineteen' ),
        'view_item'           => __( 'View Resources', 'twentynineteen' ),
        'add_new_item'        => __( 'Add New Resources', 'twentynineteen' ),
        'add_new'             => __( 'Add New', 'twentynineteen' ),
        'edit_item'           => __( 'Edit Resources', 'twentynineteen' ),
        'update_item'         => __( 'Update Resources', 'twentynineteen' ),
        'search_items'        => __( 'Search Resources', 'twentynineteen' ),
        'not_found'           => __( 'Not Found', 'twentynineteen' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'twentynineteen' ),
    );

    $args = array(
        'label'               => __( 'Resources', 'twentynineteen' ),
        'description'         => __( 'Custom post types', 'twentynineteen' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields','page-attributes' ), 
        'taxonomies'          => array( 'category','post_tag' ),
        'menu_icon'           => 'dashicons-screenoptions',
        'show_in_menu'        => true,
        'hierarchical'        => true,
        'has_archive'         => true,
        'public'              => true,
        'menu_position'       => 25,     
        'capability_type'     => 'post',
    );
    
    // Registering your Custom Post Type*/
    register_post_type( 'resources', $args );
    
 
}
add_action( 'init', 'custom_post_type' );

add_action( 'admin_bar_menu', 'wp_admin_bar_my_custom_account_menu', 11 );
 
function wp_admin_bar_my_custom_account_menu( $wp_admin_bar ) {
$user_id = get_current_user_id();
$current_user = wp_get_current_user();
$profile_url = get_edit_profile_url( $user_id );
 
if ( 0 != $user_id ) {
/* Add the "My Account" menu */
$avatar = get_avatar( $user_id, 28 );
$howdy = sprintf( __('Hello, %1$s'), $current_user->display_name );
$class = empty( $avatar ) ? '' : 'with-avatar';
 
$wp_admin_bar->add_menu( array(
'id' => 'my-account',
'parent' => 'top-secondary',
'title' => $howdy . $avatar,
'href' => $profile_url,
'meta' => array(
'class' => $class,
),
) );
 
}
}

// Job Notification email Start //

add_action( 'transition_post_status', 'send_mails_on_publish', 10, 3 );

function send_mails_on_publish( $new_status, $old_status, $post )
{
    if('job_listing' == get_post_type( $post )){ 
        $url = get_permalink($post->ID);
        $title='New Job Posted'; //Email Subject
        $message='Hello Admin,<br><br>New Job Posted you may review this job using below url<br>'.$url; // Email message
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $headers[] = 'From: Orange County <sfranklin@ocds.org>'; //Email From address
        wp_mail( 'sfranklin@ocds.org',$title, $message,$headers );
    }
}

// Enable Visual Editor

function bbp_enable_visual_editor( $args = array() ) {
    $args['tinymce'] = true;
    return $args;
}
add_filter( 'bbp_after_get_the_content_parse_args', 'bbp_enable_visual_editor' );


/** membership derectory custom category **/

add_action( 'init', 'membership_directory_taxonomies', 0 ); 
function membership_directory_taxonomies() { 
    register_taxonomy( 
        'membership_directory_category', 
        'membership-directory', 
        array( 
            'labels' => array( 
                'name' => 'Directory Categories', 
                'add_new_item' => 'Add New Category', 
                'new_item_name' => "New Category" ,
                'post_tag' => 'Tags'
            ), 
            'show_ui' => true, 
            'show_tagcloud' => false, 
            'hierarchical' => true 
            
        ) 
    ); 
}

add_action( 'init', 'member_location_taxonomies', 0 ); 
function member_location_taxonomies() { 
    register_taxonomy( 
        'member_location_category', 
        'membership-directory', 
        array( 
            'labels' => array( 
                'name' => 'Location Categories', 
                'add_new_item' => 'Add New Category', 
                'new_item_name' => "New Category" ,
                'post_tag' => 'Tags'
            ), 
            'show_ui' => true, 
            'show_tagcloud' => false, 
            'hierarchical' => true 
            
        ) 
    ); 
}
/** membership derectory custom category  end **/



/** shortcode for membership directory **/

function membership_directory_shortcode(){
    
 ?>
     <div class="member-filter">
         <div class="alphabatic-ocds-filter">
             <input type="hidden" name="alphabet-filter" class="alphabet-filter"/>
             <?php
             foreach (range('A', 'Z') as $char) { ?>
             <a href="#" class="alpha alpha_<?php echo $char;?>" alpha="<?php echo $char;?>"><?php echo $char; ?></a>   
             <?php } ?>
             <strong><a href="#" class="alpha current_alpha" alpha="11">RESET / ALL</a></strong>
         </div>
         
         <div class="directory-category-filter"> 
             <?php
                 $args = array(
                   "taxonomy" => "membership_directory_category",
                   "orderby" => "name",
                   "order"   => "ASC"
               );
    
               $cats = get_categories($args);
               ?>
                <select id="directory-categories" name="directory-categories" class="directory-categories">
                   <option value=""> Filter By Practice </option>
                  <?php
                    foreach($cats as $cat) {  ?>
                      <option value="<?php echo $cat->slug; ?>" ><?php echo $cat->name; ?></option> 
                   <?php  }  ?>
                </select>
                
                <?php
                 $args = array(
                   "taxonomy" => "member_location_category",
                   "orderby" => "name",
                   "order"   => "ASC"
                   );
        
                   $locations = get_categories($args);
                   ?>
                
                <select id="directory-locations" name="directory-locations" class="directory-locations select2">
                   <option value=""> Filter By Location </option>
                  <?php
                    foreach($locations as $location) {  ?>
                      <option value="<?php echo $location->slug; ?>" ><?php echo $location->name; ?></option> 
                   <?php  }  ?>
                </select>
         </div>
         
         <div class="search-filter">
             <form method="post" id="directory-search">
                 <input type="text" name="directory-search-filetr" class="directory-search-filetr" placeholder="Search by Name">
                 <input type="submit" name="search" value="search" class="search-btn">
             </form>
         </div>
         
     </div>
 
 <?php
    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
     $args = array(  
        'post_type' => 'membership_directory',
        'post_status' => 'publish',
        'orderby' => 'name',
        'order' => 'ASC',
        'paged' => $paged,
        'posts_per_page' => 12
       
    );

    $query = new WP_Query( $args ); 
    ?>
   <div class="membership_directory_demo" id="membership_directory_demo"> 
   <?php
    while ( $query->have_posts() ) { $query->the_post(); 
     $thumbnail_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), array(350,228),true );
     
         if (has_post_thumbnail( get_the_ID()) ){
           $url=$thumbnail_url[0];
       }
        ?>
        
        <div class="membership_directory_item member_list">
            <div class="member_img"><div class="overlay"></div><img src="<?php echo $url ;?>"></div>
            <div class="member_info">
            <h2><?php echo get_the_title(); ?></h2>
            <p class="member-phone"><?php if(get_post_meta(get_the_ID(),"phone_number",true)){ ?>
            <strong>Phone: </strong><a href="tel:<?php echo get_post_meta(get_the_ID(), "phone_number",true); ?>"><?php echo get_post_meta(get_the_ID(), "phone_number",true); ?></a><?php } ?></p>
            <?php

                $post_type = get_post_type(get_the_ID());   
                $taxonomies = get_object_taxonomies($post_type); 
                 for($i=0; $i<=count($taxonomies); $i++){
                    if($taxonomies[$i] == 'membership_directory_category'){
                    $taxonomy_names = wp_get_object_terms(get_the_ID(), $taxonomies[$i],  array("fields" => "names")); 
                    
                    if(!empty($taxonomy_names)) :?>             
                          <span class="member-sub-cats"><strong><?php echo implode(" / ", $taxonomy_names); ?></strong></span>
                       <?php 
                    endif;
                    }
                 }
            ?>
            <?php if(get_post_meta(get_the_ID(),"e-mail",true) != "" && get_post_meta(get_the_ID(),"e-mail",true) != "N/A"){ ?>
            <p class="member-email"><strong>E-mail: </strong><a href="mailto:<?php echo get_post_meta(get_the_ID(), "e-mail",true); ?>"><?php echo get_post_meta(get_the_ID(), "e-mail",true); ?></a></p>
            <?php } ?>
            <p class="member-address"><?php if(get_post_meta(get_the_ID(),"address",true) != "" && get_post_meta(get_the_ID(),"address",true) != "N/A"){ ?><strong>Address: </strong><?php echo get_post_meta(get_the_ID(), "address",true); ?><?php } ?></p>
            <p class="member-fax"><?php if(get_post_meta(get_the_ID(),"fax",true) != "" && get_post_meta(get_the_ID(),"fax",true) != "N/A"){ ?><strong>Fax: </strong><?php echo get_post_meta(get_the_ID(), "fax",true); ?><?php } ?></p>
            <p class="member-web"><?php if(get_post_meta(get_the_ID(),"web",true) != "" && get_post_meta(get_the_ID(),"web",true) != "http://N/A"){ ?><strong>Web: </strong><a href="<?php echo get_post_meta(get_the_ID(), "web",true); ?>"><?php echo get_post_meta(get_the_ID(), "web",true); ?></a><?php } ?></p>
            <p class="member-fictitious"><?php if(get_post_meta(get_the_ID(),"fictitious",true) != "N/A" && get_post_meta(get_the_ID(),"fictitious",true) != "" ){ ?><strong>Fictitious: </strong><?php echo get_post_meta(get_the_ID(), "fictitious",true); ?><?php } ?></p>
            <p class="member-university"><?php if(get_post_meta(get_the_ID(),"university",true) != "" && get_post_meta(get_the_ID(),"university",true) != "N/A"){ ?><strong>University: </strong><?php echo get_post_meta(get_the_ID(), "university",true); ?><?php } ?></p>
            </div>
           
        </div>
   <?php } ?>
       
       <div class="pagination blog_listing_pagi">
            <?php 
                echo paginate_links( array(
                    'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                    'total'        => $query->max_num_pages,
                    'current'      => max(
                     1, get_query_var( 'paged' ) ),
                    'format'       => '?paged=%#%',
                    'show_all'     => false,
                    'type'         => 'plain',
                    'end_size'     => 2,
                    'mid_size'     => 1,
                    'prev_next'    => true,
                    'prev_text'    => sprintf( '<i class="fas fa-arrow-left"></i>' ),
                    'next_text'    => sprintf( '<i class="fas fa-arrow-right"></i>' ),
                    'add_args'     => false,
                    'add_fragment' => '',
                ) );
            ?>
        </div>
   
    </div>
    
    
    
    <div class = "cvf_pag_loading">
        <div class = "cvf_universal_container">
            <div class="cvf-universal-content"></div>
        </div>
    </div>
    <div class="loader_main">
        <div class="loader"></div>
    </div>
  <?php
}
add_shortcode('membership_directory_code','membership_directory_shortcode');


/** shortcode for membership directory end **/


add_filter( 'posts_where', 'title_like_posts_where', 10, 2 );
function title_like_posts_where( $where, &$wp_query ) {
    global $wpdb;
    if ( $post_title_like = $wp_query->get( 'post_title_like' ) ) {
        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'' . esc_sql( $wpdb->esc_like( $post_title_like ) ) . '%\'';
    }
    return $where;
}

/** filter ajax call function **/
function member_filter_result(){
    
    /** search code **/
    
        if($_POST["search_result"] != ""){
     // echo  $result = $_POST["search_result"];
        
        $page = sanitize_text_field($_POST['page']);
        $cur_page = $page;
        $page -= 1;
        $per_page = 12;
        $previous_btn = true;
        $next_btn = true;
        $first_btn = true;
        $last_btn = true;
        $start = $page * $per_page;
        
        $args = array(  
        
         'post_type'=>'membership_directory',
         'post_status'=>'publish',
         'posts_per_page'=>$per_page, 
         'offset' => $start ,
         'paged' => $page , 
         's' => $_POST["search_result"],
         'orderby' => 'name',
         'order' => 'ASC',
         //'meta_query' => $meta_query,
       
        );
    
        $query = new WP_Query( $args );  
         ?>
         <div class="membership_directory_filter" id="membership_directory_demo"> 
       <?php
       
        if ( $query->have_posts() ){
                while ( $query->have_posts() ) { $query->the_post(); 
                 $thumbnail_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), array(350,228),true );
                 
                     if (has_post_thumbnail( get_the_ID()) ){
                       $url=$thumbnail_url[0];
                   }
                    ?>
                    <div class="membership_directory_item member_list">
                        <div class="member_img"><div class="overlay"></div><img src="<?php echo $url ;?>"></div>
                        <div class="member_info">
                        <h2><?php echo get_the_title(); ?></h2>
                        <p class="member-phone"><?php if(get_post_meta(get_the_ID(),"phone_number",true)){ ?>
                        <strong>Phone: </strong><a href="tel:<?php echo get_post_meta(get_the_ID(), "phone_number",true); ?>"><?php echo get_post_meta(get_the_ID(), "phone_number",true); ?></a><?php } ?></p>
                        <?php

                            $post_type = get_post_type(get_the_ID());   
                            $taxonomies = get_object_taxonomies($post_type); 
                             for($i=0; $i<=count($taxonomies); $i++){
                                if($taxonomies[$i] == 'membership_directory_category'){
                                $taxonomy_names = wp_get_object_terms(get_the_ID(), $taxonomies[$i],  array("fields" => "names")); 
                                
                                if(!empty($taxonomy_names)) :?>             
                                      <span class="member-sub-cats"><strong><?php echo implode(" / ", $taxonomy_names); ?></strong></span>
                                   <?php 
                                endif;
                                }
                             }
                        ?>
                        <?php if(get_post_meta(get_the_ID(),"e-mail",true) != "" && get_post_meta(get_the_ID(),"e-mail",true) != "N/A"){ ?>
                        <p class="member-email"><strong>E-mail: </strong><a href="mailto:<?php echo get_post_meta(get_the_ID(), "e-mail",true); ?>"><?php echo get_post_meta(get_the_ID(), "e-mail",true); ?></a></p>
                        <?php } ?>
                        <p class="member-address"><?php if(get_post_meta(get_the_ID(),"address",true) != "" && get_post_meta(get_the_ID(),"address",true) != "N/A"){ ?><strong>Address: </strong><?php echo get_post_meta(get_the_ID(), "address",true); ?><?php } ?></p>
                        <p class="member-fax"><?php if(get_post_meta(get_the_ID(),"fax",true) != "" && get_post_meta(get_the_ID(),"fax",true) != "N/A"){ ?><strong>Fax: </strong><?php echo get_post_meta(get_the_ID(), "fax",true); ?><?php } ?></p>
                        <p class="member-web"><?php if(get_post_meta(get_the_ID(),"web",true) != "" && get_post_meta(get_the_ID(),"web",true) != "N/A"){ ?><strong>Web: </strong><a href="<?php echo get_post_meta(get_the_ID(), "web",true); ?>"><?php echo get_post_meta(get_the_ID(), "web",true); ?></a><?php } ?></p>
                        <p class="member-fictitious"><?php if(get_post_meta(get_the_ID(),"fictitious",true) != "N/A" && get_post_meta(get_the_ID(),"fictitious",true) != "" ){ ?><strong>Fictitious: </strong><?php echo get_post_meta(get_the_ID(), "fictitious",true); ?><?php } ?></p>
                        <p class="member-university"><?php if(get_post_meta(get_the_ID(),"university",true) != "" && get_post_meta(get_the_ID(),"university",true) != "N/A"){ ?><strong>University: </strong><?php echo get_post_meta(get_the_ID(), "university",true); ?><?php } ?></p>
                        </div>
                       
                    </div>
                    
                    <?php 
                  
                } 
                    
                    $numberOfPosts=$query->found_posts;
                    
                  $msg = "<div class='cvf-universal-content'>" . $msg . "</div><br class = 'clear' />";

                  $no_of_paginations = ceil($numberOfPosts / $per_page);
            
                    if ($cur_page >= 7) {
                        $start_loop = $cur_page - 3;
                        if ($no_of_paginations > $cur_page + 3)
                            $end_loop = $cur_page + 3;
                        else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
                            $start_loop = $no_of_paginations - 6;
                            $end_loop = $no_of_paginations;
                        } else {
                            $end_loop = $no_of_paginations;
                        }
                    } else {
                        $start_loop = 1;
                        if ($no_of_paginations > 7)
                            $end_loop = 7;
                        else
                            $end_loop = $no_of_paginations;
                    }
            
                    $pag_container .= "
                    <div class='cvf-universal-pagination'>
                        <ul>";
            
                    if ($previous_btn && $cur_page > 1) {
                        $pre = $cur_page - 1;
                        $pag_container .= "<li p='$pre' class='active'><i class='fas fa-arrow-left'></i></li>";
                    } else if ($previous_btn) {
                        $pag_container .= "<li class='inactive'><i class='fas fa-arrow-left'></i></li>";
                    }
                    for ($i = $start_loop; $i <= $end_loop; $i++) {
            
                        if ($cur_page == $i)
                        $pag_container .= "<li p='$i' class = 'selected' >{$i}</li>";
                        else
                         $pag_container .= "<li p='$i' class='active'>{$i}</li>";
                    }
            
                    if ($next_btn && $cur_page < $no_of_paginations) {
                        $nex = $cur_page + 1;
                        $pag_container .= "<li p='$nex' class='active'><i class='fas fa-arrow-right'></i></li>";
                    } else if ($next_btn) {
                        $pag_container .= "<li class='inactive'><i class='fas fa-arrow-right'></i></li>";
                    }
            
                    $pag_container = $pag_container . "
                        </ul>
                    </div>";
                    if($numberOfPosts != 0){
                       if($numberOfPosts > $per_page ){    ?>  
                        <div class = "cvf-pagination-content"><?php echo $msg ; ?></div> <div class = "cvf-pagination-nav"><?php echo $pag_container; ?></div>
                    <?php   }
                    }
                    
                    
                   else{
                        echo "<div class='listing-member'>
                         <h3>Sorry, no member found!</h3>
                        </div>";
                    }
                
                
                }
    
       else { ?>
           <div class="listing-member">
             <h3>Sorry, no member found!</h3>
            </div>
       <?php } ?>
        </div>
     
        <?php
        
        
    } 
    
    /** search code **/
    
   
    
    /** category ,location filter and alphabatic filter **/
    
        else{ 
            
            if ($_POST["selected_alphabets"]){
                $alphabats = $_POST["selected_alphabets"];
            }
            
            $text_query = array('relation' => 'AND');
            
             if ($_POST["selected_category"] != '')
                {
                    $text_query[] =  array(
                                'taxonomy' => 'membership_directory_category',
                                'field' => 'slug',
                                'terms' => $_POST["selected_category"],
                                'compare' => '=='
                            );
                }
            
                if ($_POST["selected_location"] != '')
                {
                    $text_query[] = array(
                                'taxonomy' => 'member_location_category',
                                'field' => 'slug',
                                'terms' => $_POST["selected_location"],
                                'compare' => '=='
                            );
                }
            
        $page = sanitize_text_field($_POST['page']);
        $cur_page = $page;
        $page -= 1;
        $per_page = 12;
        $previous_btn = true;
        $next_btn = true;
        $first_btn = true;
        $last_btn = true;
        $start = $page * $per_page;
       
         $args = array(  
        
         'post_type'=>'membership_directory',
         'post_status'=>'publish',
         'posts_per_page'=>$per_page, 
         'offset' => $start ,
         'paged' => $page , 
         'post_title_like' => $alphabats,
         'orderby' => 'name',
         'order' => 'ASC',
         //'meta_query' => $meta_query,
        // 'orderby'    => array( 'meta_value' => 'DESC','title' => 'ASC' ),
         'tax_query' => $text_query,
       
        );
    
        $query = new WP_Query( $args );  
        ?>
         <div class="membership_directory_filter" id="membership_directory_demo"> 
       <?php
        if ( $query->have_posts() ){
        while ( $query->have_posts() ) { $query->the_post(); 
         $thumbnail_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), array(350,228),true );
         
             if (has_post_thumbnail( get_the_ID()) ){
               $url=$thumbnail_url[0];
           }
            ?>
            <div class="membership_directory_item member_list">
                <div class="member_img"><div class="overlay"></div><img src="<?php echo $url ;?>"></div>
                <div class="member_info">
                <h2><?php echo get_the_title(); ?></h2>
                <p class="member-phone"><?php if(get_post_meta(get_the_ID(),"phone_number",true)){ ?>
                <strong>Phone: </strong><a href="tel:<?php echo get_post_meta(get_the_ID(), "phone_number",true); ?>"><?php echo get_post_meta(get_the_ID(), "phone_number",true); ?></a><?php } ?></p>
                <?php

                    $post_type = get_post_type(get_the_ID());   
                    $taxonomies = get_object_taxonomies($post_type); 
                     for($i=0; $i<=count($taxonomies); $i++){
                        if($taxonomies[$i] == 'membership_directory_category'){
                        $taxonomy_names = wp_get_object_terms(get_the_ID(), $taxonomies[$i],  array("fields" => "names")); 
                        
                        if(!empty($taxonomy_names)) :?>             
                              <span class="member-sub-cats"><strong><?php echo implode(" / ", $taxonomy_names); ?></strong></span>
                           <?php 
                        endif;
                        }
                     }
                ?>
                <?php if(get_post_meta(get_the_ID(),"e-mail",true) != "" && get_post_meta(get_the_ID(),"e-mail",true) != "N/A"){ ?>
                <p class="member-email"><strong>E-mail: </strong><a href="mailto:<?php echo get_post_meta(get_the_ID(), "e-mail",true); ?>"><?php echo get_post_meta(get_the_ID(), "e-mail",true); ?></a></p>
                <?php } ?>
                <p class="member-address"><?php if(get_post_meta(get_the_ID(),"address",true) != "" && get_post_meta(get_the_ID(),"address",true) != "N/A"){ ?><strong>Address: </strong><?php echo get_post_meta(get_the_ID(), "address",true); ?><?php } ?></p>
                <p class="member-fax"><?php if(get_post_meta(get_the_ID(),"fax",true) != "" && get_post_meta(get_the_ID(),"fax",true) != "N/A"){ ?><strong>Fax: </strong><?php echo get_post_meta(get_the_ID(), "fax",true); ?><?php } ?></p>
                <p class="member-web"><?php if(get_post_meta(get_the_ID(),"web",true) != "" && get_post_meta(get_the_ID(),"web",true) != "N/A"){ ?><strong>Web: </strong><a href="<?php echo get_post_meta(get_the_ID(), "web",true); ?>"><?php echo get_post_meta(get_the_ID(), "web",true); ?></a><?php } ?></p>
                <p class="member-fictitious"><?php if(get_post_meta(get_the_ID(),"fictitious",true) != "N/A" && get_post_meta(get_the_ID(),"fictitious",true) != "" ){ ?><strong>Fictitious: </strong><?php echo get_post_meta(get_the_ID(), "fictitious",true); ?><?php } ?></p>
                <p class="member-university"><?php if(get_post_meta(get_the_ID(),"university",true) != "" && get_post_meta(get_the_ID(),"university",true) != "N/A"){ ?><strong>University: </strong><?php echo get_post_meta(get_the_ID(), "university",true); ?><?php } ?></p>
                </div>
               
            </div>
       <?php } 
       $numberOfPosts=$query->found_posts;

        $msg = "<div class='cvf-universal-content'>" . $msg . "</div><br class = 'clear' />";

         $no_of_paginations = ceil($numberOfPosts / $per_page) ;

        if ($cur_page >= 7) {
            $start_loop = $cur_page - 3;
            if ($no_of_paginations > $cur_page + 3)
                $end_loop = $cur_page + 3;
            else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
                $start_loop = $no_of_paginations - 6;
                $end_loop = $no_of_paginations;
            } else {
                $end_loop = $no_of_paginations;
            }
        } else {
            $start_loop = 1;
            if ($no_of_paginations > 7)
                $end_loop = 7;
            else
                $end_loop = $no_of_paginations;
        }

        $pag_container .= "
        <div class='cvf-universal-pagination'>
            <ul>";

        if ($previous_btn && $cur_page > 1) {
            $pre = $cur_page - 1;
            $pag_container .= "<li p='$pre' class='active'><i class='fas fa-arrow-left'></i></li>";
        } else if ($previous_btn) {
            $pag_container .= "<li class='inactive'><i class='fas fa-arrow-left'></i></li>";
        }
        for ($i = $start_loop; $i <= $end_loop; $i++) {

            if ($cur_page == $i)
            $pag_container .= "<li p='$i' class = 'selected' >{$i}</li>";
            else
             $pag_container .= "<li p='$i' class='active'>{$i}</li>";
        }

        if ($next_btn && $cur_page < $no_of_paginations) {
            $nex = $cur_page + 1;
            $pag_container .= "<li p='$nex' class='active'><i class='fas fa-arrow-right'></i></li>";
        } else if ($next_btn) {
            $pag_container .= "<li class='inactive'><i class='fas fa-arrow-right'></i></li>";
        }

        $pag_container = $pag_container . "
            </ul>
        </div>";
        if($numberOfPosts != 0){
           if($numberOfPosts > $per_page ){    ?>  
            <div class = "cvf-pagination-content"><?php echo $msg ; ?></div> <div class = "cvf-pagination-nav"><?php echo $pag_container; ?></div>
        <?php   }
        }
        
        
       else{
            echo "<div class='listing-member'>
             <h3>Sorry, no member found!</h3>
            </div>";
        }
       
       
       
       }
       
       else { ?>
           <div class="listing-member">
             <h3>Sorry, no member found!</h3>
            </div>
       <?php } ?>
        </div>
     
        <?php
        
     
     }
     
    /** category ,location filter and alphabatic filter **/
    
   
    
    
        
  die();  
}
add_action( 'wp_ajax_nopriv_member_filter_result', 'member_filter_result' );
add_action( 'wp_ajax_member_filter_result', 'member_filter_result' );

/** filter ajax call function end **/


/** General Practice Direcoty **/
 function general_practice_directory_shortcode(){
 $orderby      = 'name';
$show_count   = 1;      // 1 for yes, 0 for no
$pad_counts   = 1;      // 1 for yes, 0 for no
$hierarchical = 1;      // 1 for yes, 0 for no
$title        = '';
$empty        = 0;

$args = array(
  'taxonomy'     => 'membership_directory_category',
  'orderby'      => $orderby,
  'show_count'   => $show_count,
  'pad_counts'   => $pad_counts,
  'hierarchical' => $hierarchical,
  'title_li'     => $title,
  'hide_empty'   => $empty
);

?>

<div class="general-prectice-directory"><h3>General Practice Geographical Directory</h3>
<div class="general-filter">
    <?php
             $args = array(
               "taxonomy" => "member_location_category",
               "orderby" => "name",
               "order"   => "ASC"
               );
    
               $locations = get_categories($args);
               ?>
            
            <select id="general-locations" name="general-locations" class="general-locations select2">
               <option value=""> Filter By Location </option>
              <?php
                foreach($locations as $location) {  ?>
                  <option value="<?php echo $location->slug; ?>" ><?php echo $location->name; ?></option> 
               <?php  }  ?>
            </select>
            <strong><a href="javascript:void(0)" class="reset_btn">RESET / ALL</a></strong>
</div>
<div class="general-memeber-list" id="general-memeber-list">
<?php

$parent_cat_arg = array('hide_empty' => false, 'parent' => 0 );
$parent_cat = get_terms('membership_directory_category',$parent_cat_arg);//category name


foreach ($parent_cat as $catVal) {
    
    if ( $catVal->name == "General Practice" ){ ?>
  
<?php
    $child_arg = array( 'hide_empty' => false, 'parent' => 0 );
    $child_cat = get_terms( 'member_location_category', $child_arg );
    
   
    echo '<ul>';
    foreach( $child_cat as $child_term ) { 
           
    $args = array('post_type' => 'membership_directory', 'order' => 'ASC',
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'membership_directory_category',
                    'field' => 'slug',
                    'terms' => $catVal->slug,
                    
                ),
                 array(
                    'taxonomy' => 'member_location_category',
                    'field' => 'slug',
                    'terms' => $child_term->slug,
                    //'include_children' => true, 
                    
                ),
            ),
         );
    
    
    $my_query = new WP_Query( $args );

if( $my_query->have_posts() ) { ?>
    <h4><?php echo $child_term->name ;?></h4>
        <li class="member-general-directory">
         <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
            <div class="memberlist-post-detail">
            <h5><?php echo get_the_title(); ?></h5>
           <a href="tel:<?php echo get_post_meta(get_the_ID(), "phone_number",true); ?>"><?php echo get_post_meta(get_the_ID(), "phone_number",true); ?></a> 
            <?php echo get_post_meta(get_the_ID(), "address",true); ?>
            </div><?php            
        endwhile; ?>
    </li> 
<?php  
}
}
?></ul>
<?php

}
}
?>
</div>
<div class = "general_pag_loading">
        <div class = "general_universal_container">
            <div class="general-universal-content"></div>
        </div>
    </div>
    <div class="loader_main">
        <div class="loader"></div>
    </div>
</div>
    


<?php
}
add_shortcode('general_practice_directory_code','general_practice_directory_shortcode');
/** General Practice Direcoty **/


/** Special Practice Direcoty **/
 function special_practice_directory_shortcode(){
 $orderby      = 'name';
$show_count   = 1;      // 1 for yes, 0 for no
$pad_counts   = 1;      // 1 for yes, 0 for no
$hierarchical = 1;      // 1 for yes, 0 for no
$title        = '';
$empty        = 0;

$args = array(
  'taxonomy'     => 'membership_directory_category',
  'orderby'      => $orderby,
  'show_count'   => $show_count,
  'pad_counts'   => $pad_counts,
  'hierarchical' => $hierarchical,
  'title_li'     => $title,
  'hide_empty'   => $empty
);

?>
<?php

$parent_cat_arg = array('hide_empty' => true, 'parent' => 0 );
$parent_cat = get_terms('membership_directory_category',$parent_cat_arg);//category name
?> 
<div class="general-prectice-directory"> 
 <h3>Specialty Geographical Directory</h3>
 
 <div class="specialty-filter">
     <?php
                 $args = array(
                   "taxonomy" => "membership_directory_category",
                   "orderby" => "name",
                   "order"   => "ASC"
               );
    
               $cats = get_categories($args);
               ?>
                <select id="specialty-categories" name="specialty-categories" class="specialty-categories">
                   <option value=""> Filter By Specialty </option>
                  <?php
                    foreach($cats as $cat) { 
                        if($cat->slug != 'general-practice' && $cat->slug != 'dual-member' && $cat->slug != 'faculty' && $cat->slug != 'post-grad-student' 
                        && $cat->slug != 'military-practice' && $cat->slug != 'retired'){ ?>
                      <option value="<?php echo $cat->slug; ?>" ><?php echo $cat->name; ?></option> 
                   <?php } }  ?>
                </select>
                
                <?php
                 $args = array(
                   "taxonomy" => "member_location_category",
                   "orderby" => "name",
                   "order"   => "ASC"
                   );
        
                   $locations = get_categories($args);
                   ?>
                
                <select id="specialty-locations" name="specialty-locations" class="specialty-locations select2">
                   <option value=""> Filter By Location </option>
                  <?php
                    foreach($locations as $location) {  ?>
                      <option value="<?php echo $location->slug; ?>" ><?php echo $location->name; ?></option> 
                   <?php  }  ?>
                </select>
                <strong><a href="javascript:void(0)" class="reset_btn">RESET / ALL</a></strong>
               
 </div>
<div class="specialty-memeber-list" id="specialty-memeber-list">
<?php
foreach ($parent_cat as $catVal) {
    
    if($catVal->slug != 'general-practice' && $catVal->slug != 'dual-member' && $catVal->slug != 'faculty' && $catVal->slug != 'post-grad-student' && $catVal->slug != 'military-practice'
    && $catVal->slug != 'retired'){ ?>

        <ul>
           <li><p class="membership_directory_heading"><?php echo $catVal->name ; ?></p></li>
             <?php       
            $subchild_arg = array( 'hide_empty' => false, 'parent' => 0 );
            $subchild_cat = get_terms( 'member_location_category', $subchild_arg ); ?>
        
                <ul>
                   <?php foreach( $subchild_cat as $subchild_term ) { 
                       
                       // echo '<li><h5>'.$subchild_term->name . '</h5></li>'; //Child Category
                        
                        
                        $args = array('post_type' => 'membership_directory', 'order' => 'ASC',
                        'tax_query' => array(
                            'relation' => 'AND',
                           
                             array(
                                'taxonomy' => 'membership_directory_category',
                                'field' => 'slug',
                                'terms' => $catVal->slug,
                                
                            ),
                             array(
                                'taxonomy' => 'member_location_category',
                                'field' => 'slug',
                                'terms' => $subchild_term->slug,
                               // 'include_children' => true, 
                                
                            ),
                        ),
                     );
            
             
                            $my_query = new WP_Query( $args ); 
                        
                            if( $my_query->have_posts() ) { ?>
                            <h4><?php echo $subchild_term->name ;?></h4>
                                    <li class="membership_directory_sub_catagory">
                                     <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
                                        <div class="memberlist-post-detail">
                                        <h5><?php echo get_the_title(); ?></h5>
                                       <a href="tel:<?php echo get_post_meta(get_the_ID(), "phone_number",true); ?>"><?php echo get_post_meta(get_the_ID(), "phone_number",true); ?></a> 
                                        <?php echo get_post_meta(get_the_ID(), "address",true); ?>
                                        </div><?php            
                                    endwhile; ?>
                                    </li> 
                            
                                </li> 
                                <?php
                            }
                        
                        wp_reset_query();
                    } ?>
               </ul>
       
        </ul>
    
<?php } 
    }
    ?>
</div>
  <div class = "specialty_pag_loading">
        <div class = "specialty_universal_container">
            <div class="specialty-universal-content"></div>
        </div>
    </div>
    <div class="loader_main">
        <div class="loader"></div>
    </div>  
    
    
</div>
    <?php
}
add_shortcode('special_practice_directory_code','special_practice_directory_shortcode');
/** special Practice Direcoty **/


function specialty_member_filter_result(){
    
    $parent_cat_arg = array('hide_empty' => true, 'parent' => 0 );
    $parent_cat = get_terms('membership_directory_category',$parent_cat_arg);//category name
    ?>
    <div class="specialty-memeber-list-result" id="specialty-memeber-list">
    <?php
    foreach ($parent_cat as $catVal) {
        
    
        if($_POST['specialty_category'] != ""){  
        
            if($catVal->slug == $_POST['specialty_category'] ){ ?>
    
            <ul>
               <li><p class="membership_directory_heading"><?php echo $catVal->name ; ?></p></li>
                 <?php       
                $subchild_arg = array( 'hide_empty' => false, 'parent' => 0 );
                $subchild_cat = get_terms( 'member_location_category', $subchild_arg ); ?>
            
                    <ul>
                       <?php foreach( $subchild_cat as $subchild_term ) { 
                           
                           // echo '<li><h5>'.$subchild_term->name . '</h5></li>'; //Child Category
                            
                            
                            $args = array('post_type' => 'membership_directory', 'order' => 'ASC',
                            'tax_query' => array(
                                'relation' => 'AND',
                               
                                 array(
                                    'taxonomy' => 'membership_directory_category',
                                    'field' => 'slug',
                                    'terms' => $_POST['specialty_category'],
                                    
                                ),
                                 array(
                                    'taxonomy' => 'member_location_category',
                                    'field' => 'slug',
                                    'terms' => $subchild_term->slug,
                                   // 'include_children' => true, 
                                    
                                ),
                                
                                
                            ),
                         );
                
                 
                                $my_query = new WP_Query( $args ); 
                            
                                if( $my_query->have_posts() ) { ?>
                                
                                <h4><?php echo $subchild_term->name ;?></h4>
                                        <li class="membership_directory_sub_catagory">
                                         <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
                                            <div class="memberlist-post-detail">
                                            <h5><?php echo get_the_title(); ?></h5>
                                           <a href="tel:<?php echo get_post_meta(get_the_ID(), "phone_number",true); ?>"><?php echo get_post_meta(get_the_ID(), "phone_number",true); ?></a> 
                                            <?php echo get_post_meta(get_the_ID(), "address",true); ?>
                                            </div><?php            
                                        endwhile; ?>
                                        </li> 
                                
                                    </li> 
                                    <?php
                                }
                            
                            wp_reset_query();
                        } ?>
                   </ul>
           
            </ul>
        
    <?php } 
    
        }
        
        elseif($_POST['specialty_location'] != ""){  
            
             if($catVal->slug != 'general-practice' && $catVal->slug != 'dual-member' && $catVal->slug != 'faculty' && $catVal->slug != 'post-grad-student' && $catVal->slug != 'military-practice' && $catVal->slug != 'retired'){ ?>
    
           
                 <?php       
                $subchild_arg = array( 'hide_empty' => false, 'parent' => 0 );
                $subchild_cat = get_terms( 'member_location_category', $subchild_arg ); ?>
            
                    <ul>
                       <?php foreach( $subchild_cat as $subchild_term ) { 
                           
                           if($subchild_term->slug == $_POST['specialty_location']){
                           
                           // echo '<li><h5>'.$subchild_term->name . '</h5></li>'; //Child Category
                            
                            
                            $args = array('post_type' => 'membership_directory', 'order' => 'ASC',
                            'tax_query' => array(
                                'relation' => 'AND',
                               
                                 array(
                                    'taxonomy' => 'membership_directory_category',
                                    'field' => 'slug',
                                    'terms' => $catVal->slug,
                                    
                                ),
                                 array(
                                    'taxonomy' => 'member_location_category',
                                    'field' => 'slug',
                                    'terms' => $_POST['specialty_location'],
                                   // 'include_children' => true, 
                                    
                                ),
                                
                                
                            ),
                         );
                
                 
                                $my_query = new WP_Query( $args ); 
                            
                                if( $my_query->have_posts() ) { ?>
                                <p class="membership_directory_heading"><?php echo $catVal->name ; ?></p>
                                <h4><?php echo $subchild_term->name ;?></h4>
                                        <li class="membership_directory_sub_catagory">
                                         <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
                                            <div class="memberlist-post-detail">
                                            <h5><?php echo get_the_title(); ?></h5>
                                           <a href="tel:<?php echo get_post_meta(get_the_ID(), "phone_number",true); ?>"><?php echo get_post_meta(get_the_ID(), "phone_number",true); ?></a> 
                                            <?php echo get_post_meta(get_the_ID(), "address",true); ?>
                                            </div><?php            
                                        endwhile; ?>
                                        </li> 
                                
                                    
                                    <?php
                                }
                            
                            wp_reset_query();
                     }   } ?>
                   </ul>
         
    <?php } 
        }
        
        else{
           
             if($catVal->slug != 'general-practice' && $catVal->slug != 'dual-member' && $catVal->slug != 'faculty' && $catVal->slug != 'post-grad-student' && $catVal->slug != 'military-practice' && $catVal->slug != 'retired'){ ?>
            
                    <ul>
                       <li><p class="membership_directory_heading"><?php echo $catVal->name ; ?></p></li>
                         <?php       
                        $subchild_arg = array( 'hide_empty' => false, 'parent' => 0 );
                        $subchild_cat = get_terms( 'member_location_category', $subchild_arg ); ?>
                    
                            <ul>
                               <?php foreach( $subchild_cat as $subchild_term ) { 
                                   
                                   // echo '<li><h5>'.$subchild_term->name . '</h5></li>'; //Child Category
                                    
                                    
                                    $args = array('post_type' => 'membership_directory', 'order' => 'ASC',
                                    'tax_query' => array(
                                        'relation' => 'AND',
                                       
                                         array(
                                            'taxonomy' => 'membership_directory_category',
                                            'field' => 'slug',
                                            'terms' => $catVal->slug,
                                            
                                        ),
                                         array(
                                            'taxonomy' => 'member_location_category',
                                            'field' => 'slug',
                                            'terms' => $subchild_term->slug,
                                           // 'include_children' => true, 
                                            
                                        ),
                                    ),
                                 );
                        
                         
                                        $my_query = new WP_Query( $args ); 
                                    
                                        if( $my_query->have_posts() ) { ?>
                                        <h4><?php echo $subchild_term->name ;?></h4>
                                                <li class="membership_directory_sub_catagory">
                                                 <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
                                                    <div class="memberlist-post-detail">
                                                    <h5><?php echo get_the_title(); ?></h5>
                                                   <a href="tel:<?php echo get_post_meta(get_the_ID(), "phone_number",true); ?>"><?php echo get_post_meta(get_the_ID(), "phone_number",true); ?></a> 
                                                    <?php echo get_post_meta(get_the_ID(), "address",true); ?>
                                                    </div><?php            
                                                endwhile; ?>
                                                </li> 
                                        
                                            </li> 
                                            <?php
                                        }
                                    
                                    wp_reset_query();
                                } ?>
                           </ul>
                   
                    </ul>
                
            <?php } 
           
            }
    
    }
        ?>
    </div>
    <?php
  
    die();
}
add_action( 'wp_ajax_nopriv_specialty_member_filter_result', 'specialty_member_filter_result' );
add_action( 'wp_ajax_specialty_member_filter_result', 'specialty_member_filter_result' );


function general_member_filter_result(){
   
if( $_POST['general_location'] != "" ){  ?>
        
<div class="general-memeber-list-result" id="general-memeber-list">
<?php

$parent_cat_arg = array('hide_empty' => false, 'parent' => 0 );
$parent_cat = get_terms('membership_directory_category',$parent_cat_arg);//category name


foreach ($parent_cat as $catVal) {
    
    if ( $catVal->name == "General Practice" ){ ?>
  
<?php
    $child_arg = array( 'hide_empty' => false, 'parent' => 0 );
    $child_cat = get_terms( 'member_location_category', $child_arg );
    
   
    echo '<ul>';
    foreach( $child_cat as $child_term ) { 
        
        if($child_term->name == $_POST['general_location']){
           
    $args = array('post_type' => 'membership_directory', 'order' => 'ASC',
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'membership_directory_category',
                    'field' => 'slug',
                    'terms' => $catVal->slug,
                    
                ),
                 array(
                    'taxonomy' => 'member_location_category',
                    'field' => 'slug',
                    'terms' => $_POST['general_location'],
                    //'include_children' => true, 
                    
                ),
            ),
         );
    
    
    $my_query = new WP_Query( $args );

if( $my_query->have_posts() ) { ?>
    <h4><?php echo $child_term->name ;?></h4>
        <li class="member-general-directory">
         <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
            <div class="memberlist-post-detail">
            <h5><?php echo get_the_title(); ?></h5>
           <a href="tel:<?php echo get_post_meta(get_the_ID(), "phone_number",true); ?>"><?php echo get_post_meta(get_the_ID(), "phone_number",true); ?></a> 
            <?php echo get_post_meta(get_the_ID(), "address",true); ?>
            </div><?php            
        endwhile; ?>
    </li> 
<?php  
}
}
}
?></ul>
<?php

}
}
?>
</div>
<?php
        }
        
if( $_POST['general_location'] == " Filter By Location " ){ ?>
    
    <div class="general-memeber-list-result" id="general-memeber-list">

<?php
    $child_arg = array( 'hide_empty' => false, 'parent' => 0 );
    $child_cat = get_terms( 'member_location_category', $child_arg );
    
   
   // echo '<ul>';
    foreach( $child_cat as $child_term ) { 
           
    $args = array('post_type' => 'membership_directory', 'order' => 'ASC',
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'membership_directory_category',
                    'field' => 'slug',
                    'terms' => 'general-practice',
                    
                ),
                 array(
                    'taxonomy' => 'member_location_category',
                    'field' => 'slug',
                    'terms' => $child_term->slug,
                    //'include_children' => true, 
                    
                ),
            ),
         );
    
    
    $my_query = new WP_Query( $args );

if( $my_query->have_posts() ) { ?>
    <h4><?php echo $child_term->name ;?></h4>
    <ul class="list-result">
        <li class="member-general-directory">
         <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
            <div class="memberlist-post-detail">
            <h5><?php echo get_the_title(); ?></h5>
           <a href="tel:<?php echo get_post_meta(get_the_ID(), "phone_number",true); ?>"><?php echo get_post_meta(get_the_ID(), "phone_number",true); ?></a> 
            <?php echo get_post_meta(get_the_ID(), "address",true); ?>
            </div><?php            
        endwhile; ?>
    </li> 
    </ul>
<?php  
}
}
?>
<?php

?>
</div>
<?php
        
    }
    
    die();
}
add_action( 'wp_ajax_nopriv_general_member_filter_result', 'general_member_filter_result' );
add_action( 'wp_ajax_general_member_filter_result', 'general_member_filter_result' );
