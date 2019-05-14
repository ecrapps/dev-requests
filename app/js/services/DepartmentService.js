(function() {
    'use strict';

	devRequestApp.factory('DepartmentService', ['$http', 'URL_REQUEST_API', function($http, URL_REQUEST_API) {

		
		var url_api = URL_REQUEST_API.URL_API;
		var factory = {};

		factory.getDepartments = function() {
			return $http({
	        	method : "GET",
	        	url : url_api + "getDepartments" 
		    });
		}

		factory.getDepartment = function(idDepartment) {
			var data = {
				idDepartment : idDepartment
			}

			return $http({
	        	method : "GET",
	        	url : url_api + "getDepartment" ,
	        	params : data
		    });
		}

		factory.deleteDepartment = function(idDepartment) {
			var data = {
				idDepartment : idDepartment
			}

			return $http({
	        	method : "DELETE",
	        	url : url_api + "deleteDepartment" ,
	        	params : data
		    });
		}

		factory.createDepartment = function(department) {
			var data = {
				name: department.name,
				costCenter: department.costCenter
			}

			return $http({
				method: "POST",
				url: url_api + "createDepartment",
				data: data
			});
		}

		factory.updateDepartment = function(department) {
			var data = {
				idDepartment: department.id,
				name: department.name,
				costCenter: department.costCenter
			}

			return $http({
				method: "PUT",
				url: url_api + "updateDepartment",
				data: data
			});
		}

		return factory;
	}]);
})();