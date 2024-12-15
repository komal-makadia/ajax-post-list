<div class="publication-banner">

    <div class="container2">

        <div class="head">

            <h1>
                <img src="<?php echo get_theme_file_uri() ?>/assets2/images/publication/dot-black.svg" alt="">
                All Publications
            </h1>

            <h2>
                Check out all of <br>
                <span>

                    Our Publications
                </span>


            </h2>


        </div>


        <div class="filter">



            <input id="searchInput" class="font14" type="text" placeholder="Search" />

            <button class="font14">
                <select class="font14" id="sortOrder">
                    <option value="">Order By</option>
                    <option value="ASC">Oldest First</option>
                    <option value="DESC">Recent First</option>
                </select>
            </button>

            <button class="font14">
                <select class="font14" name="authors" id="publication_authors">
                    <option value="">Author</option>
                    <?php
                    $args = array(
                        'post_type' => 'people',
                        'posts_per_page' => '-1',
                        'orderby' => 'title',
                        'order' => 'ASC',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'people-category',
                                'field' => 'slug',
                                'terms' => 'publication-author',
                            ),
                        ),
                    );

                    $authors = new WP_Query($args);

                    if ($authors->have_posts()) {
                        while ($authors->have_posts()) {
                            $authors->the_post();
                            ?>
                            <option value="<?php echo get_the_ID(); ?>"><?php the_title(); ?></option>
                            <?php
                        }
                        wp_reset_postdata();
                    }
                    ?>
                </select>

            </button>


            <button class="font14">
                <select class="font14" name="research_verticals" id="research_verticals">
                    <option value="">Research Verticals</option>
                    <?php
                    $args = array(
                        'post_type' => 'publication',
                        'posts_per_page' => -1,
                    );

                    $query = new WP_Query($args);
                    $all_categories = array();

                    if ($query->have_posts()) {
                        while ($query->have_posts()) {
                            $query->the_post();

                            // Retrieve the research_categories custom field
                            $research_categories = get_field('reasearch_categories');

                            if ($research_categories) {
                                foreach ($research_categories as $category) {
                                    // Check if the category has no ancestors (is a parent category)
                                    if (empty(get_ancestors($category->term_id, 'research-category'))) {
                                        // Store unique category names and their IDs
                                        $all_categories[$category->term_id] = esc_html($category->name);
                                    }
                                }
                            }
                        }

                        // Display all unique parent research categories as <option> elements
                        foreach ($all_categories as $term_id => $name) {
                            echo '<option value="' . esc_attr($term_id) . '" data-category="' . strtolower(str_replace([' ', ','], ['-', ''], $name)) . '">' . esc_html($name) . '</option>';
                        }
                    } else {
                        echo 'No publications with research categories found.';
                    }

                    wp_reset_postdata();
                    ?>





                </select>
            </button>

            <button class="font14" id="search">Search</button>
            <button style="clearSearchField" class="font14" id="clearSearchField">Clear Filter</button>

        </div>


        <div class="publications_list">

            <div class="tabBtnContainer">


                <?php
                // Step 1: Retrieve all categories in the 'publication-category' taxonomy
                $categories = get_terms(array(
                    'taxonomy' => 'publication-category',
                    'hide_empty' => true, // Hide empty categories
                ));

                // Step 2: Initialize an array to hold the counts
                $category_counts = array();

                // Count the posts for 'All Publications'
                $all_publications_count = wp_count_posts('publication')->publish; // Get total number of published publications
                $blogs_count = wp_count_posts('blog')->publish;
                $opeds_count = wp_count_posts('news')->publish;
                $newsLetter_count = wp_count_posts('newsletter')->publish;

                $total_posts_count = $all_publications_count + $blogs_count + $opeds_count;

                $category_counts[] = array(
                    'name' => 'All Publications',
                    'count' => $all_publications_count,
                    'slug' => 'all' // Add a slug for "All Research Studies"
                );

                // Step 3: Loop through each category and get the post counts
                foreach ($categories as $category) {
                    $args = array(
                        'post_type' => 'publication',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'publication-category',
                                'field' => 'term_id',
                                'terms' => $category->term_id,
                            ),
                        ),
                        'posts_per_page' => -1, // Get all posts in this category
                    );

                    $query = new WP_Query($args);
                    if ($category->name == "India Policy Forum") {
                        /* $category_counts[] = array(
                            'name' => 'Op-eds',
                            'count' => 0,
                            'slug' => 'op-eds',
                        ); */
                    } else {
                        $category_counts[] = array(
                            'name' => $category->name,
                            'count' => $query->found_posts, // Get the number of posts found
                            'slug' => $category->slug // Get the slug for each category
                        );
                    }

                    wp_reset_postdata(); // Reset post data
                }

                // Step 4: Sort the categories alphabetically by name
                usort($category_counts, function ($a, $b) {
                    return strcmp($a['name'], $b['name']);
                });

                // Step 5: Display the results
                foreach ($category_counts as $category_count) {
                    echo '<label class="tabBtn font20">';
                    echo '<input type="checkbox" name="pubCat" data-category="' . esc_attr($category_count['slug']) . '" /> ';
                    echo $category_count['name'] . ' (' . $category_count['count'] . ')';
                    echo '</label>';
                }
                ?>
                <label class="tabBtn font20">
                    <input type="checkbox" name="pubCat" data-category="blog" />
                    Blog <?php echo '(' . $blogs_count . ')'; ?>
                </label>
                <label class="tabBtn font20">
                    <input type="checkbox" name="pubCat" data-category="op-eds" />
                    Op-Eds <?php echo '(' . $opeds_count . ')'; ?>
                </label>
                <label class="tabBtn font20">
                    <input type="checkbox" name="pubCat" data-category="newsletter" />
                    Newsletter <?php echo '(' . $newsLetter_count . ')'; ?>
                </label>


                <div id="ascendinglottie-directURL" class="sideAbstract">
                    <div id="ascendinglottieContainerDirectURL" class="lottieContainer">

                    </div>
                </div>



            </div>

            <div class="list">

                <div class="blob1">
                    <img src="<?php echo get_theme_file_uri() ?>/assets2/images/podcasts/blob.svg" alt="" />
                </div>
                <div class="blob2">
                    <img src="<?php echo get_theme_file_uri() ?>/assets2/images/podcasts/blob.svg" alt="" />
                </div>


                <div class="content">

                    <!-- <?php
                    $publications = new WP_Query(array(
                        'posts_per_page' => 12,
                        'post_type' => 'publication',
                        'orderby' => 'date',
                        'order' => 'DESC',
                    ));

                    if ($publications->have_posts()):
                        while ($publications->have_posts()):
                            $publications->the_post(); ?>
                            <div class="cardItem cardItem2">
                                <div class="cardTop">
                                    <?php
                                    $categories = get_the_terms(get_the_ID(), 'publication-category');

                                    if ($categories && !is_wp_error($categories) && !empty($categories)) {
                                        echo '<div class="cardTag">';
                                        foreach ($categories as $category) {
                                            echo esc_html($category->name) . ' '; // Safely output category names
                                        }
                                        echo '</div>';
                                    } else {
                                        echo '<div class="cardTag" style="    background-color: transparent;"></div>'; // Blank div when no categories exist
                                    }
                                    ?>


                                    <div class="cardDate"><?php echo get_the_date('F Y'); ?></div>
                                </div>
                                <div class="cardMid">
                                    <h3><?php the_title(); ?></h3>
                                    <div class="cardAuthor">
                                        <?php
                                        $source_post_id = get_the_ID();
                                        $related_posts = get_field('publications_author_name', $source_post_id);

                                        if ($related_posts) {
                                            $author_names = array();
                                            foreach ($related_posts as $related_post) {
                                                $author_names[] = get_the_title($related_post);
                                            }
                                            $last_author = array_pop($author_names);
                                            if (!empty($author_names)) {
                                                echo implode(', ', $author_names) . ' & ' . $last_author;
                                            } else {
                                                echo $last_author;
                                            }
                                        } else {
                                            echo '';
                                        }
                                        ?>

                                    </div>
                                    <div class="cardDes">
                                        <p><?php echo wp_trim_words(get_the_content(), 10, '...'); ?></p>
                                    </div>
                                </div>
                                <div class="cardBottom">
                                    <a class="btn btnLg btnSecondary" href="<?php echo get_permalink(); ?>">Read more</a>
                                    <a class="btn btnLg" href="#">Download</a>
                                </div>
                            </div>
                        <?php endwhile;
                        wp_reset_postdata();
                    else: ?>
                        <p>No publications found.</p>
                    <?php endif; ?> -->

                </div>

                <div class="viewMore">

                    <h1>
                        View more
                    </h1>

                </div>

            </div>


        </div>







    </div>


</div>