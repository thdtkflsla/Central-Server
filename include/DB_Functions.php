<?php
class DB_Functions {
 
    private $conn;
 
    // constructor
    function __construct() {
        require_once 'DB_Connect.php';
        // database 연결
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }
 
    // 회원삭제 나중에함 
    function __destruct() {
         
    }

    public function storUser($name, $email, $serialnumber, $password){
        $uuid = uniqid('', true);
        $hash = $this -> hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt

        $stmt = $this->conn->prepare("INSERT INTO users(unique_id, name, email, serialnumber, encrypted_password, salt, created_at) VALUES(?, ?, ?, ?, ?, ?, NOW())");
        $stmt -> bind_param("ssssss", $uuid, $name, $email, $serialnumber, $encrypted_password, $salt);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
 
            return $user;
        } else {
            return false;
        }
    }
    
    public function getUserByEmailAndPassword($email, $password) {
 
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
 
        $stmt->bind_param("s", $email);
 
        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
 
            // verifying user password
            $salt = $user['salt'];
            $encrypted_password = $user['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $user;
            }
        } else {
            return NULL;
        }
    }

    /**
     * 사용자 유무 체크
     */
    public function isUserExisted($email) {
        $stmt = $this->conn->prepare("SELECT email from users WHERE email = ?");
 
        $stmt->bind_param("s", $email);
 
        $stmt->execute();
 
        $stmt->store_result();
 
        if ($stmt->num_rows > 0) {
            // 유저가 존재할 때 
            $stmt->close();
            return true;
        } else {
            // 유저 존재 안함
            $stmt->close();
            return false;
        }
    }

    /*일련번호 확인*/

    public function isserialnumberExisted($serialnumber){
        $stmt = $this->conn->prepare("SELECT serialnumber from serial_number WHERE serialnumber = ?");
        $stmt -> bind_param("s", $serialnumber);
        $stmt->execute();
 
        $stmt->store_result();
 
        if ($stmt->num_rows > 0) {
            $stmt->close();
            return false;
        } else {
            $stmt->close();
            return true;
        }
    }


    //상태 보내기
    public function sendData($window, $door, $gas, $boiler, $email){
        $stmt = $this->conn->prepare("UPDATE serial_number
        SET window = ?,door = ?,gas = ?,boiler = ?
        WHERE serialnumber = (SELECT serialnumber FROM users WHERE email =?)");
        $stmt -> bind_param("sssss", $window, $door, $gas, $boiler, $email);
        mysqli_commit($stmt);
        $result = $stmt->execute();
        
        //$stmt->close();
        
        
        if($result){
            $stmt->close();
            return true;
        }else{
            $stmt->close();
            return false;
        }
    }

    //컨트롤러 상태 보내기
    public function nowstate($window, $door, $gas, $boiler, $smoke_alarm, $nowtemp, $serialnumber){
        $stmt = $this->conn->prepare("UPDATE nowstate
        SET window = ?,door = ?,gas = ?,boiler = ?,smoke_alarm=?,nowtemp=?
        WHERE serialnumber = ?");
        $stmt -> bind_param("sssssis", $window, $door, $gas, $boiler,$smoke_alarm, $nowtemp, $serialnumber);
        mysqli_commit($stmt);
        $result = $stmt->execute();
        
        //$stmt->close();
        
        
        if($result){
            $stmt->close();
            return true;
        }else{
            $stmt->close();
            return false;
        }
    }

    //현제온도 저장
    public function nowtemp($nowtemp, $serialnumber){
        $stmt = $this->conn->prepare("UPDATE serial_number
        SET nowtemp = ?
        WHERE serialnumber = ?");
        $stmt -> bind_param("is", $nowtemp, $serialnumber);
        mysqli_commit($stmt);
        $result = $stmt->execute();
        
        //$stmt->close();
        
        
        if($result){
            $stmt->close();
            return true;
        }else{
            $stmt->close();
            return false;
        }
    }


    
    //데이터 가져오기
    public function importdata($email){
        $stmt = $this->conn->prepare("SELECT window, door,boiler,gas,nowtemp FROM nowstate WHERE serialnumber = (SELECT serialnumber FROM users WHERE email =?)");
        $stmt -> bind_param("s",$email);
        $stmt->execute();
        $serial_number = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $serial_number;

    }


    //mode 설정
    public function set_mode($saving_mode, $security_mode, $alarm_mode, $temp, $email){
        $stmt = $this->conn->prepare("UPDATE con_mode
        SET saving_mode = ?,security_mode = ?,alarm_mode = ?,temp =?
        WHERE serialnumber = (SELECT serialnumber FROM users WHERE email =?)");
        $stmt -> bind_param("sssis", $saving_mode, $security_mode, $alarm_mode, $temp, $email);
        mysqli_commit($stmt);
        $result = $stmt->execute();
        
        //$stmt->close();
        
        
        if($result){
            $stmt->close();
            return true;
        }else{
            $stmt->close();
            return false;
        }
    }

    //모드 정보 가져오기
    public function importmode($email){
        $stmt = $this->conn->prepare("SELECT saving_mode, security_mode, alarm_mode, temp FROM con_mode WHERE serialnumber = (SELECT serialnumber FROM users WHERE email =?)");
        $stmt -> bind_param("s",$email);
        $stmt->execute();
        $con_mode = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $con_mode;
    }
    
    //컨트롤러가 받아가는 데이터
    public function controller($serialnumber){
        $stmt = $this->conn->prepare("SELECT window, door,boiler,gas FROM serial_number WHERE serialnumber = ?");
        $stmt -> bind_param("s",$serialnumber);
        $stmt->execute();
        $serial_number = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $serial_number;

    }


   
    public function controller_mode($serialnumber){
        $stmt = $this->conn->prepare("SELECT saving_mode, security_mode,alarm_mode,temp FROM con_mode WHERE serialnumber = ?");
        $stmt -> bind_param("s",$serialnumber);
        $stmt->execute();
        $mode = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $mode;

    }

    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {
 
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }
 
    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {
 
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
 
        return $hash;
    }
    
}

?>