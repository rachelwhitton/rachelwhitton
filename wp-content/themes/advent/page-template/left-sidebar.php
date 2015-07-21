<?php
/**
 * Template Name: Left Sidebar
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

    <div class="webpage-container">

        <div class="blog-main">

            <!-- Left Sidebar Start -->

            <?php get_sidebar(); ?>

            <!-- Left Sidebar End -->
            <!-- Left Sidebar Content Start -->
            <div class="col-md-9 col-sm-8">
                <div class="blog-details single">
                    <?php while (have_posts()) : the_post(); ?>

                        <?php $advent_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_id()), 'large'); ?>
                        <?php if ($advent_image[0] != "") { ?>
                            <div class="blog-img">
                                <img src="<?php echo esc_url($advent_image[0]); ?>" width="<?php echo $advent_image[1]; ?>" height="<?php echo $advent_image[2]; ?>" alt="<?php echo get_the_title(); ?>" class="img-responsive" />
                            </div>
                        <?php } ?>
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
                <?php comments_template('', true); ?>
            </div>

        </div>
    </div>
    <!-- Left Sidebar Content End -->
</section>
<?php get_footer(); ?>

