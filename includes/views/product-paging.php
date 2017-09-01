<?php foreach ( $posts as $post ) : ?>
<article id="product-<?php echo $post->ID; ?>" <?php post_class( 'product chili-item isotope-item product-item column span-4' ); ?>>
	<span class="onsale">Sale!</span>
	<figure class="woocom-project">
		<div class="woo-buttons-on-img">
				<?php /**
				* Display the Featured Thumbnail
				*/
				 ?>
				<a href="<?php echo get_the_permalink($post->ID); ?>"><img src="<?php echo $this->get_the_post_thumbnail_url($post->ID); ?>"  class="iso-lazy-load front-image iso-lazy-load preload-me iso-layzr-loaded" alt="" style="will-change: auto;">
				<img src="http://wordpress.io/wp-plugin/wp-content/uploads/2016/01/merlion-singapore.jpg" src="<?php echo WPEASY_ASSETS_URL ?>/images/Facebook.gif" class="show-on-hover back-image iso-lazy-load preload-me iso-layzr-loaded" alt="" style="will-change: auto;">
				</a>
				
			<div class="woo-buttons">
				<a rel="nofollow" href="<?php the_permalink(); ?>?add-to-cart=50017" class="product_type_simple add_to_cart_button"> <span class="filter-popup">Add to cart</span></a>
			</div>
		</div>
		<figcaption class="woocom-list-content">
			<?php do_action('weasy_before_list_title'); ?>
			<h4 class="entry-title"><a href="<?php echo get_the_permalink($post->ID); ?>" title="<?php echo $post->post_title; ?>" rel="bookmark"><?php echo $post->post_title; ?></a></h4>
		 	<span class="price"><span class="woocommerce-Price-amount amount">$ 69.69</span></span>
			<?php do_action('weasy_after_list_title'); ?>
		</figcaption>
	</figure>
</article>
<?php endforeach; ?>