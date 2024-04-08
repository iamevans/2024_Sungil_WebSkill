<?php
require_once "HwanMysql.php";

$host = "localhost";
$user = "root";
$pw = "";
$db = "sungil";
$connect = new HwanMysql($host, $user, $pw, $db);


if($_POST['mode'] == "new"){
    $query = "insert into notice(msg) values (?)";
    $d = [$_POST['msg']];
    $res = $connect->insertQuery($query, $d);

    print $res;
}else if($_POST['mode'] == "modify"){

}else if($_POST['mode'] == "delete"){

}
