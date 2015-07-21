<?php

/*
 * advent Main Sidebar
 */

function advent_widgets_init() {

    register_sidebar(array(
        'name' => __('Main Sidebar', 'advent'),
        'id' => 'sidebar-1',
        'description' => __('Main sidebar that appears on the right.', 'advent'),
        'before_widget' => '<aside id="%1$s" class="sidebar-widget widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<div class="sidebar-widget"><h3>',
        'after_title' => '</h3></div>',
    ));
}

add_action('widgets_init', 'advent_widgets_init');

/**
 * Set up post entry meta.    
 * Meta information for current post: categories, tags, permalink, author, and date.    
 * */


function advent_entry_meta() {

	$advent_categories_list = get_the_category_list(',','');
	
	$advent_tag_list = get_the_tag_list('', ',' );
	
	$advent_author= ucfirst(get_the_author());
	
	$advent_author_url= esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
	
	$advent_comments = wp_count_comments(get_the_ID()); 	
	
	$advent_date = sprintf('<time datetime="%1$s">%2$s</time>', esc_attr(get_the_date('c')), esc_html(get_the_date('M d, Y')));
?>	
	
               <li><a href="<?php echo $advent_author_url; ?>" rel="tag"><?php echo $advent_author; ?></a></li>
                
                <li><?php echo $advent_date; ?></li>
                
                <?php if(!empty($advent_categories_list)) { ?><li><?php _e('Post in : ', 'advent'); ?><?php echo $advent_categories_list; ?></li><?php } ?>
                
                <?php if(!empty($advent_tag_list)) { ?><li><?php _e('Tags : ', 'advent'); ?><?php echo $advent_tag_list; ?></li><?php } ?>
                
                <li><?php $advent_comment = comments_number(__('No Comments', 'advent'), __('1 Comment', 'advent'), __('% Comments', 'advent')); ?></li>
		
<?php 	
}
