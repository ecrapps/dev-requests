<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class RequestController {

	private $container;

	public function __construct($container){
		$this->container = $container;
	}
	
	public function getRequests(Request $request, Response $response, $args){
		$getRequests = "SELECT *
		 				FROM `requests` 
		 				ORDER BY `dateCreated` DESC";
		$getRequestsResult = $this->container->db->query($getRequests);

		for ($i=0 ; $i<sizeof($getRequestsResult) ; $i++) {
			// Add department info to each element
			$getRequestDepartment = "SELECT `id`, 
											`name`, 
											`costCenter` 
									 FROM `departments` 
									 WHERE `id` = '".$getRequestsResult[$i]['idDepartment']."' 
									 ORDER BY `name` ASC";
			$getRequestDepartmentsResult = $this->container->db->query($getRequestDepartment);

			if (sizeof($getRequestDepartmentsResult) > 0) {
				$getRequestsResult[$i]['department'] = $getRequestDepartmentsResult[0];
			}

			// Add status information to each element
			$getRequestStatus = "SELECT `id`, 
										`label` 
								 FROM `statuses` 
								 WHERE `id` = '".$getRequestsResult[$i]['idStatus']."' 
								 ORDER BY `label` ASC";
			$getRequestStatusResult = $this->container->db->query($getRequestStatus);

			if (sizeof($getRequestStatusResult) > 0) {
				$getRequestsResult[$i]['status'] = $getRequestStatusResult[0];
			}

			// Add applicant information to each element
			$getRequestApplicant = "SELECT  `id`, 
											`userName`, 
											`name`, 
											`userGroup` 
									FROM `users` 
									WHERE `id` = '".$getRequestsResult[$i]['idApplicant']."'";
			$getRequestApplicantResult = $this->container->db->query($getRequestApplicant);

			if (sizeof($getRequestApplicantResult) > 0) {
				$getRequestsResult[$i]['applicant'] = $getRequestApplicantResult[0];
			}
		}

		return $response->withStatus(200)
        				->write(json_encode($getRequestsResult,JSON_NUMERIC_CHECK));
	}

	public function getRequest(Request $request, Response $response, $args){
		$getQueryParams = $request->getQueryParams();
		$datas = new stdClass();
		$datas->params = json_decode(json_encode($getQueryParams), FALSE);

		$getRequest = "SELECT * 
					   FROM `requests` 
					   WHERE `requests` . `id` = :idRequest";
		$getRequestResult = $this->container->db->query($getRequest, $datas);

		// Add department info to this element
		$getRequestDepartment = "SELECT * 
								 FROM `departments` 
								 WHERE `id` = '".$getRequestResult[0]['idDepartment']."' 
								 ORDER BY `name` ASC";
		$getRequestDepartmentsResult = $this->container->db->query($getRequestDepartment);

		if (sizeof($getRequestDepartmentsResult) > 0) {
			$getRequestResult[0]['department'] = $getRequestDepartmentsResult[0];
		}

		// Add status information to this element
		$getRequestStatus = "SELECT * 
							 FROM `statuses` 
							 WHERE `id` = '".$getRequestResult[0]['idStatus']."' 
							 ORDER BY `label` ASC";
		$getRequestStatusResult = $this->container->db->query($getRequestStatus);

		if (sizeof($getRequestStatusResult) > 0) {
			$getRequestResult[0]['status'] = $getRequestStatusResult[0];
		}

		// Add applicant information to this element
		$getRequestApplicant = "SELECT  `id`, 
										`userName`, 
										`name`, 
										`userGroup` 
								FROM `users` 
								WHERE `id` = '".$getRequestResult[0]['idApplicant']."'";
		$getRequestApplicantResult = $this->container->db->query($getRequestApplicant);

		if (sizeof($getRequestApplicantResult) > 0) {
			$getRequestResult[0]['applicant'] = $getRequestApplicantResult[0];
		}

		return $response->withStatus(200)
        				->write(json_encode($getRequestResult,JSON_NUMERIC_CHECK));
	}

	public function createRequest(Request $request, Response $response, $args){
		$getParsedBody = $request->getParsedBody();
		$datas = new stdClass();
		$datas->params = json_decode(json_encode($getParsedBody), FALSE);

		$idApplicant = filter_var($getParsedBody['idApplicant'], FILTER_SANITIZE_STRING);
		$getApplicant = "SELECT `userName`  FROM `users` WHERE `id` = '".$idApplicant."'";
		$getApplicantResult = $this->container->db->query($getApplicant, $datas);

		$applicantUsername = $getApplicantResult[0]['userName'];
		$annee = date("Y");

		$uploadFolder = 'uploads/'.$annee.'/'.$applicantUsername;

		if (!file_exists($uploadFolder)) {
			mkdir($uploadFolder, 0777, true);
		}

		if (isset($_FILES['file'])) {
			$filename = $uploadFolder.'/'.$_FILES['file']['name'];
			$fileUploadResult = move_uploaded_file($_FILES['file']['tmp_name'], $filename);
		}
		else {
			$filename = "";
			$fileUploadResult = true;
		}

		$createRequest = "INSERT INTO `requests`  (`idApplicant`,
												   `idDepartment`,
												   `projectName`,
												   `currentSituationDescr`,
												   `currentIssueDescr`,
												   `proposedSolutionDescr`,
												   `addedFile`,
												   `benInvY1`,
												   `benInvY2`,
												   `benInvY3`,
												   `benInvY4`,
												   `benCostY1`,
												   `benCostY2`,
												   `benCostY3`,
												   `benCostY4`,
												   `benBenefY1`,
												   `benBenefY2`,
												   `benBenefY3`,
												   `benBenefY4`,
												   `budgetEstimated`,
												   `budgetAvailable`,
												   `projectManager`,
												   `projectManagerBusiness`,
												   `projectManagerIT`,
												   `projSched1Business`,
												   `projSched1ExpDate`,
												   `projSched1IT`,
												   `projSched1External`,
												   `projSched1Assets`,
												   `projSched3Business`,
												   `projSched3ExpDate`,
												   `projSched3IT`,
												   `projSched3External`,
												   `projSched3Assets`,
												   `projSched4Business`,
												   `projSched4ExpDate`,
												   `projSched4IT`,
												   `projSched4External`,
												   `projSched4Assets`,
												   `projSched5Business`,
												   `projSched5ExpDate`,
												   `projSched5IT`,
												   `projSched5External`,
												   `projSched5Assets`,
												   `projSched6Business`,
												   `projSched6ExpDate`,
												   `projSched6IT`,
												   `projSched6External`,
												   `projSched6Assets`,
												   `constraints`,
												   `idStatus`,
												   `dateNewStatus`) 
						   VALUES (:idApplicant,
								   :idDepartment,
								   :projectName,
								   :currentSituationDescr,
								   :currentIssueDescr,
								   :proposedSolutionDescr,
								   '".$filename."',
								   :benInvY1,
								   :benInvY2,
								   :benInvY3,
								   :benInvY4,
								   :benCostY1,
								   :benCostY2,
								   :benCostY3,
								   :benCostY4,
								   :benBenefY1,
								   :benBenefY2,
								   :benBenefY3,
								   :benBenefY4,
								   :budgetEstimated,
								   :budgetAvailable,
								   :projectManager,
								   :projectManagerBusiness,
								   :projectManagerIT,
								   :projSched1Business,
								   :projSched1ExpDate,
								   :projSched1IT,
								   :projSched1External,
								   :projSched1Assets,
								   :projSched3Business,
								   :projSched3ExpDate,
								   :projSched3IT,
								   :projSched3External,
								   :projSched3Assets,
								   :projSched4Business,
								   :projSched4ExpDate,
								   :projSched4IT,
								   :projSched4External,
								   :projSched4Assets,
								   :projSched5Business,
								   :projSched5ExpDate,
								   :projSched5IT,
								   :projSched5External,
								   :projSched5Assets,
								   :projSched6Business,
								   :projSched6ExpDate,
								   :projSched6IT,
								   :projSched6External,
								   :projSched6Assets,
								   :constraints,
								   :idStatus,
								   NOW())";
		$createRequestResult = $this->container->db->query($createRequest, $datas);
		
		return $response->withStatus(200)
        				->write(json_encode($createRequestResult && $fileUploadResult,JSON_NUMERIC_CHECK));
	}

	public function updateRequest(Request $request, Response $response, $args){
		$getParsedBody = $request->getParsedBody();
		$datas = new stdClass();
		$datas->params = json_decode(json_encode($getParsedBody), FALSE);

		$idApplicant = filter_var($getParsedBody['idApplicant'], FILTER_SANITIZE_STRING);
		$getApplicant = "SELECT `userName`  FROM `users` WHERE `id` = '".$idApplicant."'";
		$getApplicantResult = $this->container->db->query($getApplicant, $datas);

		$applicantUsername = $getApplicantResult[0]['userName'];
		$annee = date("Y");

		$uploadFolder = 'uploads/'.$annee.'/'.$applicantUsername;

		if (!file_exists($uploadFolder)) {
			mkdir($uploadFolder, 0777, true);
		}

		if (isset($_FILES['file'])) {
			$filename = $uploadFolder.'/'.$_FILES['file']['name'];
			$fileUploadResult = move_uploaded_file($_FILES['file']['tmp_name'], $filename);
		}
		else {
			$filename = "";
			$fileUploadResult = true;
		}

		$updateRequest = "UPDATE `requests` SET    `idApplicant` = :idApplicant,
												   `idDepartment` = :idDepartment,
												   `projectName` = :projectName,
												   `currentSituationDescr` = :currentSituationDescr,
												   `currentIssueDescr` = :currentIssueDescr,
												   `proposedSolutionDescr` = :proposedSolutionDescr,
												   `addedFile` = '".$filename."',
												   `benInvY1` = :benInvY1,
												   `benInvY2` = :benInvY2,
												   `benInvY3` = :benInvY3,
												   `benInvY4` = :benInvY4,
												   `benCostY1` = :benCostY1,
												   `benCostY2` = :benCostY2,
												   `benCostY3` = :benCostY3,
												   `benCostY4` = :benCostY4,
												   `benBenefY1` = :benBenefY1,
												   `benBenefY2` = :benBenefY2,
												   `benBenefY3` = :benBenefY3,
												   `benBenefY4` = :benBenefY4,
												   `budgetEstimated` = :budgetEstimated,
												   `budgetAvailable` = :budgetAvailable,
												   `projectManager` = :projectManager,
												   `projectManagerBusiness` = :projectManagerBusiness,
												   `projectManagerIT` = :projectManagerIT,
												   `projSched1Business` = :projSched1Business,
												   `projSched1ExpDate` = :projSched1ExpDate,
												   `projSched1IT` = :projSched1IT,
												   `projSched1External` = :projSched1External,
												   `projSched1Assets` = :projSched1Assets,
												   `projSched3Business` = :projSched3Business,
												   `projSched3ExpDate` = :projSched3ExpDate,
												   `projSched3IT` = :projSched3IT,
												   `projSched3External` = :projSched3External,
												   `projSched3Assets` = :projSched3Assets,
												   `projSched4Business` = :projSched4Business,
												   `projSched4ExpDate` = :projSched4ExpDate,
												   `projSched4IT` = :projSched4IT,
												   `projSched4External` = :projSched4External,
												   `projSched4Assets` = :projSched4Assets,
												   `projSched5Business` = :projSched5Business,
												   `projSched5ExpDate` = :projSched5ExpDate,
												   `projSched5IT` = :projSched5IT,
												   `projSched5External` = :projSched5External,
												   `projSched5Assets` = :projSched5Assets,
												   `projSched6Business` = :projSched6Business,
												   `projSched6ExpDate` = :projSched6ExpDate,
												   `projSched6IT` = :projSched6IT,
												   `projSched6External` = :projSched6External,
												   `projSched6Assets` = :projSched6Assets,
												   `constraints` = :constraints 
							WHERE `requests`.`id` = :idRequest";
		$updateRequestResult = $this->container->db->query($updateRequest, $datas);

		return $response->withStatus(200)
						->write(json_encode($updateRequestResult && $fileUploadResult,JSON_NUMERIC_CHECK));
	}

	public function deleteRequest(Request $request, Response $response, $args){
		$getQueryParams = $request->getQueryParams();
		$datas = new stdClass();
		$datas->params = json_decode(json_encode($getQueryParams), FALSE);

		$deleteRequest = "DELETE FROM `requests` WHERE `requests` . `id` = :idRequest";
		$deleteRequestResult = $this->container->db->query($deleteRequest, $datas);

		return $response->withStatus(200)
        				->write(json_encode($deleteRequestResult,JSON_NUMERIC_CHECK));
	}

	public function setRequestStatus(Request $request, Response $response, $args){
		$getParsedBody = $request->getParsedBody();
		$datas = new stdClass();
		$datas->params = json_decode(json_encode($getParsedBody), FALSE);

		// Récupérer le status actuel
		// Récupérer l'historique des status
		$getRequestStatus = "SELECT r.`idStatus`, 
									s.`label` AS statusLabel, 
									r.`dateNewStatus`, 
									r.`prevStatuses` 
								FROM `requests` AS r 
								LEFT JOIN `statuses` AS s ON r.`idStatus` = s.`id` 
								WHERE r.`id` = ".$getParsedBody['idRequest'];
		$getRequestStatusResult = $this->container->db->query($getRequestStatus);
		// Ajouter à l'historique des status le statut actuel
		$prevStatuses = $getRequestStatusResult[0]['prevStatuses'];
		if (strlen($getRequestStatusResult[0]['prevStatuses']) != 0) {
			$prevStatuses .= " ### ";
		}
		$prevStatuses .= $getRequestStatusResult[0]['statusLabel'] . " --> " . 
						 $getRequestStatusResult[0]['dateNewStatus'];

		$setRequestStatus = "UPDATE `requests` 
								SET `requests`.`idStatus` = :idNewStatus,
									`requests`.`dateNewStatus` = NOW(), 
									`requests`.`prevStatuses` = '$prevStatuses' ".
								"WHERE `requests`.`id` = :idRequest";
		$setRequestStatusResult = $this->container->db->query($setRequestStatus, $datas);

		return $response->withStatus(200)
    				->write(json_encode($setRequestStatusResult,JSON_NUMERIC_CHECK));
	}

	public function generatePDF(Request $request, Response $response, $args){
		require_once("./dependencies/fpdf/fpdf.php");//require_once("/var/www/html/dev-requests/public/dependencies/fpdf/fpdf.php");
		require_once("./dependencies/fpdf/fpdi.php");//require_once("/var/www/html/dev-requests/public/dependencies/fpdf/fpdi.php");

		$getQueryParams = $request->getQueryParams();
		$datas = new stdClass();
		$datas->params = json_decode(json_encode($getQueryParams), FALSE);

		$getRequest = "SELECT * 
					   FROM `requests` 
					   WHERE `requests`.`id` = :idRequest";
		$getRequestResult = $this->container->db->query($getRequest, $datas);

		$request = $getRequestResult[0];

		// Add department information to this element
		$getDepartment = "SELECT `name` 
					      FROM `departments` 
					      WHERE `departments`.`id` = ".$request['idDepartment'];
		$getDepartmentResult = $this->container->db->query($getDepartment, $datas);

		$request['department'] = $getDepartmentResult[0]['name'];

		// Add status information to this element
		$getStatus = "SELECT `label` 
					  FROM `statuses` 
					  WHERE `statuses`.`id` = ".$request['idStatus'];
		$getStatusResult = $this->container->db->query($getStatus, $datas);

		$request['status'] = $getStatusResult[0]['label'];

		// Add applicant information to this element
		$getApplicant = "SELECT `name` 
						FROM `users` 
						WHERE `users`.`id` = ".$request['idApplicant'];
		$getApplicantResult = $this->container->db->query($getApplicant, $datas);

		$request['applicant'] = $getApplicantResult[0]['name'];

		$chemin_complet = "./tmp/tmp.pdf";//$chemin_complet = "/var/www/html/dev-requests/public/tmp/tmp.pdf";

		$PDF = new FPDI(); // Création de l'instance PDF
		$pageCount = $PDF->setSourceFile("./dependencies/includes/Fiche info projet.pdf"); // On définit notre pdf source //$pageCount = $PDF->setSourceFile("/var/www/html/dev-requests/public/dependencies/includes/Fiche info projet.pdf"); // On définit notre pdf source
		$tplIdx = $PDF->importPage(1); // On récupère la page 1 de la source
		$PDF->addPage(); // On crée une page à notre pdf toujours vierge
		$PDF->useTemplate($tplIdx); // Sur cette page on dessine notre pdf source
		$PDF->SetAutoPageBreak(false);
		$PDF->SetFont("Arial", "", 8);
		$PDF->setXY(100,12.5);
		$PDF->Cell(20, 4,utf8_decode($request['projectName']),0,1,'L'); // Intitulé
		$PDF->setXY(45,28);
		$PDF->Cell(20, 4,utf8_decode($request['applicant']),0,1,'L'); // Demandeur
		$PDF->setXY(45,33);
		$PDF->Cell(20, 4,utf8_decode($request['department']),0,1,'L'); // Service
		$PDF->setXY(155,28);
		$PDF->Cell(20, 4,utf8_decode($request['status']),0,1,'L'); // Statut
		$PDF->setXY(155,33);
		$tmp = explode("-", substr($request['dateNewStatus'], 0, 10));
		$PDF->Cell(20, 4,$tmp[2] . "/" . $tmp[1] . "/" . $tmp[0],0,1,'L'); // Modifié le
		$PDF->setXY(45,53);
		$PDF->MultiCell(157, 4,utf8_decode($request['currentSituationDescr']),0,'L', false); // Situation actuelle
		$PDF->setXY(45,74);
		$PDF->MultiCell(157, 4,utf8_decode($request['currentIssueDescr']),0,'L', false); // Problème
		$PDF->setXY(45,94);
		$PDF->MultiCell(157, 4,utf8_decode($request['proposedSolutionDescr']),0,'L', false); // Solution proposée
		$PDF->setXY(90,130);
		$PDF->Cell(20, 4,$request['benInvY1'],0,1,'C'); // Investissements Y1
		$PDF->setXY(113,130);
		$PDF->Cell(20, 4,$request['benInvY2'],0,1,'C'); // Investissements Y2
		$PDF->setXY(135,130);
		$PDF->Cell(20, 4,$request['benInvY3'],0,1,'C'); // Investissements Y3
		$PDF->setXY(158,130);
		$PDF->Cell(20, 4,$request['benInvY4'],0,1,'C'); // Investissements Y4
		$PDF->setXY(181,130);
		$PDF->Cell(20, 4,$request['benInvY1'] + $request['benInvY2'] + $request['benInvY3'] + $request['benInvY4'],0,1,'C'); // Investissements Total
		$PDF->setXY(90,135.5);
		$PDF->Cell(20, 4,$request['benCostY1'],0,1,'C'); // Coûts d'exploitation Y1
		$PDF->setXY(113,135.5);
		$PDF->Cell(20, 4,$request['benCostY2'],0,1,'C'); // Coûts d'exploitation Y2
		$PDF->setXY(135,135.5);
		$PDF->Cell(20, 4,$request['benCostY3'],0,1,'C'); // Coûts d'exploitation Y3
		$PDF->setXY(158,135.5);
		$PDF->Cell(20, 4,$request['benCostY4'],0,1,'C'); // Coûts d'exploitation Y4
		$PDF->setXY(181,135.5);
		$PDF->Cell(20, 4,$request['benCostY1'] + $request['benCostY2'] + $request['benCostY3'] + $request['benCostY4'],0,1,'C'); // Coûts d'exploitation Total
		$PDF->setXY(90,140.5);
		$PDF->Cell(20, 4,$request['benBenefY1'],0,1,'C'); // Bénéfices Y1
		$PDF->setXY(113,140.5);
		$PDF->Cell(20, 4,$request['benBenefY2'],0,1,'C'); // Bénéfices Y2
		$PDF->setXY(135,140.5);
		$PDF->Cell(20, 4,$request['benBenefY3'],0,1,'C'); // Bénéfices Y3
		$PDF->setXY(158,140.5);
		$PDF->Cell(20, 4,$request['benBenefY4'],0,1,'C'); // Bénéfices Y4
		$PDF->setXY(181,140.5);
		$PDF->Cell(20, 4,$request['benBenefY1'] + $request['benBenefY2'] + $request['benBenefY3'] + $request['benBenefY4'],0,1,'C'); // Bénéfices Total
		$PDF->setXY(90,145.5);
		$PDF->Cell(20, 4,$request['benBenefY1'] - ($request['benInvY1'] + $request['benCostY1']),0,1,'C'); // Total Y1
		$PDF->setXY(113,145.5);
		$PDF->Cell(20, 4,$request['benBenefY2'] - ($request['benInvY2'] + $request['benCostY2']),0,1,'C'); // Total Y2
		$PDF->setXY(135,145.5);
		$PDF->Cell(20, 4,$request['benBenefY3'] - ($request['benInvY3'] + $request['benCostY3']),0,1,'C'); // Total Y3
		$PDF->setXY(158,145.5);
		$PDF->Cell(20, 4,$request['benBenefY4'] - ($request['benInvY4'] + $request['benCostY4']),0,1,'C'); // Total Y4
		$PDF->setXY(181,145.5);
		$PDF->Cell(20, 4,$request['benBenefY1'] - ($request['benInvY1'] + $request['benCostY1']) + 
						 $request['benBenefY2'] - ($request['benInvY2'] + $request['benCostY2']) + 
						 $request['benBenefY3'] - ($request['benInvY3'] + $request['benCostY3']) + 
						 $request['benBenefY4'] - ($request['benInvY4'] + $request['benCostY4']),0,1,'C'); // Total Total
		$PDF->setXY(25,166);
		$PDF->Cell(60, 4,$request['budgetEstimated'],0,1,'L'); // Budget estimé
		$PDF->setXY(125,166);
		$PDF->Cell(60, 4,$request['budgetAvailable'],0,1,'L'); // Budget disponible
		$PDF->setXY(45,186.5);
		$PDF->Cell(40, 4,utf8_decode($request['projectManager']),0,1,'L'); // Sponsor du projet
		$PDF->setXY(155,186.5);
		$PDF->Cell(40, 4,utf8_decode($request['projectManagerBusiness']),0,1,'L'); // Chef de projet Business
		$PDF->setXY(45,191.5);
		$PDF->Cell(40, 4,utf8_decode($request['projectManagerIT']),0,1,'L'); // Chef de projet IT
		$PDF->setXY(90,224);
		$PDF->Cell(20, 4,$request['projSched1Business'],0,1,'C');
		$PDF->setXY(113,224);
		$tmp = explode("-", substr($request['projSched1ExpDate'], 0, 10));
		$PDF->Cell(20, 4,$tmp[2] . "/" . $tmp[1] . "/" . $tmp[0],0,1,'C');
		$PDF->setXY(135,224);
		$PDF->Cell(20, 4,$request['projSched1IT'],0,1,'C');
		$PDF->setXY(158,224);
		$PDF->Cell(20, 4,$request['projSched1External'],0,1,'C');
		$PDF->setXY(181,224);
		$PDF->Cell(20, 4,$request['projSched1Assets'],0,1,'C');
		$PDF->setXY(90,229.5);
		$PDF->Cell(20, 4,$request['projSched3Business'],0,1,'C');
		$PDF->setXY(113,229.5);
		$tmp = explode("-", substr($request['projSched3ExpDate'], 0, 10));
		$PDF->Cell(20, 4,$tmp[2] . "/" . $tmp[1] . "/" . $tmp[0],0,1,'C');
		$PDF->setXY(135,229.5);
		$PDF->Cell(20, 4,$request['projSched3IT'],0,1,'C');
		$PDF->setXY(158,229.5);
		$PDF->Cell(20, 4,$request['projSched3External'],0,1,'C');
		$PDF->setXY(181,229.5);
		$PDF->Cell(20, 4,$request['projSched3Assets'],0,1,'C');
		$PDF->setXY(90,234.5);
		$PDF->Cell(20, 4,$request['projSched4Business'],0,1,'C');
		$PDF->setXY(113,234.5);
		$tmp = explode("-", substr($request['projSched4ExpDate'], 0, 10));
		$PDF->Cell(20, 4,$tmp[2] . "/" . $tmp[1] . "/" . $tmp[0],0,1,'C');
		$PDF->setXY(135,234.5);
		$PDF->Cell(20, 4,$request['projSched4IT'],0,1,'C');
		$PDF->setXY(158,234.5);
		$PDF->Cell(20, 4,$request['projSched4External'],0,1,'C');
		$PDF->setXY(181,234.5);
		$PDF->Cell(20, 4,$request['projSched4Assets'],0,1,'C');
		$PDF->setXY(90,239.5);
		$PDF->Cell(20, 4,$request['projSched5Business'],0,1,'C');
		$PDF->setXY(113,239.5);
		$tmp = explode("-", substr($request['projSched5ExpDate'], 0, 10));
		$PDF->Cell(20, 4,$tmp[2] . "/" . $tmp[1] . "/" . $tmp[0],0,1,'C');
		$PDF->setXY(135,239.5);
		$PDF->Cell(20, 4,$request['projSched5IT'],0,1,'C');
		$PDF->setXY(158,239.5);
		$PDF->Cell(20, 4,$request['projSched5External'],0,1,'C');
		$PDF->setXY(181,239.5);
		$PDF->Cell(20, 4,$request['projSched5Assets'],0,1,'C');
		$PDF->setXY(90,244.5);
		$PDF->Cell(20, 4,$request['projSched6Business'],0,1,'C');
		$PDF->setXY(113,244.5);
		$tmp = explode("-", substr($request['projSched6ExpDate'], 0, 10));
		$PDF->Cell(20, 4,$tmp[2] . "/" . $tmp[1] . "/" . $tmp[0],0,1,'C');
		$PDF->setXY(135,244.5);
		$PDF->Cell(20, 4,$request['projSched6IT'],0,1,'C');
		$PDF->setXY(158,244.5);
		$PDF->Cell(20, 4,$request['projSched6External'],0,1,'C');
		$PDF->setXY(181,244.5);
		$PDF->Cell(20, 4,$request['projSched6Assets'],0,1,'C');
		/*$PDF->setXY(90,249.5);
		$PDF->Cell(20, 4,$request['projSched6Business'],0,1,'C');
		$PDF->setXY(113,249.5);
		$tmp = explode("-", substr($request['projSched6ExpDate'], 0, 10));
		$PDF->Cell(20, 4,$tmp[2] . "/" . $tmp[1] . "/" . $tmp[0],0,1,'C');
		$PDF->setXY(135,249.5);
		$PDF->Cell(20, 4,$request['projSched6IT'],0,1,'C');
		$PDF->setXY(158,249.5);
		$PDF->Cell(20, 4,$request['projSched6External'],0,1,'C');
		$PDF->setXY(181,249.5);
		$PDF->Cell(20, 4,$request['projSched6Assets'],0,1,'C');*/
		//$PDF->setXY(90,255);
		$PDF->setXY(90,249.5);
		$PDF->Cell(20, 4,$request['projSched1Business'] + 
						 /*$request['projSched2Business'] +*/ 
						 $request['projSched3Business'] + 
						 $request['projSched4Business'] + 
						 $request['projSched5Business'] + 
						 $request['projSched6Business'],0,1,'C');
		//$PDF->setXY(135,255);
		$PDF->setXY(135,249.5);
		$PDF->Cell(20, 4,$request['projSched1IT'] + 
						 /*$request['projSched2IT'] + */
						 $request['projSched3IT'] + 
						 $request['projSched4IT'] + 
						 $request['projSched5IT'] + 
						 $request['projSched6IT'],0,1,'C');
		//$PDF->setXY(158,255);
		$PDF->setXY(158,249.5);
		$PDF->Cell(20, 4,$request['projSched1External'] + 
						 /*$request['projSched2External'] + */
						 $request['projSched3External'] + 
						 $request['projSched4External'] + 
						 $request['projSched5External'] + 
						 $request['projSched6External'],0,1,'C');
		//$PDF->setXY(181,255);
		$PDF->setXY(181,249.5);
		$PDF->Cell(20, 4,$request['projSched1Assets'] + 
						 /*$request['projSched2Assets'] + */
						 $request['projSched3Assets'] + 
						 $request['projSched4Assets'] + 
						 $request['projSched5Assets'] + 
						 $request['projSched6Assets'],0,1,'C');
		$PDF->setXY(45,273);
		$PDF->MultiCell(157, 4,utf8_decode($request['constraints']),0,'L', false);

		$PDF->Output($chemin_complet, "F"); // J'enregistre le tout dans $chemin_complet. S'il n'existe pas, ça le crée

		$res = $response->withHeader('Content-Description', 'File Transfer')
						   ->withHeader('Content-Type', 'application/octet-stream')
						   ->withHeader('Content-Disposition', 'attachment;filename="'.basename($chemin_complet).'"')
						   ->withHeader('Expires', '0')
						   ->withHeader('Cache-Control', 'must-revalidate')
						   ->withHeader('Pragma', 'public')
						   ->withHeader('Content-Length', filesize($chemin_complet));

		readfile($chemin_complet);
		return $res;
	}

}