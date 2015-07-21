<?php
/**
 * Archive Page template file
 * */
get_header();
?>
<section>
    <!--Breadcrumb  Part Start-->
    <div class="breadcumb-bg">
        <div class="webpage-container container">
            <div class="site-breadcumb">
                <h1><?php
                    if (is_day()) :
                        _e('Daily Archives', 'advent');
                        echo " : " . get_the_date();
                    elseif (is_month()) :
                        _e('Monthly Archives', 'advent');
                        echo " : " . get_the_date('F Y');
                    elseif (is_year()) :
                        _e('Yearly Archives', 'advent');
                        echo " : " . get_the_date('Y');
                    else :
                        _e('Archives', 'advent');
                    endif;
                    ?>
                </h1>
                <ol class="breadcrumb breadcrumb-menubar">
                    <li>
<?php if (function_exists('advent_custom_breadcrumbs')) advent_custom_breadcrumbs(); ?>
                    </li>
                </ol>
            </div>
        </div>
    </div>    
    <!--Breadcrumb Part End-->
    <!-- Blogs start -->
    <div class="webpage-container">
        <div class="blog-main">


            <div class="col-md-9 col-sm-8">
<?php while (have_posts()) : the_post(); ?>
                    <div class="blog-details ">
                        <div class="blog-head">
                            <a href="<?php echo esc_url(get_permalink()); ?>"><?php echo get_the_title(); ?></a>
                            <ul><?php advent_entry_meta(); ?></ul>
                        </div>
                        
                        
                         
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'large', array( 'alt' => get_the_title(), 'class' => 'img-responsive') ); ?>
						<?php endif; ?>   
                            
                        <div class="blog-info">
    <?php the_excerpt(); ?>
                        </div>
                    </div>
<?php endwhile; ?> 
                <div class="post-pagination col-md-12 no-padding">
                    <div class="page-links">
                        <?php
                        // Previous/next page navigation.
                        the_posts_pagination(array(
                            'prev_text' => __('Previous', 'advent'),
                            'next_text' => '&nbsp;&nbsp;' . __(' Next', 'advent')
                        ));
                        ?></div>
                </div>
            </div>	
<?php get_sidebar(); ?>
        </div>

    </div>
</div>
<!-- Blogs End -->
</section>
<?php get_footer(); ?>
