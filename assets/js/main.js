jQuery(document).ready(function($) {
	var $grid = $('.grid').isotope({
		itemSelector: '.grid-item',
		  layoutMode: 'fitRows',
		  masonry: {
		    columnWidth: 60
		  },
		  cellsByRow: {
		    columnWidth: 220,
		    rowHeight: 220
		  },
		  masonryHorizontal: {
		    rowHeight: 110
		  },
		  cellsByColumn: {
		    columnWidth: 220,
		    rowHeight: 220
		  }
		});
	$grid.imagesLoaded().progress( function( instance, image ) {
	  var result = image.isLoaded ? 'loaded' : 'broken';
	  console.log( 'image is ' + result + ' for ' + image.img.src );
	});
	/*$('.grid').imagesLoaded()
	  .always( function( instance ) {
	    console.log('all images loaded');
	  })
	  .done( function( instance ) {
	    console.log('all images successfully loaded');
	  })
	  .fail( function() {
	    console.log('all images loaded, at least one is broken');
	  })
	  .progress( function( instance, image ) {
	    var result = image.isLoaded ? 'loaded' : 'broken';
	    console.log( 'image is ' + result + ' for ' + image.img.src );
	  });*/
});