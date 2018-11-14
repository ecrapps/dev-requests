<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class UserController {

	private $container;

	public function __construct($container){
		$this->container = $container;
	}
	
	public function getUsers(Request $request, Response $response, $args){
		$getUsers = "SELECT `id`, 
							`userName`, 
							`name`, 
							`userGroup` 
						FROM `users` 
						ORDER BY `name` ASC";
		$getUsersResult = $this->container->db->query($getUsers);

		return $response->withStatus(200)
        				->write(json_encode($getUsersResult,JSON_NUMERIC_CHECK));
	}

	public function getUser(Request $request, Response $response, $args){
		$getQueryParams = $request->getQueryParams();
		$datas = new stdClass();
		$datas->params = json_decode(json_encode($getQueryParams), FALSE);

		$getUser = "SELECT * 
					   FROM `users` 
					   WHERE `users` . `id` = :idUser";
		$getUserResult = $this->container->db->query($getUser, $datas);

		return $response->withStatus(200)
        				->write(json_encode($getUserResult,JSON_NUMERIC_CHECK));
	}

	public function createUser(Request $request, Response $response, $args){
		$getParsedBody = $request->getParsedBody();
		$datas = new stdClass();
		$datas->params = json_decode(json_encode($getParsedBody), FALSE);

		$createUser = "INSERT INTO `users` (`name`, 
											`userName`, 
											`passwd`, 
											`userGroup`) 
								VALUES (:name, 
										:userName, 
										:passwd, 
										:userGroup)";
		$createUserResult = $this->container->db->query($createUser, $datas);
		
		return $response->withStatus(200)
        				->write(json_encode($createUserResult,JSON_NUMERIC_CHECK));
	}

	public function updateUser(Request $request, Response $response, $args){
		$getParsedBody = $request->getParsedBody();
		$datas = new stdClass();
		$datas->params = json_decode(json_encode($getParsedBody), FALSE);

		$updateUser = "UPDATE `users` SET `name` = :name, 
										  `userName` = :userName, 
										  `passwd` = :passwd, 
										  `userGroup` = :userGroup 
						WHERE `users`.`id` = :idUser";
		$updateUserResult = $this->container->db->query($updateUser, $datas);

		return $response->withStatus(200)
        				->write(json_encode($updateUserResult,JSON_NUMERIC_CHECK));
	}

	public function deleteUser(Request $request, Response $response, $args){
		$getQueryParams = $request->getQueryParams();
		$datas = new stdClass();
		$datas->params = json_decode(json_encode($getQueryParams), FALSE);

		$deleteUser = "DELETE FROM `users` 
							WHERE `users` . `id` = :idUser";
		$deleteUserResult = $this->container->db->query($deleteUser, $datas);

		return $response->withStatus(200)
        				->write(json_encode($deleteUserResult,JSON_NUMERIC_CHECK));
	}

}