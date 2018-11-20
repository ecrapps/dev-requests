<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class DepartmentController {

	private $container;

	public function __construct($container){
		$this->container = $container;
	}
	
	public function getDepartments(Request $request, Response $response, $args){
		$getDepartments = "SELECT * FROM `departments`";
		$getDepartmentsResult = $this->container->db->query($getDepartments);

		return $response->withStatus(200)
        				->write(json_encode($getDepartmentsResult,JSON_NUMERIC_CHECK));
	}

	public function getDepartment(Request $request, Response $response, $args){
		$getQueryParams = $request->getQueryParams();
		$datas = new stdClass();
		$datas->params = json_decode(json_encode($getQueryParams), FALSE);

		$getDepartment = "SELECT * 
						   FROM `departments` 
						   WHERE `departments` . `id` = :idDepartment";
		$getDepartmentResult = $this->container->db->query($getDepartment, $datas);

		return $response->withStatus(200)
        				->write(json_encode($getDepartmentResult,JSON_NUMERIC_CHECK));
	}

	public function createDepartment(Request $request, Response $response, $args){
		$getParsedBody = $request->getParsedBody();
		$datas = new stdClass();
		$datas->params = json_decode(json_encode($getParsedBody), FALSE);

		$createDepartment = "INSERT INTO `departments` (`name`, 
														  `costCenter`) 
									VALUES (:name, 
											:costCenter)";
		$createDepartmentResult = $this->container->db->query($createDepartment, $datas);
		
		return $response->withStatus(200)
        				->write(json_encode($createDepartmentResult,JSON_NUMERIC_CHECK));
	}

	public function updateDepartment(Request $request, Response $response, $args){
		$getParsedBody = $request->getParsedBody();
		$datas = new stdClass();
		$datas->params = json_decode(json_encode($getParsedBody), FALSE);

		$updateDepartment = "UPDATE `departments` SET `name` = :name, 
														`costCenter` = :costCenter 
								WHERE `departments`.`id` = :idDepartment";
		$updateDepartmentResult = $this->container->db->query($updateDepartment, $datas);

		return $response->withStatus(200)
        				->write(json_encode($updateDepartmentResult,JSON_NUMERIC_CHECK));
	}

	public function deleteDepartment(Request $request, Response $response, $args){
		$getQueryParams = $request->getQueryParams();
		$datas = new stdClass();
		$datas->params = json_decode(json_encode($getQueryParams), FALSE);

		$deleteDepartment = "DELETE FROM `departments` 
								WHERE `departments` . `id` = :idDepartment";
		$deleteDepartmentResult = $this->container->db->query($deleteDepartment, $datas);

		return $response->withStatus(200)
        				->write(json_encode($deleteDepartmentResult,JSON_NUMERIC_CHECK));
	}

}