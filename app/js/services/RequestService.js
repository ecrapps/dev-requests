(function() {
    'use strict';

	devRequestApp.factory('RequestService', ['$http', 'URL_REQUEST_API', 'Upload', function($http, URL_REQUEST_API, Upload) {
		
		var url_api = URL_REQUEST_API.URL_API;
		var factory = {};

		factory.createRequest = function(request) {
			if (typeof(request.benInvY2) === 'undefined') { request.benInvY2 = 0; };
			if (typeof(request.benInvY3) === 'undefined') { request.benInvY3 = 0; };
			if (typeof(request.benInvY4) === 'undefined') { request.benInvY4 = 0; };
			if (typeof(request.benCostY2) === 'undefined') { request.benCostY2 = 0; };
			if (typeof(request.benCostY3) === 'undefined') { request.benCostY3 = 0; };
			if (typeof(request.benCostY4) === 'undefined') { request.benCostY4 = 0; };
			if (typeof(request.benBenefY2) === 'undefined') { request.benBenefY2 = 0; };
			if (typeof(request.benBenefY3) === 'undefined') { request.benBenefY3 = 0; };
			if (typeof(request.benBenefY4) === 'undefined') { request.benBenefY4 = 0; };
			if (typeof(request.projectManagerIT) === 'undefined') { request.projectManagerIT = ""; };
			if (typeof(request.projSched1IT) === 'undefined') { request.projSched1IT = 0; };
			/*if (typeof(request.projSched2IT) === 'undefined') { request.projSched2IT = 0; };*/
			if (typeof(request.projSched3IT) === 'undefined') { request.projSched3IT = 0; };
			if (typeof(request.projSched4IT) === 'undefined') { request.projSched4IT = 0; };
			if (typeof(request.projSched5IT) === 'undefined') { request.projSched5IT = 0; };
			if (typeof(request.projSched6IT) === 'undefined') { request.projSched6IT = 0; };
			if (typeof(request.projSched1External) === 'undefined') { request.projSched1External = 0; };
			/*if (typeof(request.projSched2External) === 'undefined') { request.projSched2External = 0; };*/
			if (typeof(request.projSched3External) === 'undefined') { request.projSched3External = 0; };
			if (typeof(request.projSched4External) === 'undefined') { request.projSched4External = 0; };
			if (typeof(request.projSched5External) === 'undefined') { request.projSched5External = 0; };
			if (typeof(request.projSched6External) === 'undefined') { request.projSched6External = 0; };
			if (typeof(request.projSched1Assets) === 'undefined') { request.projSched1Assets = 0; };
			/*if (typeof(request.projSched2Assets) === 'undefined') { request.projSched2Assets = 0; };*/
			if (typeof(request.projSched3Assets) === 'undefined') { request.projSched3Assets = 0; };
			if (typeof(request.projSched4Assets) === 'undefined') { request.projSched4Assets = 0; };
			if (typeof(request.projSched5Assets) === 'undefined') { request.projSched5Assets = 0; };
			if (typeof(request.projSched6Assets) === 'undefined') { request.projSched6Assets = 0; };
			if (typeof(request.constraints) === 'undefined') { request.constraints = ""; };
			if (typeof(request.rgpdTypeData) === 'undefined') { request.rgpdTypeData = ""; };
			if (typeof(request.rgpdFinalite) === 'undefined') { request.rgpdFinalite = ""; };
			if (typeof(request.rgpdProcessus) === 'undefined') { request.rgpdProcessus = ""; };
			if (typeof(request.rgpdImpact) === 'undefined') { request.rgpdImpact = ""; };
			var d1 = new Date(request.projSched1ExpDate) ;
			d1.setTime( d1.getTime() - d1.getTimezoneOffset()*60*1000 );
			var d3 = new Date(request.projSched3ExpDate) ;
			d3.setTime( d3.getTime() - d3.getTimezoneOffset()*60*1000 );
			var d4 = new Date(request.projSched4ExpDate) ;
			d4.setTime( d4.getTime() - d4.getTimezoneOffset()*60*1000 );
			var d5 = new Date(request.projSched5ExpDate) ;
			d5.setTime( d5.getTime() - d5.getTimezoneOffset()*60*1000 );
			var d6 = new Date(request.projSched6ExpDate) ;
			d6.setTime( d6.getTime() - d6.getTimezoneOffset()*60*1000 );

			var data = {
							idApplicant: request.applicant.id,
							idDepartment: request.department.id,
							projectName: request.projectName,
							currentSituationDescr: request.currentSituationDescr,
							currentIssueDescr: request.currentIssueDescr,
							proposedSolutionDescr: request.proposedSolutionDescr,
							benInvY1: request.benInvY1,
							benInvY2: request.benInvY2,
							benInvY3: request.benInvY3,
							benInvY4: request.benInvY4,
							benCostY1: request.benCostY1,
							benCostY2: request.benCostY2,
							benCostY3: request.benCostY3,
							benCostY4: request.benCostY4,
							benBenefY1: request.benBenefY1,
							benBenefY2: request.benBenefY2,
							benBenefY3: request.benBenefY3,
							benBenefY4: request.benBenefY4,
							budgetEstimated: request.budgetEstimated,
							budgetAvailable: request.budgetAvailable,
							projectManager: request.projectManager,
							projectManagerBusiness: request.projectManagerBusiness,
							projectManagerIT: request.projectManagerIT,
							projSched1Business: request.projSched1Business,
							projSched1ExpDate: d1,
							projSched1IT: request.projSched1IT,
							projSched1External: request.projSched1External,
							projSched1Assets: request.projSched1Assets,
							projSched3Business: request.projSched3Business,
							projSched3ExpDate: d3,
							projSched3IT: request.projSched3IT,
							projSched3External: request.projSched3External,
							projSched3Assets: request.projSched3Assets,
							projSched4Business: request.projSched4Business,
							projSched4ExpDate: d4,
							projSched4IT: request.projSched4IT,
							projSched4External: request.projSched4External,
							projSched4Assets: request.projSched4Assets,
							projSched5Business: request.projSched5Business,
							projSched5ExpDate: d5,
							projSched5IT: request.projSched5IT,
							projSched5External: request.projSched5External,
							projSched5Assets: request.projSched5Assets,
							projSched6Business: request.projSched6Business,
							projSched6ExpDate: d6,
							projSched6IT: request.projSched6IT,
							projSched6External: request.projSched6External,
							projSched6Assets: request.projSched6Assets,
							constraints: request.constraints,
							rgpdTypeData: request.rgpdTypeData,
							rgpdFinalite: request.rgpdFinalite,
							rgpdProcessus: request.rgpdProcessus,
							rgpdImpact: request.rgpdImpact,
							idStatus: 1
						}

			return Upload.upload({
				method: "POST",
				url: url_api + "createRequest",
				data: data,
				file: request.addedFile
			});
		}

		factory.updateRequest = function(request) {
			var d1 = new Date(request.projSched1ExpDate) ;
			d1.setTime( d1.getTime() - d1.getTimezoneOffset()*60*1000 );
			var d3 = new Date(request.projSched3ExpDate) ;
			d3.setTime( d3.getTime() - d3.getTimezoneOffset()*60*1000 );
			var d4 = new Date(request.projSched4ExpDate) ;
			d4.setTime( d4.getTime() - d4.getTimezoneOffset()*60*1000 );
			var d5 = new Date(request.projSched5ExpDate) ;
			d5.setTime( d5.getTime() - d5.getTimezoneOffset()*60*1000 );
			var d6 = new Date(request.projSched6ExpDate) ;
			d6.setTime( d6.getTime() - d6.getTimezoneOffset()*60*1000 );

			var data = {
							idRequest: request.id,
							idApplicant: request.applicant.id,
							idDepartment: request.department.id,
							projectName: request.projectName,
							currentSituationDescr: request.currentSituationDescr,
							currentIssueDescr: request.currentIssueDescr,
							proposedSolutionDescr: request.proposedSolutionDescr,
							benInvY1: request.benInvY1,
							benInvY2: request.benInvY2,
							benInvY3: request.benInvY3,
							benInvY4: request.benInvY4,
							benCostY1: request.benCostY1,
							benCostY2: request.benCostY2,
							benCostY3: request.benCostY3,
							benCostY4: request.benCostY4,
							benBenefY1: request.benBenefY1,
							benBenefY2: request.benBenefY2,
							benBenefY3: request.benBenefY3,
							benBenefY4: request.benBenefY4,
							budgetEstimated: request.budgetEstimated,
							budgetAvailable: request.budgetAvailable,
							projectManager: request.projectManager,
							projectManagerBusiness: request.projectManagerBusiness,
							projectManagerIT: request.projectManagerIT,
							projSched1Business: request.projSched1Business,
							projSched1ExpDate: d1,
							projSched1IT: request.projSched1IT,
							projSched1External: request.projSched1External,
							projSched1Assets: request.projSched1Assets,
							projSched3Business: request.projSched3Business,
							projSched3ExpDate: d3,
							projSched3IT: request.projSched3IT,
							projSched3External: request.projSched3External,
							projSched3Assets: request.projSched3Assets,
							projSched4Business: request.projSched4Business,
							projSched4ExpDate: d4,
							projSched4IT: request.projSched4IT,
							projSched4External: request.projSched4External,
							projSched4Assets: request.projSched4Assets,
							projSched5Business: request.projSched5Business,
							projSched5ExpDate: d5,
							projSched5IT: request.projSched5IT,
							projSched5External: request.projSched5External,
							projSched5Assets: request.projSched5Assets,
							projSched6Business: request.projSched6Business,
							projSched6ExpDate: d6,
							projSched6IT: request.projSched6IT,
							projSched6External: request.projSched6External,
							projSched6Assets: request.projSched6Assets,
							constraints: request.constraints,
							rgpdTypeData: request.rgpdTypeData,
							rgpdFinalite: request.rgpdFinalite,
							rgpdProcessus: request.rgpdProcessus,
							rgpdImpact: request.rgpdImpact,
						}
			
			return Upload.upload({
				method: "POST",
				url: url_api + "updateRequest",
				data: data,
				file: request.addedFile
			});
		}

		factory.getRequests = function() {
			return $http({
	        	method : "GET",
	        	url : url_api + "getRequests" 
		    });
		}

		factory.getRequest = function(idRequest) {
			var data = {
				idRequest : idRequest
			}

			return $http({
	        	method : "GET",
	        	url : url_api + "getRequest" ,
	        	params : data
		    });
		}

		factory.deleteRequest = function(idRequest) {
			var data = {
				idRequest : idRequest
			}

			return $http({
	        	method : "DELETE",
	        	url : url_api + "deleteRequest" ,
	        	params : data
		    });
		}

		factory.setRequestStatus = function(idRequest, idNewStatus) {
			var data = {
				idRequest : idRequest,
				idNewStatus : idNewStatus
			}

			return $http({
	        	method : "PUT",
	        	url : url_api + "setRequestStatus" ,
	        	data : data
		    });
		}

		factory.generatePDF = function(idRequest) {
			var data = {
				idRequest : idRequest
			}

			return $http({
	        	method : "GET",
	        	url : url_api + "generatePDF" ,
	        	params : data,
	        	responseType: 'blob'
		    });
		}

		return factory;
	}]);
})();