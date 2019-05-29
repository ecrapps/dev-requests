<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

date_default_timezone_set('UTC');

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

		$projectName = filter_var($getParsedBody['projectName'], FILTER_SANITIZE_STRING);
		$idApplicant = filter_var($getParsedBody['idApplicant'], FILTER_SANITIZE_STRING);

		$getApplicant = "SELECT `userName`  FROM `users` WHERE `id` = '".$idApplicant."'";
		$getApplicantResult = $this->container->db->query($getApplicant, $datas);
		$applicantUsername = $getApplicantResult[0]['userName'];

		if (isset($_FILES['file'])) {
			$annee = date("Y");
			$uploadFolder = 'uploads/'.$annee.'/'.$applicantUsername;
			$filename = $_FILES['file']['name'];

			if (!file_exists($uploadFolder)) {
				mkdir($uploadFolder, 0777, true);
			}

			$fileUploadResult = move_uploaded_file($_FILES['file']['tmp_name'], $uploadFolder.'/'.$filename);
		}
		else {
			$uploadFolder = "";
			$filename = "";
			$fileUploadResult = true;
		}

		$createRequest = "INSERT INTO `requests`  (`idApplicant`,
												   `idDepartment`,
												   `projectName`,
												   `currentSituationDescr`,
												   `currentIssueDescr`,
												   `proposedSolutionDescr`,
												   `filePath`,
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
												   `rgpdTypeData`,
												   `rgpdFinalite`,
												   `rgpdProcessus`,
												   `rgpdImpact`,
												   `rgpdCommentaireDPO`,
												   `idStatus`,
												   `dateNewStatus`,
												   `userNewStatus`) 
						   VALUES (:idApplicant,
								   :idDepartment,
								   :projectName,
								   :currentSituationDescr,
								   :currentIssueDescr,
								   :proposedSolutionDescr,
								   '".$uploadFolder."',
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
								   :rgpdTypeData,
								   :rgpdFinalite,
								   :rgpdProcessus,
								   :rgpdImpact,
								   :rgpdCommentaireDPO,
								   :idStatus,
								   NOW(),
								   '".$applicantUsername."')";
		$createRequestResult = $this->container->db->query($createRequest, $datas);

		$this->sendMailDPO('create', $projectName);
		
		return $response->withStatus(200)
        				->write(json_encode($createRequestResult && $fileUploadResult,JSON_NUMERIC_CHECK));
	}

	public function updateRequest(Request $request, Response $response, $args){
		$getParsedBody = $request->getParsedBody();
		$datas = new stdClass();
		$datas->params = json_decode(json_encode($getParsedBody), FALSE);

		$projectName = filter_var($getParsedBody['projectName'], FILTER_SANITIZE_STRING);
		$idApplicant = filter_var($getParsedBody['idApplicant'], FILTER_SANITIZE_STRING);

		$getApplicant = "SELECT `userName`  FROM `users` WHERE `id` = '".$idApplicant."'";
		$getApplicantResult = $this->container->db->query($getApplicant);
		$applicantUsername = $getApplicantResult[0]['userName'];

		if (isset($_FILES['file'])) {
			$idRequest = filter_var($getParsedBody['idRequest'], FILTER_SANITIZE_STRING);

			$getCurrentAddedFile = "SELECT `addedFile`  FROM `requests` WHERE `id` = '".$idRequest."'";
			$getCurrentAddedFileResult = $this->container->db->query($getCurrentAddedFile);
			$currentAddedFile = $getCurrentAddedFileResult[0]['addedFile'];

			$annee = date("Y");
			$uploadFolder = 'uploads/'.$annee.'/'.$applicantUsername;
			$filename = $_FILES['file']['name'];

			if ( $currentAddedFile && (file_exists($uploadFolder.'/'.$currentAddedFile)) ) {
				unlink($uploadFolder.'/'.$currentAddedFile);
			}

			if (!file_exists($uploadFolder)) {
				mkdir($uploadFolder, 0777, true);
			}
			
			$fileUploadResult = move_uploaded_file($_FILES['file']['tmp_name'], $uploadFolder.'/'.$filename);
		}
		else {
			$uploadFolder = "";
			$filename = "";
			$fileUploadResult = true;
		}

		$updateRequest = "UPDATE `requests` SET    `idApplicant` = :idApplicant,
												   `idDepartment` = :idDepartment,
												   `projectName` = :projectName,
												   `currentSituationDescr` = :currentSituationDescr,
												   `currentIssueDescr` = :currentIssueDescr,
												   `proposedSolutionDescr` = :proposedSolutionDescr,
												   `filePath` = '".$uploadFolder."',
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
												   `constraints` = :constraints,
												   `rgpdTypeData` = :rgpdTypeData,
												   `rgpdFinalite` = :rgpdFinalite,
												   `rgpdProcessus` = :rgpdProcessus,
												   `rgpdImpact` = :rgpdImpact,
												   `rgpdCommentaireDPO` = :rgpdCommentaireDPO 
							WHERE `requests`.`id` = :idRequest";
		$updateRequestResult = $this->container->db->query($updateRequest, $datas);

		$this->sendMailDPO('update', $projectName);

		return $response->withStatus(200)
						->write(json_encode($updateRequestResult && $fileUploadResult,JSON_NUMERIC_CHECK));
	}

	public function deleteRequest(Request $request, Response $response, $args){
		$getQueryParams = $request->getQueryParams();
		$datas = new stdClass();
		$datas->params = json_decode(json_encode($getQueryParams), FALSE);
		
		$getAddedFile = "SELECT `addedFile`, `filePath`  FROM `requests` WHERE `id` = '".$_GET['idRequest']."'";
		$getAddedFileResult = $this->container->db->query($getAddedFile);
		$filename = $getAddedFileResult[0]['addedFile'];
		$filepath = $getAddedFileResult[0]['filePath'];

		if ( $filename && (file_exists($filepath.'/'.$filename)) ) {
			unlink($filepath.'/'.$filename);
		}

		$deleteRequest = "DELETE FROM `requests` WHERE `requests` . `id` = :idRequest";
		$deleteRequestResult = $this->container->db->query($deleteRequest, $datas);

		return $response->withStatus(200)
        				->write(json_encode($deleteRequestResult,JSON_NUMERIC_CHECK));
	}

	public function setRequestStatus(Request $request, Response $response, $args){
		$getParsedBody = $request->getParsedBody();
		$datas = new stdClass();
		$datas->params = json_decode(json_encode($getParsedBody), FALSE);

		// Récupérer le statut actuel
		// Récupérer l'historique des statuts
		$getRequestStatus = "SELECT r.`idStatus`, 
									s.`label` AS statusLabel, 
									r.`dateNewStatus`,  
									r.`userNewStatus`, 
									r.`prevStatuses` 
								FROM `requests` AS r 
								LEFT JOIN `statuses` AS s ON r.`idStatus` = s.`id` 
								WHERE r.`id` = ".$getParsedBody['idRequest'];
		$getRequestStatusResult = $this->container->db->query($getRequestStatus);
		// Ajouter à l'historique des statuts le statut actuel
		$prevStatuses = $getRequestStatusResult[0]['prevStatuses'];
		if (strlen($getRequestStatusResult[0]['prevStatuses']) != 0) {
			$prevStatuses .= " ### ";
		}
		$prevStatuses .= $getRequestStatusResult[0]['statusLabel'] . " --> " . 
						 $getRequestStatusResult[0]['dateNewStatus'] . " --> " . 
						 $getRequestStatusResult[0]['userNewStatus'];

		$setRequestStatus = "UPDATE `requests` 
								SET `requests`.`idStatus` = :idNewStatus, 
									`requests`.`dateNewStatus` = NOW(), 
									`requests`.`userNewStatus` = :userNewStatus, 
									`requests`.`prevStatuses` = '$prevStatuses' ".
								"WHERE `requests`.`id` = :idRequest";
		$setRequestStatusResult = $this->container->db->query($setRequestStatus, $datas);

		return $response->withStatus(200)
    				->write(json_encode($setRequestStatusResult,JSON_NUMERIC_CHECK));
	}

	public function generatePDF(Request $request, Response $response, $args){
		require_once(ROOT_FOLDER."/dependencies/fpdf/fpdf.php");
		require_once(ROOT_FOLDER."/dependencies/fpdf/fpdi.php");

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

		$chemin_complet = ROOT_FOLDER."/tmp/tmp.pdf";

		$PDF = new FPDI(); // Création de l'instance PDF
		$pageCount = $PDF->setSourceFile(ROOT_FOLDER."/dependencies/includes/Fiche info projet.pdf"); // On définit notre pdf source
		// PAGE 1
		$tplIdx = $PDF->importPage(1); // On récupère la page 1 de la source
		$PDF->addPage(); // On crée une page à notre pdf toujours vierge
		$PDF->useTemplate($tplIdx); // Sur cette page on dessine notre pdf source
		$PDF->SetAutoPageBreak(false);
		$PDF->SetFont("Arial", "", 8);
		$PDF->setXY(100,13);
		$PDF->Cell(20, 4,utf8_decode($request['projectName']),0,1,'L'); // Intitulé
		$PDF->setXY(45,31.5);
		$PDF->Cell(20, 4,utf8_decode($request['applicant']),0,1,'L'); // Demandeur
		$PDF->setXY(45,36.5);
		$PDF->Cell(20, 4,utf8_decode($request['department']),0,1,'L'); // Service
		$PDF->setXY(155,31.5);
		$PDF->Cell(20, 4,utf8_decode($request['status']),0,1,'L'); // Statut
		$PDF->setXY(155,36.5);
		$tmp = explode("-", substr($request['dateNewStatus'], 0, 10));
		$PDF->Cell(20, 4,$tmp[2] . "/" . $tmp[1] . "/" . $tmp[0],0,1,'L'); // Modifié le
		$PDF->setXY(45,57);
		$PDF->MultiCell(157, 4,utf8_decode($request['currentSituationDescr']),0,'L', false); // Situation actuelle
		$PDF->setXY(45,103);
		$PDF->MultiCell(157, 4,utf8_decode($request['currentIssueDescr']),0,'L', false); // Problème
		$PDF->setXY(45,150);
		$PDF->MultiCell(157, 4,utf8_decode($request['proposedSolutionDescr']),0,'L', false); // Solution proposée
		$PDF->setXY(90,216.5);
		$PDF->Cell(20, 4,$request['benInvY1'],0,1,'C'); // Investissements Y1
		$PDF->setXY(113,216.5);
		$PDF->Cell(20, 4,$request['benInvY2'],0,1,'C'); // Investissements Y2
		$PDF->setXY(135,216.5);
		$PDF->Cell(20, 4,$request['benInvY3'],0,1,'C'); // Investissements Y3
		$PDF->setXY(158,216.5);
		$PDF->Cell(20, 4,$request['benInvY4'],0,1,'C'); // Investissements Y4
		$PDF->setXY(181,216.5);
		$PDF->Cell(20, 4,$request['benInvY1'] + $request['benInvY2'] + $request['benInvY3'] + $request['benInvY4'],0,1,'C'); // Investissements Total
		$PDF->setXY(90,222.5);
		$PDF->Cell(20, 4,$request['benCostY1'],0,1,'C'); // Coûts d'exploitation Y1
		$PDF->setXY(113,222.5);
		$PDF->Cell(20, 4,$request['benCostY2'],0,1,'C'); // Coûts d'exploitation Y2
		$PDF->setXY(135,222.5);
		$PDF->Cell(20, 4,$request['benCostY3'],0,1,'C'); // Coûts d'exploitation Y3
		$PDF->setXY(158,222.5);
		$PDF->Cell(20, 4,$request['benCostY4'],0,1,'C'); // Coûts d'exploitation Y4
		$PDF->setXY(181,222.5);
		$PDF->Cell(20, 4,$request['benCostY1'] + $request['benCostY2'] + $request['benCostY3'] + $request['benCostY4'],0,1,'C'); // Coûts d'exploitation Total
		$PDF->setXY(90,228);
		$PDF->Cell(20, 4,$request['benBenefY1'],0,1,'C'); // Bénéfices Y1
		$PDF->setXY(113,228);
		$PDF->Cell(20, 4,$request['benBenefY2'],0,1,'C'); // Bénéfices Y2
		$PDF->setXY(135,228);
		$PDF->Cell(20, 4,$request['benBenefY3'],0,1,'C'); // Bénéfices Y3
		$PDF->setXY(158,228);
		$PDF->Cell(20, 4,$request['benBenefY4'],0,1,'C'); // Bénéfices Y4
		$PDF->setXY(181,228);
		$PDF->Cell(20, 4,$request['benBenefY1'] + $request['benBenefY2'] + $request['benBenefY3'] + $request['benBenefY4'],0,1,'C'); // Bénéfices Total
		$PDF->setXY(90,233);
		$PDF->Cell(20, 4,$request['benBenefY1'] - ($request['benInvY1'] + $request['benCostY1']),0,1,'C'); // Total Y1
		$PDF->setXY(113,233);
		$PDF->Cell(20, 4,$request['benBenefY2'] - ($request['benInvY2'] + $request['benCostY2']),0,1,'C'); // Total Y2
		$PDF->setXY(135,233);
		$PDF->Cell(20, 4,$request['benBenefY3'] - ($request['benInvY3'] + $request['benCostY3']),0,1,'C'); // Total Y3
		$PDF->setXY(158,233);
		$PDF->Cell(20, 4,$request['benBenefY4'] - ($request['benInvY4'] + $request['benCostY4']),0,1,'C'); // Total Y4
		$PDF->setXY(181,233);
		$PDF->Cell(20, 4,$request['benBenefY1'] - ($request['benInvY1'] + $request['benCostY1']) + 
						 $request['benBenefY2'] - ($request['benInvY2'] + $request['benCostY2']) + 
						 $request['benBenefY3'] - ($request['benInvY3'] + $request['benCostY3']) + 
						 $request['benBenefY4'] - ($request['benInvY4'] + $request['benCostY4']),0,1,'C'); // Total Total
		$PDF->setXY(25,253.5);
		$PDF->Cell(60, 4,$request['budgetEstimated'],0,1,'L'); // Budget estimé
		$PDF->setXY(125,253.5);
		$PDF->Cell(60, 4,$request['budgetAvailable'],0,1,'L'); // Budget disponible
		$PDF->setXY(45,274);
		$PDF->Cell(40, 4,utf8_decode($request['projectManager']),0,1,'L'); // Sponsor du projet
		$PDF->setXY(155,274);
		$PDF->Cell(40, 4,utf8_decode($request['projectManagerBusiness']),0,1,'L'); // Chef de projet Business
		$PDF->setXY(45,279.5);
		$PDF->Cell(40, 4,utf8_decode($request['projectManagerIT']),0,1,'L'); // Chef de projet IT

		// PAGE 2
		$tplIdx = $PDF->importPage(2); // On récupère la page 1 de la source
		$PDF->addPage(); // On crée une page à notre pdf toujours vierge
		$PDF->useTemplate($tplIdx); // Sur cette page on dessine notre pdf source
		$PDF->setXY(90,31.5);
		$PDF->Cell(20, 4,$request['projSched1Business'],0,1,'C');
		$PDF->setXY(113,31.5);
		$tmp = explode("-", substr($request['projSched1ExpDate'], 0, 10));
		$PDF->Cell(20, 4,$tmp[2] . "/" . $tmp[1] . "/" . $tmp[0],0,1,'C');
		$PDF->setXY(135,31.5);
		$PDF->Cell(20, 4,$request['projSched1IT'],0,1,'C');
		$PDF->setXY(158,31.5);
		$PDF->Cell(20, 4,$request['projSched1External'],0,1,'C');
		$PDF->setXY(181,31.5);
		$PDF->Cell(20, 4,$request['projSched1Assets'],0,1,'C');
		$PDF->setXY(90,37);
		$PDF->Cell(20, 4,$request['projSched3Business'],0,1,'C');
		$PDF->setXY(113,37);
		$tmp = explode("-", substr($request['projSched3ExpDate'], 0, 10));
		$PDF->Cell(20, 4,$tmp[2] . "/" . $tmp[1] . "/" . $tmp[0],0,1,'C');
		$PDF->setXY(135,37);
		$PDF->Cell(20, 4,$request['projSched3IT'],0,1,'C');
		$PDF->setXY(158,37);
		$PDF->Cell(20, 4,$request['projSched3External'],0,1,'C');
		$PDF->setXY(181,37);
		$PDF->Cell(20, 4,$request['projSched3Assets'],0,1,'C');
		$PDF->setXY(90,42);
		$PDF->Cell(20, 4,$request['projSched4Business'],0,1,'C');
		$PDF->setXY(113,42);
		$tmp = explode("-", substr($request['projSched4ExpDate'], 0, 10));
		$PDF->Cell(20, 4,$tmp[2] . "/" . $tmp[1] . "/" . $tmp[0],0,1,'C');
		$PDF->setXY(135,42);
		$PDF->Cell(20, 4,$request['projSched4IT'],0,1,'C');
		$PDF->setXY(158,42);
		$PDF->Cell(20, 4,$request['projSched4External'],0,1,'C');
		$PDF->setXY(181,42);
		$PDF->Cell(20, 4,$request['projSched4Assets'],0,1,'C');
		$PDF->setXY(90,47);
		$PDF->Cell(20, 4,$request['projSched5Business'],0,1,'C');
		$PDF->setXY(113,47);
		$tmp = explode("-", substr($request['projSched5ExpDate'], 0, 10));
		$PDF->Cell(20, 4,$tmp[2] . "/" . $tmp[1] . "/" . $tmp[0],0,1,'C');
		$PDF->setXY(135,47);
		$PDF->Cell(20, 4,$request['projSched5IT'],0,1,'C');
		$PDF->setXY(158,47);
		$PDF->Cell(20, 4,$request['projSched5External'],0,1,'C');
		$PDF->setXY(181,47);
		$PDF->Cell(20, 4,$request['projSched5Assets'],0,1,'C');
		$PDF->setXY(90,52);
		$PDF->Cell(20, 4,$request['projSched6Business'],0,1,'C');
		$PDF->setXY(113,52);
		$tmp = explode("-", substr($request['projSched6ExpDate'], 0, 10));
		$PDF->Cell(20, 4,$tmp[2] . "/" . $tmp[1] . "/" . $tmp[0],0,1,'C');
		$PDF->setXY(135,52);
		$PDF->Cell(20, 4,$request['projSched6IT'],0,1,'C');
		$PDF->setXY(158,52);
		$PDF->Cell(20, 4,$request['projSched6External'],0,1,'C');
		$PDF->setXY(181,52);
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
		$PDF->setXY(90,57);
		$PDF->Cell(20, 4,$request['projSched1Business'] + 
						 /*$request['projSched2Business'] +*/ 
						 $request['projSched3Business'] + 
						 $request['projSched4Business'] + 
						 $request['projSched5Business'] + 
						 $request['projSched6Business'],0,1,'C');
		//$PDF->setXY(135,255);
		$PDF->setXY(135,57);
		$PDF->Cell(20, 4,$request['projSched1IT'] + 
						 /*$request['projSched2IT'] + */
						 $request['projSched3IT'] + 
						 $request['projSched4IT'] + 
						 $request['projSched5IT'] + 
						 $request['projSched6IT'],0,1,'C');
		//$PDF->setXY(158,255);
		$PDF->setXY(158,57);
		$PDF->Cell(20, 4,$request['projSched1External'] + 
						 /*$request['projSched2External'] + */
						 $request['projSched3External'] + 
						 $request['projSched4External'] + 
						 $request['projSched5External'] + 
						 $request['projSched6External'],0,1,'C');
		//$PDF->setXY(181,255);
		$PDF->setXY(181,57);
		$PDF->Cell(20, 4,$request['projSched1Assets'] + 
						 /*$request['projSched2Assets'] + */
						 $request['projSched3Assets'] + 
						 $request['projSched4Assets'] + 
						 $request['projSched5Assets'] + 
						 $request['projSched6Assets'],0,1,'C');
		$PDF->setXY(45,83);
		$PDF->MultiCell(157, 4,utf8_decode($request['constraints']),0,'L', false);
		$PDF->setXY(15,129);
		$PDF->MultiCell(177, 4,utf8_decode($request['rgpdTypeData']),0,'L', false);
		$PDF->setXY(15,155.5);
		$PDF->MultiCell(177, 4,utf8_decode($request['rgpdFinalite']),0,'L', false);
		$PDF->setXY(15,180);
		$PDF->MultiCell(177, 4,utf8_decode($request['rgpdProcessus']),0,'L', false);
		$PDF->setXY(15,206);
		$PDF->MultiCell(177, 4,utf8_decode($request['rgpdImpact']),0,'L', false);
		$PDF->setXY(15,232);
		$PDF->MultiCell(177, 4,utf8_decode($request['rgpdCommentaireDPO']),0,'L', false);
		// Historique des statuts précédents
		$statut = explode(" ### ", $request['prevStatuses']);
		if ($statut[0] == "") // N'a pas trouvé de chaîne ### dans prevStatuses
			$statut = [];
		for ($i = 0 ; $i < count($statut) ; $i++) {
			$tmpStatut = explode(" --> ", $statut[$i]);
			$statut[$i] = $tmpStatut;
		}
		$c = count($statut);
		$statut[$c][0] = $request['status'];
		$statut[$c][1] = $request['dateNewStatus'];
		$statut[$c][2] = $request['userNewStatus'];
		for ($i = 0, $y = 265 ; $i < count($statut) ; $i++, $y += 5) {
			if (isset($statut[$i][2]) && $statut[$i][2] != "") {
				$parQui = " par ".utf8_decode($statut[$i][2]);
			} else {
				$parQui = "";
			}
			$PDF->setXY(15, $y);
			$PDF->MultiCell(177, 4,utf8_decode($statut[$i][0]).' le '.utf8_decode($statut[$i][1]).$parQui,1,'L', false);
		}
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

	private function sendMailDPO($mode, $projectName) {
		$getEmailDPO = "SELECT `email`  FROM `users` WHERE `dpo` = true";
		$getEmailDPOResult = $this->container->db->query($getEmailDPO);

		if (sizeof($getEmailDPOResult)>0) {
			($mode === "create") ? $modeMessage = "créée" : $modeMessage = "modifiée";
			($mode === "create") ? $modeSujet = "Création" : $modeSujet = "Modification";
	
			// Déclaration de l'adresse de destination.
			$mail = $getEmailDPOResult[0]['email'];
			//=====Déclaration du message
			$passage_ligne = "\n";
			$message_txt = "Bonjour,".$passage_ligne.$passage_ligne;
			$message_txt .= "La demande suivante a été ".$modeMessage." : ".$projectName.$passage_ligne.$passage_ligne;

			//=====Création de la boundary
			$boundary = "-----=".md5(rand());
			//==========

			//=====Définition du sujet.
			$sujet = "DevReq - ".$modeSujet." d'une demande de développement";
			//=========
				
			//=====Création du header de l'e-mail.
			$header = "From: \"DevReq (ne pas répondre)\" <noreply@deutschebahn.com>".$passage_ligne;
			
			for ($i=1 ; $i<sizeof($getEmailDPOResult) ; $i++)
				$header.= "Cc: \"".$getEmailDPOResult[$i]['email']."\" <".$getEmailDPOResult[$i]['email'].">".$passage_ligne;
			
			$header.= "MIME-Version: 1.0".$passage_ligne;
			$header.= "Content-Type: text/plain; charset=ISO-8859-1".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
			//==========
	
			//=====Ajout du message au format texte.
			$message = $passage_ligne.$message_txt.$passage_ligne;
			//==========
				
			//=====Envoi de l'e-mail.
			mail($mail,utf8_decode($sujet),utf8_decode($message),utf8_decode($header));
			//==========
		}
	}

}