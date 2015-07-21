<?php
/**
 * The template for displaying Comments.
 *
 */
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required())
    return;
?>
<div class="comments-article">
    <?php if (have_comments()) : ?>
        <div class="article-title">
            <h2>
             <?php printf( _n( 'One Comment', '%1$s Comments', get_comments_number(), 'advent' ),
		   number_format_i18n( get_comments_number() ), get_the_title() );
	     ?>   
            </h2>
        </div>

        <ol class="comment-list">
        <?php wp_list_comments(array('avatar_size' => 80, 'style' => 'ol', 'short_ping' => true,)); ?>
        </ol>
        <?php paginate_comments_links(); ?> 
    <?php endif; ?>
<?php comment_form(); ?>
</div>

