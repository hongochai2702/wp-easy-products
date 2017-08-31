(function() {
    'use strict';
	var wpajaxurl = '/wp-plugin/wp-admin/admin-ajax.php';
	
	var app = angular.module('WEProducts', [])
			.factory('PagerService', PagerService)
        	.controller('ctrlProductLayout', ctrlProductLayout);
	
	app.run(function ($rootScope, $http) {
	    $rootScope.convertToNonVietnamese = function (str) {
	        str = str.toLowerCase();
	        str = str.replace(/Ă |Ă¡|áº¡|áº£|Ă£|Ă¢|áº§|áº¥|áº­|áº©|áº«|Äƒ|áº±|áº¯|áº·|áº³|áºµ/g, "a");
	        str = str.replace(/Ă¨|Ă©|áº¹|áº»|áº½|Ăª|á»|áº¿|á»‡|á»ƒ|á»…/g, "e");
	        str = str.replace(/Ă¬|Ă­|á»‹|á»‰|Ä©/g, "i");
	        str = str.replace(/Ă²|Ă³|á»|á»|Ăµ|Ă´|á»“|á»‘|á»™|á»•|á»—|Æ¡|á»|á»›|á»£|á»Ÿ|á»¡/g, "o");
	        str = str.replace(/Ă¹|Ăº|á»¥|á»§|Å©|Æ°|á»«|á»©|á»±|á»­|á»¯/g, "u");
	        str = str.replace(/á»³|Ă½|á»µ|á»·|á»¹/g, "y");
	        str = str.replace(/Ä‘/g, "d");
	        return str;
	    }
	});
	
	
	function ctrlProductLayout(PagerService, $scope,$rootScope ,$http ,$window ) {
	    var vm = this;
	    
	    $scope.products = [];
		$http({
			method: 'GET',
			url: wpajaxurl,
			params: {
				action		: 'we_get_products',
				per_pages 	: 20
			}
		}).then(function(res) {
		    //vm.dummyItems = _.range(1, 151); // dummy array of items to be paged
			console.log(res.data);
		    $scope.products = res.data;
		    vm.dummyItems = $scope.products; // dummy array of items to be paged
		    
		    vm.pager = {};
		    vm.setPage = setPage;
		
		    initController();
		
		    function initController() {
		        // initialize to page 1
		        vm.setPage(1);
		    }
		
		    function setPage(page) {
		        if (page < 1 || page > vm.pager.totalPages) {
		            return;
		        }
		
		        // get pager object from service
		        vm.pager = PagerService.GetPager(vm.dummyItems.length, page);
		
		        // get current page of items
		        vm.items = vm.dummyItems.slice(vm.pager.startIndex, vm.pager.endIndex + 1);
		    }
		});
	}
	
	function PagerService() {
	    // service definition
	    var service = {};
	
	    service.GetPager = GetPager;
	
	    return service;
	
	    // service implementation
	    function GetPager(totalItems, currentPage, pageSize) {
	        // default to first page
	        currentPage = currentPage || 1;
	
	        // default page size is 10
	        //1
	        pageSize = pageSize || 9;
	
	        // calculate total pages
	        var totalPages = Math.ceil(totalItems / pageSize);
	
	        var startPage, endPage;
	        //2
	        if (totalPages <= 9) {
	            // less than 10 total pages so show all
	            startPage = 1;
	            endPage = totalPages;
	        } else {
	            // more than 10 total pages so calculate start and end pages
	            if (currentPage <= 6) {
	                startPage = 1;
	                //3
	                endPage = 9;
	            } else if (currentPage + 4 >= totalPages) {
	                startPage = totalPages - 9;
	                endPage = totalPages;
	            } else {
	                startPage = currentPage - 5;
	                endPage = currentPage + 4;
	            }
	        }
	
	        // calculate start and end item indexes
	        var startIndex = (currentPage - 1) * pageSize;
	        var endIndex = Math.min(startIndex + pageSize - 1, totalItems - 1);
	
	        // create an array of pages to ng-repeat in the pager control
	        var pages = _.range(startPage, endPage + 1);
	
	        // return object with all pager properties required by the view
	        return {
	            totalItems: totalItems,
	            currentPage: currentPage,
	            pageSize: pageSize,
	            totalPages: totalPages,
	            startPage: startPage,
	            endPage: endPage,
	            startIndex: startIndex,
	            endIndex: endIndex,
	            pages: pages
	        };
	    }
	}
})();