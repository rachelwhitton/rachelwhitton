<?php 
/**
 * Main Page template file
**/
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
				<?php if(function_exists('advent_custom_breadcrumbs')) advent_custom_breadcrumbs(); ?>
				</li>
				</ol>
			</div>
		</div>
    </div>    
    <!--Breadcrumb Part End-->
	<!-- Blog start -->
	<div class="webpage-container">
		<div class="blog-main">
		<div class="col-md-9 col-sm-8">
			<div class="blog-details single">
				 <?php while ( have_posts() ) : the_post(); ?>
				
                         <?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'large', array( 'alt' => get_the_title(), 'class' => 'img-responsive') ); ?>
						<?php endif; ?> 
				<div class="blog-info">
					<p><?php the_content();
					wp_link_pages( array(
							'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'advent' ) . '</span>',
							'after'       => '</div>',
							'link_before' => '<span>',
							'link_after'  => '</span>',
						) );
					 ?></p>
				</div>
				 <?php endwhile; ?> 
			</div>
			<?php comments_template( '', true ); ?>
			</div>
		<?php get_sidebar(); ?>
		</div>
	</div>
	<!-- Blog End -->
</section>
<?php get_footer(); ?>

