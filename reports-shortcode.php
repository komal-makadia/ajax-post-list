// Register 'year' taxonomy for posts
function add_custom_taxonomy_year() {
    $labels = array(
        'name'              => _x( 'Years', 'taxonomy general name', 'textdomain' ),
        'singular_name'     => _x( 'Year', 'taxonomy singular name', 'textdomain' ),
        'search_items'      => __( 'Search Years', 'textdomain' ),
        'all_items'         => __( 'All Years', 'textdomain' ),
        'parent_item'       => __( 'Parent Year', 'textdomain' ),
        'parent_item_colon' => __( 'Parent Year:', 'textdomain' ),
        'edit_item'         => __( 'Edit Year', 'textdomain' ),
        'update_item'       => __( 'Update Year', 'textdomain' ),
        'add_new_item'      => __( 'Add New Year', 'textdomain' ),
        'new_item_name'     => __( 'New Year Name', 'textdomain' ),
        'menu_name'         => __( 'Year', 'textdomain' ),
    );

    $args = array(
        'hierarchical'      => false, // False to make it non-hierarchical like tags
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'year' ),
    );

    register_taxonomy( 'year', array( 'post' ), $args );
}
add_action( 'init', 'add_custom_taxonomy_year' );

// Automatically assign the 'year' taxonomy term when a post is published
function add_year_taxonomy_on_publish( $ID, $post ) {
    // Get the publication year of the post
    $year = get_the_date( 'Y', $ID );

    // Assign the year as a term in the 'year' taxonomy
    wp_set_post_terms( $ID, $year, 'year', false );
}
add_action( 'save_post', 'add_year_taxonomy_on_publish', 10, 2 );

// Function to populate 'year' taxonomy for existing posts
function populate_year_taxonomy_for_old_posts() {
    // Get all posts
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => -1, // Get all posts
        'post_status'    => 'publish',
    );

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $post_id = get_the_ID();
            $year = get_the_date( 'Y', $post_id );

            // Assign the year as a term in the 'year' taxonomy
            wp_set_post_terms( $post_id, $year, 'year', false );
        }
        // Restore original post data
        wp_reset_postdata();
    }
}

// Uncomment this line to run the function once and then comment it again.
 //populate_year_taxonomy_for_old_posts();

// Dynamic Shortcode for Reports Archive with Filters

function dynamic_report_archive_with_filters() {
    ob_start(); ?>
    <div class="report-archive-container">
        <!-- Filter Header Dropdown -->
        

        <!-- Posts List -->
        <div class="report-posts-list" id="report-posts-list">
            <?php
			$page = max(1, get_query_var('paged', 1)); // Current page
            $per_page = 5; // Items per page
            $start = ($page - 1) * $per_page; // Offset for query
            $cur_page = $page;
            // Query posts based on filters
            //$paged = get_query_var('paged') ? get_query_var('paged') : 1;
            $query_args = array(
                'post_type' => 'post',
                'posts_per_page'=>$per_page, 
				 'offset' => $start ,
				 'paged' => $page , 
                'category_name' => 'annual-reports,sustainability-reports,financial-reports',
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
                    <div class="report-post">
                        <div class="report-post-thumbnail">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>" target="_blank">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="report-post-content">
                            
                            <span class="report-post-category">
                                <?php
                                $categories = get_the_category();

								$category_names = []; // Initialize an array to hold category names
								$fy_tags = []; // Initialize an array to hold unique FY tags

								if ($categories) {
									foreach ($categories as $category) {
										$category_names[] = esc_html($category->name); // Add category name to the array

										// Fetch tags associated with the post
										$tag_years = get_the_tags();

										if ($tag_years) {
											foreach ($tag_years as $tag) {
												// Check if the slug is a four-digit year
												if (preg_match('/^\d{4}$/', $tag->slug)) {
													$fy_tags[esc_html($tag->name)] = true; // Use associative array to ensure uniqueness
												}
											}
										}
									}

									// Join category names with "&"
									$category_names_output = implode(' & ', $category_names);

									// Extract unique FY tags and join them with ", "
									$fy_tags_output = !empty($fy_tags) ? ' ' . implode(', ', array_keys($fy_tags)) : '';

									// Display categories with FY tags
									echo $category_names_output .' FY'.$fy_tags_output . '<br>';
								}

                                ?>
                            </span>
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            <a class="download-link" href="<?php the_permalink(); ?>" target="_blank">
                                Download PDF <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 11.3l-1-1.1-4 4V3h-1.5v11.3L7 10.2l-1 									1.1 6.2 5.8 5.8-5.8zm.5 3.7v3.5h-13V15H4v5h16v-5h-1.5z"></path></svg>
                            </a>
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
            $pag_container .= "<div class='cvf-universal-pagination'><ul>";

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
                if ($cur_page + 1 == $i) {
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
                echo "<div class='cvf-pagination-content'>{$msg}</div><div class='cvf-pagination-nav post-list-nav'>{$pag_container}</div>";
            }

                wp_reset_postdata();
            else : ?>
                <p>No reports found.</p>
            <?php endif; ?>
        </div>

        <!-- Loading Animation (Optional) -->
        <div class="cvf_pag_loading">
            <div class="cvf_universal_container">
                <div class="cvf-universal-content"></div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('dynamic_report_shortcode', 'dynamic_report_archive_with_filters');

function filter_header_part_shortcode(){
	// Fetch the categories you need (replace with actual slugs)
    $categories = get_categories(array(
        'taxonomy' => 'category',
        'slug' => array('annual-reports', 'sustainability-reports', 'financial-reports'),
        'hide_empty' => false
    ));

    // Fetch the tags for the year
    $tags = get_tags(array(
        'taxonomy' => 'year', // Assuming 'year' is a custom tag taxonomy, adjust if necessary
        'hide_empty' => false
    ));

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
                    <select name="category" class="filter-dropdown category-dropdown" id="category-dropdown">
                        <option value="">All Reports</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo esc_attr($category->slug); ?>"><?php echo esc_html($category->name); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Tags dropdown -->
                    <select name="tag" class="filter-dropdown tag-dropdown" id="tag-dropdown">
                        <option value="">All Years</option>
                        <?php foreach ($tags as $tag) : ?>
                            <option value="<?php echo esc_attr($tag->slug); ?>"><?php echo esc_html($tag->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-right">
                    <!-- Search Bar -->
                    <div class="search-bar" id="search-bar">
                        
                        <input type="text" name="s" placeholder="Search...">
<svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="15" height="14" fill="none" viewBox="0 0 15 14"><ellipse cx="6.7696" cy="6.46739" rx="5.96736" ry="5.96739" stroke="var(--filter-query-block-icon-color)"></ellipse><path d="M13.8023 13.5L10.9893 10.687" stroke="var(--filter-query-block-icon-color)"></path></svg>
                    </div>
                </div>
            </div>
        </div>
<?php
return ob_get_clean();
}
add_shortcode('filter_header_part_shortcode', 'filter_header_part_shortcode');

function reports_post_result() {
    // Sanitize and retrieve the current page number from the POST request
//echo $_POST['page'];
    $page = sanitize_text_field($_POST['page']);
    $cur_page = $page;
    $page -= 1; // Adjust for zero-based index
    $per_page = 5;
	$previous_btn = true;
	$next_btn = true;
	$first_btn = true;
	$last_btn = true;
	$start = $page * $per_page;

    // Query posts only from the specified categories

    $query_args = array(
        'post_type' => 'post',
        'posts_per_page' => $per_page,
        'offset' => $page * $per_page,
        'paged' => $page + 1, // Use 1-based index for pagination
        'category_name' => 'annual-reports,sustainability-reports,financial-reports', // Adjust to your actual category slugs
    );

    // Add filters based on the selected dropdowns (all reports, categories, tags, or search)
    if (isset($_POST["selected_category"]) && !empty($_POST["selected_category"])) {
        $query_args['category_name'] = sanitize_text_field($_POST["selected_category"]);
    }

	if($_POST["selected_category"] = "" && $_POST["selected_tag"] ="" && $_POST['search_result'] = ""){
		 $query_args['post_type'] = 'post';
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
    <div class="report-posts-list">
        <?php
        if ($report_posts->have_posts()) {
            while ($report_posts->have_posts()) {
                $report_posts->the_post(); ?>
                <div class="report-post">
                    <div class="report-post-thumbnail">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>" target="_blank">
                                <?php the_post_thumbnail('medium'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="report-post-content">
                        
                        <span class="report-post-category">
                            <?php
                            // Display categories with FY tag
                            $categories = get_the_category();

								$category_names = []; // Initialize an array to hold category names
								$fy_tags = []; // Initialize an array to hold unique FY tags

								if ($categories) {
									foreach ($categories as $category) {
										$category_names[] = esc_html($category->name); // Add category name to the array

										// Fetch tags associated with the post
										$tag_years = get_the_tags();

										if ($tag_years) {
											foreach ($tag_years as $tag) {
												// Check if the slug is a four-digit year
												if (preg_match('/^\d{4}$/', $tag->slug)) {
													$fy_tags[esc_html($tag->name)] = true; // Use associative array to ensure uniqueness
												}
											}
										}
									}

									// Join category names with "&"
									$category_names_output = implode(' & ', $category_names);

									// Extract unique FY tags and join them with ", "
									$fy_tags_output = !empty($fy_tags) ? ' ' . implode(', ', array_keys($fy_tags)) : '';

									// Display categories with FY tags
									echo $category_names_output .' FY'.$fy_tags_output . '<br>';
								}

                            ?>
                        </span>
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        <a class="download-link" href="<?php the_permalink(); ?>" target="_blank">
                            Download PDF <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 11.3l-1-1.1-4 4V3h-1.5v11.3L7 10.2l-1 1.1 6.2 5.8 5.8-5.8zm.5 3.7v3.5h-13V15H4v5h16v-5h-1.5z"></path></svg>
                        </a>
                    </div>
                </div>
            <?php }

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
            $pag_container .= "<div class='cvf-universal-pagination'><ul>";

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
                if ($cur_page + 1 == $i) {
                    $pag_container .= "<li p='$i' class='selected'>{$i}</li>";
                } else {
                    $pag_container .= "<li p='$i' class='active'>{$i}</li>";
                }
            }

            // Next Button
//             if ($cur_page < $no_of_paginations - 1) {
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
                echo "<div class='cvf-pagination-content'>{$msg}</div><div class='cvf-pagination-nav'>{$pag_container}</div>";
            }
        } else {
            echo "<div class='listing-member'><h3>Sorry, no Reports found!</h3></div>";
        }

        wp_reset_postdata();
        ?>
    </div>

    <?php
    die();
}

add_action('wp_ajax_nopriv_reports_post_result', 'reports_post_result');
add_action('wp_ajax_reports_post_result', 'reports_post_result');
