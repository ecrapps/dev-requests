(function() {
	'use strict';

	devRequestApp.controller('EditRequestController',
		function EditRequestController($rootScope, $scope, $location, $routeParams, RequestService, DepartmentService, StatusService, MainService, toaster, Upload){

			$scope.cancelEdit = cancelEdit;
			$scope.displayCostCenter = displayCostCenter;
			$scope.displayingCostCenter = false;
			$scope.editRequest = editRequest;
		    var ObjWeAreIT = MainService.getWeAreIT($location);
			$scope.weAreIT = ObjWeAreIT.weAreIT;
			$scope.basePath = ObjWeAreIT.basePath;

			// In case of edition of an existing request
			if (typeof $routeParams.idRequest !== 'undefined'){
				RequestService.getRequest($routeParams.idRequest)
					.then(function mySuccess(response) {
							$scope.request = response.data[0];
							$scope.request.projSched1ExpDate = new Date($scope.request.projSched1ExpDate);
							/*$scope.request.projSched2ExpDate = new Date($scope.request.projSched2ExpDate);*/
							$scope.request.projSched3ExpDate = new Date($scope.request.projSched3ExpDate);
							$scope.request.projSched4ExpDate = new Date($scope.request.projSched4ExpDate);
							$scope.request.projSched5ExpDate = new Date($scope.request.projSched5ExpDate);
							$scope.request.projSched6ExpDate = new Date($scope.request.projSched6ExpDate);
							console.log("getRequest succeeded");
						}, function myError(reason) {
							console.log("getRequest failed");
						});
			} else {
			// In case of creation of a new request
				$scope.request = {
					department: {
						id: 10,
					},
					projectName: "a",
					currentSituationDescr: "a",
					currentIssueDescr: "a",
					proposedSolutionDescr: "a",
					benInvY1: 1,
					benCostY1: 1,
					benBenefY1: 1,
					budgetEstimated: 1,
					budgetAvailable: 1,
					projectManager: "a",
					projectManagerBusiness: "a",
					projSched1Business: 1000,
					projSched3Business: 1000,
					projSched4Business: 1000,
					projSched5Business: 1000,
					projSched6Business: 1000,
				};
				$scope.request.projSched1ExpDate = new Date("10/01/2018");
				$scope.request.projSched3ExpDate = new Date("10/01/2018");
				$scope.request.projSched4ExpDate = new Date("10/01/2018");
				$scope.request.projSched5ExpDate = new Date("10/01/2018");
				$scope.request.projSched6ExpDate = new Date("10/01/2018");
				$scope.request.applicant = $rootScope.user;
			}

			// Call to API to get all departements
			DepartmentService.getDepartments()
				.then(function mySuccess(response) {
						$scope.departments = response.data;
					}, function myError(reason) {
						console.log("getDepartements failed");
					});

			// Call to API to get all statuses
			StatusService.getStatuses()
				.then(function mySuccess(response) {
						$scope.statuses = response.data;
					}, function myError(reason) {
						console.log("getStatuses failed");
					});


			////////////////////////////////////////////////////////////////////////////////////////
			function displayCostCenter(idDepartment) {
				DepartmentService.getDepartment(idDepartment)
					.then(function mySuccess(response) {
							$scope.selectedDepartment = response.data[0];
							$scope.displayingCostCenter = true;
						}, function myError(reason) {
							$scope.displayingCostCenter = false;
							console.log("getDepartment failed");
						});
			}

			function createRequest(request) {
				RequestService.createRequest(request)
					.then(function mySuccess(response) {
						toaster.pop({
										type: 'success',
						                title: 'Demande sauvegardée',
						                body: 'Votre demande a bien été sauvegardée.',
						                timeout: 3000
						           	});
						console.log("createRequest succeeded");
					}, function myError(reason) {
						console.log("createRequest failed");
					});
			}

			function updateRequest(request) {
				RequestService.updateRequest(request)
					.then(function mySuccess(response) {
						toaster.pop({
										type: 'success',
						                title: 'Demande mise à jour',
						                body: 'Votre demande a bien été mise à jour.',
						                timeout: 3000
						           	});
						console.log("updateRequest succeeded");
					}, function myError(reason) {
						console.log("updateRequest failed");
					});
			}

			function editRequest(request) {
				if (typeof request.id === 'undefined') {
					// If an id exists, we are in creation mode
					createRequest(request);
				} else {
					// Else, we are in edition mode
					updateRequest(request);
				}
			}

			function cancelEdit(){
				window.location = "index.html";
			}

		}
	);
})();