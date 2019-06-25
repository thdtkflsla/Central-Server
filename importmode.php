<?php

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

$response = array("error" => FALSE);

$email = $_POST['email'];

$con_mode = $db->importmode($email);
$response["error"] = FALSE;
$response["con_mode"]["saving_mode"] = $con_mode["saving_mode"];
$response["con_mode"]["security_mode"] = $con_mode["security_mode"];
$response["con_mode"]["alarm_mode"] = $con_mode["alarm_mode"];
$response["con_mode"]["temp"] = $con_mode["temp"];
echo json_encode($response, JSON_UNESCAPED_UNICODE);

?>