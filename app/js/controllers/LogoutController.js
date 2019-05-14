(function() {
	'use strict';

	devRequestApp.controller('LogoutController', ['$rootScope', '$location',
								function($rootScope, $location) {

			$rootScope.loggedIn = false;
			$rootScope.user.id = "";
			$rootScope.user.name = "";
			$rootScope.user.userName = "";
			$rootScope.user.groupIsIT = false;

			$location.url("/login");

		}
	]);
})();