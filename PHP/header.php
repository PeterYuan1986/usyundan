<?php
if (isset($_GET['xl'])) {
    session_id(decode($_GET['xl']));
}
/*if(time()-session_id()>600)
{
    session_destroy();
}*/

session_start();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$access = "1D7F00B3B06A9135";
$userid = "elephxp";
$passwd = "ABC123efg@";
$developmodel="test";      //"prod" or "test" model

function encode($url){
   return base64_encode("yhy".$url);    
}

function decode($url){
    return ltrim(base64_decode($url),"yhy");
}
?>