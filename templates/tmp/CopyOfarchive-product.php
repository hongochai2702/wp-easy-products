<?php
/**
 * The template for displaying post archives
 *
 * @package Layers
 * @since Layers 1.0.0
 */

get_header(); ?>
<?php get_template_part( 'partials/header' , 'page-title' ); ?>
<?php do_action('before_layers_builder_widgets'); ?>

<section class="container content-main archive-wpeasy-product clearfix" ng-app="WEProducts">
	<?php get_sidebar( 'left' ); ?>
	
	<?php if( have_posts() ) : ?>
		<div class="we-product-layout">
			<?php while( have_posts() ) : the_post(); ?>
				<?php we_get_template_part( 'content-archive', 'grid' ); ?>
			<?php endwhile; // while has_post(); ?>
		</div>
	<?php the_posts_pagination(); ?>
	<?php endif; // if has_post() ?>

	<?php get_sidebar( 'right' ); ?>
</section>
<?php get_footer();