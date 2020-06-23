<?php

//服务器相关信息
$servername = "localhost";
$username = "root";
$password = "";
$database = "usyundan";
$tablename = 'date';
$column_orderid = 'orderid';
$column_price = 'price';
$column_cost = 'cost';
$column_time = 'date';
$column_info = 'info';
$column_status = 'status';
$column_tid = 'transactionid';
$column_tracking_number = 'trackingnumber';


// Create connection

$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (mysqli_connect_error($conn)) {
    die("Connection to Server failed");
}


//UPS 相关信息
$access = "1D7F00B3B06A9135";   //UPC ACCESS
$userid = "elephxp";            //UPS USER ID
$passwd = "ABC123efg@";         //UPS USER PWD
$developmodel = "test";         //"prod" or "test" model
//页面跳转索引
$createrequest_to = 'addressValidation.php?xl=';
$addressValidation_to = 'createresponse.php?xl=';
$createresponse_to = './pay/pay.php?xl=';

//支付平台账户密码
//$appid = '201906129696'; //测试账户，
//$appsecret = 'b24f797cfff12c0aadff9b6ce4169bd2'; //测试账户，


//查询订单信息
function lookup_info($orderid) {
    global $tablename, $column_orderid, $conn;
    $sql = "SELECT info FROM " . $tablename . " WHERE " . $column_orderid . "='" . $orderid . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    return json_decode($row[0]);
}

//返回当前汇率
function get_m2rate() {
    global $conn;
    $sql = "SELECT rate FROM m2rate ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    return $row[0];
}

//设置汇率
function set_m2rate($a) {
    global $conn;
    $sql = "INSERT INTO m2rate( rate ) VALUES (" . $a . ")";
    $result = mysqli_query($conn, $sql);
    return $result;
}

//生成订单号，并与服务器验证是否唯一，将订单信息及价钱更新
function genorder($price, $cost, $info) {
    global $tablename, $column_orderid, $conn, $column_price, $column_cost, $column_info;
    do {
        $today = date("ymd");
        $rand = sprintf("%04d", rand(0, 9999));
        $unique = $today . $rand;
        $sql = "INSERT INTO " . $tablename . " (" . $column_orderid . ", " . $column_price . ", " . $column_cost . ", " . $column_info . ") VALUES ('" . $unique . "','" . $price . "','" . $cost . "','" . $info . "')";
        $result = mysqli_query($conn, $sql);
    } while (!$result);
    return $unique;
}

//更新订单状态
function updateorder_status($orderid, $status) {
    global $tablename, $column_orderid, $conn, $column_status;
    $sql = "UPDATE " . $tablename . " SET " . $column_status . "='" . $status . "' WHERE " . $column_orderid . "=" . $orderid;
    $result = mysqli_query($conn, $sql);
    return $result;
}

//获取订单相关信息
function getorder_allinfo($orderid) {
    global $tablename, $column_tid, $column_orderid, $conn, $column_price, $column_cost, $column_info, $column_status, $column_time, $column_tracking_number;
    $sql = "SELECT " . $column_tid . "," . $column_price . "," . $column_cost . "," . $column_status . "," . $column_time . "," . $column_tracking_number . "," . $column_info . " FROM " . $tablename . " WHERE " . $column_orderid . "=" . $orderid;
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    return $row;  
}

//更新订单收款方流水号
function updateorder_transaction_id($orderid, $tid) {
    global $tablename, $column_tid, $column_orderid, $conn;
    $sql = "UPDATE " . $tablename . " SET " . $column_tid . "='" . $tid . "' WHERE " . $column_orderid . "=" . $orderid;
    $result = mysqli_query($conn, $sql);
    return $result;
}

//更新订单label tracking number
function updateorder_label($orderid, $label) {
    global $tablename,$column_tracking_number,$column_orderid,$conn;
    $sql = "UPDATE " . $tablename . " SET " . $column_tracking_number . "='" . $label . "' WHERE " . $column_orderid . "=" . $orderid;
    $result = mysqli_query($conn, $sql);
    return $result;
}

//如果有xl=，用此命令导入session
function startSID() {
    if (isset($_GET['xl'])) {
        $SID = decode($_GET['xl']);
        session_id($SID);
    }
    session_start();
}

//检查session是否过期，15分钟
function check_session_expiration() {
    $now = time();
    if ((isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after'] ) || !isset($_SESSION['discard_after'])) {
        // this session has worn out its welcome; kill it and start a brand new one 
        header('Location:timeout.php?xl=' . encode(session_id()));
    }
// either new or old, it should live at most for another hour
    else {
        $_SESSION['discard_after'] = $now + 900;  //过期15分钟session销毁跳到timeout
    }
}

//session加密
function encode($url) {
    return base64_encode("yhy" . $url);
}

//session解密
function decode($url) {
    return ltrim(base64_decode($url), "yhy");
}
?>
