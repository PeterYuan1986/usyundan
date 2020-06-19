<?php

if (isset($_GET['xl'])) {
    session_id(decode($_GET['xl']));
}
session_start();
$now = time();
if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {
    // this session has worn out its welcome; kill it and start a brand new one
    session_unset();
    session_destroy();
    header('Location:timeout.php');
}
// either new or old, it should live at most for another hour
$_SESSION['discard_after'] = $now + 600;

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

$createrequest_to = 'addressValidation.php?xl=' . encode(session_id());
$addressValidation_to = 'createresponse.php?xl=' . encode(session_id());
$createresponse_to = 'printLabel.php?xl=' . encode(session_id());
?>