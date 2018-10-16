<?php
	
	ini_set('display_errors',1);

	require '../vendor/autoload.php';

	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	// db configuration
	require __DIR__ . '/settings/settings.php';
	$app = new \Slim\App(["settings" => $config]);

	//Setting content type header
	$app->add(function ($req, $res, $next) {
	    $response = $next($req, $res);
	    return $response
	            ->withHeader('Access-Control-Allow-Origin', '*')
	            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
	            ->withHeader('Content-Type', 'application/json')
	            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
	});

	//Adding dependencies
	require __DIR__ . '/dependencies/dependencies.php';

	// devrequests app
	require __DIR__ . '/api/controllers/RequestController.php';
	require __DIR__ . '/api/controllers/DepartmentController.php';
	require __DIR__ . '/api/controllers/StatusController.php';
	require __DIR__ . '/api/controllers/LoginController.php';
	require __DIR__ . '/api/controllers/UserController.php';
	require __DIR__ . '/api/routes.php';

	$app->run();