<?php
/**
 * Single Post template file
 * */
get_header();
?>
<section>
    <!--Breadcrumb  Part Start-->
    <div class="breadcumb-bg">
        <div class="webpage-container container">
            <div class="site-breadcumb">
                <h1><?php the_title(); ?></h1>
                <ol class="breadcrumb breadcrumb-menubar">
                    <li>
                        <?php if (function_exists('advent_custom_breadcrumbs')) advent_custom_breadcrumbs(); ?>
                    </li>
                </ol>
            </div>
        </div>
    </div>    
    <!--Breadcrumb Part End-->
    <!--  Post Detail Part Start -->
    <div class="webpage-container">
        <div class="blog-main">
            <div id="post-<?php the_ID(); ?>" <?php post_class("col-md-9 col-sm-8"); ?>> 
                <div class="blog-details single">
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="blog-head">
                            <ul><?php advent_entry_meta(); ?></ul>
                        </div>
                        
                        <?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'large', array( 'alt' => get_the_title(), 'class' => 'img-responsive') ); ?>
						<?php endif; ?> 
                        <div class="blog-info">
                            <?php
                                the_content();
                                wp_link_pages(array(
                                    'before' => '<div class="page-links"><span class="page-links-title">' . __('Pages:', 'advent') . '</span>',
                                    'after' => '</div>',
                                    'link_before' => '<span>',
                                    'link_after' => '</span>',
                                ));
                                ?>
                        </div>
<?php endwhile; ?> 
                </div>
                <div class="post-pagination col-md-12 no-padding">
                    <div class="prev-post">
<?php previous_post_link(); ?>
                    </div>
                    <div class="next-post">
<?php next_post_link(); ?>  
                    </div>
                </div>
            <?php comments_template('', true); ?>
            </div>
<?php get_sidebar(); ?>
        </div>
    </div>
    <!-- Post Detail Part End -->
</section>
<?php get_footer(); ?>







