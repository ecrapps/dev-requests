(function() {
	'use strict';

	devRequestApp.controller('UserController', ['$scope', 'UserService',
								function($scope, UserService) {

			$scope.users = [];

			UserService.getUsers()
				.then(function mySuccess(response) {
					$scope.users = response.data;
				}, function myError(reason) {
					console.log("getUsers failed");
				});

		}
	]);
})();