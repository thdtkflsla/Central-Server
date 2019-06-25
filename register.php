<?php

require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);

if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['serialnumber']) && isset($_POST['password'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $serialnumber = $_POST['serialnumber'];
    $password = $_POST['password'];

    //이메일 중복확인
    if($db -> isUserExisted($email)){

        $response["error"] = TRUE;
        $response["error_msg"] = "이미 존재하는 사용자 입니다" . $email;
        echo json_encode($response);
    } else {
        // create a new user
        $user = $db->storUser($name, $email, $serialnumber, $password);
        if($user){
            // user stored successfully
            $response["error"] = FALSE;
            $response["uid"] = $user["unique_id"];
            $response["user"]["name"] = $user["name"];
            $response["user"]["email"] = $user["email"];
            $response["user"]["serialnumber"] = $user["serialnumber"];
            $response["user"]["created_at"] = $user["created_at"];
            $response["user"]["updated_at"] = $user["updated_at"];
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "등록시 알 수없는 오류가 발생했습니다!";
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
    }
}else {
    $response["error"] = TRUE;
    $response["error_msg"] = "이름, 전자 메일, 일련 번호 또는 암호가 누락되었습니다!";
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}

?>