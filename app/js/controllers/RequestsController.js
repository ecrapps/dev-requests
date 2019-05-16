(function() {
	'use strict';

	devRequestApp.controller('RequestsController', 
		function RequestsController($rootScope, $scope, $location, RequestService, StatusService, MainService, toaster){
			$scope.deleteRequest = deleteRequest;
			$scope.exportDataAsCsv = exportDataAsCsv;
		    $scope.generatePDF = generatePDF;
		    var getRequests = getRequests;
		    var getStatuses = getStatuses;
		    $scope.saveStatus = saveStatus;
			var setRequestStatus = setRequestStatus;
			$scope.statusChanged = [];
			$scope.toggleStatusChanged = toggleStatusChanged;

			// ag-grid data
			$scope.sortReverse = false;
		    $scope.sortType = 'Date de saisie';

		    var columnDefs = [
			   {
			   		headerName:   "", 
			   		width: 		  25, 
			   		suppressFilter: true,
			   		template: 	  "<span><a href='#/getRequests' data-toggle='modal' data-target='#requestDetailsModal' data-request={{data}} title='Voir plus'><i class='fas fa-search-plus'></i></a></span>"
			   },
			   {
				   	headerName:   "Date de saisie", 
				   	width: 		  75, 
				   	field: 		  "dateCreated", 
				   	filter: 	  'agDateColumnFilter', 
				   	template: 	  '{{data.dateCreated | strToDate}}'
			   },
			   {
			   		headerName:   "Demandeur", 
			   		width: 		  150, 
			   		field: 		  "applicant.name", 
			   		filter: 	  'agTextColumnFilter'
			   },
			   {
			   		headerName:   "Département", 
			   		width: 		  125, 
			   		field: 		  "department.name", 
			   		filter: 	  'agTextColumnFilter', 
			   		template: 	  '<span ng-bind="data.department.name"></span>'
			   },
			   {
			   		headerName:   "Intitulé", 
			   		width: 		  250, 
			   		field: 		  "projectName", 
			   		filter: 	  'agTextColumnFilter'
			   },
			   {
			   		headerName:   "Statut", 
			   		width: 		  100, 
			   		field: 		  "status.label", 
			   		filter: 	  'agTextColumnFilter', 
			   		cellRenderer: statusCellRendererFunc
			   },
			   {
			   		headerName:   "", 
			   		width: 		  25, 
			   		suppressFilter: true,
			   		template: 	  "<span ng-if=\"user.groupIsIT || (data.status.id===1 && data.applicant.name===\'"+$rootScope.user.name+"\')\"><a href='#/editRequest/{{data.id}}' title='Modifier'><i class='far fa-edit'></i></a></span>"
			   },
			   {
			   		headerName:   "", 
			   		width: 		  25, 
			   		suppressFilter: true,
			   		template: 	  "<span><a href='#/getRequests' ng-click='generatePDF(data.id)' title='Télécharger le fichier PDF'><i class='far fa-file-pdf'></i></a></span>"
			   },
			   {
			   		headerName:   "", 
			   		width: 		  25, 
			   		suppressFilter: true,
			   		template: 	  "<span><a href='../public/{{data.filePath}}/{{data.addedFile}}' ng-if='data.addedFile' download='{{data.addedFile}}' title='Télécharger la pièce jointe'><i class='fas fa-paperclip'></i></a></span>"
			   },
			   {
			   		headerName:   "", 
			   		width: 		  25, 
			   		suppressFilter: true,
			   		template: 	  "<span ng-if=\"user.groupIsIT || (data.status.id===1 && data.applicant.name===\'"+$rootScope.user.name+"\')\"><a href='#/getRequests/' ng-click='deleteRequest(data.id)' title='Supprimer'><i class='far fa-trash-alt'></i></a></span>"
			   },
			   // Data to export - but not display
			   {
			   		headerName:   "ID", 
			   		width: 		  5, 
			   		field: 		  "id", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Situation actuelle", 
			   		width: 		  5, 
			   		field: 		  "currentSituationDescr", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Problème", 
			   		width: 		  5, 
			   		field: 		  "currentIssueDescr", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Solution proposée", 
			   		width: 		  5, 
			   		field: 		  "proposedSolutionDescr", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Fichier joint", 
			   		width: 		  5, 
			   		field: 		  "addedFile", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Investissements Y1", 
			   		width: 		  5, 
			   		field: 		  "benInvY1", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Coûts Y1", 
			   		width: 		  5, 
			   		field: 		  "benCostY1", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Bénéfice Y1", 
			   		width: 		  5, 
			   		field: 		  "benBenefY1", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Investissements Y2", 
			   		width: 		  5, 
			   		field: 		  "benInvY2", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Coûts Y2", 
			   		width: 		  5, 
			   		field: 		  "benCostY2", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Bénéfice Y2", 
			   		width: 		  5, 
			   		field: 		  "benBenefY2", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Investissements Y3", 
			   		width: 		  5, 
			   		field: 		  "benInvY3", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Coûts Y3", 
			   		width: 		  5, 
			   		field: 		  "benCostY3", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Bénéfice Y3", 
			   		width: 		  5, 
			   		field: 		  "benBenefY3", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Investissements Y4", 
			   		width: 		  5, 
			   		field: 		  "benInvY4", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Coûts Y4", 
			   		width: 		  5, 
			   		field: 		  "benCostY4", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Bénéfice Y4", 
			   		width: 		  5, 
			   		field: 		  "benBenefY4", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Budget Estimé", 
			   		width: 		  5, 
			   		field: 		  "budgetEstimated", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Budget Disponible", 
			   		width: 		  5, 
			   		field: 		  "budgetAvailable", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Sponsor du projet", 
			   		width: 		  5, 
			   		field: 		  "projectManager", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Chef de projet Business", 
			   		width: 		  5, 
			   		field: 		  "projectManagerBusiness", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Chef de projet IT", 
			   		width: 		  5, 
			   		field: 		  "projectManagerIT", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 1 - Charge Business (en h)", 
			   		width: 		  5, 
			   		field: 		  "projSched1Business", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 1 - Expected Delivery Date", 
			   		width: 		  5, 
			   		field: 		  "projSched1ExpDate", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 1 - IT (en h)", 
			   		width: 		  5, 
			   		field: 		  "projSched1IT", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 1 - External (en h)", 
			   		width: 		  5, 
			   		field: 		  "projSched1External", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 1 - Assets (en h)", 
			   		width: 		  5, 
			   		field: 		  "projSched1Assets", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 2 - Charge Business (en h)", 
			   		width: 		  5, 
			   		field: 		  "projSched2Business", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 2 - Expected Delivery Date", 
			   		width: 		  5, 
			   		field: 		  "projSched2ExpDate", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 2 - IT (en h)", 
			   		width: 		  5, 
			   		field: 		  "projSched2IT", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 2 - External (en h)", 
			   		width: 		  5, 
			   		field: 		  "projSched2External", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 2 - Assets (en h)", 
			   		width: 		  5, 
			   		field: 		  "projSched2Assets", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 3 - Charge Business (en h)", 
			   		width: 		  5, 
			   		field: 		  "projSched3Business", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 3 - Expected Delivery Date", 
			   		width: 		  5, 
			   		field: 		  "projSched3ExpDate", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 3 - IT (en h)", 
			   		width: 		  5, 
			   		field: 		  "projSched3IT", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 3 - External (en h)", 
			   		width: 		  5, 
			   		field: 		  "projSched3External", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 3 - Assets (en h)", 
			   		width: 		  5, 
			   		field: 		  "projSched3Assets", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 4 - Charge Business (en h)", 
			   		width: 		  5, 
			   		field: 		  "projSched4Business", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 4 - Expected Delivery Date", 
			   		width: 		  5, 
			   		field: 		  "projSched4ExpDate", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 4 - IT (en h)", 
			   		width: 		  5, 
			   		field: 		  "projSched4IT", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 4 - External (en h)", 
			   		width: 		  5, 
			   		field: 		  "projSched4External", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 4 - Assets (en h)", 
			   		width: 		  5, 
			   		field: 		  "projSched4Assets", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 5 - Charge Business (en h)", 
			   		width: 		  5, 
			   		field: 		  "projSched5Business", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 5 - Expected Delivery Date", 
			   		width: 		  5, 
			   		field: 		  "projSched5ExpDate", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 5 - IT (en h)", 
			   		width: 		  5, 
			   		field: 		  "projSched5IT", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 5 - External (en h)", 
			   		width: 		  5, 
			   		field: 		  "projSched5External", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 5 - Assets (en h)", 
			   		width: 		  5, 
			   		field: 		  "projSched5Assets", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 6 - Charge Business (en h)", 
			   		width: 		  5, 
			   		field: 		  "projSched6Business", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 6 - Expected Delivery Date", 
			   		width: 		  5, 
			   		field: 		  "projSched6ExpDate", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 6 - IT (en h)", 
			   		width: 		  5, 
			   		field: 		  "projSched6IT", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 6 - External (en h)", 
			   		width: 		  5, 
			   		field: 		  "projSched6External", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Etape 6 - Assets (en h)", 
			   		width: 		  5, 
			   		field: 		  "projSched6Assets", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Contraintes", 
			   		width: 		  5, 
			   		field: 		  "constraints", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Type de données collectées", 
			   		width: 		  5, 
			   		field: 		  "rgpdTypeData", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Fianlité de la collecte", 
			   		width: 		  5, 
			   		field: 		  "rgpdFinalite", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Processus lié", 
			   		width: 		  5, 
			   		field: 		  "rgpdProcessus", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Impact", 
			   		width: 		  5, 
			   		field: 		  "rgpdImpact", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Date dernier changement de statut", 
			   		width: 		  5, 
			   		field: 		  "dateNewStatus", 
			   		hide: 		  true
			   },
			   {
			   		headerName:   "Status précédents", 
			   		width: 		  5, 
			   		field: 		  "prevStatuses", 
			   		hide: 		  true
			   }

			];

			$scope.gridOptions = {
		        columnDefs: columnDefs,

		        rowData: null,
		        angularCompileRows: true,
		        enableColResize : false,
		        suppressMovableColumns: true,
		        enableSorting : true,
		        enableFilter : true,
		        pagination : true,
			    onGridReady: function(params) {
			    	//using setTimeout because 
			    	//gridReady gets called before data is bound
		            setTimeout(function(){
		             	params.api.sizeColumnsToFit();
		            }, 1000);
			    }
		    };
		    // end ag-grid data

			getStatuses();
			getRequests();

			function exportDataAsCsv() {
				var params = {
					columnKeys: ['id',
								 'dateCreated',
								 'applicant.name',
								 'department.name',
								 'projectName',
								 'currentSituationDescr',
								 'currentIssueDescr',
								 'proposedSolutionDescr',
								 'addedFile',
								 'benInvY1',
								 'benInvY2',
								 'benInvY3',
								 'benInvY4',
								 'benCostY1',
								 'benCostY2',
								 'benCostY3',
								 'benCostY4',
								 'benBenefY1',
								 'benBenefY2',
								 'benBenefY3',
								 'benBenefY4',
								 'budgetEstimated',
								 'budgetAvailable',
								 'projectManager',
								 'projectManagerBusiness',
								 'projectManagerIT',
								 'projSched1Business',
								 'projSched1ExpDate',
								 'projSched1IT',
								 'projSched1External',
								 'projSched1Assets',
								 'projSched2Business',
								 'projSched2ExpDate',
								 'projSched2IT',
								 'projSched2External',
								 'projSched2Assets',
								 'projSched3Business',
								 'projSched3ExpDate',
								 'projSched3IT',
								 'projSched3External',
								 'projSched3Assets',
								 'projSched4Business',
								 'projSched4ExpDate',
								 'projSched4IT',
								 'projSched4External',
								 'projSched4Assets',
								 'projSched5Business',
								 'projSched5ExpDate',
								 'projSched5IT',
								 'projSched5External',
								 'projSched5Assets',
								 'projSched6Business',
								 'projSched6ExpDate',
								 'projSched6IT',
								 'projSched6External',
								 'projSched6Assets',
								 'constraints',
								 'rgpdTypeData',
								 'rgpdFinalite',
								 'rgpdProcessus',
								 'rgpdImpact',
								 'status.label',
								 'dateNewStatus',
								 'prevStatuses'
								]
			    };

			    $scope.gridOptions.api.exportDataAsCsv(params);
			}

			function deleteRequest(idRequest) {
				RequestService.deleteRequest(idRequest)
					.then(function mySuccess(response) {
						console.log("deleteRequest succeeded");
					}, function myError(reason) {
						console.log("deleteRequest failed");
					});
			}

			function generatePDF(idRequest) {
				RequestService.generatePDF(idRequest)
					.then(function mySuccess(response) {
						var file = new Blob([response.data], {type: "application/pdf"});
						saveAs(file, "download.pdf");
						//console.log("generatePDF succeeded");
					}, function myError(reason) {
						console.log("generatePDF failed");
					});
			}

			function getRequests() {
				RequestService.getRequests()
					.then(function mySuccess(response) {
						console.log("getRequests succeeded");
						$scope.gridOptions.api.setRowData(response.data);
						for (var i = 0; i < response.data.length; i++) {
							$scope.statusChanged[response.data[i].id] = false;
						}
					}, function myError(reason) {
						console.log("getRequests failed");
					});
			}

			function getStatuses() {
				StatusService.getStatuses()
					.then(function mySuccess(response) {
						$scope.statuses = response.data;
					}, function myError(reason) {
						console.log("getStatuses failed");
					});
			}

			function setRequestStatus(idRequest, idStatus) {
				RequestService.setRequestStatus(idRequest, idStatus)
					.then(function mySuccess(response) {
						console.log("setRequestStatus succeeded");
					}, function myError(reason) {
						console.log("setRequestStatus failed");
					});
			}

			function saveStatus(idRequest, idNewStatus) {
				setRequestStatus(idRequest, idNewStatus);
			}

			function toggleStatusChanged(idRequest, state) {
				$scope.statusChanged[idRequest] = state;
			}

			// ag-grid methods
			function statusCellRendererFunc() {
				return "<span ng-if=\"user.groupIsIT\"><select ng-init=\"newStatus.id=data.status.id\" ng-change=\"newStatus.id!=data.status.id ? toggleStatusChanged(data.id, true) : toggleStatusChanged(data.id, false)\" style=\"width:100px\" ng-model=\"newStatus\" ng-options=\"status as status.label disable when (data.status.id === 1 && status.id > 2) for status in statuses track by status.id\"><option value=\"\" disabled>Choisissez un statut</option></select><span ng-if='statusChanged[data.id]'>&nbsp;&nbsp;<a href=\"#/getRequests/\" ng-click=\"saveStatus(data.id, newStatus.id)\"><i class=\"far fa-save\"></i></a></span></span><span ng-show=\"!user.groupIsIT\">{{data.status.label}}</span>";
			}
			// end ag-grid methods
		}
	);
})();