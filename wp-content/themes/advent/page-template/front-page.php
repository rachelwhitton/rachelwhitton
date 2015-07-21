<?php
/*
 * Template Name: Home Page
 */
$advent_options = get_option('advent_theme_options');
?>
<?php get_header(); ?>

<section>
    <!-- features section start -->
    <div class="webpage-container container">
        <?php if (!empty($advent_options['home-title']) OR !empty($advent_options['home-content'])) { ?>
            <div class="pro-features text-center">
                <?php if (!empty($advent_options['home-title'])) { ?> <h2> <?php echo esc_attr($advent_options['home-title']); ?></h2> <?php } ?>
                <?php if (!empty($advent_options['home-content'])) { ?> <p> <?php echo esc_textarea($advent_options['home-content']); ?></p> <?php } ?>
            </div>        
        <?php } ?>    
        <div class="pro-features-icon row">
            <?php for ($advent_section_i = 1; $advent_section_i <= 6; $advent_section_i++): ?>
                <?php if (!empty($advent_options['faicon-' . $advent_section_i]) && !empty($advent_options['section-title-' . $advent_section_i])) { ?>		
                    <div class="col-md-4 col-sm-6 clear-feature">

                        <div class="bg-color animation text-center">
                            <div class="inner-bg">
                                <span class="fa <?php echo $advent_options['faicon-' . $advent_section_i]; ?> fa-3x fa-icons"></span>
                            </div>
                        </div>
                        <?php if (!empty($advent_options['section-title-' . $advent_section_i])) { ?>		
                            <div class="pro-features-info">
                                <?php if (!empty($advent_options['section-title-' . $advent_section_i])) { ?>
                                    <h2><?php echo esc_attr($advent_options['section-title-' . $advent_section_i]); ?></h2>
                                <?php } ?>
                                <?php if (!empty($advent_options['section-content-' . $advent_section_i])) { ?>
                                    <p><?php echo esc_textarea($advent_options['section-content-' . $advent_section_i]); ?></p>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php } endfor; ?>
        </div>     
    </div>
    <!-- features section end -->

    <!-- How it works part start -->
    <?php if (!empty($advent_options['howitwork']) OR !empty($advent_options['howitworktitle']) OR !empty($advent_options['howitworkdesc']) OR !empty($advent_options['howitwork-img'])) { ?>
        <div class="works">
            <div class="webpage-container">
                <div class="how-works">
                    <?php if (!empty($advent_options['howitwork'])) { ?><h2> <?php echo esc_attr($advent_options['howitwork']); ?></h2><?php } ?>
                    <div class="<?php echo $advent_options['howitwork-img'] ? 'col-sm-6 col-md-8' : 'col-sm-12 col-md-12' ?> works-left">
                        <?php if (!empty($advent_options['howitworktitle'])) { ?><h2><?php echo esc_attr($advent_options['howitworktitle']); ?></h2><?php } ?>
                        <?php if (!empty($advent_options['howitworkdesc'])) { ?><p><?php echo esc_textarea($advent_options['howitworkdesc']); ?></p> <?php } ?>
                    </div>
                    <?php if (!empty($advent_options['howitwork-img'])) { ?>
                        <div class="col-sm-6 col-md-4 chart-img">
                            <img src="<?php echo esc_url($advent_options['howitwork-img']); ?>" class="img-responsive" alt="<?php echo get_the_title(); ?>">
                        </div><?php } ?>
                </div>
            </div>     
        </div>
    <?php } ?>
    <!-- How it works part end -->

    <!-- recent post part start -->
    <?php if (!empty($advent_options['post-category'])) { ?>
        <div class="container webpage-container">
            <?php if (!empty($advent_options['post-title'])) { ?>
                <div class="col-md-12 no-padding title text-center">
                    <h2>
                        <?php echo esc_attr($advent_options['post-title']); ?>
                    </h2>
                </div>
            <?php } ?>
            <?php
            $advent_args = array(
                'cat' => $advent_options['post-category'],
                'meta_query' => array(
                    array(
                        'key' => '_thumbnail_id',
                        'compare' => 'EXISTS'
                    ),
                )
            );
            $advent_query = new $wp_query($advent_args);
            ?>
            <?php if ($advent_query->have_posts()) { ?>       
                <div class="row home-gallery"> 
                    <?php
                    if ($advent_query->found_posts >= 4) {
                        ?>
                        <div class="slider-button">
                            <a class="btn prev btn_lr">
                                <i class="fa fa-angle-left"></i>
                            </a>
                            <a class="btn next btn_lr">
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </div> 
                    <?php } ?>
                    <div class="home-gallery-image" id="our-brand"> 
                        <?php
                        while ($advent_query->have_posts()) {
                            $advent_query->the_post();
                            ?>
                            <div class="item home-gallery-box">               
                                <div class="home-gallery-img">
									
                                    <?php if ( has_post_thumbnail() ) : ?>
											<?php the_post_thumbnail( 'advent-home-thumbnail-image', array( 'alt' => get_the_title(), 'class' => 'img-responsive') ); ?>
									<?php endif; ?>

                                    <div class="home-gallery-img-hover">
                                        <div class="mask"></div>
                                        <ul>
                                            <li><a href="<?php echo get_permalink(get_the_ID()) ?>"><i class="fa fa-arrows"></i></a></li>
                                        </ul>
                                    </div>                    
                                </div>
                            </div>
                        <?php } ?>
                        <?php wp_reset_postdata(); ?>
                    </div>        	
                </div>
            </div> 
        <?php } else { ?>
            <p><?php _e('No posts found', 'advent') ?></p> 
        <?php } ?>   
        <!-- recent post part end -->
    <?php } ?>

</section>
<?php get_footer(); ?>
