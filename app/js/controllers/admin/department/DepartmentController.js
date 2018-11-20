(function() {
	'use strict';

	devRequestApp.controller('DepartmentController', ['$scope', '$location', '$routeParams' , 'DepartmentService', 'toaster',
								function($scope, $location, $routeParams, DepartmentService, toaster) {

			$scope.getDepartments = getDepartments;
			$scope.deleteDepartment = deleteDepartment;
			$scope.editDepartment = editDepartment;
			$scope.createDepartment = createDepartment;
			$scope.updateDepartment = updateDepartment;

			// If controller is called alongside EditDepartment.html page
			if ($location.url().indexOf('editDepartment') > -1) {
				$scope.title = "";
				$scope.department = {};

				if (typeof $routeParams.idDepartment !== 'undefined'){
					// In case of edition of an existing department
					DepartmentService.getDepartment($routeParams.idDepartment)
						.then(function mySuccess(response) {
								$scope.department = response.data[0];
								$scope.title = "Editer " + $scope.department.name;
								console.log("getDepartment succeeded");
							}, function myError(reason) {
								console.log("getDepartment failed");
							});
				} else {
					// In case of creation of a new department
					$scope.title = "Création d'un nouveau département";
				}
			} else {
			// If controller is called alongside Department.html page
				$scope.departments = [];

				getDepartments();
			}

			function getDepartments() {
				DepartmentService.getDepartments()
					.then(function mySuccess(response) {
						$scope.departments = response.data;
						
						$(document).ready( function () {
							$('#table_id').DataTable({
								order: [[1, 'asc']]
							});
						} );
					}, function myError(reason) {
						console.log("getDepartments failed");
					});
			}

			function deleteDepartment(idDepartment) {
				DepartmentService.deleteDepartment(idDepartment)
					.then(function mySuccess(response) {
						toaster.pop({
							type: 'success',
							title: 'Département supprimé',
							body: 'Le département a bien été supprimé.',
							timeout: 3000
						});
						let pos = $scope.departments
								.map(function(d) { return d.id; })
								.indexOf(idDepartment);

						$('#table_id').DataTable()
							.row(pos)
							.remove()
							.search( '' )
							.columns().search( '' )
							.draw();

						$scope.departments.splice(pos, 1);

						console.log("delete department succeeded");
					}, function myError(reason) {
						toaster.pop({
							type: 'error',
							title: 'Département supprimé : erreur',
							body: 'Une erreur a eu lieu lors de la suppression du département.',
							timeout: 3000
						});
						console.log("delete department failed");
					});
			}

			function createDepartment(department) {
				DepartmentService.createDepartment(department)
					.then(function mySuccess(response) {
						toaster.pop({
										type: 'success',
						                title: 'Département enregistré',
						                body: 'Le département a bien été créé.',
						                timeout: 3000
						           	});
						console.log("createDepartment succeeded");
					}, function myError(reason) {
						console.log("createDepartment failed");
					});
			}

			function updateDepartment(department) {
				DepartmentService.updateDepartment(department)
					.then(function mySuccess(response) {
						toaster.pop({
										type: 'success',
						                title: 'Département mis à jour',
						                body: 'Le département a bien été mis à jour.',
						                timeout: 3000
						           	});
						console.log("updateDepartment succeeded");
					}, function myError(reason) {
						console.log("updateDepartment failed");
					});
			}

			function editDepartment(department) {
				if (typeof department.id === 'undefined') {
					// If an id exists, we are in creation mode
					createDepartment(department);
				} else {
					// Else, we are in edition mode
					updateDepartment(department);
				}
			}

		}
	]);
})();