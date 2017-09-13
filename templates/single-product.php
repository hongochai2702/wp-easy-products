<?php
/**
 * The template for displaying a single post
 *
 * @package Layers
 * @since Layers 1.0.0
 */

get_header(); 
get_template_part( 'partials/header' , 'page-title' );

global $weCustomizerOptions;

// Customizer setting.
$weSetting = $weCustomizerOptions->getSettings();
$product = get_product_posted_fields($post->ID);
$sidebar = ( $weSetting['we_product_layout_sidebar'] != 'none' ) ? $weSetting['we_product_layout_sidebar'] : '';

?>

<section id="post-<?php the_ID(); ?>" <?php post_class( 'content-main clearfix' ); ?>>
	<?php do_action('layers_before_post_loop'); ?>
	<div class="row">

		<?php get_sidebar( 'left' ); ?>

		<?php if( have_posts() ) : ?>

			<?php while( have_posts() ) : the_post(); ?>
				<article <?php echo !empty($sidebar) ? 'span-9 column': 'span-12'; ?>>
					<?php we_get_template_part( 'content-single', 'product' ); ?>
				</article>
			<?php endwhile; // while has_post(); ?>

		<?php endif; // if has_post() ?>

		<?php get_sidebar( 'right' ); ?>
	</div>
	<?php do_action('layers_after_post_loop'); ?>
</section>

<?php get_footer();