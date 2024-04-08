<?php

class HwanMysql
{
    private $connect;
    public function __construct($host, $user, $passwd, $db)
    {
        // mysqli 객체 생성 시 오류가 발생하면 예외를 발생시킵니다.
        $this->connect = new mysqli($host, $user, $passwd, $db);
        if ($this->connect->connect_error) {
            throw new Exception("DB connect Error: " . $this->connect->connect_error);
        }
        $this->connect->set_charset("utf8");
    }

    // 소멸자에서 mysqli 객체의 close() 메서드를 호출합니다.
    public function __destruct()
    {
        $this->connect->close();
    }
    
    public function prepare($query) {
      try {
        // Prepare the statement using mysqli_stmt::prepare()
        $stmt = $this->connect->prepare($query);
        if ($stmt === false) {
          throw new Exception("Prepare Error: " . $this->connect->error);
        }
        return $stmt;
      } catch (Exception $e) {
        throw $e; // Re-throw the exception for handling in calling code
      }
    }

    public function insertQuery($query, $d){
        try{
            // prepare() 메서드의 반환값을 검사합니다.
            $stmt = $this->connect->prepare($query);
            if ($stmt === false) {
                throw new Exception("Prepare Error: " . $this->connect->error);
            }
            $param = array();
            $type = "";

            if(count($d)>0){
                for($i = 0 ; $i < count($d) ; $i++){
                    $param[] = &$d[$i];
                    $t = is_numeric($d[$i]) ? "i" : "s";
                    $type = $type.$t;
                }
                array_unshift($param, $type);
                // bind_param() 메서드의 반환값을 검사합니다.
                if (!call_user_func_array(array($stmt, "bind_param"), $param)) {
                    throw new Exception("Bind Error: " . $stmt->error);
                }
            }

            // execute() 메서드의 반환값을 검사합니다.
            if (!$stmt->execute()) {
                throw new Exception("Execute Error: " . $stmt->error);
            }
            $res = $stmt->affected_rows;
            $idx = $this->connect->insert_id;
            $stmt->close();

            if(is_numeric($res)) {
                //insert 쿼리 시 마지막 아이디를 반환
                if(strpos($query, "insert") > -1) return $idx;
                else return $res;
            }
            //else return $this->connect->error;
            else return -1;

        }catch (Exception $exception){
            //echo $exception->getMessage();
            return -1;
        }
    }

//    public function selectQuery1($query, $d) {
//        try{
//            // prepare() 메서드의 반환값을 검사합니다.
//            $stmt = $this->connect->prepare($query);
//            if ($stmt === false) {
//                throw new Exception("Prepare Error: " . $this->connect->error);
//            }
//            $param = array();
//            $type = "";
//
//            if(count($d)>0){
//                for($i = 0 ; $i < count($d) ; $i++){
//                    $param[] = &$d[$i];
//                    $t = is_numeric($d[$i]) ? "i" : "s";
//                    $type = $type.$t;
//                }
//                array_unshift($param, $type);
//                // bind_param() 메서드의 반환값을 검사합니다.
//                if (!call_user_func_array(array($stmt, "bind_param"), $param)) {
//                    throw new Exception("Bind Error: " . $stmt->error);
//                }
//            }
//
//            // execute() 메서드의 반환값을 검사합니다.
//            if (!$stmt->execute()) {
//                throw new Exception("Execute Error: " . $stmt->error);
//            }
//            $res = $stmt->get_result();
//            $stmt->close();
//
//            return $res;
//
//        }catch (Exception $exception){
//            return $exception->getMessage();
//        }
//    }
    
    
    public function selectQuery1($query, $d){
  try{
    $stmt = $this->connect->prepare($query);
    if ($stmt === false) {
      throw new Exception("Prepare Error: " . $this->connect->error);
    }
    $param = array();
    $type = "";

    if(count($d)>0){
      for($i = 0 ; $i < count($d) ; $i++){
        $param[] = &$d[$i]; // Pass by reference
        $t = is_numeric($d[$i]) ? "i" : "s";
        $type = $type.$t;
      }
      array_unshift($param, $type);
      if (!call_user_func_array(array($stmt, "bind_param"), $param)) {
        throw new Exception("Bind Error: " . $stmt->error);
      }
    }

    if (!$stmt->execute()) {
      throw new Exception("Execute Error: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $res = array();

    while ($row = $result->fetch_array()){
      $res[] = $row;
    }
    $stmt->close();

    return $res;
  }catch (Exception $exception){
    echo('fail');
    echo $exception->getMessage(); // Consider more specific handling
  }
}

//    public function selectQuery1($query, $d){
//        try{
//            // prepare() 메서드의 반환값을 검사합니다.
//            $stmt = $this->connect->prepare($query);
//            if ($stmt === false) {
//                throw new Exception("Prepare Error: " . $this->connect->error);
//            }
//            $param = array();
//            $type = "";
//
//            if(count($d)>0){
//                for($i = 0 ; $i < count($d) ; $i++){
//                    $param[] = $d[$i];
//                    $t = is_numeric($d[$i]) ? "i" : "s";
//                    $type = $type.$t;
//                }
//                array_unshift($param, $type);
//                // bind_param() 메서드의 반환값을 검사합니다.
//                if (!call_user_func_array(array($stmt, "bind_param"), $param)) {
//                    throw new Exception("Bind Error: " . $stmt->error);
//                }
//            }
//
//            // execute() 메서드의 반환값을 검사합니다.
//            if (!$stmt->execute()) {
//                throw new Exception("Execute Error: " . $stmt->error);
//            }
//
//            $result = $stmt->get_result();
//            $res = array();
//
//            while ($row = $result->fetch_array()){
//                $res[] = $row;
//            }
//            $stmt->close();
//
//            return $res;
//        }catch (Exception $exception){
//            echo('fail');
//            echo $exception->getMessage();
//        }
//    }

    public function selectQueryCount($query, $d){
        try{
            // selectQuery() 메서드의 반환값을 검사합니다.
            $res = $this->selectQuery($query, $d);
            if (empty($res)) {
                throw new Exception("Select Error: No result found");
            }
            return count($res);
        }catch (Exception $exception) {
            echo $exception->getMessage();
        }

    }

}
