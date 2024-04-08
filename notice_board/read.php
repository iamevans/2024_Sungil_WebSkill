<?php
require_once "HwanMysql.php";

$query = "select * from notice where idx > ? order by idx desc limit 0, 1";
$connect = new HwanMysql("localhost", "root", "", "sungil");
$res = $connect->selectQuery1($query, [$_POST['idx']]);

if(count($res) == 1){
    $res[0]['result'] = 1;
    $rt = json_encode($res[0]);
}else{
    $rt = json_encode(["result"=>0]);
}

echo $rt;
