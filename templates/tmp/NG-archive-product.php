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
		<div class="we-product-layout" ng-controller="ctrlProductLayout as vm" >
			<div class="grid">
				<?php we_get_template_part( 'content-archive', 'grid' ); ?>
			</div>
			<!-- ISOTOPE - Masory layout -->
			<!-- pager -->
			<ul ng-if="vm.pager.pages.length" class="pagination">
				<li ng-class="{disabled:vm.pager.currentPage === 1}"><a
					ng-click="vm.setPage(1)">First</a>
				</li>
				<li ng-class="{disabled:vm.pager.currentPage === 1}"><a
					ng-click="vm.setPage(vm.pager.currentPage - 1)">Previous</a>
				</li>
				<li ng-repeat="page in vm.pager.pages"
					ng-class="{active:vm.pager.currentPage === page}"><a
					ng-click="vm.setPage(page)">{{page}}</a>
				</li>
				<li ng-class="{disabled:vm.pager.currentPage === vm.pager.totalPages}">
					<a ng-click="vm.setPage(vm.pager.currentPage + 1)">Next</a>
				</li>
				<li ng-class="{disabled:vm.pager.currentPage === vm.pager.totalPages}">
					<a ng-click="vm.setPage(vm.pager.totalPages)">Last</a>
				</li>
			</ul>
		</div>
		
<?php //the_posts_pagination(); ?>
	<?php endif; // if has_post() ?>

	<?php get_sidebar( 'right' ); ?>
</section>
<?php get_footer();