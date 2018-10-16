<?php
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	//Goper App group route
	$app->group('/dev-requests', function () use ($app) {
	    
		/*
		 * 	LOGN
		 */

	    // Login
		$app->post('/checkLogin', '\LoginController:checkLogin');
	    

		/*
		 * 	REQUESTS
		 */

	    // Get all requests
		$app->get('/getRequests', '\RequestController:getRequests');

		// Get unique request
		$app->get('/getRequest', '\RequestController:getRequest');

		// Add a request
		$app->post('/createRequest', '\RequestController:createRequest');

		// Update a request
		$app->put('/updateRequest', '\RequestController:updateRequest');

		// Delete a request
		$app->delete('/deleteRequest', '\RequestController:deleteRequest');

		// Set a new status to a request
		$app->put('/setRequestStatus', '\RequestController:setRequestStatus');

		// Generate PDF
		$app->get('/generatePDF', '\RequestController:generatePDF');


		/*
		 * 	DEPARTMENTS
		 */

	    // Get all departments
		$app->get('/getDepartments', '\DepartmentController:getDepartments');

		// Get unique department
		$app->get('/getDepartment', '\DepartmentController:getDepartment');

		// Add a department
		$app->post('/createDepartment', '\DepartmentController:createDepartment');

		// Update a department
		$app->put('/updateDepartment', '\DepartmentController:updateDepartment');

		// Delete a department
		$app->delete('/deleteDepartment', '\DepartmentController:deleteDepartment');


		/*
		 * 	STATUSES
		 */

	    // Get all statuses
		$app->get('/getStatuses', '\StatusController:getStatuses');

		// Get unique status
		$app->get('/getStatus', '\StatusController:getStatus');

		// Add a status
		$app->post('/createStatus', '\StatusController:createStatus');

		// Update a status
		$app->put('/updateStatus', '\StatusController:updateStatus');

		// Delete a status
		$app->delete('/deleteStatus', '\StatusController:deleteStatus');


		/*
		 * 	USERS
		 */

	    // Get all users
		$app->get('/getUsers', '\UserController:getUsers');

		// Get unique user
		$app->get('/getUser', '\UserController:getUser');

		// Add a user
		$app->post('/createUser', '\UserController:createUser');

		// Update a user
		$app->put('/updateUser', '\UserController:updateUser');

		// Delete a user
		$app->delete('/deleteUser', '\UserController:deleteUser');


	}); //->add(new IsSessionAliveMiddleware($container));