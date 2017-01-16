
<html ng-app="myApp" lang="en">
<head>
  <title>HW8</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link data-require="bootstrap-css@3.1.1" data-semver="3.1.1" rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" />
  <script data-require="angular.js@1.3.0" data-semver="1.3.0" src="https://code.angularjs.org/1.3.0/angular.js"></script>
  <script data-require="jquery@*" data-semver="2.0.3" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
  <script data-require="bootstrap@3.1.1" data-semver="3.1.1" src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
  <!--<link rel="./stylesheet" href="style.css" />-->
  <!--<script src="./script.js"></script>-->
  <!--<script src="./dirPagination.js"></script>-->
  
  <script>
	// Code goes here

/*function changeColor(ele){
	if(ele.childNodes[0].getAttribute("class") == "glyphicon glyphicon-star-empty"){
		ele.childNodes[0].setAttribute("class", "glyphicon glyphicon-star");
	}
	else{
		ele.childNodes[0].setAttribute("class", "glyphicon glyphicon-star-empty");
	}
	
}*/


var myApp = angular.module('myApp', ['angularUtils.directives.dirPagination']);





myApp.filter('legislatorFilter', function(){
	return function(legislators, legStars){
		var res = [];
		angular.forEach(legislators, function(legislator, scope){
			if(legStars[legislator.bioguide_id] == true) res.push(legislator);
		});
		return res;
	};
});

myApp.filter('billFilter', function(){
	return function(bills, billStars){
		var res = [];
		angular.forEach(bills, function(bill, scope){
			if(billStars[bill.bill_id] == true) res.push(bill);
		});
		return res;
	};
});

myApp.filter('committeeFilter', function(){
	return function(committees, comStars){
		var res = [];
		angular.forEach(committees, function(committee, scope){
			if(comStars[committee.committee_id] == true) res.push(committee);
		});
		return res;
	};
});





myApp.filter('actBillSel', function(){//select active bills
    return function(items){
        var res = [];
        angular.forEach(items, function(item){
            if(item.history.active == true) res.push(item);
        });
        return res;
    };
});
myApp.filter('newBillSel', function(){//select new bills
    return function(items){
        var res = [];
        angular.forEach(items, function(item){
            if(item.history.active == false) res.push(item);
        });
        return res;
    };
});


function MyController($scope, $http) {
	$scope.sideBarDisplay = true;
	$scope.toggleSideBar=function(){
		$scope.sideBarDisplay = $scope.sideBarDisplay == true ? false : true;
	}
	//Get all required data
	$http.get("query.php?legislators").then(function(response) {
        $scope.legislatorsJson = response.data.results;
    }, function(response){
		$scope.legislatorsJson = "Legislators data access fail";
		alert("Legislators data access fail");
	});
	$http.get("query.php?bills&active=true").then(function(response) {
        $scope.billsJsonActive = response.data.results;
    }, function(response){
		$scope.billsJsonActive = "Bills data access fail";
		alert("Bills data access fail");
	});
	$http.get("query.php?bills").then(function(response) {
        $scope.billsJsonNew = response.data.results;
    }, function(response){
		$scope.billsJsonNew = "Bills data access fail";
		alert("Bills data access fail");
	});
	$http.get("query.php?committees").then(function(response) {
        $scope.committeesJson = response.data.results;
    }, function(response){
		$scope.committeesJson = "Committees data access fail";
		alert("Committees data access fail");
	});
	
	
	//Favorites Variables
	
	
	/*$scope.legStars = {};
	for(var i = 0; i < $scope.legislatorsJson.length; ++i){
		$scope.legStars[$scope.legislatorsJson[i].bioguide_id] = false;
	}
	$scope.legFavChange = function(legislator){
		if($scope.legStars[legislator.bioguide_id] == false){
			$scope.legStars[legislator.bioguide_id] = true;
		}
		else{
			$scope.legStars[legislator.bioguide_id] = false;
		}
	}
	
	
	$scope.billStars = {};*/
	
	
	$scope.legStars = new Object();
	angular.forEach($scope.legislatorsJson, function(legislator){
            $scope.legStars[legislator.bioguide_id] = false;
    });
	
	$scope.legFavChange = function(legislator){
		if($scope.legStars[legislator.bioguide_id] == false){
			$scope.legStars[legislator.bioguide_id] = true;
		}
		else{
			$scope.legStars[legislator.bioguide_id] = false;
		}
	}
	
	$scope.billStars = new Object();
	angular.forEach($scope.billsJson, function(bill){
            $scope.billStars[bill.bill_id] = false;
    });
	
	$scope.billFavChange = function(bill){
		if($scope.billStars[bill.bill_id] == false){
			$scope.billStars[bill.bill_id] = true;
		}
		else{
			$scope.billStars[bill.bill_id] = false;
		}
	}
	
	$scope.comStars = new Object();
	angular.forEach($scope.committeesJson, function(committee){
            $scope.comStars[committee.committee_id] = false;
    });
	
	$scope.comFavChange = function(committee){
		if($scope.comStars[committee.committee_id] == false){
			$scope.comStars[committee.committee_id] = true;
		}
		else{
			$scope.comStars[committee.committee_id] = false;
		}
	}
	
	
	
	//Get some requird information in detail
	//get five bills for legislator detail
	
	$scope.getFiveBillsNCommittees = function(sponsor_id, member_ids){
		
		$http.get("query.php?bills&sponsor_id=" + sponsor_id).then(function(response){
			$scope.fiveBillsJson = response.data.results;
		}, function(response){
			$scope.fiveBillsJson = "Five bills access fail";
			alert("Five bills access fail");
		});
		$http.get("query.php?committees&member_ids=" + member_ids).then(function(response){
			$scope.fiveCommitteesJson = response.data.results;
		}, function(response){
			$scope.fiveCommitteesJson = "Five committees access fail";
			alert("Five committees access fail");
		});
	}
	$scope.getDetailInfoLeg = function(legislator){
		$scope.getFiveBillsNCommittees(legislator.bioguide_id, legislator.bioguide_id);
		$scope.curDetailedLeg = legislator;
		
		//Used in Term bar
		$scope.curTime = new Date();
		//alert($scope.curTime);
		$scope.startTime = new Date(legislator.term_start);
		//alert($scope.startTime);
		$scope.endTime = new Date(legislator.term_end);
		//alert($scope.endTime);

		$scope.termPercent = ($scope.curTime.getTime() - $scope.startTime.getTime()) * 100 / ($scope.endTime.getTime() - $scope.startTime.getTime());
		if($scope.termPercent > 100) $scope.termPercent = 100;
		else if($scope.termPercent < 0) $scope.termPercent = 0;
		else $scope.termPercent = Math.round($scope.termPercent);
	}
	
	$scope.getDetailInfoBill = function(bill){
		$scope.curDetailedBill = bill;
	}
	//get five committees for legislator detail
	/*$scope.getFiveCommittees = function(member_ids){
		$http.get("query.php?committees&member_ids=" + member_ids).then(function(response){
			$scope.fiveCommitteesJson = response.data.results;
		}, function(response){
			$scope.fiveCommitteesJson = "Five committees access fail";
			alert("Five committees access fail");
		});
	}*/
	
	
	
	
	
	//*******states dropdown***********************
	$scope.allStates = [
	'Alabama', 
	'Alaska', 
	'Arizona', 
	'Arkansas', 
	'California', 
	'Colorado', 
	'Connecticut', 
	'Delaware', 
	'Florida', 
	'Georgia', 
	'Hawaii', 
	'Idaho', 
	'Illinois', 
	'Indiana', 
	'Iowa', 
	'Kansas', 
	'Kentucky', 
	'Louisiana', 
	'Maine', 
	'Maryland', 
	'Massachusetts', 
	'Michigan', 
	'Minnesota', 
	'Mississippi', 
	'Missouri', 
	'Montana', 
	'Nebraska', 
	'Nevada', 
	'New Hampshire', 
	'New Jersey', 
	'New Mexico', 
	'New York', 
	'North Carolina', 
	'North Dakota', 
	'Ohio', 
	'Oklahoma', 
	'Oregon', 
	'Pennsylvania', 
	'Rhode Island', 
	'South Carolina', 
	'South Dakota', 
	'Tennessee', 
	'Texas', 
	'Utah', 
	'Vermont', 
	'Virginia', 
	'Washington', 
	'West Virginia', 
	'Wisconsin', 
	'Wyoming' 
	];
	$scope.selectedState = "";
	$scope.selectedStateOnButton = "All States";
	$scope.dropboxitemselected = function (state) {
		$scope.selectedState = state;
		$scope.selectedStateOnButton = state;
    }
	$scope.selectAllState = function(){

		$scope.selectedState = "";
		$scope.selectedStateOnButton = "All States";
	}
	
	
	/************************pagination*************************/
	//legislators
	//States
	
	
	//House
	
	//Senate
	
	//Bills
	//Active Bills
	
	//New Bills
	
	//Committees
	//House
	
	//Senate
	
	//Joint
	
	//Favorites
	//Legislators
	
	//Bills
	
	//Committees
	
	
	$scope.dateChange = function(digitDate){
		$scope.textDate = "";
		switch(digitDate.substring(5, 7)){
			case "01": $scope.month = "Jan";
			break;
			case "02": $scope.month = "Feb";
			break;
			case "03": $scope.month = "Mar";
			break;
			case "04": $scope.month = "Apr";
			break;
			case "05": $scope.month = "May";
			break;
			case "06": $scope.month = "June";
			break;
			case "07": $scope.month = "July";
			break;
			case "08": $scope.month = "Aug";
			break;
			case "09": $scope.month = "Sep";
			break;
			case "10": $scope.month = "Oct";
			break;
			case "11": $scope.month = "Nov";
			break;
			case "12": $scope.month = "Dec";
			break;
		}
		switch(digitDate.substring(8, 10)){
			case "01": $scope.date = "1";
			break;
			case "02": $scope.date = "2";
			break;
			case "03": $scope.date = "3";
			break;
			case "04": $scope.date = "4";
			break;
			case "05": $scope.date = "5";
			break;
			case "06": $scope.date = "6";
			break;
			case "07": $scope.date = "7";
			break;
			case "08": $scope.date = "8";
			break;
			case "09": $scope.date = "9";
			break;
			case "10": $scope.date = "10";
			break;
			case "11": $scope.date = "11";
			break;
			case "12": $scope.date = "12";
			break;
		}
		$scope.textDate = $scope.month + " " + $scope.date + ", " + digitDate.substring(0, 4);
		return $scope.textDate;
	}
	
	
  $scope.pageChangeHandler = function(num) {
      console.log('meals page changed to ' + num);
  };
}

function OtherController($scope) {
  $scope.pageChangeHandler = function(num) {
    console.log('going to page ' + num);
  };
}

myApp.controller('MyController', MyController);
myApp.controller('OtherController', OtherController);
  </script>
  <script>
	/**
 * dirPagination - AngularJS module for paginating (almost) anything.
 *
 *
 * Credits
 * =======
 *
 * Daniel Tabuenca: https://groups.google.com/d/msg/angular/an9QpzqIYiM/r8v-3W1X5vcJ
 * for the idea on how to dynamically invoke the ng-repeat directive.
 *
 * I borrowed a couple of lines and a few attribute names from the AngularUI Bootstrap project:
 * https://github.com/angular-ui/bootstrap/blob/master/src/pagination/pagination.js
 *
 * Copyright 2014 Michael Bromley <michael@michaelbromley.co.uk>
 */

(function() {

    /**
     * Config
     */
    var moduleName = 'angularUtils.directives.dirPagination';
    var DEFAULT_ID = '__default';

    /**
     * Module
     */
    angular.module(moduleName, [])
        .directive('dirPaginate', ['$compile', '$parse', 'paginationService', dirPaginateDirective])
        .directive('dirPaginateNoCompile', noCompileDirective)
        .directive('dirPaginationControls', ['paginationService', 'paginationTemplate', dirPaginationControlsDirective])
        .filter('itemsPerPage', ['paginationService', itemsPerPageFilter])
        .service('paginationService', paginationService)
        .provider('paginationTemplate', paginationTemplateProvider)
        .run(['$templateCache',dirPaginationControlsTemplateInstaller]);

    function dirPaginateDirective($compile, $parse, paginationService) {

        return  {
            terminal: true,
            multiElement: true,
            priority: 100,
            compile: dirPaginationCompileFn
        };

        function dirPaginationCompileFn(tElement, tAttrs){

            var expression = tAttrs.dirPaginate;
            // regex taken directly from https://github.com/angular/angular.js/blob/v1.4.x/src/ng/directive/ngRepeat.js#L339
            var match = expression.match(/^\s*([\s\S]+?)\s+in\s+([\s\S]+?)(?:\s+as\s+([\s\S]+?))?(?:\s+track\s+by\s+([\s\S]+?))?\s*$/);

            var filterPattern = /\|\s*itemsPerPage\s*:\s*(.*\(\s*\w*\)|([^\)]*?(?=\s+as\s+))|[^\)]*)/;
            if (match[2].match(filterPattern) === null) {
                throw 'pagination directive: the \'itemsPerPage\' filter must be set.';
            }
            var itemsPerPageFilterRemoved = match[2].replace(filterPattern, '');
            var collectionGetter = $parse(itemsPerPageFilterRemoved);

            addNoCompileAttributes(tElement);

            // If any value is specified for paginationId, we register the un-evaluated expression at this stage for the benefit of any
            // dir-pagination-controls directives that may be looking for this ID.
            var rawId = tAttrs.paginationId || DEFAULT_ID;
            paginationService.registerInstance(rawId);

            return function dirPaginationLinkFn(scope, element, attrs){

                // Now that we have access to the `scope` we can interpolate any expression given in the paginationId attribute and
                // potentially register a new ID if it evaluates to a different value than the rawId.
                var paginationId = $parse(attrs.paginationId)(scope) || attrs.paginationId || DEFAULT_ID;
                // In case rawId != paginationId we deregister using rawId for the sake of general cleanliness
                // before registering using paginationId
                paginationService.deregisterInstance(rawId);
                paginationService.registerInstance(paginationId);

                var repeatExpression = getRepeatExpression(expression, paginationId);
                addNgRepeatToElement(element, attrs, repeatExpression);

                removeTemporaryAttributes(element);
                var compiled =  $compile(element);

                var currentPageGetter = makeCurrentPageGetterFn(scope, attrs, paginationId);
                paginationService.setCurrentPageParser(paginationId, currentPageGetter, scope);

                if (typeof attrs.totalItems !== 'undefined') {
                    paginationService.setAsyncModeTrue(paginationId);
                    scope.$watch(function() {
                        return $parse(attrs.totalItems)(scope);
                    }, function (result) {
                        if (0 <= result) {
                            paginationService.setCollectionLength(paginationId, result);
                        }
                    });
                } else {
                    paginationService.setAsyncModeFalse(paginationId);
                    scope.$watchCollection(function() {
                        return collectionGetter(scope);
                    }, function(collection) {
                        if (collection) {
                            var collectionLength = (collection instanceof Array) ? collection.length : Object.keys(collection).length;
                            paginationService.setCollectionLength(paginationId, collectionLength);
                        }
                    });
                }

                // Delegate to the link function returned by the new compilation of the ng-repeat
                compiled(scope);
                    
                // When the scope is destroyed, we make sure to remove the reference to it in paginationService
                // so that it can be properly garbage collected
                scope.$on('$destroy', function destroyDirPagination() {
                    paginationService.deregisterInstance(paginationId);
                });
            };
        }

        /**
         * If a pagination id has been specified, we need to check that it is present as the second argument passed to
         * the itemsPerPage filter. If it is not there, we add it and return the modified expression.
         *
         * @param expression
         * @param paginationId
         * @returns {*}
         */
        function getRepeatExpression(expression, paginationId) {
            var repeatExpression,
                idDefinedInFilter = !!expression.match(/(\|\s*itemsPerPage\s*:[^|]*:[^|]*)/);

            if (paginationId !== DEFAULT_ID && !idDefinedInFilter) {
                repeatExpression = expression.replace(/(\|\s*itemsPerPage\s*:\s*[^|\s]*)/, "$1 : '" + paginationId + "'");
            } else {
                repeatExpression = expression;
            }

            return repeatExpression;
        }

        /**
         * Adds the ng-repeat directive to the element. In the case of multi-element (-start, -end) it adds the
         * appropriate multi-element ng-repeat to the first and last element in the range.
         * @param element
         * @param attrs
         * @param repeatExpression
         */
        function addNgRepeatToElement(element, attrs, repeatExpression) {
            if (element[0].hasAttribute('dir-paginate-start') || element[0].hasAttribute('data-dir-paginate-start')) {
                // using multiElement mode (dir-paginate-start, dir-paginate-end)
                attrs.$set('ngRepeatStart', repeatExpression);
                element.eq(element.length - 1).attr('ng-repeat-end', true);
            } else {
                attrs.$set('ngRepeat', repeatExpression);
            }
        }

        /**
         * Adds the dir-paginate-no-compile directive to each element in the tElement range.
         * @param tElement
         */
        function addNoCompileAttributes(tElement) {
            angular.forEach(tElement, function(el) {
                if (el.nodeType === 1) {
                    angular.element(el).attr('dir-paginate-no-compile', true);
                }
            });
        }

        /**
         * Removes the variations on dir-paginate (data-, -start, -end) and the dir-paginate-no-compile directives.
         * @param element
         */
        function removeTemporaryAttributes(element) {
            angular.forEach(element, function(el) {
                if (el.nodeType === 1) {
                    angular.element(el).removeAttr('dir-paginate-no-compile');
                }
            });
            element.eq(0).removeAttr('dir-paginate-start').removeAttr('dir-paginate').removeAttr('data-dir-paginate-start').removeAttr('data-dir-paginate');
            element.eq(element.length - 1).removeAttr('dir-paginate-end').removeAttr('data-dir-paginate-end');
        }

        /**
         * Creates a getter function for the current-page attribute, using the expression provided or a default value if
         * no current-page expression was specified.
         *
         * @param scope
         * @param attrs
         * @param paginationId
         * @returns {*}
         */
        function makeCurrentPageGetterFn(scope, attrs, paginationId) {
            var currentPageGetter;
            if (attrs.currentPage) {
                currentPageGetter = $parse(attrs.currentPage);
            } else {
                // If the current-page attribute was not set, we'll make our own.
                // Replace any non-alphanumeric characters which might confuse
                // the $parse service and give unexpected results.
                // See https://github.com/michaelbromley/angularUtils/issues/233
                var defaultCurrentPage = (paginationId + '__currentPage').replace(/\W/g, '_');
                scope[defaultCurrentPage] = 1;
                currentPageGetter = $parse(defaultCurrentPage);
            }
            return currentPageGetter;
        }
    }

    /**
     * This is a helper directive that allows correct compilation when in multi-element mode (ie dir-paginate-start, dir-paginate-end).
     * It is dynamically added to all elements in the dir-paginate compile function, and it prevents further compilation of
     * any inner directives. It is then removed in the link function, and all inner directives are then manually compiled.
     */
    function noCompileDirective() {
        return {
            priority: 5000,
            terminal: true
        };
    }

    function dirPaginationControlsTemplateInstaller($templateCache) {
        $templateCache.put('angularUtils.directives.dirPagination.template', '<ul class="pagination" ng-if="1 < pages.length || !autoHide"><li ng-if="boundaryLinks" ng-class="{ disabled : pagination.current == 1 }"><a href="" ng-click="setCurrent(1)">&laquo;</a></li><li ng-if="directionLinks" ng-class="{ disabled : pagination.current == 1 }"><a href="" ng-click="setCurrent(pagination.current - 1)">&lsaquo;</a></li><li ng-repeat="pageNumber in pages track by tracker(pageNumber, $index)" ng-class="{ active : pagination.current == pageNumber, disabled : pageNumber == \'...\' || ( ! autoHide && pages.length === 1 ) }"><a href="" ng-click="setCurrent(pageNumber)">{{ pageNumber }}</a></li><li ng-if="directionLinks" ng-class="{ disabled : pagination.current == pagination.last }"><a href="" ng-click="setCurrent(pagination.current + 1)">&rsaquo;</a></li><li ng-if="boundaryLinks"  ng-class="{ disabled : pagination.current == pagination.last }"><a href="" ng-click="setCurrent(pagination.last)">&raquo;</a></li></ul>');
    }

    function dirPaginationControlsDirective(paginationService, paginationTemplate) {

        var numberRegex = /^\d+$/;

        var DDO = {
            restrict: 'AE',
            scope: {
                maxSize: '=?',
                onPageChange: '&?',
                paginationId: '=?',
                autoHide: '=?'
            },
            link: dirPaginationControlsLinkFn
        };

        // We need to check the paginationTemplate service to see whether a template path or
        // string has been specified, and add the `template` or `templateUrl` property to
        // the DDO as appropriate. The order of priority to decide which template to use is
        // (highest priority first):
        // 1. paginationTemplate.getString()
        // 2. attrs.templateUrl
        // 3. paginationTemplate.getPath()
        var templateString = paginationTemplate.getString();
        if (templateString !== undefined) {
            DDO.template = templateString;
        } else {
            DDO.templateUrl = function(elem, attrs) {
                return attrs.templateUrl || paginationTemplate.getPath();
            };
        }
        return DDO;

        function dirPaginationControlsLinkFn(scope, element, attrs) {

            // rawId is the un-interpolated value of the pagination-id attribute. This is only important when the corresponding dir-paginate directive has
            // not yet been linked (e.g. if it is inside an ng-if block), and in that case it prevents this controls directive from assuming that there is
            // no corresponding dir-paginate directive and wrongly throwing an exception.
            var rawId = attrs.paginationId ||  DEFAULT_ID;
            var paginationId = scope.paginationId || attrs.paginationId ||  DEFAULT_ID;

            if (!paginationService.isRegistered(paginationId) && !paginationService.isRegistered(rawId)) {
                var idMessage = (paginationId !== DEFAULT_ID) ? ' (id: ' + paginationId + ') ' : ' ';
                if (window.console) {
                    console.warn('Pagination directive: the pagination controls' + idMessage + 'cannot be used without the corresponding pagination directive, which was not found at link time.');
                }
            }

            if (!scope.maxSize) { scope.maxSize = 9; }
            scope.autoHide = scope.autoHide === undefined ? true : scope.autoHide;
            scope.directionLinks = angular.isDefined(attrs.directionLinks) ? scope.$parent.$eval(attrs.directionLinks) : true;
            scope.boundaryLinks = angular.isDefined(attrs.boundaryLinks) ? scope.$parent.$eval(attrs.boundaryLinks) : false;

            var paginationRange = Math.max(scope.maxSize, 5);
            scope.pages = [];
            scope.pagination = {
                last: 1,
                current: 1
            };
            scope.range = {
                lower: 1,
                upper: 1,
                total: 1
            };

            scope.$watch('maxSize', function(val) {
                if (val) {
                    paginationRange = Math.max(scope.maxSize, 5);
                    generatePagination();
                }
            });

            scope.$watch(function() {
                if (paginationService.isRegistered(paginationId)) {
                    return (paginationService.getCollectionLength(paginationId) + 1) * paginationService.getItemsPerPage(paginationId);
                }
            }, function(length) {
                if (0 < length) {
                    generatePagination();
                }
            });

            scope.$watch(function() {
                if (paginationService.isRegistered(paginationId)) {
                    return (paginationService.getItemsPerPage(paginationId));
                }
            }, function(current, previous) {
                if (current != previous && typeof previous !== 'undefined') {
                    goToPage(scope.pagination.current);
                }
            });

            scope.$watch(function() {
                if (paginationService.isRegistered(paginationId)) {
                    return paginationService.getCurrentPage(paginationId);
                }
            }, function(currentPage, previousPage) {
                if (currentPage != previousPage) {
                    goToPage(currentPage);
                }
            });

            scope.setCurrent = function(num) {
                if (paginationService.isRegistered(paginationId) && isValidPageNumber(num)) {
                    num = parseInt(num, 10);
                    paginationService.setCurrentPage(paginationId, num);
                }
            };

            /**
             * Custom "track by" function which allows for duplicate "..." entries on long lists,
             * yet fixes the problem of wrongly-highlighted links which happens when using
             * "track by $index" - see https://github.com/michaelbromley/angularUtils/issues/153
             * @param id
             * @param index
             * @returns {string}
             */
            scope.tracker = function(id, index) {
                return id + '_' + index;
            };

            function goToPage(num) {
                if (paginationService.isRegistered(paginationId) && isValidPageNumber(num)) {
                    var oldPageNumber = scope.pagination.current;

                    scope.pages = generatePagesArray(num, paginationService.getCollectionLength(paginationId), paginationService.getItemsPerPage(paginationId), paginationRange);
                    scope.pagination.current = num;
                    updateRangeValues();

                    // if a callback has been set, then call it with the page number as the first argument
                    // and the previous page number as a second argument
                    if (scope.onPageChange) {
                        scope.onPageChange({
                            newPageNumber : num,
                            oldPageNumber : oldPageNumber
                        });
                    }
                }
            }

            function generatePagination() {
                if (paginationService.isRegistered(paginationId)) {
                    var page = parseInt(paginationService.getCurrentPage(paginationId)) || 1;
                    scope.pages = generatePagesArray(page, paginationService.getCollectionLength(paginationId), paginationService.getItemsPerPage(paginationId), paginationRange);
                    scope.pagination.current = page;
                    scope.pagination.last = scope.pages[scope.pages.length - 1];
                    if (scope.pagination.last < scope.pagination.current) {
                        scope.setCurrent(scope.pagination.last);
                    } else {
                        updateRangeValues();
                    }
                }
            }

            /**
             * This function updates the values (lower, upper, total) of the `scope.range` object, which can be used in the pagination
             * template to display the current page range, e.g. "showing 21 - 40 of 144 results";
             */
            function updateRangeValues() {
                if (paginationService.isRegistered(paginationId)) {
                    var currentPage = paginationService.getCurrentPage(paginationId),
                        itemsPerPage = paginationService.getItemsPerPage(paginationId),
                        totalItems = paginationService.getCollectionLength(paginationId);

                    scope.range.lower = (currentPage - 1) * itemsPerPage + 1;
                    scope.range.upper = Math.min(currentPage * itemsPerPage, totalItems);
                    scope.range.total = totalItems;
                }
            }
            function isValidPageNumber(num) {
                return (numberRegex.test(num) && (0 < num && num <= scope.pagination.last));
            }
        }

        /**
         * Generate an array of page numbers (or the '...' string) which is used in an ng-repeat to generate the
         * links used in pagination
         *
         * @param currentPage
         * @param rowsPerPage
         * @param paginationRange
         * @param collectionLength
         * @returns {Array}
         */
        function generatePagesArray(currentPage, collectionLength, rowsPerPage, paginationRange) {
            var pages = [];
            var totalPages = Math.ceil(collectionLength / rowsPerPage);
            var halfWay = Math.ceil(paginationRange / 2);
            var position;

            if (currentPage <= halfWay) {
                position = 'start';
            } else if (totalPages - halfWay < currentPage) {
                position = 'end';
            } else {
                position = 'middle';
            }

            var ellipsesNeeded = paginationRange < totalPages;
            var i = 1;
            while (i <= totalPages && i <= paginationRange) {
                var pageNumber = calculatePageNumber(i, currentPage, paginationRange, totalPages);

                var openingEllipsesNeeded = (i === 2 && (position === 'middle' || position === 'end'));
                var closingEllipsesNeeded = (i === paginationRange - 1 && (position === 'middle' || position === 'start'));
                if (ellipsesNeeded && (openingEllipsesNeeded || closingEllipsesNeeded)) {
                    pages.push('...');
                } else {
                    pages.push(pageNumber);
                }
                i ++;
            }
            return pages;
        }

        /**
         * Given the position in the sequence of pagination links [i], figure out what page number corresponds to that position.
         *
         * @param i
         * @param currentPage
         * @param paginationRange
         * @param totalPages
         * @returns {*}
         */
        function calculatePageNumber(i, currentPage, paginationRange, totalPages) {
            var halfWay = Math.ceil(paginationRange/2);
            if (i === paginationRange) {
                return totalPages;
            } else if (i === 1) {
                return i;
            } else if (paginationRange < totalPages) {
                if (totalPages - halfWay < currentPage) {
                    return totalPages - paginationRange + i;
                } else if (halfWay < currentPage) {
                    return currentPage - halfWay + i;
                } else {
                    return i;
                }
            } else {
                return i;
            }
        }
    }

    /**
     * This filter slices the collection into pages based on the current page number and number of items per page.
     * @param paginationService
     * @returns {Function}
     */
    function itemsPerPageFilter(paginationService) {

        return function(collection, itemsPerPage, paginationId) {
            if (typeof (paginationId) === 'undefined') {
                paginationId = DEFAULT_ID;
            }
            if (!paginationService.isRegistered(paginationId)) {
                throw 'pagination directive: the itemsPerPage id argument (id: ' + paginationId + ') does not match a registered pagination-id.';
            }
            var end;
            var start;
            if (angular.isObject(collection)) {
                itemsPerPage = parseInt(itemsPerPage) || 9999999999;
                if (paginationService.isAsyncMode(paginationId)) {
                    start = 0;
                } else {
                    start = (paginationService.getCurrentPage(paginationId) - 1) * itemsPerPage;
                }
                end = start + itemsPerPage;
                paginationService.setItemsPerPage(paginationId, itemsPerPage);

                if (collection instanceof Array) {
                    // the array just needs to be sliced
                    return collection.slice(start, end);
                } else {
                    // in the case of an object, we need to get an array of keys, slice that, then map back to
                    // the original object.
                    var slicedObject = {};
                    angular.forEach(keys(collection).slice(start, end), function(key) {
                        slicedObject[key] = collection[key];
                    });
                    return slicedObject;
                }
            } else {
                return collection;
            }
        };
    }

    /**
     * Shim for the Object.keys() method which does not exist in IE < 9
     * @param obj
     * @returns {Array}
     */
    function keys(obj) {
        if (!Object.keys) {
            var objKeys = [];
            for (var i in obj) {
                if (obj.hasOwnProperty(i)) {
                    objKeys.push(i);
                }
            }
            return objKeys;
        } else {
            return Object.keys(obj);
        }
    }

    /**
     * This service allows the various parts of the module to communicate and stay in sync.
     */
    function paginationService() {

        var instances = {};
        var lastRegisteredInstance;

        this.registerInstance = function(instanceId) {
            if (typeof instances[instanceId] === 'undefined') {
                instances[instanceId] = {
                    asyncMode: false
                };
                lastRegisteredInstance = instanceId;
            }
        };

        this.deregisterInstance = function(instanceId) {
            delete instances[instanceId];
        };
        
        this.isRegistered = function(instanceId) {
            return (typeof instances[instanceId] !== 'undefined');
        };

        this.getLastInstanceId = function() {
            return lastRegisteredInstance;
        };

        this.setCurrentPageParser = function(instanceId, val, scope) {
            instances[instanceId].currentPageParser = val;
            instances[instanceId].context = scope;
        };
        this.setCurrentPage = function(instanceId, val) {
            instances[instanceId].currentPageParser.assign(instances[instanceId].context, val);
        };
        this.getCurrentPage = function(instanceId) {
            var parser = instances[instanceId].currentPageParser;
            return parser ? parser(instances[instanceId].context) : 1;
        };

        this.setItemsPerPage = function(instanceId, val) {
            instances[instanceId].itemsPerPage = val;
        };
        this.getItemsPerPage = function(instanceId) {
            return instances[instanceId].itemsPerPage;
        };

        this.setCollectionLength = function(instanceId, val) {
            instances[instanceId].collectionLength = val;
        };
        this.getCollectionLength = function(instanceId) {
            return instances[instanceId].collectionLength;
        };

        this.setAsyncModeTrue = function(instanceId) {
            instances[instanceId].asyncMode = true;
        };

        this.setAsyncModeFalse = function(instanceId) {
            instances[instanceId].asyncMode = false;
        };

        this.isAsyncMode = function(instanceId) {
            return instances[instanceId].asyncMode;
        };
    }

    /**
     * This provider allows global configuration of the template path used by the dir-pagination-controls directive.
     */
    function paginationTemplateProvider() {

        var templatePath = 'angularUtils.directives.dirPagination.template';
        var templateString;

        /**
         * Set a templateUrl to be used by all instances of <dir-pagination-controls>
         * @param {String} path
         */
        this.setPath = function(path) {
            templatePath = path;
        };

        /**
         * Set a string of HTML to be used as a template by all instances
         * of <dir-pagination-controls>. If both a path *and* a string have been set,
         * the string takes precedence.
         * @param {String} str
         */
        this.setString = function(str) {
            templateString = str;
        };

        this.$get = function() {
            return {
                getPath: function() {
                    return templatePath;
                },
                getString: function() {
                    return templateString;
                }
            };
        };
    }
})();

  </script>
  
  <style>
	.scrollable-menu {
		height: auto;
		max-height: 200px;
		overflow-x: hidden;
	}
	.col-xs-2{
		margin-bottom: -800px;
		padding-bottom: 800px;
	}
	.my-controller {
	  border: 1px solid #fcc;
	  padding: 5px;
	  margin: 3px;
	}
	.my-controller small {
	  color: #c99;
	}
	.other-controller {

	  padding: 5px;
	  margin: 3px;
	}
	.other-controller small {
	  color: #99c;
	}
	.third-controller {
	  border: 1px solid #cfc;
	  padding: 5px;
	  margin: 3px;
	}
	.third-controller small {
	  color: #9c9;
	}
  </style>
</head>

<body ng-controller="MyController">
<div class="page-container" style = "background-color : #FAFAFA">
	<!-- top sunlight part-->
	
	<div class = "row" style = "background-color : #FFFFFF">
        <div class = "col-xs-1 "><span class="glyphicon glyphicon-align-justify" style="padding:10px;" ng-click="toggleSideBar()"></span></div>
		<div class="col-xs-3"></div>
		<div class = "col-xs-5"><a class="navbar-brand center" href="http://sunlightfoundation.com/" target="_blank"><img src="http://cs-server.usc.edu:45678/hw/hw8/images/logo.png" height="30">Congress API</a></div>
		<div class="col-xs-3"></div>
	</div>
	
	
	<div class = "row">
		<div class = "col-xs-2 row-height" style = "height : 100%; background-color : #444444" ng-show = "sideBarDisplay">
			<ul class="nav nav-stacked">
			  <li class="active"><a data-toggle="tab" href="#Legislators">Legislators</a></li>
			  <li><a data-toggle="tab" href="#Bills">Bills</a></li>
			  <li><a data-toggle="tab" href="#Committees">Committees</a></li>
			  <li><a data-toggle="tab" href="#Favorites">Favorites</a></li>
			</ul>
		</div>
		
		
		
		<div class = "col-xs-10 row-height">
		<div class = "tab-content">
			<!--Marker Legislators***********************************************************************************************-->
			<div class = "tab-pane fade in active" id = "Legislators">
			<table class="table">
				<thead>
				  <tr>
					<th><h2>Legislators</h2></th>
				  </tr>
				</thead>
				<tbody>
				  <tr>
					<td>
					<div id="legCaro" class="carousel slide" data-ride="carousel" data-interval="false">
					<div class="carousel-inner" role="listbox">
					<!-- carousel 1-->
						<div class="item active" style = "border-style : solid; border-color: #F0F0F0;" style = "background-color : #FFFFFF">
							<div class = "panel-headding" style = "border-bottom: 1px solid #F0F0F0;">
								<ul class="nav nav-tabs">
								  <li class="active"><a data-toggle="tab" href="#tabLegState">By State</a></li>
								  <li><a data-toggle="tab" href="#tabLegHouse">House</a></li>
								  <li><a data-toggle="tab" href="#tabLegSenate">Senate</a></li>
								</ul>
							</div>
				
							<div class="panel-body">
							<div class = "tab-content">
								<div id = "tabLegState" class = "tab-pane fade in active">
								<!-- By State -->
								<div class="panel panel-default">
									<div class="panel-heading">
										<div class = "container-fluid">
											<span><b>Legislators By State</b></span>
											<span  class = "col-sm-2" style = "float: right">
												<div class="dropdown">
													<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">{{selectedStateOnButton}}</button>
													<span class="caret"></span></button>
													<ul class="dropdown-menu scrollable-menu" role="menu">
														<li><a ng-click = "selectAllState()">All State</a></li>
														<li ng-repeat = "stateName in allStates"><a ng-click = "dropboxitemselected(stateName)">{{stateName}}</a></li>
													</ul>
												</div>
											</span>
										</div>
									</div>
									<div class="panel-body">
										<table class = "table">
											<thead>
												<tr>
													<th>Party</th>
													<th>Name</th>
													<th>Chamber</th>
													<th>District</th>
													<th>State</th>
													<th></th>
												  </tr>
											</thead>
											<tbody>
												<tr dir-paginate="legislator in legislatorsJson | filter:selectedState | orderBy:'last_name'| itemsPerPage: 10" pagination-id="legState_pg"><!-- repeat -->
													<td><img src = "{{legislator.party == 'R' ? 'http://cs-server.usc.edu:45678/hw/hw8/images/r.png' : 'http://cs-server.usc.edu:45678/hw/hw8/images/d.png'}}"  height="20"></td>
													<td>{{legislator.last_name + ", " + legislator.first_name}}</td>
													<td><img src = "{{legislator.chamber == 'house' ? 'http://cs-server.usc.edu:45678/hw/hw8/images/h.png' : 'http://cs-server.usc.edu:45678/hw/hw8/images/s.svg'}}"  height="20">{{legislator.chamber == "house" ? "House" : "Senate";}}</td>
													<td>{{legislator.district ? "District " + legislator.district : "N.A."}}</td>
													<td>{{legislator.state_name}}</td>
													<td><a href="#legCaro" role="button" data-slide="next"><button type="button" class="btn btn-primary" ng-click="getDetailInfoLeg(legislator)">View Details</button></a></td>
												</tr>
											</tbody>
										</table>
										<div ng-controller="OtherController" class="other-controller">
										  <div class="text-center">
										  <dir-pagination-controls pagination-id="legState_pg" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="http://helloworld2-149708.appspot.com/dirPagination.tpl.html"></dir-pagination-controls>
										  </div>
										</div>
									</div>
								</div>
								</div>
								<div id = "tabLegHouse" class = "tab-pane fade">
								<!-- By House use ng-hide to hide -->
								<div class="panel panel-default">
									<div class="panel-heading">
										<div class = "container-fluid">
											<span><b>Legislators By House</b></span>
											<span style = "float: right;">
												<input ng-model="legHouseSel" id="search" class="form-control" placeholder="Search"><!-- ng-modal -->
											</span>
										</div>
									</div>
									<div class="panel-body">
										<table class = "table">
											<thead>
												<tr>
													<th>Party</th>
													<th>Name</th>
													<th>Chamber</th>
													<th>District</th>
													<th>State</th>
													<th></th>
												  </tr>
											</thead>
											<tbody>
												<tr dir-paginate="legislator in legislatorsJson | filter:{chamber:'house'} | filter:legHouseSel | orderBy:'last_name' | itemsPerPage: 10" pagination-id="legHouse_pg"><!-- repeat -->
													<td><img src = "{{legislator.party == 'R' ? 'http://cs-server.usc.edu:45678/hw/hw8/images/r.png' : 'http://cs-server.usc.edu:45678/hw/hw8/images/d.png'}}"  height="20"></td>
													<td>{{legislator.last_name + ", " + legislator.first_name}}</td>
													<td><img src = "{{legislator.chamber == 'house' ? 'http://cs-server.usc.edu:45678/hw/hw8/images/h.png' : 'http://cs-server.usc.edu:45678/hw/hw8/images/s.svg'}}"  height="20">{{legislator.chamber == "house" ? "House" : "Senate";}}</td>
													<td>{{legislator.district ? "District " + legislator.district : "N.A."}}</td>
													<td>{{legislator.state_name}}</td>
													<td><a href="#legCaro" role="button" data-slide="next"><button type="button" class="btn btn-primary" ng-click="getDetailInfoLeg(legislator)">View Details</button></a></td>
												</tr>
											</tbody>
										</table>
										<div ng-controller="OtherController" class="other-controller">
								
										  <div class="text-center">
										  <dir-pagination-controls pagination-id="legHouse_pg" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="http://helloworld2-149708.appspot.com/dirPagination.tpl.html"></dir-pagination-controls>
										  </div>
										</div>
									</div>
								</div>
								</div>
								<div id = "tabLegSenate" class = "tab-pane fade">
								<!-- By Senate -->
								<div class="panel panel-default">
									<div class="panel-heading">
										<div class = "container-fluid">
											<span><b>Legislators By Senate</b></span>
											<span style = "float: right;">
												<input ng-model="legSenateSel" id="search" class="form-control" placeholder="Search"><!-- ng-modal -->
											</span>
										</div>
									</div>
									<div class="panel-body">
										<table class = "table">
											<thead>
												<tr>
													<th>Party</th>
													<th>Name</th>
													<th>Chamber</th>
									
													<th>State</th>
													<th></th>
												  </tr>
											</thead>
											<tbody>
												<tr dir-paginate="legislator in legislatorsJson | filter:{chamber:'senate'} | filter:legSenateSel | orderBy:'last_name' | itemsPerPage: 10" pagination-id="legSenate_pg"><!-- repeat -->
													<td><img src = "{{legislator.party == 'R' ? 'http://cs-server.usc.edu:45678/hw/hw8/images/r.png' : 'http://cs-server.usc.edu:45678/hw/hw8/images/d.png'}}"  height="20"></td>
													<td>{{legislator.last_name + ", " + legislator.first_name}}</td>
													<td><img src = "{{legislator.chamber == 'house' ? 'http://cs-server.usc.edu:45678/hw/hw8/images/h.png' : 'http://cs-server.usc.edu:45678/hw/hw8/images/s.svg'}}"  height="20">{{legislator.chamber == "house" ? "House" : "Senate";}}</td>
									
													<td>{{legislator.state_name}}</td>
													<td><a href="#legCaro" role="button" data-slide="next"><button type="button" class="btn btn-primary" ng-click="getDetailInfoLeg(legislator)">View Details</button></a></td>
												</tr>
											</tbody>
										</table>
										<div ng-controller="OtherController" class="other-controller">
								
										  <div class="text-center">
										  <dir-pagination-controls pagination-id="legSenate_pg" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="http://helloworld2-149708.appspot.com/dirPagination.tpl.html"></dir-pagination-controls>
										  </div>
										</div>
									</div>
								</div>
								</div>
							</div>
							</div>

						</div>
						<!-- carousel 2-->
						<div class="item" style = "background-color : #FFFFFF">
						<div class="panel panel-default">
							<div class="panel-heading" style = "padding: 0; margin: 0;">
								<div class = "container">
									<span><a href="#legCaro" role="button" data-slide="prev"><button type="button" class="btn btn-default"><span class = "glyphicon glyphicon-chevron-left"></span></button></a><big>Details</big></span>
									<span style="float:right;"><button type="button" class="btn btn-default" ng-click="legFavChange(curDetailedLeg)"><span class="{{legStars[legislator.bioguide_id]==true ? 'glyphicon glyphicon-star' : 'glyphicon glyphicon-star-empty'}}"></span></button></span>
								</div>
							</div>
							<div class="panel-body">
							<div class = "row">
								
								<div class = "col-sm-6">
								<table class = "table">
									<tbody>
										<tr>
											<td>
											<div class="col-sm-5"><img src="{{'https://theunitedstates.io/images/congress/original/' + curDetailedLeg.bioguide_id + '.jpg'}}" height = "200">
											</div>
											<div class="col-sm-7">
												<table class = "table">
													<tbody>
														<tr><td>{{curDetailedLeg.title + ". " + curDetailedLeg.last_name + ", " + curDetailedLeg.first_name}}</td></tr>
														<tr><td><a>{{curDetailedLeg.oc_email}}</a></td></tr>
														<tr><td>{{"Chamber: " + curDetailedLeg.chamber}}</td></tr>
														<tr><td>Contact: <a>{{curDetailedLeg.phone}}</a></td></tr>
														<tr><td><img src = "{{curDetailedLeg.party == 'R' ? 'http://cs-server.usc.edu:45678/hw/hw8/images/r.png' : 'http://cs-server.usc.edu:45678/hw/hw8/images/d.png'}}"  height="20">{{curDetailedLeg.party == "R" ? "Republic" : "Democrat"}}</td></tr>
													</tbody>
												</table>
											</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-4"><b>Start Term</b></div>
												<div class = "col-sm-8">{{ curDetailedLeg.term_start | date : 'mediumDate'}}</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-4"><b>End Term</b></div>
												<div class = "col-sm-8">{{ curDetailedLeg.term_end | date : 'mediumDate'}}</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-4"><b>Term</b></div>
												<div class = "col-sm-8">
													<div class="progress">
														<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{termPercent}}" aria-valuemin="0" aria-valuemax="100" style="width:{{termPercent}}%">
														  {{termPercent}}%
														</div>
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-4"><b>Office</b></div>
												<div class = "col-sm-8">{{curDetailedLeg.office}}</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-4"><b>State</b></div>
												<div class = "col-sm-8">{{curDetailedLeg.state_name}}</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-4"><b>Fax</b></div>
												<div class = "col-sm-8"><a>{{curDetailedLeg.fax ? curDetailedLeg.fax : "N.A."}}</a></div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-4"><b>Birthday</b></div>
												<div class = "col-sm-8">{{ curDetailedLeg.birthday | date : 'mediumDate'}}</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-4"><b>Social Links</b></div>
												<div class = "col-sm-8">
													<a ng-show="curDetailedLeg.twitter_id" href="{{'https://twitter.com/' + curDetailedLeg.twitter_id}}"  target="_blank"><img src="http://cs-server.usc.edu:45678/hw/hw8/images/t.png" width="30"></a>
													<a ng-show="curDetailedLeg.facebook_id" href="{{'https://www.facebook.com/' + curDetailedLeg.facebook_id}}"  target="_blank"><img src="http://cs-server.usc.edu:45678/hw/hw8/images/f.png" width="30"></a>
													<a ng-show="curDetailedLeg.website" href="{{curDetailedLeg.website}}"  target="_blank"><img src="http://cs-server.usc.edu:45678/hw/hw8/images/w.png" width="30"></a>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
								</div>
								

								<div class = "col-sm-6">
								<p><big>Committees</big></p>
								<table class = "table">
									<thead>
										<tr>
											<th>Chamber</th>
											<th>Committee ID</th>
											<th>Name</th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat = "committee in fiveCommitteesJson">
											<td>{{committee.chamber}}</td>
											<td>{{committee.committee_id}}</td>
											<td>{{committee.name}}</td>
										</tr>
									</tbody>
								</table>
								<p><big>Bills</big></p>
								<table class = "table">
									<thead>
										<tr>
											<th>Bill ID</th>
											<th>Title</th>
											<th>Chamber</th>
											<th>Bill Type</th>
											<th>Congress</th>
											<th>Link</th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat = "bill in fiveBillsJson">
											<td>{{bill.bill_id}}</td>
											<td>{{bill.official_title}}</td>
											<td>{{bill.chamber}}</td>
											<td>{{bill.bill_type}}</td>
											<td>{{bill.congress}}</td>
											<td><a ng-show = "bill.last_version.urls.pdf" href="{{bill.last_version.urls.pdf}}" target="_blank">Link</a></td>
										</tr>
									</tbody>
								</table>
								</div>

							</div>
							</div>
						</div>
						</div>
					</div>
					</div>
					</td>
				  </tr>
				  
				</tbody>
				
				
			 </table>
			 </div>
			 
			 
			 
			 
			 
			 
			<!--Marker Bills*******************************************************************************-->
			<div class = "tab-pane fade" id = "Bills">
			<table class="table">
				<thead>
				  <tr>
					<th>Bills</th>
				  </tr>
				</thead>
				<tbody>
				  <tr>
					<td>
					<div id="billCaro" class="carousel slide" data-ride="carousel" data-interval="false">
					<div class="carousel-inner" role="listbox">
					<!-- carousel 1-->
						<div class="item active" style = "border-style : solid; border-color : #F0F0F0" style = "background-color : #FFFFFF">
							<div class="panel-heading" style = "border-bottom : 1px solid #F0F0F0; padding: 0; margin: 0;">
								<ul class="nav nav-tabs">
								  <li class="active"><a data-toggle="tab" href="#tabBillActive">Active Bills</a></li>
								  <li><a data-toggle="tab" href="#tabBillNew">New Bills</a></li>
								</ul>
							</div>
							<div class="panel-body">
							<div class = "tab-content">
							<div id = "tabBillActive" class = "tab-pane fade in active">
								<!-- By Active Bills -->
								<div class="panel panel-default">
									<div class="panel-heading">
										<div class = "container-fluid">
											<span>Active Bills</span>
											<span style = "float: right;">
												<input ng-model="billsActSel" id="search" class="form-control" placeholder="Search"><!-- ng-modal -->
											</span>
										</div>
									</div>
									<div class="panel-body">
										<table class = "table">
											<thead>
												<tr>
													<th>Bill ID</th>
													<th>Bill Type</th>
													<th>Title</th>
													<th>Chamber</th>
													<th>Introduced On</th>
													<th>Sponsor</th>
													<th></th>
												  </tr>
											</thead>
											<tbody>
												<tr dir-paginate="bill in billsJsonActive | actBillSel | limitTo:50 | filter:billsActSel | itemsPerPage: 10" pagination-id="billAct_pg"><!-- repeat -->
													<td>{{bill.bill_id}}</td>
													<td>{{bill.bill_type}}</td>
													<td>{{bill.official_title}}</td>
													<td><img src = "{{bill.chamber == 'house' ? 'http://cs-server.usc.edu:45678/hw/hw8/images/h.png' : 'http://cs-server.usc.edu:45678/hw/hw8/images/s.svg'}}"  height="20">{{bill.chamber == "house" ? "House" : "Senate";}}</td>
													<td>{{bill.introduced_on}}</td>
													<td>{{bill.sponsor.title + ". " + bill.sponsor.last_name + ", " + bill.sponsor.first_name}}</td>
													<td><a href="#billCaro" role="button" data-slide="next"><button type="button" class="btn btn-primary" ng-click = "getDetailInfoBill(bill)">View Details</button></a></td>
												</tr>
											</tbody>
										</table>
										<div ng-controller="OtherController" class="other-controller">
									
										  <div class="text-center">
										  <dir-pagination-controls pagination-id="billAct_pg" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="http://helloworld2-149708.appspot.com/dirPagination.tpl.html"></dir-pagination-controls>
										  </div>
										</div>
									</div>
								</div>
							</div>
							
							<div id = "tabBillNew" class = "tab-pane fade">
								<!-- By new bills use ng-hide to hide -->
								<div class="panel panel-default">
									<div class="panel-heading">
										<div class = "container-fluid">
											<span>New Bills</span>
											<span style = "float: right;">
												<input ng-model="billsNewSel" id="search" class="form-control" placeholder="Search"><!-- ng-modal -->
											</span>
										</div>
									</div>
									<div class="panel-body">
										<table class = "table">
											<thead>
												<tr>
													<th>Bill ID</th>
													<th>Bill Type</th>
													<th>Title</th>
													<th>Chamber</th>
													<th>Introduced On</th>
													<th>Sponsor</th>
													<th></th>
												  </tr>
											</thead>
											<tbody>
												<tr dir-paginate="bill in billsJsonNew | newBillSel | limitTo:50 | filter:billsNewSel | itemsPerPage: 10" pagination-id="billNew_pg"><!-- repeat -->
													<td>{{bill.bill_id}}</td>
													<td>{{bill.bill_type}}</td>
													<td>{{bill.official_title}}</td>
													<td><img src = "{{bill.chamber == 'house' ? 'http://cs-server.usc.edu:45678/hw/hw8/images/h.png' : 'http://cs-server.usc.edu:45678/hw/hw8/images/s.svg'}}"  height="20">{{bill.chamber == "house" ? "House" : "Senate";}}</td>
													<td>{{bill.introduced_on}}</td>
													<td>{{bill.sponsor.title + ". " + bill.sponsor.last_name + ", " + bill.sponsor.first_name}}</td>
													<td><a href="#billCaro" role="button" data-slide="next"><button type="button" class="btn btn-primary" ng-click = "getDetailInfoBill(bill)">View Details</button></a></td>
												</tr>
											</tbody>
										</table>
										<div ng-controller="OtherController" class="other-controller">
							
										  <div class="text-center">
										  <dir-pagination-controls pagination-id="billNew_pg" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="http://helloworld2-149708.appspot.com/dirPagination.tpl.html"></dir-pagination-controls>
										  </div>
										</div>
									</div>
								</div>
							</div>
							</div>
							</div>
						</div>
						<!-- carousel 2-->
						<div class="item" style = "background-color : #FFFFFF">
						<div class="panel panel-default">
							<div class="panel-heading" style = "padding: 0; margin: 0;">
								<div class = "container">
									<span><a href="#billCaro" role="button" data-slide="prev"><button type="button" class="btn btn-default"><span class = "glyphicon glyphicon-chevron-left"></span></button></a><big>Details</big></span>
									<span style="float:right;"><button type="button" class="btn btn-default" ng-click="billFavChange(curDetailedBill)"><span class="{{billStars[bill.bill_id]==true ? 'glyphicon glyphicon-star' : 'glyphicon glyphicon-star-empty'}}"></span></button></span>
								</div>
							</div>
							<div class="panel-body">
							<div class = "row">
								
								<div class = "col-sm-6">
								<table class = "table">
									<tbody>
										<tr>
											<td>
												<div class = "col-sm-3">Bill ID</div>
												<div class = "col-sm-9">{{curDetailedBill.bill_id}}</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-3">Title</div>
												<div class = "col-sm-9">{{curDetailedBill.official_title}}</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-3">Bill Type</div>
												<div class = "col-sm-9">{{curDetailedBill.bill_type}}</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-3">Sponsor</div>
												<div class = "col-sm-9">{{curDetailedBill.sponsor.title + ". " + curDetailedBill.sponsor.last_name + ", " + curDetailedBill.sponsor.first_name}}</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-3">Chamber</div>
												<div class = "col-sm-9">{{curDetailedBill.chamber == "house" ? "House" : "Senate"}}</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-3">Status</div>
												<div class = "col-sm-9">{{curDetailedBill.history.active ? "Active" : "New"}}</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-3">Introduced On</div>
												<div class = "col-sm-9">{{curDetailedBill.introduced_on}}</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-3">Congress URL</div>
												<div class = "col-sm-9"><a href="{{curDetailedBill.urls.congress}}" target="_blank">URL</a></div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-3">Version Status</div>
												<div class = "col-sm-9">{{curDetailedBill.last_version.version_name ? curDetailedBill.last_version.version_name : "N.A."}}</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-3">Bill URL</div>
												<div class = "col-sm-9"><a href="{{curDetailedBill.last_version.urls.pdf}}" target="_blank">Link</a></div>
											</td>
										</tr>
									</tbody>
								</table>
								</div>
								<div class = "col-sm-6" style="overflow:hidden;">
									<object width="400" height="400" data="{{curDetailedBill.last_version.urls.pdf}}"></object>
								</div>
							</div>
							</div>
						</div>
						</div>
					</div>
					</div>
					</td>
				  </tr>
				  
				</tbody>
				
				
			</table>
			</div>
			 
			<!-- Marker Committees***************************************************************************** -->
			<div class = "tab-pane fade" id = "Committees">
			<table class="table">
				<thead>
				  <tr>
					<th>Committees (Please Note the save button sometimes need to click twice to save. Haven't figure out why)</th>
				  </tr>
				</thead>
				<tbody>
				  <tr>
					<td>
						<div class="panel panel-default" style = "background-color : #FFFFFF">
							<div class="panel-heading" style = "padding: 0; margin: 0;">
								<ul class="nav nav-tabs">
								  <li class="active"><a data-toggle="tab" href="#tabComHouse">House</a></li>
								  <li><a data-toggle="tab" href="#tabComSenate">Senate</a></li>
								  <li><a data-toggle="tab" href="#tabComJoint">Joint</a></li>
								</ul>
							</div>
							<div class="panel-body">
							<div class = "tab-content">
								<div id = "tabComHouse" class = "tab-pane fade in active">
								<!-- By House -->
								<div class="panel panel-default">
									<div class="panel-heading">
										<div class = "container-fluid">
											<span><b>House</b></span>
											<span style = "float: right;">
												<input ng-model="comHouseSel" id="search" class="form-control" placeholder="Search"><!-- ng-modal -->
											</span>
										</div>
									</div>
									<div class="panel-body">
										<table class = "table">
											<thead>
												<tr>
													<th></th>
													<th>Chamber</th>
													<th>Committee ID</th>
													<th>Name</th>
													<th>Parent Committee</th>
													<th>Contact</th>
													<th>Office</th>
												  </tr>
											</thead>
											<tbody>
												<tr dir-paginate="committee in committeesJson | filter:{chamber:'house'} | filter:comHouseSel | itemsPerPage: 10" pagination-id="comHouse_pg"><!-- repeat -->
													<td><button type="button" class="btn btn-default" ng-click="comFavChange(committee)"><span class="{{comStars[committee.committee_id]==true ? 'glyphicon glyphicon-star' : 'glyphicon glyphicon-star-empty'}}"></span></button></td>
													<td><img src = "{{committee.chamber == 'house' ? 'http://cs-server.usc.edu:45678/hw/hw8/images/h.png' : 'http://cs-server.usc.edu:45678/hw/hw8/images/s.svg'}}"  height="20">{{committee.chamber == "house" ? "House" : "Senate";}}</td>
													<td>{{committee.committee_id}}</td>
													<td>{{committee.name}}</td>
													<td>{{committee.parent_committee_id}}</td>
													<td>{{committee.phone ? committee.phone : "N.A."}}</td>
													<td>{{committee.office ? committee.office : "N.A."}}</td>
												</tr>
											</tbody>
										</table>
										<div ng-controller="OtherController" class="other-controller">
									
										  <div class="text-center">
										  <dir-pagination-controls pagination-id="comHouse_pg" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="http://helloworld2-149708.appspot.com/dirPagination.tpl.html"></dir-pagination-controls>
										  </div>
										</div>
									</div>
								</div>
								</div>
								
								<div id = "tabComSenate" class = "tab-pane fade">
								<!-- By Senate use ng-hide to hide -->
								<div class="panel panel-default">
									<div class="panel-heading">
										<div class = "container-fluid">
											<span><b>Senate</b></span>
											<span style = "float: right;">
												<input ng-model="comSenateSel" id="search" class="form-control" placeholder="Search"><!-- ng-modal -->
											</span>
										</div>
									</div>
									<div class="panel-body">
										<table class = "table">
											<thead>
												<tr>
													<th></th>
													<th>Chamber</th>
													<th>Committee ID</th>
													<th>Name</th>
													<th>Parent Committee</th>
													<th>Contact</th>
													<th>Office</th>
												  </tr>
											</thead>
											<tbody>
												<tr dir-paginate="committee in committeesJson | filter:{chamber:'senate'} | filter:comSenateSel | itemsPerPage: 10" pagination-id="comSenate_pg"><!-- repeat -->
													<td><button type="button" class="btn btn-default" ng-click="comFavChange(committee)"><span class="{{comStars[committee.committee_id]==true ? 'glyphicon glyphicon-star' : 'glyphicon glyphicon-star-empty'}}"></span></button></td>
													<td><img src = "{{committee.chamber == 'house' ? 'http://cs-server.usc.edu:45678/hw/hw8/images/h.png' : 'http://cs-server.usc.edu:45678/hw/hw8/images/s.svg'}}"  height="20">{{committee.chamber == "house" ? "House" : "Senate";}}</td>
													<td>{{committee.committee_id}}</td>
													<td>{{committee.name}}</td>
													<td>{{committee.parent_committee_id}}</td>
													<td>{{committee.phone ? committee.phone : "N.A."}}</td>
													<td>{{committee.office ? committee.office : "N.A."}}</td>
												</tr>
											</tbody>
										</table>
										<div ng-controller="OtherController" class="other-controller">
										
										  <div class="text-center">
										  <dir-pagination-controls pagination-id="comSenate_pg" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="http://helloworld2-149708.appspot.com/dirPagination.tpl.html"></dir-pagination-controls>
										  </div>
										</div>
									</div>
								</div>
								</div>
								
								<div id = "tabComJoint" class = "tab-pane fade">
								<!-- By Joint -->
								<div class="panel panel-default">
									<div class="panel-heading">
										<div class = "container-fluid">
											<span><b>Joint</b></span>
											<span style = "float: right;">
												<input ng-model="comJointSel" id="search" class="form-control" placeholder="Search"><!-- ng-modal -->
											</span>
										</div>
									</div>
									<div class="panel-body">
										<table class = "table">
											<thead>
												<tr>
													<th></th>
													<th>Chamber</th>
													<th>Committee ID</th>
													<th>Name</th>
													<th>Parent Committee</th>
													<th>Contact</th>
													<th>Office</th>
												  </tr>
											</thead>
											<tbody>
												<tr dir-paginate="committee in committeesJson | filter:{chamber:'joint'}| filter:comJointSel | itemsPerPage: 10" pagination-id="comJoint_pg"><!-- repeat -->
													<td><button type="button" class="btn btn-default" ng-click="comFavChange(committee)"><span class="{{comStars[committee.committee_id]==true ? 'glyphicon glyphicon-star' : 'glyphicon glyphicon-star-empty'}}"></span></button></td>
													<td><img src = "http://cs-server.usc.edu:45678/hw/hw8/images/s.svg"  height="20">Joint</td>
													<td>{{committee.committee_id}}</td>
													<td>{{committee.name}}</td>
													<td>{{committee.parent_committee_id}}</td>
													<td>{{committee.phone ? committee.phone : "N.A."}}</td>
													<td>{{committee.office ? committee.office : "N.A."}}</td>
												</tr>
											</tbody>
										</table>
										<div ng-controller="OtherController" class="other-controller">
									
										  <div class="text-center">
										  <dir-pagination-controls pagination-id="comJoint_pg" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="http://helloworld2-149708.appspot.com/dirPagination.tpl.html"></dir-pagination-controls>
										  </div>
										</div>
									</div>
								</div>
								</div>
							</div>
							</div>
						</div>
						
					
					</td>
				  </tr>
				  
				</tbody>
				
				
			</table>
			</div>
			 
			 
			 
			 
			 
			 
			<!-- Marker Favorites ************************************************************************************ -->
			<div class = "tab-pane fade" id = "Favorites">
			<table class="table">
				<thead>
				  <tr>
					<th>Favorites</th>
				  </tr>
				</thead>
				<tbody>
				  <tr>
					<td>
					<div id="favCaro" class="carousel slide" data-ride="carousel" data-interval="false">
					<div class="carousel-inner" role="listbox">
					<!-- carousel 1-->
						<div class="item active">
						<div class="panel panel-default" style = "background-color : #FFFFFF">
							<div class="panel-heading" style = "padding: 0; margin: 0;">
								<ul class="nav nav-tabs">
								  <li class="active"><a data-toggle="tab" href="#tabFavLeg">Legislators</a></li>
								  <li><a data-toggle="tab" href="#tabFavBill">Bills</a></li>
								  <li><a data-toggle="tab" href="#tabFavCom">Committees</a></li>
								</ul>
							</div>
							<div class="panel-body">
							<div class = "tab-content">
								<div id = "tabFavLeg" class = "tab-pane fade in active">
								<!-- By Legislators -->
								<div class="panel panel-default">
									<div class="panel-heading">
										<div class = "container-fluid">
											<span>Favorite Legislators</span>
										</div>
									</div>
									<div class="panel-body">
										<table class = "table">
											<thead>
												<tr>
													<th></th>
													<th>Image</th>
													<th>Party</th>
													<th>Name</th>
													<th>Chamber</th>
													<th>State</th>
													<th>Email</th>
													<th>Detalis</th>
												  </tr>
											</thead>
											<tbody>
												<tr ng-repeat="legislator in legislatorsJson | legislatorFilter:legStars"><!-- repeat use uni -->
													<td></td>
													<td><img src="{{'https://theunitedstates.io/images/congress/original/' + legislator.bioguide_id + '.jpg'}}" height = "50"></td>
													<td>{{legislator.party == "R" ? "Republic" : "Democrat"}}</td>
													<td>{{legislator.last_name + ", " + legislator.first_name}}</td>
													<td>{{legislator.chamber}}</td>
													<td>{{legislator.state_name}}</td>
													<td><a>{{legislator.oc_email}}</a></td>
													<td><a href="#legCaro" role="button" data-slide="next""><button type="button" class="btn btn-default" ng-click="getDetailInfoLeg(legislator)">View Details</button></a></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								</div>
								
								<div id = "tabFavBill" class = "tab-pane fade">
								<!-- By Bills use ng-hide to hide -->
								<div class="panel panel-default">
									<div class="panel-heading">
										<div class = "container-fluid">
											<span>Favorite House</span>
											
										</div>
									</div>
									<div class="panel-body">
										<table class = "table">
											<thead>
												<tr>
													<th></th>
													<th>Bill ID</th>
													<th>Bill Type</th>
													<th>Title</th>
													<th>Chamber</th>
													<th>Introduced On</th>
													<th>Sponsor</th>
													<th>Details</th>
												  </tr>
											</thead>
											<tbody>
												<tr ng-repeat="bill in billsJson | billFilter:billStars"><!-- repeat -->
													<td></td>
													<td>{{bill.bill_id}}</td>
													<td>{{bill.bill_type}}</td>
													<td>{{bill.official_title}}</td>
													<td>{{bill.chamber}}</td>
													<td>{{bill.introduced_on}}</td>
													<td>{{bill.sponsor.title + ". " + bill.sponsor.last_name + ", " + bill.sponsor.first_name}}</td>
													<td><a href="#billCaro" role="button" data-slide="next""><button type="button" class="btn btn-default" ng-click="getDetailInfoBill(bill)">View Details</button></a></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								</div>
								
								<div id = "tabFavCom" class = "tab-pane fade">
								<!-- By Committees -->
								<div class="panel panel-default">
									<div class="panel-heading">
										<div class = "container-fluid">
											<span>Favorite Committees</span>
											
										</div>
									</div>
									<div class="panel-body">
										<table class = "table">
											<thead>
												<tr>
													<th></th>
													<th>Chamber</th>
													<th>Committee ID</th>
													<th>Name</th>
													<th>Parent Committee</th>
													<th>Sub Committee</th>
												  </tr>
											</thead>
											<tbody>
												<tr ng-repeat="committee in committeesJson | committeeFilter:comStars"><!-- repeat -->
													<td></td>
													<td>{{committee.chamber}}</td>
													<td>{{committee.committee_id}}</td>
													<td>{{committee.name}}</td>
													<td>{{committee.parent_committee_id}}</td>
													<td>{{committee.subcommittee}}</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								</div>
							</div>
							</div>
						</div>
						</div>
						
						
						<!-- carousel 2-->
						<div class="item">
						<div class="panel panel-default">
							<div class="panel-heading" style = "padding: 0; margin: 0;">
								<div class = "container">
									<span><a href="#favCaro" role="button" data-slide-to="0"><button type="button" class="btn btn-default">back</button></a></span>
								</div>
							</div>
							<div class="panel-body">
							<div class = "row">
								
								<div class = "col-sm-6">
								<table class = "table">
									<tbody>
										<tr>
											<td>image</td>
											<td>
												<table class = "table">
													<tbody>
														<tr>
															<td>NA</td>
															<td>NA</td>
															<td>NA</td>
															<td>NA</td>
															<td>NA</td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-4">Start Term</div>
												<div class = "col-sm-8">adfasd</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-4">End Term</div>
												<div class = "col-sm-8">adfasd</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-4">Term</div>
												<div class = "col-sm-8">adfasd</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-4">Office</div>
												<div class = "col-sm-8">adfasd</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-4">State</div>
												<div class = "col-sm-8">adfasd</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-4">Fax</div>
												<div class = "col-sm-8">adfasd</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-4">Birthday</div>
												<div class = "col-sm-8">adfasd</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-4">Social Links</div>
												<div class = "col-sm-8">adfasd</div>
											</td>
										</tr>
									</tbody>
								</table>
								</div>
								

								<div class = "col-sm-6">
								<p>Committees</p>
								<table class = "table">
									<thead>
										<tr>
											<th>Chamber</th>
											<th>Committee ID</th>
											<th>Name</th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat = "i in testArray1">
											<td>NA</td>
											<td></td>
											<td></td>
										</tr>
									</tbody>
								</table>
								<p>Bills</p>
								<table class = "table">
									<thead>
										<tr>
											<th>Bill ID</th>
											<th>Title</th>
											<th>Chamber</th>
											<th>Bill Type</th>
											<th>Congress</th>
											<th>Link</th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat = "i in testArray1">
											<td>NA</td>
											<td>NA</td>
											<td>NA</td>
											<td>NA</td>
											<td>NA</td>
											<td>NA</td>
										</tr>
									</tbody>
								</table>
								</div>

							</div>
							</div>
						</div>
						</div>
						<!-- carousel 3-->
						<div class="item">
						<div class="panel panel-default">
							<div class="panel-heading" style = "padding: 0; margin: 0;">
								<div class = "container">
									<span><a href="#favCaro" role="button" data-slide-to="0"><button type="button" class="btn btn-default">back</button></a></span>
								</div>
							</div>
							<div class="panel-body">
							<div class = "row">
								
								<div class = "col-sm-6">
								<table class = "table">
									<tbody>
										<tr>
											<td>
												<div class = "col-sm-3">Bill ID</div>
												<div class = "col-sm-9">adfasd</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-3">Title</div>
												<div class = "col-sm-9">adfasd</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-3">Bill Type</div>
												<div class = "col-sm-9">adfasd</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-3">Sponsor</div>
												<div class = "col-sm-9">adfasd</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-3">Chamber</div>
												<div class = "col-sm-9">adfasd</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-3">Status</div>
												<div class = "col-sm-9">adfasd</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-3">Introduced On</div>
												<div class = "col-sm-9">adfasd</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-3">Congress URL</div>
												<div class = "col-sm-9">adfasd</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-3">Version Status</div>
												<div class = "col-sm-9">adfasd</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class = "col-sm-3">Bill URL</div>
												<div class = "col-sm-9">adfasd</div>
											</td>
										</tr>
									</tbody>
								</table>
								</div>
								<div class = "col-sm-6">
								<p>pdf</p>
								</div>
							</div>
							</div>
						</div>
						</div>

					</div>
					</div>
					</td>
				  </tr>
				  
				</tbody>
				
				
			</table>
			</div>
		</div>
		</div>
	</div>
</div>


<script>

</script>


</body>
</html>
