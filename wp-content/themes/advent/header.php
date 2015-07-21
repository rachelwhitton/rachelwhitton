<?php
/**
 * The Header template for our theme
 */
$advent_options = get_option('advent_theme_options');
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">	
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
        <!--[if lt IE 9]>
                <script src="<?php echo esc_url(get_template_directory_uri()); ?>/js/html5.js"></script>
        <![endif]-->
        <?php if (!empty($advent_options['favicon'])) { ?>
            <link rel="shortcut icon" href="<?php echo esc_url($advent_options['favicon']); ?>">
        <?php } ?>
        <?php wp_head(); ?>
       
    </head>
    <body <?php body_class(); ?>>
        <?php /* start condition for only home page */
        if (is_page_template('page-template/front-page.php')) {
            ?> 
		<div class="header_bg">
                <span class="mask-overlay"></span>
                <div class="webpage-container">
                        <?php if (!empty($advent_options['headertop-logo']) OR !empty($advent_options['topheading'])) { ?>
                        <div class="col-sm-6 col-md-6 col-sm-offset-1 text-center center-block">
                                <?php if (!empty($advent_options['headertop-logo'])) { ?>
                                <div class="logo">
                                    <?php if (!empty($advent_options['headertop-logo'])) { 
										 $advent_image = esc_url($advent_options['headertop-logo']); 
										 $advent_id = advent_get_image_id($advent_image);
										 $advent_image = wp_get_attachment_image_src($advent_id, 'headertop-logo');
									?>
										<img class="img-circle img-responsive" alt="<?php echo esc_attr($advent_options['headertop-logo']); ?>" src="<?php echo esc_url($advent_image[0]); ?>" width="<?php echo $advent_image[1]; ?>" height="<?php echo $advent_image[2]; ?>">
                                <?php } ?>
                                </div>
                            <?php } ?>
        <?php if (!empty($advent_options['topheading'])) { ?>
                                <div class="slogan">
                                    <h1><?php echo esc_html($advent_options['topheading']); ?></h1>
                                </div>
                        <?php } ?>
                        </div>
                    <?php } ?>
    <?php if (!empty($advent_options['headertop-img'])) { ?>
                        <div class="col-sm-5 col-md-6 mobile">                
                            <img src="<?php echo esc_url($advent_options['headertop-img']); ?>" class="img-responsive" alt="<?php echo esc_attr(get_the_title()); ?>">             
                        </div>
    <?php } ?>
                </div>
            </div>
<?php } /* end condition for only home page */ ?>   
        <header>
            <div class="scrolling-header">
                <div class="header-menu" id="stickyheader">
                    <div class="webpage-container">            
                        <div class="col-sm-12 col-md-2">
                            <div  class="menu-logo">
                                <?php if (empty($advent_options['logo'])) { ?>
									<div class='site-title-text' >
										<a href="<?php echo esc_url(home_url('/')); ?>" class="site-title">
											<?php bloginfo( 'name' ); ?>
										</a>		
										<p class="site-description"><?php bloginfo( 'description' ); ?></p>
									  </div>
                                <?php } else { 
										$advent_options_image = getimagesize($advent_options['logo']) ;	
									?>
                                    <a href="<?php echo esc_url(home_url('/')); ?>">
                                    <img alt="<?php _e('logo', 'advent') ?>" src="<?php echo esc_url($advent_options['logo']); ?>" height= "<?php echo $advent_options_image[1]; ?>" width="<?php echo $advent_options_image[0]; ?>" class="img-responsive" >
                                    </a>
                                    <?php } ?>
                                    
                                    
                                <div class="navbar-header res-nav-header toggle-respon">
<?php if (has_nav_menu('primary')) { ?>
                                        <button type="button" class="navbar-toggle menu_toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                            <span class="sr-only"></span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                        </button>
<?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-10">
                            <?php
                            $advent_defaults = array(
                                'theme_location' => 'primary',
                                'container' => 'div',
                                'container_class' => 'collapse navbar-collapse main_menu no-padding',
                                'container_id' => 'example-navbar-collapse',
                                'menu_class' => '',
                                'echo' => true,
                                'before' => '',
                                'after' => '',
                                'link_before' => '',
                                'link_after' => '',
                                'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                                'depth' => 0,
                                'walker' => ''
                            );
                            if (has_nav_menu('primary')) {
                                wp_nav_menu($advent_defaults);
                            } 
                            ?>       
                        </div>               
                    </div>
                </div>
            </div>      
<?php if (get_header_image()) { ?>
                <div class="custom-header-img">
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                    </a>
                </div>
<?php } ?>   
        </header>
