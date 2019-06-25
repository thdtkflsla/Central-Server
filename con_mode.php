<?php
require_once 'include/DB_Functions.php';
$db = new DB_Functions();

$response = array("error" => FALSE);

if(isset($_POST['saving_mode']) && isset($_POST['security_mode']) && isset($_POST['alarm_mode'])){
    
    $saving_mode = $_POST['saving_mode'];
    $security_mode = $_POST['security_mode'];
    $alarm_mode = $_POST['alarm_mode'];
    $temp = $_POST['temp'];
    $email = $_POST['email'];

    if($db -> set_mode($saving_mode, $security_mode, $alarm_mode, $temp, $email)){
        $response["error"] = FALSE;
        $response["error_msg"] = "저장 완료~!";
        echo json_encode($response, JSON_UNESCAPED_UNICODE);

       
        
    }else{
        $response["error"] = TRUE;
        $response["error_msg"] = "제품번호를 찾을 수 없습니다.";
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

}else{
    $response["error"] = TRUE;
    $response["error_msg"] = "전송실패하였습니다";
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
?>