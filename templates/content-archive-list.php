<?php
/**
 * This template is used for displaying posts in post lists
 *
 * @package Layers
 * @since Layers 1.0.0
 */

global $post, $layers_post_meta_to_display, $weCustomizerOptions;

// Customizer setting.
$weSetting = $weCustomizerOptions->getSettings();
$column = $weCustomizerOptions->get_column_items();
?>
<?php do_action('weasy_before_list_product'); ?>
<article id="product-<?php the_ID(); ?>" <?php post_class( 'product chili-item isotope-item product-item ' . $column ); ?>>
	<span class="onsale">Sale!</span>
	<figure class="woocom-project">
		<div class="woo-buttons-on-img">
				<?php /**
				* Display the Featured Thumbnail
				*/
				global $weProductData; ?>
				<a href="<?php the_permalink(); ?>"><img data-src="<?php echo $weProductData->get_the_post_thumbnail_url(get_the_ID(), 'weasy-image-size-thumb'); ?>" src="<?php echo WPEASY_ASSETS_URL ?>/images/Facebook.gif" class="iso-lazy-load front-image iso-lazy-load preload-me iso-layzr-loaded" alt="" style="will-change: auto;">
				</a>
				
			<div class="woo-buttons">
				<a rel="nofollow" href="<?php the_permalink(); ?>?add-to-cart=50017" class="product_type_simple add_to_cart_button"> <span class="filter-popup">Add to cart</span></a>
			</div>
		</div>
		<figcaption class="woocom-list-content">
			<?php do_action('weasy_before_list_title'); ?>
			<h4 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h4>
		 	<span class="price"><span class="woocommerce-Price-amount amount">$ 69.69</span></span>
		 	<div class="woocommerce-product-details__short-description">
                <p>Phasellus leo odio, maximus a dui pellentesque, ornare tincidunt ante. Nullam venenatis tortor vitae scelerisque rhoncus. Sed ut suscipit ex, eget porta urna. Aenean tempor at ante non dapibus.</p>
            </div>
			<?php do_action('weasy_after_list_title'); ?>
		</figcaption>
	</figure>
</article>
<?php do_action('weasy_after_list_product'); ?>
