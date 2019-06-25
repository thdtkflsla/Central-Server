<?php
require_once 'include/DB_Functions.php';
$db = new DB_Functions();

$response = array("error" => FALSE);

$serialnumber = $_POST['serialnumber'];

$mode = $db->controller_mode($serialnumber);
$response["error"] = FALSE;
$response["mode"]["saving_mode"] = $mode["saving_mode"];
$response["mode"]["security_mode"] = $mode["security_mode"];
$response["mode"]["alarm_mode"] = $mode["alarm_mode"];
$response["mode"]["temp"] = $mode["temp"];
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>