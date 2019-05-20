<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class LoginController {

	private $container;

	public function __construct($container){
		$this->container = $container;
	}
	
	public function checkLogin(Request $request, Response $response, $args){
		$getParsedBody = $request->getParsedBody();
		$datas = new stdClass();
		$datas->params = json_decode(json_encode($getParsedBody), FALSE);
		$datas->params->password = hash('sha256', $datas->params->password);

		$checkLogin = "SELECT id, userName, name, userGroup ";
		$checkLogin .= "FROM users ";
		$checkLogin .= "WHERE userName = :login ";
		$checkLogin .= "AND passwd = :password ";
		$checkLogin .= "AND inactif = 0 ";
		$checkLoginResult = $this->container->db->query($checkLogin, $datas);

		$responseLogin = new stdClass();
		if ($checkLoginResult) {
			$responseLogin->loginSucceed = true;
			$responseLogin->user = new stdClass();
			$responseLogin->user->id = $checkLoginResult[0]['id'];
			$responseLogin->user->name = $checkLoginResult[0]['name'];
			$responseLogin->user->userName = $checkLoginResult[0]['userName'];
			// Vérifier si l'utilisateur est du groupe IT
			if ($checkLoginResult[0]['userGroup'] == "IT")
				$responseLogin->user->groupIsIT = true;
			else
				$responseLogin->user->groupIsIT = false;
		}
		else
			$responseLogin->loginSucceed = false;
		return $response->withStatus(200)
        				->write(json_encode($responseLogin,JSON_NUMERIC_CHECK));
	}

}