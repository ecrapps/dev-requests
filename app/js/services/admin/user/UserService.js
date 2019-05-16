(function() {
    'use strict';

	devRequestApp.factory('UserService', ['$http', 'URL_REQUEST_API', function($http, URL_REQUEST_API) {

		
		var url_api = URL_REQUEST_API.URL_API;
		var factory = {};

		factory.getUsers = function() {
			return $http({
	        	method : "GET",
	        	url : url_api + "getUsers" 
		    });
		}

		factory.getUser = function(idUser) {
			var data = {
				idUser : idUser
			}

			return $http({
	        	method : "GET",
	        	url : url_api + "getUser" ,
	        	params : data
		    });
		}

		factory.deleteUser = function(idUser) {
			var data = {
				idUser : idUser
			}

			return $http({
	        	method : "DELETE",
	        	url : url_api + "deleteUser" ,
	        	params : data
		    });
		}

		factory.createUser = function(user) {
			var data = {
				name: user.name,
				userName: user.userName,
				passwd: user.passwd,
				userGroup: user.userGroup,
				dpo: user.dpo,
				email: user.email
			}

			return $http({
				method: "POST",
				url: url_api + "createUser",
				data: data
			});
		}

		factory.updateUser = function(user) {
			var data = {
				idUser: user.id,
				name: user.name,
				userName: user.userName,
				userGroup: user.userGroup,
				dpo: user.dpo,
				email: user.email
			}

			if (user.passwd) data.passwd = user.passwd;

			return $http({
				method: "PUT",
				url: url_api + "updateUser",
				data: data
			});
		}

		return factory;
	}]);
})();