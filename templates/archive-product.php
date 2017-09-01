<?php
/**
 * The template for displaying post archives
 *
 * @package Layers
 * @since Layers 1.0.0
 */
global $weCustomizerOptions;
$weSetting = $weCustomizerOptions->getSettings();
get_header(); ?>
<script type="text/javascript">
<!-- WE JAVASCRIPT CONFIG
	var we_catalog_display_type = '<?php echo $weSetting['we_catalog_display_type']; ?>';
//-->
</script>
<?php get_template_part( 'partials/header' , 'page-title' ); ?>
<?php do_action('before_layers_builder_widgets'); ?>

<section class="container content-main archive-wpeasy-product cart-btn-on-img accent-gradient wc-img-hover clearfix" ng-app="WEProducts">
	<?php get_sidebar( 'left' ); ?>
	
	<?php if( have_posts() ) : ?>
		<div class="we-product-layout">
			<div class="row isotope">
			<?php while( have_posts() ) : the_post(); ?>
				<?php we_get_template_part( 'content-archive', 'grid' ); ?>
			<?php endwhile; // while has_post(); ?>
			</div>
		</div>
		
		<?php if ( $weSetting['we_catalog_display_type'] == 'pagination' ) : ?>
			<!-- Type: pagination -->
			<?php the_posts_pagination(); ?>
		<?php elseif ( $weSetting['we_catalog_display_type'] == 'infinite-scroll' ) : ?>
			<!-- Type: infinite-scroll -->
			<div id="loading"></div>
		<?php elseif ( $weSetting['we_catalog_display_type'] == 'load-more' ) : ?>
			<!-- Type: load-more -->
			<button type="button" class="button button-loadmore" id="productLoadMore">Load More</button>
		<?php endif; // if has_post() ?>
	<?php endif; // if has_post() ?>

	<?php get_sidebar( 'right' ); ?>
</section>
<?php get_footer();