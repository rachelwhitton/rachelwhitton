<?php
/**
 * 404 page template file
 * */
get_header();
?>
<section>
    <!--Breadcrumb  Part Start-->
    <div class="breadcumb-bg">
        <div class="webpage-container container">
            <div class="site-breadcumb">
                <h1><?php _e('404 - Article Not Found', 'advent'); ?></h1>
                <ol class="breadcrumb breadcrumb-menubar">
                    <li>
                        <?php if (function_exists('advent_custom_breadcrumbs')) advent_custom_breadcrumbs(); ?>
                    </li>
                </ol>
            </div>
        </div>
    </div>    
    <!--Breadcrumb Part End-->
    <!-- 404 Content Start -->
    <div class="webpage-container container">
        <div class="blog-main">
            <div class="jumbotron">
                <h1><?php _e('Epic 404 - Article Not Found', 'advent') ?></h1>
                <p><?php _e("This is embarassing. We can't find what you were looking for.", "advent") ?></p>
                <section class="post_content">
                    <p><?php _e('Whatever you were looking for was not found, but maybe try looking again or search using the form below.', 'advent') ?></p>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php get_search_form(); ?>
                        </div>
                    </div>
                </section>
            </div>            
        </div>
    </div>
    <!-- 404 Content Start End -->
</section>
<?php get_footer(); ?>
