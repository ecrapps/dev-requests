(function() {
	'use strict';

	devRequestApp.controller('StatusController', ['$scope', '$location', '$routeParams' , 'StatusService', 'toaster',
								function($scope, $location, $routeParams, StatusService, toaster) {

			$scope.getStatuses = getStatuses;
			$scope.deleteStatus = deleteStatus;
			$scope.editStatus = editStatus;
			$scope.createStatus = createStatus;
			$scope.updateStatus = updateStatus;

			// If controller is called alongside EditStatus.html page
			if ($location.url().indexOf('editStatus') > -1) {
				$scope.title = "";
				$scope.status = {};

				if (typeof $routeParams.idStatus !== 'undefined'){
					// In case of edition of an existing status
					StatusService.getStatus($routeParams.idStatus)
						.then(function mySuccess(response) {
								$scope.status = response.data[0];
								$scope.title = "Editer " + $scope.status.label;
								console.log("getStatus succeeded");
							}, function myError(reason) {
								console.log("getStatus failed");
							});
				} else {
					// In case of creation of a new status
					$scope.title = "Création d'un nouveau statut";
				}
			} else {
			// If controller is called alongside Status.html page
				$scope.statuses = [];

				getStatuses();
			}

			function getStatuses() {
				StatusService.getStatuses()
					.then(function mySuccess(response) {
						$scope.statuses = response.data;
						
						$(document).ready( function () {
							$('#table_id').DataTable({
								order: [[1, 'asc']]
							});
						} );
					}, function myError(reason) {
						console.log("getStatuses failed");
					});
			}

			function deleteStatus(idStatus) {
				StatusService.deleteStatus(idStatus)
					.then(function mySuccess(response) {
						toaster.pop({
							type: 'success',
							title: 'Statut supprimé',
							body: 'Le statut a bien été supprimé.',
							timeout: 3000
						});
						let pos = $scope.statuses
								.map(function(s) { return s.id; })
								.indexOf(idStatus);

						$('#table_id').DataTable()
							.row(pos)
							.remove()
							.search( '' )
							.columns().search( '' )
							.draw();

						$scope.statuses.splice(pos, 1);

						console.log("delete status succeeded");
					}, function myError(reason) {
						toaster.pop({
							type: 'error',
							title: 'Statut supprimé : erreur',
							body: 'Une erreur a eu lieu lors de la suppression du statut.',
							timeout: 3000
						});
						console.log("delete status failed");
					});
			}

			function createStatus(status) {
				StatusService.createStatus(status)
					.then(function mySuccess(response) {
						toaster.pop({
										type: 'success',
						                title: 'Statut enregistré',
						                body: 'Le statut a bien été créé.',
						                timeout: 3000
						           	});
						console.log("createStatus succeeded");
					}, function myError(reason) {
						console.log("createStatus failed");
					});
			}

			function updateStatus(status) {
				StatusService.updateStatus(status)
					.then(function mySuccess(response) {
						toaster.pop({
										type: 'success',
						                title: 'Utilisateur mis à jour',
						                body: 'L\'utilisateur a bien été mis à jour.',
						                timeout: 3000
						           	});
						console.log("updateStatus succeeded");
					}, function myError(reason) {
						console.log("updateStatus failed");
					});
			}

			function editStatus(status) {
				if (typeof status.id === 'undefined') {
					// If an id exists, we are in creation mode
					createStatus(status);
				} else {
					// Else, we are in edition mode
					updateStatus(status);
				}
			}

		}
	]);
})();