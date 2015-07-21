<?php $advent_options = get_option('advent_theme_options'); ?>
<footer>
    <div class="webpage-container text-center center-block">
        <div class="social-media col-md-12">
            <?php if (!empty($advent_options['email']) OR !empty($advent_options['facebook']) OR !empty($advent_options['twitter']) OR !empty($advent_options['pinterest'])) { ?>    
                <ul class="list-unstyled list-inline">
                    <?php if (!empty($advent_options['email'])) { ?><li><a href="mailto:<?php echo esc_attr($advent_options['email']); ?>" class="social-icon btn"><i class="fa fa-envelope"></i><?php _e('EMAIL', 'advent') ?></a></li><?php } ?>		                
                    <?php if (!empty($advent_options['facebook'])) { ?><li><a href="<?php echo esc_url($advent_options['facebook']); ?>" class="social-icon btn"><i class="fa fa-facebook"></i><?php _e('FACEBOOK', 'advent') ?></a></li><?php } ?>
                    <?php if (!empty($advent_options['twitter'])) { ?><li><a href="<?php echo esc_url($advent_options['twitter']); ?>" class="social-icon btn"><i class="fa fa-twitter"></i><?php _e('TWITTER', 'advent') ?></a></li><?php } ?>
                    <?php if (!empty($advent_options['pinterest'])) { ?><li><a href="<?php echo esc_url($advent_options['pinterest']); ?>" class="social-icon btn"><i class="fa fa-pinterest"></i><?php _e('PINTEREST', 'advent') ?></a></li><?php } ?>
                </ul>
            <?php } ?>   
            <p>
                <?php
                if (!empty($advent_options['footertext'])) {
                    echo esc_attr($advent_options['footertext']) . '. ';
                }
                printf(__('Powered by %1$s and %2$s.', 'advent'), '<a href="http://wordpress.org" target="_blank">WordPress</a>', '<a href="http://fruitthemes.com/wordpress-themes/advent" target="_blank">Advent</a>');
                ?></p>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
