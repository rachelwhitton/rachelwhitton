<?php

/*
 * Advent  Breadcrumbs
 */
global $advent_options;

function advent_custom_breadcrumbs() {

    $advent_showonhome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $advent_delimiter = '/'; // advent_delimiter between crumbs
    $advent_home = __('Home', 'advent'); // text for the 'Home' link
    $advent_showcurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $advent_before = ' '; // tag before the current crumb
    $advent_after = ' '; // tag after the current crumb

    global $post;
    $advent_homelink = esc_url(home_url());

    if (is_home() || is_front_page()) {

        if ($advent_showonhome == 1)
            echo '<ol class="breadcrumb"><li class="active"><a href="' . $advent_homelink . '">' . $advent_home . '</a></li></ol>';
    } else {

        echo '<ol class="breadcrumb"><li class="active"><a href="' . $advent_homelink . '">' . $advent_home . '</a> ' . $advent_delimiter . ' ';
        if (is_category()) {
            $advent_thisCat = get_category(get_query_var('cat'), false);
            if ($advent_thisCat->parent != 0)
                echo get_category_parents($advent_thisCat->parent, TRUE, ' ' . $advent_delimiter . ' ');
            echo $advent_before . __('Archive by category', 'advent') . ' "' . single_cat_title('', false) . '"' . $advent_after;
        } elseif (is_search()) {
            echo $advent_before . __('Search Results For ', 'advent') . ' "' . get_search_query() . '"' . $advent_after;
        } elseif (is_day()) {
            echo '<a href="' . esc_url(get_year_link(get_the_time('Y'))) . '">' . get_the_time('Y') . '</a> ' . $advent_delimiter . ' ';
            echo '<a href="' . esc_url(get_month_link(get_the_time('Y'), get_the_time('m'))) . '">' . get_the_time('F') . '</a> ' . $advent_delimiter . ' ';
            echo $advent_before . get_the_time('d') . $advent_after;
        } elseif (is_month()) {
            echo '<a href="' . esc_url(get_year_link(get_the_time('Y'))) . '">' . get_the_time('Y') . '</a> ' . $advent_delimiter . ' ';
            echo $advent_before . get_the_time('F') . $advent_after;
        } elseif (is_year()) {
            echo $advent_before . get_the_time('Y') . $advent_after;
        } elseif (is_single() && !is_attachment()) {
            if (get_post_type() != 'post') {
                $advent_post_type = get_post_type_object(get_post_type());
                $advent_slug = $advent_post_type->rewrite;
                echo '<a href="' . $advent_homelink . '/' . $advent_slug['slug'] . '/">' . $advent_post_type->labels->singular_name . '</a>';
                if ($advent_showcurrent == 1)
                    echo ' ' . $advent_delimiter . ' ' . $advent_before . get_the_title() . $advent_after;
            } else {
                $advent_cat = get_the_category();
                $advent_cat = $advent_cat[0];
                $advent_cats = get_category_parents($advent_cat, TRUE, ' ' . $advent_delimiter . ' ');
                if ($advent_showcurrent == 0)
                    $advent_cats =
                            preg_replace("#^(.+)\s$advent_delimiter\s$#", "$1", $advent_cats);
                echo $advent_cats;
                if ($advent_showcurrent == 1)
                    echo $advent_before . get_the_title() . $advent_after;
            }
        } elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
            $advent_post_type = get_post_type_object(get_post_type());
            echo $advent_before . $advent_post_type->labels->singular_name . $advent_after;
        } elseif (is_attachment()) {
            $advent_parent = get_post($post->post_parent);
            $advent_cat = get_the_category($advent_parent->ID);
            $advent_cat = $advent_cat[0];
            echo get_category_parents($advent_cat, TRUE, ' ' . $advent_delimiter . ' ');
            echo '<a href="' . get_permalink($advent_parent) . '">' . $advent_parent->post_title . '</a>';
            if ($advent_showcurrent == 1)
                echo ' ' . $advent_delimiter . ' ' . $advent_before . get_the_title() . $advent_after;
        } elseif (is_page() && !$post->post_parent) {
            if ($advent_showcurrent == 1)
                echo $advent_before . get_the_title() . $advent_after;
        } elseif (is_page() && $post->post_parent) {
            $advent_parent_id = $post->post_parent;
            $advent_breadcrumbs = array();
            while ($advent_parent_id) {
                $advent_page = get_post($advent_parent_id);
                $advent_breadcrumbs[] = '<a href="' . get_permalink($advent_page) . '">' . get_the_title($advent_page) . '</a>';
                $advent_parent_id = $advent_page->post_parent;
            }
            $advent_breadcrumbs = array_reverse($advent_breadcrumbs);
            for ($advent_i = 0; $advent_i < count($advent_breadcrumbs); $advent_i++) {
                echo $advent_breadcrumbs[$advent_i];
                if ($advent_i != count($advent_breadcrumbs) - 1)
                    echo ' ' . $advent_delimiter . ' ';
            }
            if ($advent_showcurrent == 1)
                echo ' ' . $advent_delimiter . ' ' . $advent_before . get_the_title() . $advent_after;
        } elseif (is_tag()) {
            echo $advent_before . _e('Posts tagged', 'advent') . ' "' . single_tag_title('', false) . '"' . $advent_after;
        } elseif (is_author()) {
            global $author;
            $advent_userdata = get_userdata($author);
            echo $advent_before . _e('Articles posted by', 'advent') . ' "' . $advent_userdata->display_name . '"' . $advent_after;
        } elseif (is_404()) {
            echo $advent_before . _e('Error 404', 'advent') . $advent_after;
        }

        if (get_query_var('paged')) {
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
                echo ' (';
            echo __('Page', 'advent') . ' ' . get_query_var('paged');
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
                echo ')';
        }

        echo '</li></ol>';
    }
}

// end advent_custom_breadcrumbs()
?>
