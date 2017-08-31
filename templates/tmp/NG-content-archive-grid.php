<?php
/**
 * This template is used for displaying posts in post lists
 *
 * @package Layers
 * @since Layers 1.0.0
 */

global $post, $layers_post_meta_to_display; ?>
<article ng-repeat="item in vm.items" id="product-<?php the_ID(); ?>" <?php post_class( 'product-news-item column span-4 grid-item' ); ?>>
	<div class="thumbnail push-bottom"><img ng-src="{{item.Image}}" alt="{{item.Name}}" title="{{item.Name}}" /></div>
	<?php do_action('layers_before_list_post_title'); ?>
	<div class="post-content">
		<header class="article-title">
			<?php do_action('layers_before_list_title'); ?>
			<h2 class="heading"><a ng-href="{{item.Link}}">{{item.Name}}</a></h2>
			<?php do_action('layers_after_list_title'); ?>
		</header>
	<?php do_action('layers_after_list_post_title'); ?>
	</div>
</article>