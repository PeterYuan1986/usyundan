<?php

if (isset($_GET['xl'])) {
    session_id(decode($_GET['xl']));
}
session_start();
$now = time();
if ((isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after'] ) || !isset($_SESSION['discard_after'])) {
    // this session has worn out its welcome; kill it and start a brand new one 
    header('Location:timeout.php?xl='. encode(session_id()));
}
// either new or old, it should live at most for another hour
else {
    $_SESSION['discard_after'] = $now + 900;  //过期15分钟session销毁跳到timeout
}

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$access = "1D7F00B3B06A9135";
$userid = "elephxp";
$passwd = "ABC123efg@";
$developmodel = "test";      //"prod" or "test" model

function encode($url) {
    return base64_encode("yhy" . $url);
}

function decode($url) {
    return ltrim(base64_decode($url), "yhy");
}

$createrequest_to = 'addressValidation.php?xl=';
$addressValidation_to = 'createresponse.php?xl=';
$createresponse_to = 'printLabel.php?xl=';
?>