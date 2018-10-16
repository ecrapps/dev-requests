(function() {
    'use strict';

	devRequestApp.factory('MainService', ['$rootScope', '$location', function($rootScope, $location) {
		
		var factory = {};

		factory.getWeAreIT = function($location) {

			// Checks whether or not we are using the "it" route
			if ($rootScope.user.groupIsIT){
				return {
					weAreIT : true
				}
			} else {
				return {
					weAreIT : false
				}
			}
		}

		return factory;
	}]);
})();