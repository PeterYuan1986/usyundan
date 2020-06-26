<?php
require_once 'header.php';
startSID();
check_session_expiration();
$QUOTE_REQUEST = $_SESSION['CHECK']['SHIP_REQUEST'];
$price = round($QUOTE_REQUEST['COST'] * get_m2rate(),2);
$cost = $QUOTE_REQUEST['ORIGINALCOST'];
$info = json_encode($QUOTE_REQUEST);

$trade_order_id = genorder($price, $cost, $info); //新建订单ID

switch ($QUOTE_REQUEST['SERVICE']['Code']) {
    case "01": {
            $QUOTE_REQUEST['SERVICE']['Descripsion'] = "UPS Next Day Air";
            break;
        }
    case "02" : {
            $QUOTE_REQUEST['SERVICE']['Descripsion'] = "UPS 2nd Day Air";
            break;
        }
    case "03" : {
            $QUOTE_REQUEST['SERVICE']['Descripsion'] = "UPS Ground";

            break;
        }
    case "12" : {
            $QUOTE_REQUEST['SERVICE']['Descripsion'] = "UPS 3 Day Select";

            break;
        }
    case "13" : {
            $QUOTE_REQUEST['SERVICE']['Descripsion'] = "UPS Next Day Air Saver";

            break;
        }
    case "14" : {
            $QUOTE_REQUEST['SERVICE']['Descripsion'] = "UPS Next Day Air Early";

            break;
        }
    case "59" : {
            $QUOTE_REQUEST['SERVICE']['Descripsion'] = "UPS 2nd Day Air A.M.";

            break;
        }
    case "07" : {
            $QUOTE_REQUEST['SERVICE']['Descripsion'] = "UPS Worldwide Express";

            break;
        }
    case "08" : {
            $QUOTE_REQUEST['SERVICE']['Descripsion'] = "UPS 2nd Day Air";

            break;
        }
    case "11": {
            $QUOTE_REQUEST['SERVICE']['Descripsion'] = "UPS Standard";

            break;
        }
    case "54" : {
            $QUOTE_REQUEST['SERVICE']['Descripsion'] = "Worldwide Express Plus";

            break;
        }
    case "65" : {
            $QUOTE_REQUEST['SERVICE']['Descripsion'] = "UPS 2nd Day Air";

            break;
        }
    case "96" : {
            $QUOTE_REQUEST['SERVICE']['Descripsion'] = "UPS Worldwide Express Freight";

            break;
        }
    case "71" : {
            $QUOTE_REQUEST['SERVICE']['Descripsion'] = "UPS Worldwide Express Freight Midday";

            break;
        }
    case "92" : {
            $QUOTE_REQUEST['SERVICE']['Descripsion'] = "UPS SurePost Less than 1LB";

            break;
        }
    case "93" : {
            $QUOTE_REQUEST['SERVICE']['Descripsion'] = "UPS SurePost 1LB or greater";

            break;
        }
    case "94" : {
            $QUOTE_REQUEST['SERVICE']['Descripsion'] = "UPS SurePost BPM";

            break;
        }
    case "95" : {
            $QUOTE_REQUEST['SERVICE']['Descripsion'] = "UPS SurePost Media Mail";

            break;
        }
}
?>
<?php 
if(isset($_REQUEST['ok'])){
   $_SESSION['CONFIRM']['SHIP_REQUEST']= $QUOTE_REQUEST ;
   $_SESSION['CONFIRM']['SHIP_REQUEST']['USYUNDAN']['SID']=$trade_order_id;
   $_SESSION['CONFIRM']['SHIP_REQUEST']['USYUNDAN']['PRICE']=$price;
    header('Location:' . $review_to. encode(session_id()));
        exit;
}

if(isset($_REQUEST['no'])){    
    deleteorder($trade_order_id);
    header('Location:' . $addressValidation_to . encode(session_id()));
        exit;
}


?>

<!-- 分割线1 -->
<html>
    <body>
        <!-- 分割线2 -->
        <div><h2>您的订单已经确认！</h2></div>

        <div><h3>订单号：<?php print $trade_order_id; ?></h3></div>

        <div><h3><?php print $QUOTE_REQUEST['SERVICE']['Descripsion']; ?></h3></div>
        
        <div><h3>预计送达时间（工作日）：<?php ($QUOTE_REQUEST['GuaranteedDelivery']['BusinessDaysInTransit']=='')?print 'N/A':print $QUOTE_REQUEST['GuaranteedDelivery']['BusinessDaysInTransit']; ?></h3></div>
        
        <div>
            <div></div>
            <table>
                <tr> 
                    <td></td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td>发件人</td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td>收件人</td>
                </tr>
                <tr> 
                    <td>姓名:</td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php print strtoupper($QUOTE_REQUEST['namefrom']) ?></td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php print strtoupper($QUOTE_REQUEST['nameto']) ?></td>
                </tr>
                <tr> 
                    <td>地址:</td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php print strtoupper($QUOTE_REQUEST['ads1from'] . " " . $QUOTE_REQUEST['ads2from'] . " " . $QUOTE_REQUEST['ads3from']); ?></td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php print strtoupper($QUOTE_REQUEST['ads1to'] . " " . $QUOTE_REQUEST['ads2to'] . " " . $QUOTE_REQUEST['ads3to']); ?></td>
                </tr>
                <tr> 
                    <td>城市:</td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php print strtoupper($QUOTE_REQUEST['cityfrom']); ?></td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php print strtoupper($QUOTE_REQUEST['cityto']); ?></td>
                </tr>
                <tr> 
                    <td>州:</td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php print strtoupper($QUOTE_REQUEST['statefrom']); ?></td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php print strtoupper($QUOTE_REQUEST['stateto']); ?></td>
                </tr>
                <tr> 
                    <td>ZIPCODE:</td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php print strtoupper($QUOTE_REQUEST['zipcodefrom']); ?></td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php print strtoupper($QUOTE_REQUEST['zipcodeto']); ?></td>
                </tr>
                <tr> 
                    <td>包裹大小:</td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php print strtoupper($QUOTE_REQUEST['weight'] . " LBS"); ?></td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php print strtoupper($QUOTE_REQUEST['length'] . " inch x " . $QUOTE_REQUEST['width'] . " inch x " . $QUOTE_REQUEST['height'] . " inch"); ?></td>
                </tr>
            </table>
        </div> 

        <div><h3>目前网站仅支持微信支付，将以人民币的方式为您结算，实际付款：</h3>
            <h2>￥<?php print $price; ?>

        </h2></div>
    <div>
        <form method="post">
            <input type='submit' name='ok' value='微信支付'>
            <input type='submit' name='no' value='取消订单'>            
        </form>
    </div>







    <!-- 分割线3 -->
</body>
</html>