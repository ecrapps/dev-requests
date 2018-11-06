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
			 * Manage users routes
			 */
			.when('/users',
				{
					templateUrl: 'templates/Users.html',
					controller: 'UsersController'
				})
			

			/*
			 * Otherwise route
			 */
			.otherwise({redirectTo: '/login'});
});



devRequestApp.constant('URL_REQUEST_API', (function() {
	var url = "http://localhost/dev-requests/public/index.php/dev-requests/";

	return {
		URL_API: url
	}
})());