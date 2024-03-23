<?php

require_once 'vendor/autoload.php'; // Asegúrate de haber instalado la biblioteca de Google Sheets mediante Composer

use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;
use Google\Client as Google_Client;

// Configura las credenciales de autenticación de la API de Google Sheets
putenv('GOOGLE_APPLICATION_CREDENTIALS=/path/to/credentials.json');

// Crea una instancia del cliente de Google Sheets
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->setApplicationName('Nombre de tu aplicación');
$client->setScopes(['https://www.googleapis.com/auth/spreadsheets']);

// Obtiene el token de acceso
$client->fetchAccessTokenWithAssertion();

// Crea una instancia de la solicitud de servicio
$serviceRequest = new DefaultServiceRequest($client->getAccessToken());
ServiceRequestFactory::setInstance($serviceRequest);

// ID de la hoja de cálculo de Google Sheets
$spreadsheetId = 'ID_DE_TU_HOJA_DE_CÁLCULO';

// Nombre de la hoja en la que deseas escribir los datos
$sheetName = 'Nombre_de_tu_hoja';

// Obtén los datos que deseas escribir en la hoja de cálculo
$data = [
	'Campo1' => $_POST['campo1'],
	'Campo2' => $_POST['campo2'],
	'Campo3' => $_POST['campo3'],
	// Agrega más campos según sea necesario
];

// Crea una instancia del servicio de hojas de cálculo de Google
$spreadsheetService = new Google\Spreadsheet\SpreadsheetService();
$spreadsheet = $spreadsheetService->getSpreadsheetById($spreadsheetId);
$worksheet = $spreadsheet->getWorksheetByTitle($sheetName);

// Escribe los datos en la hoja de cálculo
$row = $worksheet->getLastRow() + 1;
foreach ($data as $column => $value) {
	$worksheet->getCell($column . $row)->setValue($value);
}

// Guarda los cambios en la hoja de cálculo
$worksheet->save();

// Envía una respuesta al cliente
echo 'ok';

?>