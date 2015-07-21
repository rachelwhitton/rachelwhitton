<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Studio
 */

?>

	</div><!-- #content -->
    
    <?php get_sidebar( 'footer' ); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<span class="theme-name">
				<?php echo esc_attr( 'Theme: Studio' ); ?>
			</span>
			<span class="theme-by">
				<?php _ex( 'by', 'attribution', 'studio' ); ?>
			</span>
			<span class="theme-author">
				<a href="<?php echo esc_url( 'http://catchthemes.com/' ); ?>" target="_blank">
					<?php echo esc_attr( 'Catch Themes' ); ?>
				</a>
			</span>
            
	         <?php if ( has_nav_menu( 'social' ) ) : ?>
	            <div class="social-menu">
			        <?php wp_nav_menu( array(
					    'theme_location' => 'social',
					    'depth'          => '1',
					    'link_before'    => '<span class="screen-reader-text">',
					    'link_after'     => '</span>' )
					    );
	                ?>
	            </div><!-- .social-menu -->       
	        <?php endif; ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
