(function() {
	'use strict';

	devRequestApp.controller('UserController', ['$scope', '$location', '$routeParams' , 'UserService', 'toaster',
								function($scope, $location, $routeParams, UserService, toaster) {

			$scope.getUsers = getUsers;
			$scope.deleteUser = deleteUser;
			$scope.editUser = editUser;
			$scope.createUser = createUser;
			$scope.updateUser = updateUser;

			// If controller is called alongside EditUser.html page
			if ($location.url().indexOf('editUser') > -1) {
				$scope.title = "";
				$scope.user = {};
				$scope.user.userGroup = "users"; // Par défaut l'utilisateur appartient au groupe "users"

				if (typeof $routeParams.idUser !== 'undefined'){
					// In case of edition of an existing user
					UserService.getUser($routeParams.idUser)
						.then(function mySuccess(response) {
								$scope.user = response.data[0];
								$scope.title = "Editer " + $scope.user.name;
								if ($scope.user.dpo === 1)
									$scope.user.dpo = true;
								if ($scope.user.inactif === 1)
									$scope.user.inactif = true;
								console.log("getUser succeeded");
							}, function myError(reason) {
								console.log("getUser failed");
							});
				} else {
					// In case of creation of a new user
					$scope.title = "Création d'un nouvel utilisateur";
				}
			} else {
			// If controller is called alongside User.html page
				$scope.users = [];

				getUsers();
			}

			function getUsers() {
				UserService.getUsers()
					.then(function mySuccess(response) {
						$scope.users = response.data;
						
						$(document).ready( function () {
							$('#table_id').DataTable({
								order: [[1, 'asc']]
							});
						} );
					}, function myError(reason) {
						console.log("getUsers failed");
					});
			}

			function deleteUser(idUser) {
				UserService.deleteUser(idUser)
					.then(function mySuccess(response) {
						toaster.pop({
							type: 'success',
							title: 'Utilisateur supprimé',
							body: 'L\'utilisateur a bien été supprimé.',
							timeout: 3000
						});
						let pos = $scope.users
								.map(function(u) { return u.id; })
								.indexOf(idUser);

						$('#table_id').DataTable()
							.row(pos)
							.remove()
							.search( '' )
							.columns().search( '' )
							.draw();

						$scope.users.splice(pos, 1);

						console.log("delete user succeeded");
					}, function myError(reason) {
						toaster.pop({
							type: 'error',
							title: 'Utilisateur supprimé : erreur',
							body: 'Une erreur a eu lieu lors de la suppression de l\'utilisateur.',
							timeout: 3000
						});
						console.log("delete user failed");
					});
			}

			function createUser(user) {
				UserService.createUser(user)
					.then(function mySuccess(response) {
						toaster.pop({
										type: 'success',
						                title: 'Utilisateur enregistré',
						                body: 'L\'utilisateur a bien été créé.',
						                timeout: 3000
						           	});
						console.log("createUser succeeded");
					}, function myError(reason) {
						console.log("createUser failed");
					});
			}

			function updateUser(user) {
				UserService.updateUser(user)
					.then(function mySuccess(response) {
						toaster.pop({
										type: 'success',
						                title: 'Utilisateur mis à jour',
						                body: 'L\'utilisateur a bien été mis à jour.',
						                timeout: 3000
						           	});
						console.log("updateUser succeeded");
					}, function myError(reason) {
						console.log("updateUser failed");
					});
			}

			function editUser(user) {
				if (typeof user.id === 'undefined') {
					// If an id exists, we are in creation mode
					createUser(user);
				} else {
					// Else, we are in edition mode
					updateUser(user);
				}
			}

		}
	]);
})();