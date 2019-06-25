<?php
require_once 'include/DB_Functions.php';
$db = new DB_Functions();

$response = array("error" => FALSE);

if(isset($_POST['window']) && isset($_POST['door']) && isset($_POST['gas']) && isset($_POST['boiler'])&& isset($_POST['nowtemp']) && isset($_POST['serialnumber'])){
    
    $window = $_POST['window'];
    $door = $_POST['door'];
    $gas = $_POST['gas'];
    $boiler = $_POST['boiler'];
    $smoke_alarm = $_POST['smoke_alarm'];
    $nowtemp = $_POST['nowtemp'];
    $serialnumber = $_POST['serialnumber'];

    if($db -> nowstate($window, $door, $gas, $boiler,$smoke_alarm, $nowtemp, $serialnumber)){
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