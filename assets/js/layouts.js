jQuery(document).ready(function($) {
	var wpajaxurl = '/wp-plugin/wp-admin/admin-ajax.php';
	var page = 1;
	var inAjaxProcessing = false;
	
	function loadProducts() {
	    var action = 'we_get_products_paging';
	    var per_pages = 10;
	
	    if (page > -1 && !inAjaxProcessing) {
	        inAjaxProcessing = true;
	        page++;
	        var ajaxPagingURI = wpajaxurl + '?action=we_get_products_paging&per_pages=' + per_pages + '&paged=' + page;
	        $('div#loading').html('<p><img src="/wp-plugin/wp-content/plugins/wp-easy-products/assets/images/Facebook.gif"></p>');
	        $.get(ajaxPagingURI, function (data) {
	            if (data !== '') {
	                $(".isotope").isotope('insert', $(data));
	                $("img.lazy").lazyload({
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
		if ($(window).scrollTop() >= $(document).height() - $(window).height() - 800) {
	    if ($('.we-product-layout').length > 0) {
	        loadProducts();
	    }
	    //if ($('#template-grid').attr('data-loadmore') === "1") {
	        //loadFreeProducts();
	    //}
	    }
	});

	$("img.iso-lazy-load").lazyload();
});