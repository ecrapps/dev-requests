<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class StatusController {

	private $container;

	public function __construct($container){
		$this->container = $container;
	}
	
	public function getStatuses(Request $request, Response $response, $args){
		$getStatuses = "SELECT * FROM `statuses`";
		$getStatusesResult = $this->container->db->query($getStatuses);

		return $response->withStatus(200)
        				->write(json_encode($getStatusesResult,JSON_NUMERIC_CHECK));
	}

	public function getStatus(Request $request, Response $response, $args){
		$getQueryParams = $request->getQueryParams();
		$datas = new stdClass();
		$datas->params = json_decode(json_encode($getQueryParams), FALSE);

		$getStatus = "SELECT * 
						   FROM `statuses` 
						   WHERE `statuses` . `id` = :idStatus";
		$getStatusResult = $this->container->db->query($getStatus, $datas);

		return $response->withStatus(200)
        				->write(json_encode($getStatusResult,JSON_NUMERIC_CHECK));
	}

	public function createStatus(Request $request, Response $response, $args){
		$getParsedBody = $request->getParsedBody();
		$datas = new stdClass();
		$datas->params = json_decode(json_encode($getParsedBody), FALSE);

		$createStatus = "INSERT INTO `statuses` (`label`) 
									VALUES (:label)";
		$createStatusResult = $this->container->db->query($createStatus, $datas);
		
		return $response->withStatus(200)
        				->write(json_encode($createStatusResult,JSON_NUMERIC_CHECK));
	}

	public function updateStatus(Request $request, Response $response, $args){
		$getParsedBody = $request->getParsedBody();
		$datas = new stdClass();
		$datas->params = json_decode(json_encode($getParsedBody), FALSE);

		$updateStatus = "UPDATE `statuses` SET `label` = :label 
								WHERE `statuses`.`id` = :idStatus";
		$updateStatusResult = $this->container->db->query($updateStatus, $datas);

		return $response->withStatus(200)
        				->write(json_encode($updateStatusResult,JSON_NUMERIC_CHECK));
	}

	public function deleteStatus(Request $request, Response $response, $args){
		$getQueryParams = $request->getQueryParams();
		$datas = new stdClass();
		$datas->params = json_decode(json_encode($getQueryParams), FALSE);

		$deleteStatus = "DELETE FROM `statuses` 
								WHERE `statuses` . `id` = :idStatus";
		$deleteStatusResult = $this->container->db->query($deleteStatus, $datas);

		return $response->withStatus(200)
        				->write(json_encode($deleteStatusResult,JSON_NUMERIC_CHECK));
	}

}