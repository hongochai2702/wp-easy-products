<?php
/**
 * This partial is used for displaying single post (or page) content
 *
 * @package Layers
 * @since Layers 1.0.0
 */

global $post, $layers_post_meta_to_display, $layers_page_title_shown;

do_action('layers_before_single_post');

/**
* Display the Featured Thumbnail
*/
//echo layers_post_featured_media( array( 'postid' => get_the_ID(), 'wrap_class' => 'thumbnail push-bottom', 'size' => 'large' ) );

if ( '' != get_the_content() ) { ?>
	<?php do_action('layers_before_single_content'); ?>

	<?php if( 'template-blank.php' != get_page_template_slug() ) { ?>
		<div class="story">
	<?php } ?>
	<?php
		$categories = get_the_terms( $post->ID, 'product_cate' );
		foreach ($categories as $val) {
			if ( $val->parent != 0 ) {
				$term = get_term( $val->parent, 'product_cate' );
			}
		}
	?>
	<?php
		$thumb_id = get_post_thumbnail_id();
		$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumbnail-size', true);
		$thumb_url = $thumb_url_array[0];
	?>
	<div class="row wpeasy-single-product">
		<div class="column span-4 wpeasy-product-image">
			<a href="<?php echo $thumb_url; ?>" class="fancybox">
			<?php echo layers_post_featured_media( array( 'postid' => get_the_ID(), 'wrap_class' => 'thumbnail push-bottom', 'size' => 'wpeasy-product-image-single-thumb' ) ); ?>
			</a>
		</div>
		<div class="column span-8 wpeasy-product-content">
			<div class="product-parent-category"><?php echo $term->name; ?></div>
			<div class="product-name"><?php the_title(); ?></div>
			<div class="product-price"><strong><?php _e( 'Giá', 'wpeasy' ); ?></strong> <?php echo number_format( get_field( 'cf_product_price' ), 0 ); ?> <sup style="vertical-align: top;">đ</sup></div>
			<p class="short-description"><?php echo get_the_excerpt(); ?></p>
		</div>
	</div>
	<div class="row wpeasy-product-content-tab">
		<div class="layout-tab-product">
            <div class="tab-tour">
                <div class="tab select" data-tab="tab1"><?php _e("Tính năng", "wpeasy"); ?></div>
                <div class="tab" data-tab="tab2"><?php _e("Thông số kỹ thuật", "wpeasy"); ?></div>
            </div>
             <div class="tab-content-group">
              	<div class="tab-content">
	                <div class="content c-tab1" style="display: block"><div class="clearfix"></div><?php the_content() ?></div>
	            	<!-- /.c-tab1 -->
                    <div class="content c-tab2"><div class="clearfix"></div><?php the_field( 'cf_parameter_technical' ); ?></div>
            	</div>
            	<!-- /.tab-content -->
             </div>
        </div>
        <!-- /.layout-tab-product -->
		
	</div>
	<script type="text/javascript">
        jQuery(document).ready(function ($) {
            $(".single-we-product .layout-tab-product .tab").click(function () {
                var tab = $(this).attr("data-tab");
                $(this).addClass("select");
                $(this).siblings().removeClass("select");
                $(".content").hide();
                $(".c-" + tab).show();
            });
        });
    </script>
	<?php /**
	* Display In-Post Pagination
	*/
	wp_link_pages( array(
		'link_before'   => '<span>',
		'link_after'    => '</span>',
		'before'        => '<p class="inner-post-pagination">' . __('<span>Pages:</span>', 'ocmx'),
		'after'     => '</p>'
	)); ?>

	<?php if( 'template-blank.php' != get_page_template_slug() ) { ?>
		</div>
		
	<?php } ?>
	
	<?php do_action('layers_after_single_content'); ?>
<?php } // '' != get_the_content()
do_action('layers_after_single_post');