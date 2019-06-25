<?php
require_once 'include/DB_Functions.php';
$db = new DB_Functions();

$response = array("error" => FALSE);

$serialnumber = $_POST['serialnumber'];

$serial_number = $db->controller($serialnumber);
$response["error"] = FALSE;
$response["serial_number"]["window"] = $serial_number["window"];
$response["serial_number"]["door"] = $serial_number["door"];
$response["serial_number"]["gas"] = $serial_number["gas"];
$response["serial_number"]["boiler"] = $serial_number["boiler"];
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>