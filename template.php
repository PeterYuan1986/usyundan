<?php

//采集首页地址
$url = 'https://tools.usps.com/go/TrackConfirmAction?tLabels=9400109205568712238385';
//获取页面代码
$rs = file_get_contents($url);
//设置匹配正则
//$fp=fopen("text.txt","a");
//$fw=fwrite($fp,$rs);
//fclose($fp);
/* <I class=titles><A
  href="http://www.xz-src.com/"
  target=_blank>留住你身边的好男人</A></I> */
$preg = '/<i\s+class=\"titles\"><a\s+href=\"[^>]+\">(.*)<\/a><\/i>/i';
//进行正则搜索
preg_match_all($preg, $rs, $title);
//计算标题数量
$count = count($title[0]);
echo $count . "<br>";
//通过标题数量进行内容采集
for ($i = 0; $i < $count; $i++) {

//设置内容页地址
    $pr = '/<a\s+href=\"[^>]+\">/isU';
    preg_match_all($pr, $title[0][$i], $jurl);
    $substr = substr($jurl[0][0], 9);
    $curl = substr($substr, 0, -18);
//获取内容页代码
    $c = file_get_contents($curl);
//设置内容页匹配正则
    $pc = '/<a\s+href=\"[^>]+\">/i';
//进行正则匹配搜索
    preg_match($pc, $c, $content);
//输出标题
    echo $title[0][$i] . "<br>";
    echo $title[1][$i] . "<br>";
    $concount = count($content[0]);
    echo $concount . "<br>";
    echo $content[0][0];
    for ($j = 0; $j < $concount; $j++) {
        
    }
}
?>