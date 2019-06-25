<?php
require_once 'include/DB_Functions.php';
$db = new DB_Functions();

$response = array("error" => FALSE);

if( isset($_POST['nowtemp'])&&isset($_POST['serialnumber'])){
    

    $nowtemp = $_POST['nowtemp'];
    $serialnumber = $_POST['serialnumber'];

    if($db -> nowtemp($nowtemp, $serialnumber)){
        $response["error"] = FALSE;
        $response["error_msg"] = "저장 완료~!";
        echo json_encode($response, JSON_UNESCAPED_UNICODE);

       
        
    }else{
        $response["error"] = TRUE;
        $response["error_msg"] = "저장할 수 없습니다";
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

}else{
    $response["error"] = TRUE;
    $response["error_msg"] = "전송실패하였습니다";
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
?>