(function() {
	'use strict';

	devRequestApp.controller('MainController',
		function MainController($rootScope, $scope, $route, $routeParams, $location, MainService){

			$scope.$route = $route;
			$scope.$location = $location;
			$scope.$routeParams = $routeParams;
			$rootScope.loggedIn = false;
			$rootScope.user = {};
			$rootScope.user.id = "";
			$rootScope.user.name = "";
			$rootScope.user.userName = "";
			$rootScope.user.groupIsIT = false;
			$rootScope.user.dpo = false;
		}
	);
})();