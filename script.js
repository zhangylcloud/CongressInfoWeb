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


function MyController($scope, $http, $window) {
	$scope.sideBarDisplay = true;
	$scope.toggleSideBar=function(){
		$scope.sideBarDisplay = $scope.sideBarDisplay == true ? false : true;
	}
	$scope.legStars = new Object();
	$scope.billStars = new Object();
	$scope.comStars = new Object();
	//Get all required data
	
	$http({
		url: 'http://homework8-149710.appspot.com/', 
		method: "GET",
		params: {legislators : 1}
	 }).then(function successCallback(response) {
		$scope.legislatorsJson = response.data.results;
		//$scope.legStars = new Object();
		angular.forEach($scope.legislatorsJson, function(legislator){
            if($window.localStorage.getItem("leg" + legislator.bioguide_id) == "a"){
				$scope.legStars[legislator.bioguide_id] = true;
			}
			else{
				$scope.legStars[legislator.bioguide_id] = false;
			}
		});
	  }, function errorCallback(response) {
		$scope.legislatorsJson = "Legislators data access fail";
		alert("Legislators data access fail");
	  });
	  
	  $http({
		url: 'http://homework8-149710.appspot.com/', 
		method: "GET",
		params: {bills : 1, active : true}
	 }).then(function successCallback(response) {
		$scope.billsJsonActive = response.data.results;
		//if(typeof billStars == "undefined") $scope.billStars = new Object();
		angular.forEach($scope.billsJsonActive, function(bill){
			if($window.localStorage.getItem("bill" + bill.bill_id)=="a"){
				$scope.billStars[bill.bill_id] = true;
			}
			else{
				$scope.billStars[bill.bill_id] = false;
			}
		});
	  }, function errorCallback(response) {
		$scope.billsJsonActive = "Bills data access fail";
		alert("Bills data access fail");
	  });
	  
	  $http({
		url: 'http://homework8-149710.appspot.com/', 
		method: "GET",
		params: {bills : 1}
	 }).then(function successCallback(response) {
		$scope.billsJsonNew = response.data.results;
		//if(typeof billStars == "undefined") $scope.billStars = new Object();
		angular.forEach($scope.billsJsonNew, function(bill){
			if($window.localStorage.getItem("bill" + bill.bill_id)=="a"){
				$scope.billStars[bill.bill_id] = true;
			}
			else{
				$scope.billStars[bill.bill_id] = false;
			}
		});
	  }, function errorCallback(response) {
		$scope.billsJsonNew = "Bills data access fail";
		alert("Bills data access fail");
	  });
	  
	  $http({
		url: 'http://homework8-149710.appspot.com/', 
		method: "GET",
		params: {committees : 1}
	 }).then(function successCallback(response) {
		$scope.committeesJson = response.data.results;
		//$scope.comStars = new Object();
		angular.forEach($scope.committeesJson, function(committee){
			if($window.localStorage.getItem("com" + committee.committee_id) == "a"){
				$scope.comStars[committee.committee_id] = true;
			}
			else{
				$scope.comStars[committee.committee_id] = false;
			}
		});
	  }, function errorCallback(response) {
		$scope.committeesJson = "Committees data access fail";
		alert("Committees data access fail");
	  });
	
	/*$http.get("query.php?legislators").then(function(response) {
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
	*/
	
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
	
	
	
	
	$scope.legFavChange = function(legislator){
		if($scope.legStars[legislator.bioguide_id] == false){
			$scope.legStars[legislator.bioguide_id] = true;
			$window.localStorage.setItem("leg" + legislator.bioguide_id, "a");
		}
		else{
			$scope.legStars[legislator.bioguide_id] = false;
			$window.localStorage.setItem("leg" + legislator.bioguide_id, "b");
		}
	}
	
	$scope.removeLeg = function(legislator){
		$scope.legStars[legislator.bioguide_id] = false;
		$window.localStorage.setItem("leg" + legislator.bioguide_id, "b") ;
	}
	
	
	
	$scope.billFavChange = function(bill){
		if($scope.billStars[bill.bill_id] == false){
			$scope.billStars[bill.bill_id] = true;
			$window.localStorage.setItem("bill" + bill.bill_id, "a");
		}
		else{
			$scope.billStars[bill.bill_id] = false;
			$window.localStorage.setItem("bill" + bill.bill_id, "b");
		}
	}
	
	$scope.removeBill = function(bill){
		$scope.billStars[bill.bill_id] = false;
		$window.localStorage.setItem("bill" + bill.bill_id, "b");
	}
	
	//$scope.comStars = new Object();
	
	
	$scope.comFavChange = function(committee){
		if($scope.comStars[committee.committee_id] == false){
			$scope.comStars[committee.committee_id] = true;
			$window.localStorage.setItem("com" + committee.committee_id, "a");
		}
		else{
			$scope.comStars[committee.committee_id] = false;
			$window.localStorage.setItem("com" + committee.committee_id, "b");
		}
	}
	
	$scope.removeCommittee = function(committee){
		$scope.comStars[committee.committee_id] = false;
		$window.localStorage.setItem("com" + committee.committee_id, "b");
	}
	
	
	//Get some requird information in detail
	//get five bills for legislator detail
	
	$scope.getFiveBillsNCommittees = function(sponsor_id, member_ids){
		
		$http({
			url: 'http://homework8-149710.appspot.com/', 
			method: "GET",
			params: {bills : 1, sponsor_id : sponsor_id}
		 }).then(function successCallback(response) {
			$scope.fiveBillsJson = response.data.results;
		  }, function errorCallback(response) {
			$scope.fiveBillsJson = "Five bills access fail";
			alert("Five bills access fail");
		  });
		  
		 $http({
			url: 'http://homework8-149710.appspot.com/', 
			method: "GET",
			params: {committees : 1, member_ids : member_ids}
		 }).then(function successCallback(response) {
			$scope.fiveCommitteesJson = response.data.results;
		  }, function errorCallback(response) {
			$scope.fiveCommitteesJson = "Five committees access fail";
			alert("Five committees access fail");
		  });
		
		
		/*$http.get("query.php?bills&sponsor_id=" + sponsor_id).then(function(response){
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
		});*/
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

myApp.controller('MyController', ['$scope', '$http', '$window', MyController]);
myApp.controller('OtherController', OtherController);