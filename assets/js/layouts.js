jQuery(document).ready(function($) {
	var wpajaxurl = '/wp-plugins/wp-admin/admin-ajax.php';
	var page = 1;
	var inAjaxProcessing = false;
	
	function loadProducts() {
	    var action = 'we_get_products_paging';
	    var per_pages = 10;
	
	    if (page > -1 && !inAjaxProcessing) {
	        inAjaxProcessing = true;
	        page++;
	        var ajaxPagingURI = wpajaxurl + '?action=we_get_products_paging&per_pages=' + per_pages + '&paged=' + page;
	        $('div#loading').html('<p><img src="/wp-plugins/wp-content/plugins/wp-easy-products/assets/images/Facebook.gif"></p>');
	        $.get(ajaxPagingURI, function (data) {
	            if (data !== '') {
	                $(".isotope").isotope('insert', $(data));
	                $("img.iso-lazy-load").lazyload({
	                    effect: "fadeIn"
	                });
	            }
	            else {
	                page = -1;
	                $(".we-product-layout").append($("<div class='clearfix'></div>"));
	            }
	
	            inAjaxProcessing = false;
	            $('div#loading').empty();
	        });
	    }
	}
	
	$(window).scroll(function () {
		// Check if category display type 'infinite-scroll'. global variable in file archive-product.
		if ( we_catalog_paging_display_type == 'infinite-scroll' ) {
			if ($(window).scrollTop() >= $(document).height() - $(window).height() - 800) {
			    if ($('.we-product-layout').length > 0) {
			        loadProducts();
			    }
		    }
		} else if ( we_catalog_paging_display_type == 'load-more' ) {
			$("#productLoadMore").on("click", function(e) {
				e.preventDefault();
				if ($('.we-product-layout').length > 0) {
			        loadProducts();
			    } else {
			    	$("#productLoadMore").remove();
			    }
			});
		} else {
			console.log("Đã hết page rồi");
		}
	});

	$("img.iso-lazy-load").lazyload();
});