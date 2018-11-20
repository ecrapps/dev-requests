'use strict';

agGrid.initialiseAgGridWithAngular1(angular);

var devRequestApp = angular.module('devRequestApp', ['ngRoute', 'agGrid', 'toaster', 'ngAnimate', 'ngFileUpload'])
	.config(function($routeProvider){
		$routeProvider
			/*
			 * Login
			 */
			.when('/',
				{
					templateUrl: 'templates/Login.html',
					controller: 'LoginController'
				})
			.when('/login',
				{
					templateUrl: 'templates/Login.html',
					controller: 'LoginController'
				})
			.when('/logout',
				{
					templateUrl: 'templates/Login.html',
					controller: 'LogoutController'
				})

			/*
			 * Guest user routes
			 */
			.when('/editRequest',
				{
					templateUrl: 'templates/EditRequest.html',
					controller: 'EditRequestController'
				})
			.when('/editRequest/:idRequest',
				{
					templateUrl: 'templates/EditRequest.html',
					controller: 'EditRequestController'
				})
			.when('/getRequests',
				{
					templateUrl: 'templates/Requests.html',
					controller: 'RequestsController'
				})
			.when('/deleteRequest/:idRequest',
				{
					templateUrl: 'templates/Requests.html',
					controller: 'RequestsController'
				})

			/*
			 * Admin routes
			 */
			.when('/admin/user',
				{
					templateUrl: 'templates/admin/user/User.html',
					controller: 'UserController'
				})
			.when('/admin/user/editUser',
				{
					templateUrl: 'templates/admin/user/EditUser.html',
					controller: 'UserController'
				})
			.when('/admin/user/editUser/:idUser',
				{
					templateUrl: 'templates/admin/user/EditUser.html',
					controller: 'UserController'
				})
			.when('/admin/department',
				{
					templateUrl: 'templates/admin/department/Department.html',
					controller: 'DepartmentController'
				})
			.when('/admin/department/editDepartment',
				{
					templateUrl: 'templates/admin/department/EditDepartment.html',
					controller: 'DepartmentController'
				})
			.when('/admin/department/editDepartment/:idDepartment',
				{
					templateUrl: 'templates/admin/department/EditDepartment.html',
					controller: 'DepartmentController'
				})
			.when('/admin/status',
				{
					templateUrl: 'templates/admin/status/Status.html',
					controller: 'StatusController'
				})
			.when('/admin/status/editStatus',
				{
					templateUrl: 'templates/admin/status/EditStatus.html',
					controller: 'StatusController'
				})
			.when('/admin/status/editStatus/:idStatus',
				{
					templateUrl: 'templates/admin/status/EditStatus.html',
					controller: 'StatusController'
				})
			

			/*
			 * Otherwise route
			 */
			.otherwise({redirectTo: '/login'});
});



devRequestApp.constant('URL_REQUEST_API', (function() {
	var url = "http://localhost/dev-requests/public/index.php/dev-requests/";//"http://vz26824.iservices.db.de/dev-requests/public/index.php/dev-requests/";

	return {
		URL_API: url
	}
})());