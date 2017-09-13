<?php
/**
 * The template for displaying post archives
 *
 * @package Layers
 * @since Layers 1.0.0
 */
global $weCustomizerOptions;

// Customizer setting.
$weSetting = $weCustomizerOptions->getSettings();
$layout = $weCustomizerOptions->get_catalog_layout();
$sidebar = ( $weSetting['we_catalog_sidebar_layout'] != 'none' ) ? $weSetting['we_catalog_sidebar_layout'] : '';

get_header(); ?>
<script type="text/javascript">
//<!-- WE JAVASCRIPT CONFIG
	var we_catalog_term_id 				= '<?php echo get_queried_object_id(); ?>' ,
		we_catalog_paging_display_type 	= '<?php echo $weSetting['we_catalog_paging_display_type']; ?>' ,
		we_catalog_layout 				= '<?php echo $weSetting['we_catalog_layout']; ?>' ,
		we_catalog_display_number		= '<?php echo $weSetting['we_catalog_display_number']; ?>';

//-->
</script>
<?php get_template_part( 'partials/header' , 'page-title' ); ?>
<?php do_action('before_layers_builder_widgets'); ?>

<section class="container content-main archive-wpeasy-product row" ng-app="WEProducts">
	<?php if ( 'left' == $sidebar ) : ?>
		<?php we_maybe_get_sidebar( $sidebar, 'column span-3' ); ?>
	<?php endif; ?>
	
	<?php if( have_posts() ) : ?>
		<div id="we-product-layout" class="catalog-<?php echo $layout; ?> <?php echo !empty($sidebar) ? 'span-9 column': 'span-12'; ?>">
			<div id="list-products" class="row cart-btn-on-img accent-gradient wc-img-hover" >
			<?php while( have_posts() ) : the_post(); ?>
				<?php we_get_template_part( 'content-archive', $layout); ?>
			<?php endwhile; // while has_post(); ?>
			</div>
			<?php if ( $weSetting['we_catalog_paging_display_type'] == 'pagination' ) : ?>
    			<!-- Type: pagination -->
    			<?php the_posts_pagination(); ?>
    		<?php elseif ( $weSetting['we_catalog_paging_display_type'] == 'infinite-scroll' ) : ?>
    			<!-- Type: infinite-scroll -->
    			<div id="loading"></div>
    		<?php elseif ( $weSetting['we_catalog_paging_display_type'] == 'load-more' ) : ?>
    			<!-- Type: load-more -->
    			<div id="loading" style="display: block; overflow: hidden; text-align: center;"></div>
    			<button type="button" class="button button-loadmore" id="productLoadMore">Load More</button>
    		<?php endif; // if has_post() ?>
		</div>
	<?php endif; // if has_post() ?>
	<?php if ( 'right' == $sidebar ) : ?>
		<?php we_maybe_get_sidebar( $sidebar, 'column span-3' ); ?>
	<?php endif; ?>
</section>
<?php get_footer();