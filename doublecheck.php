<?php
require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if(isset($_POST['serialnumber'])){
    
    $serialnumber = $_POST['serialnumber'];

    if($db -> isserialnumberExisted($serialnumber)){
        // user already existed
        $response["error"] = TRUE;
        $response["error_msg"] = "제품번호를 찾을 수 없습니다." . $serialnumber;
        echo json_encode($response);
    }else{
        $response["error"] = FALSE;
        echo json_encode($response);
    }
}else{
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters serialnumber is missing!";
    echo json_encode($response);
}
?>