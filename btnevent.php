<?php
require_once 'include/DB_Functions.php';
$db = new DB_Functions();

$response = array("error" => FALSE);

if(isset($_POST['chkwindow']) && isset($_POST['chkdoor']) && isset($_POST['chkgas']) && isset($_POST['chkboiler'])){
    
    $window = $_POST['chkwindow'];
    $door = $_POST['chkdoor'];
    $gas = $_POST['chkgas'];
    $boiler = $_POST['chkboiler'];
    $email = $_POST['email'];

    if($db -> sendData($window, $door, $gas, $boiler, $email)){
        /*$response["window"] = $window;
        $response["door"] = $door;
        $response["gas"] = $gas;
        $response["boiler"] = $boiler;*/
        //echo json_encode($onoff, JSON_UNESCAPED_UNICODE);
        $response["error"] = FALSE;
        echo json_encode($response, JSON_UNESCAPED_UNICODE);

       
        
    }else{
        $response["error"] = TRUE;
        $response["error_msg"] = "제품번호를 찾을 수 없습니다.";
        echo json_encode($response);
    }

}else{
    $response["error"] = TRUE;
    $response["error_msg"] = "전송실패하였습니다";
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
?>