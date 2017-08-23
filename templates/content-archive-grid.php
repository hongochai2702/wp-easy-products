<?php
/**
 * This template is used for displaying posts in post lists
 *
 * @package Layers
 * @since Layers 1.0.0
 */

global $post, $layers_post_meta_to_display; ?>

<article id="product-<?php the_ID(); ?>" <?php post_class( 'product-news-item column span-3' ); ?>>
	
	<?php /**
	* Display the Featured Thumbnail
	*/
	echo layers_post_featured_media( array( 'postid' => get_the_ID(), 'wrap_class' => 'thumbnail push-bottom', 'size' => 'wpeasy-product-image-catalog-thumb' ) ); ?>
	<?php do_action('layers_before_list_post_title'); ?>
	<div class="post-content">
		<header class="article-title">
		<?php do_action('layers_before_list_title'); ?>
		<h2 class="heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php do_action('layers_after_list_title'); ?>
	</header>
	<?php do_action('layers_after_list_post_title'); ?>
	</div>
</article>