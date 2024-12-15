
function nm_filter_header_part_shortcode(){
	// Fetch the categories you need (replace with actual slugs)
    $categories = get_categories(array(
        'taxonomy' => 'category',
        'slug' => array('newsroom', 'newsroom1'),
        'hide_empty' => false
    ));

    // Fetch the tags for the year
    $tags = get_tags(array(
        'taxonomy' => 'year', // Assuming 'year' is a custom tag taxonomy, adjust if necessary
        'hide_empty' => false
    ));
	$current_year = date('Y'); 
    ob_start(); ?>
        <!-- Filter Header Dropdown -->
        <div class="filter-header-dropdown">
            <div class="report-filters">
                <div class="filter-left">

                    <!-- All Reports dropdown -->
<!--                     <select name="all_reports" class="filter-dropdown all-dropdown">
                        <option value="">All Reports</option>
                        <?php //foreach ($categories as $category) : ?>
                            <option value="<?php //echo esc_attr($category->slug); ?>"><?php //echo esc_html($category->name); ?></option>
                        <?php //endforeach; ?>
                    </select> -->
					

                    <!-- Categories dropdown -->
                    <select name="category" class="filter-dropdown category-dropdown" id="news-category-dropdown">
                        <option value="">All Newsroom</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo esc_attr($category->slug); ?>"><?php echo esc_html($category->name); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Tags dropdown -->
                    <select name="tag" class="filter-dropdown tag-dropdown" id="news-tag-dropdown">
                        <option value="">All Years</option>
                        <?php foreach ($tags as $tag) : 
							//if ($tag->name == $current_year) {?>
                            <option value="<?php echo esc_attr($tag->slug); ?>"><?php echo esc_html($tag->name); ?></option>
                        <?php //} 
							endforeach; ?>
                    </select>
                </div>
                <div class="filter-right">
                    <!-- Search Bar -->
                    <div class="search-bar" id="news-search-bar">
                        
                        <input type="text" name="s" placeholder="Search...">
<!-- <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="15" height="14" fill="none" viewBox="0 0 15 14"><ellipse cx="6.7696" cy="6.46739" rx="5.96736" ry="5.96739" stroke="var(--filter-query-block-icon-color)"></ellipse><path d="M13.8023 13.5L10.9893 10.687" stroke="var(--filter-query-block-icon-color)"></path></svg> -->
                    </div>
                </div>
            </div>
        </div>
<?php
return ob_get_clean();
}
add_shortcode('nm_filter_header_part_shortcode', 'nm_filter_header_part_shortcode');
function dynamic_newsroom_archive_with_filters() {
    ob_start(); ?>
    <div class="report-archive-container">
        <!-- Filter Header Dropdown -->
        
        <!-- Posts List -->
        <div class="newsroom-post-listing" id="newsroom-post-listing">
            <?php
            $page = max(1, get_query_var('paged', 1)); // Current page
            $per_page = 9; // Items per page
            $start = ($page - 1) * $per_page; // Offset for query
            $cur_page = $page;

			$query_args = array(
                'post_type' => 'post', // Updated to 'newsroom-post'
                'posts_per_page' => $per_page,
                'offset' => $start,
                'paged' => $page,
                'category_name' => 'newsroom,newsroom1',
            );

            // Apply All Reports filter
            if (!empty($_GET['all_reports'])) {
                $query_args['category_name'] = sanitize_text_field($_GET['all_reports']);
            }

            // Apply Category filter
            if (!empty($_GET['category'])) {
                $query_args['category_name'] = sanitize_text_field($_GET['category']);
            }

            // Apply Tag filter
            if (!empty($_GET['tag'])) {
                $query_args['tag'] = sanitize_text_field($_GET['tag']);
            }

            // Apply Search filter
            if (!empty($_GET['s'])) {
                $query_args['s'] = sanitize_text_field($_GET['s']);
            }

            // Execute the query
            $report_posts = new WP_Query($query_args);

            if ($report_posts->have_posts()) : 
                while ($report_posts->have_posts()) : $report_posts->the_post(); ?>
                    <div class="newsroom-post-item"> <!-- Updated class name -->
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="newsroom-post-image"> <!-- Updated class name -->
                                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a>
                            </div>
                        <?php endif; ?>
                        <div class="newsroom-post-details"> <!-- Updated class name -->
                            <div class="newsroom-post-date"> <!-- Updated class name -->
                                <?php echo get_the_date('d F Y'); // Output the date in the desired format ?>
                            </div>
                            
                                <a class="newsroom-post-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                           
                        </div>
                    </div>
                <?php endwhile;

                // Pagination Logic
                $numberOfPosts = $report_posts->found_posts;
                $no_of_paginations = ceil($numberOfPosts / $per_page);
                $pag_container = "";
                
                // Pagination calculation
                if ($cur_page >= 7) {
                    $start_loop = $cur_page - 3;
                    $end_loop = min($cur_page + 3, $no_of_paginations);
                    if ($cur_page > $no_of_paginations - 6) {
                        $start_loop = max($no_of_paginations - 6, 1);
                    }
                } else {
                    $start_loop = 1;
                    $end_loop = min(7, $no_of_paginations);
                }

                // Start Pagination Container
                $pag_container .= "<div class='newsroom-universal-pagination'><ul>";

                // Previous Button
                if ($cur_page > 1) {
                    $pre = $cur_page - 1;
                    $pag_container .= "<li p='$pre' class='active'>
                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='20' height='20'>
                            <path fill='none' stroke='currentColor' stroke-width='2' d='M15 19l-7-7 7-7'></path>
                        </svg>
                    </li>";
                } else {
                    $pag_container .= "<li class='inactive'>
                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='20' height='20'>
                            <path fill='none' stroke='currentColor' stroke-width='2' d='M15 19l-7-7 7-7'></path>
                        </svg>
                    </li>";
                }

                // Page Number Links
                for ($i = $start_loop; $i <= $end_loop; $i++) {
                    if ($cur_page == $i) {
                        $pag_container .= "<li p='$i' class='selected'>{$i}</li>";
                    } else {
                        $pag_container .= "<li p='$i' class='active'>{$i}</li>";
                    }
                }

                // Next Button
                if ($cur_page < $no_of_paginations) {
                    $nex = $cur_page + 1;
                    $pag_container .= "<li p='$nex' class='active'>
                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='20' height='20'>
                            <path fill='none' stroke='currentColor' stroke-width='2' d='M9 5l7 7-7 7'></path>
                        </svg>
                    </li>";
                } else {
                    $pag_container .= "<li class='inactive'>
                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='20' height='20'>
                            <path fill='none' stroke='currentColor' stroke-width='2' d='M9 5l7 7-7 7'></path>
                        </svg>
                    </li>";
                }

                // End Pagination Container
                $pag_container .= "</ul></div>";

                // Display Content or No Members Found Message
                if ($numberOfPosts > $per_page) {
                    echo "<div class='newsroom-pagination-content'></div><div class='newsroom-pagination-nav post-list-nav'>{$pag_container}</div>";
                }

                wp_reset_postdata();
            else : ?>
                <p>No reports found.</p>
            <?php endif; ?>
        </div>

        <div class="newsroom_pag_loading">
            <div class="newsroom_universal_container">
                <div class="newsroom-universal-content"></div>
            </div>
        </div>
    </div>
 <!-- Loading Animation (Optional) -->
       
    <?php
    return ob_get_clean();
}
add_shortcode('dynamic_newsroom_shortcode', 'dynamic_newsroom_archive_with_filters');
function news_post_result() {
    // Sanitize and retrieve the current page number from the POST request
    $page = isset($_POST['page']) ? sanitize_text_field($_POST['page']) : 1;
    $cur_page = max(1, $page); // Ensure the current page is at least 1
    $page -= 1; // Adjust for zero-based index
    $per_page = 9;
    $start = $page * $per_page;

    // Set the query arguments for fetching posts
    $query_args = array(
        'post_type' => 'post',
        'posts_per_page' => $per_page,
        'offset' => $start,
        'paged' => $cur_page,
        'category_name' => 'newsroom,news-room-2,news-room-1',
    );

    // Apply filters based on the selected dropdowns (category, tag, search)
    if (isset($_POST["selected_category"]) && !empty($_POST["selected_category"])) {
        $query_args['category_name'] = sanitize_text_field($_POST["selected_category"]);
    }

    if (isset($_POST["selected_tag"]) && !empty($_POST["selected_tag"])) {
        $query_args['tag'] = sanitize_text_field($_POST["selected_tag"]);
    }

    if (isset($_POST['search_result']) && !empty($_POST['search_result'])) {
        $query_args['s'] = sanitize_text_field($_POST['search_result']);
    }

    // Execute the query
    $report_posts = new WP_Query($query_args);

    ?>
<?php if ($report_posts->have_posts()) { ?>
    <div class="newsroom-post-listing">
        
            <?php while ($report_posts->have_posts()) { 
                $report_posts->the_post(); ?>
                <div class="newsroom-post-item">
                    <?php if (has_post_thumbnail()) { ?>
                        <div class="newsroom-post-image">
                             <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a>
                        </div>
                    <?php } ?>
                    <div class="newsroom-post-details">
                        <div class="newsroom-post-date">
                            <?php echo get_the_date('d F Y'); ?>
                        </div>
                        <a class="newsroom-post-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </div>
                </div>
            <?php } ?>
     </div>
		<?php
            // Pagination Logic
            $numberOfPosts = $report_posts->found_posts;
            $no_of_paginations = ceil($numberOfPosts / $per_page);
            $pag_container = "";

            // Pagination calculation
            $start_loop = ($cur_page >= 7) ? max($cur_page - 3, 1) : 1;
            $end_loop = min($cur_page + 3, $no_of_paginations);

            // Start Pagination Container
            $pag_container .= "<div class='newsroom-universal-pagination'><ul>";

            // Previous Button
            if ($cur_page > 1) {
                $pre = $cur_page - 1;
                $pag_container .= "<li p='$pre' class='active'>
                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='20' height='20'>
                        <path fill='none' stroke='currentColor' stroke-width='2' d='M15 19l-7-7 7-7'></path>
                    </svg>
                </li>";
            } else {
                $pag_container .= "<li class='inactive'>
                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='20' height='20'>
                        <path fill='none' stroke='currentColor' stroke-width='2' d='M15 19l-7-7 7-7'></path>
                    </svg>
                </li>";
            }

            // Page Number Links
            for ($i = $start_loop; $i <= $end_loop; $i++) {
                if ($cur_page == $i) {
                    $pag_container .= "<li p='$i' class='selected'>{$i}</li>";
                } else {
                    $pag_container .= "<li p='$i' class='active'>{$i}</li>";
                }
            }

            // Next Button
            if ($cur_page < $no_of_paginations) {
                $nex = $cur_page + 1;
                $pag_container .= "<li p='$nex' class='active'>
                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='20' height='20'>
                        <path fill='none' stroke='currentColor' stroke-width='2' d='M9 5l7 7-7 7'></path>
                    </svg>
                </li>";
            } else {
                $pag_container .= "<li class='inactive'>
                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='20' height='20'>
                        <path fill='none' stroke='currentColor' stroke-width='2' d='M9 5l7 7-7 7'></path>
                    </svg>
                </li>";
            }

            // End Pagination Container
            $pag_container .= "</ul></div>";

            // Display Pagination if needed
            if ($numberOfPosts > $per_page) {
                echo "<div class='newsroom-pagination-content'></div>
                      <div class='newsroom-pagination-nav post-list-nav'>{$pag_container}</div>";
            }
        } else { 
            // No posts found message
            echo "<div class='listing-member'><h3>Sorry, no News found!</h3></div>";
        }
        wp_reset_postdata(); ?>
    <?php
    die();
}

// Register the AJAX actions for logged-in and non-logged-in users
add_action('wp_ajax_nopriv_news_post_result', 'news_post_result');
add_action('wp_ajax_news_post_result', 'news_post_result');




