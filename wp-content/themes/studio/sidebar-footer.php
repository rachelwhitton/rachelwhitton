<?php
/**
 * The sidebar containing the footer widget area.
 *
 * @package Studio
 */

if ( ! is_active_sidebar( 'footer-widget-left' )  
  && ! is_active_sidebar( 'footer-widget-right' ) ) {
	return;
}
?>

<div id="secondary" class="widget-area" role="complementary">
    <div class="footer-content">
        <div class="footer-col">
	        <?php dynamic_sidebar( 'footer-widget-left' ); ?>
        </div><!-- .footer-col -->
        <div class="footer-col">
	        <?php dynamic_sidebar( 'footer-widget-right' ); ?>
        </div><!-- .footer-col -->
    </div><!-- .footer-content -->
</div><!-- #secondary -->
