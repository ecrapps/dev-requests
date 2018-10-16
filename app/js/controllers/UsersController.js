(function() {
	'use strict';

	devRequestApp.controller('UsersController', ['$rootScope', '$scope', 'UsersService', 'toaster', '$location',
								function($rootScope, $scope, UsersService, toaster, $location) {

			$scope.createUser = createUser;
			$scope.user = {};


			////////////////////////////////////////////////////////////////////////////////////////
			function createUser(user) {
				UsersService.createUser(user)
					.then(function mySuccess(response) {
				        if (response.data.loginSucceed) {
		        			toaster.pop({
										type: 'success',
						                title: 'Utilisateur enregistré',
						                body: 'Utilisateur enregistré avec succès',
						                timeout: 3000
						           	});
		        		} else {
		        			toaster.pop({
										type: 'error',
						                title: 'Erreur lors de l\'enregistrement',
						                body: 'Erreur lors de l\'enregistrement de l\'Utilisateur',
						                timeout: 3000
						           	});
		        		}
				    }, function myError(response) {
				    	console.log("response error :", response);
					});
			}

		}
	]);
})();