(function() {
    'use strict';

	devRequestApp.factory('StatusService', ['$http', 'URL_REQUEST_API', function($http, URL_REQUEST_API) {
		
		var url_api = URL_REQUEST_API.URL_API;
		var factory = {};

		factory.getStatuses = function() {
			return $http({
	        	method : "GET",
	        	url : url_api + "getStatuses" 
		    });
		}

		factory.getStatus = function(idStatus) {
			var data = {
				idStatus : idStatus
			}

			return $http({
	        	method : "GET",
	        	url : url_api + "getStatus" ,
	        	params : data
		    });
		}

		factory.deleteStatus = function(idStatus) {
			var data = {
				idStatus : idStatus
			}

			return $http({
	        	method : "DELETE",
	        	url : url_api + "deleteStatus" ,
	        	params : data
		    });
		}

		factory.createStatus = function(status) {
			var data = {
				label: status.label
			}

			return $http({
				method: "POST",
				url: url_api + "createStatus",
				data: data
			});
		}

		factory.updateStatus = function(status) {
			var data = {
				idStatus: status.id,
				label: status.label
			}

			return $http({
				method: "PUT",
				url: url_api + "updateStatus",
				data: data
			});
		}

		return factory;
	}]);
})();