(function() {
    'use strict';

	devRequestApp.factory('LoginService', ['$http', 'URL_REQUEST_API', function($http, URL_REQUEST_API) {

		
		var url_api = URL_REQUEST_API.URL_API;
		var factory = {};

		factory.checkLogin = function(user) {
			return $http({
	        	method : "POST",
	        	url : url_api + "checkLogin",
		        data : user
		    });
		}

		return factory;
	}]);
})();