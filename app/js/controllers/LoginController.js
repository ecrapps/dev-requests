(function() {
	'use strict';

	devRequestApp.controller('LoginController', ['$rootScope', '$scope', 'LoginService', 'toaster', '$location',
								function($rootScope, $scope, LoginService, toaster, $location) {

			$scope.login = login;
			$scope.user = {};

			if ($rootScope.loggedIn)
				$location.url("/editRequest");


			////////////////////////////////////////////////////////////////////////////////////////
			function login(user) {
				LoginService.checkLogin(user)
					.then(function mySuccess(response) {
				        if (response.data.loginSucceed) {
		        			$rootScope.loggedIn = true;
							$rootScope.user.id = response.data.user.id;
							$rootScope.user.name = response.data.user.name;
							$rootScope.user.userName = response.data.user.userName;
							$rootScope.user.groupIsIT = response.data.user.groupIsIT;
							toaster.pop({
										type: 'success',
						                title: 'Connexion autorisée',
						                body: 'Redirection en cours...',
						                timeout: 1000
						           	});
		        			$location.url("/editRequest");
		        		} else {
		        			toaster.pop({
										type: 'error',
						                title: 'Erreur de connexion',
						                body: 'Identifiant ou mot de passe incorrect.',
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