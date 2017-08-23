<?php
// shortcode for grid product

function cf_recent_product_shortcode( $atts ){
	ob_start();
	extract( shortcode_atts(array( 'posts_per_page' => 4, 'category_name' => 'dien-thoai-ip'), $atts ) );	
	$args = array(
			'post_type'				=> 'wpeasy-product',
			'posts_per_page'		=> $posts_per_page,			
			'tax_query' => array(
		        array(
		            'taxonomy' => 'product_category',
		            'field' => 'slug', 
		            'terms' => $category_name
		        )
		    ),
			'post_status'			=> 'publish',
			'order'					=> 'DESC',
			'orderby'				=> 'date'
		);

	$wpQuery = new WP_Query( $args );
	?>
	<div class="archive-wpeasy-product">
	<?php if( $wpQuery->have_posts() ) : ?>
			<?php while( $wpQuery->have_posts() ) : $wpQuery->the_post(); ?>
				<?php include( WPEASY_SINGLE_LOCAL . '/template/archive-grid.php' ); ?>
			<?php endwhile; // while has_post(); ?>
		</div>
		<?php wp_reset_postdata(); ?>
	<?php endif; // if has_post() ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode('recent_cf_products', 'cf_recent_product_shortcode');