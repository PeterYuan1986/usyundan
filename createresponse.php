<?php
require_once 'header.php';
startSID();
check_session_expiration();
?>


<?php
//Configuration
$wsdl = "./SCHEMA-WSDLs/RateWS.wsdl";
$operation = "ProcessRate";
$endpointurl = 'https://onlinetools.ups.com/webservices/Rate';
$outputFileName = "lastresponse.xml";
$outputFileName_reqest = "lastrequest.xml";

function processRate($QUOTE_REQUEST) {
    //create soap request
    $option['RequestOption'] = 'Shop';
    $request['Request'] = $option;

    $pickuptype['Code'] = '01';
    $pickuptype['Description'] = '';
    $request['PickupType'] = $pickuptype;

    $customerclassification['Code'] = '00';
    $customerclassification['Description'] = 'Classfication';
    $request['CustomerClassification'] = $customerclassification;

    $shipper['Name'] = 'FULFILLMENT';
    $shipper['ShipperNumber'] = '86F304';
    $address['AddressLine'] = array
        (
        '7101 NC HIGHWAY 751',
        ''
    );
    $address['City'] = 'DURHAM';
    $address['StateProvinceCode'] = 'NC';
    $address['PostalCode'] = '27707';
    $address['CountryCode'] = 'US';
    $shipper['Address'] = $address;
    $shipment['Shipper'] = $shipper;

    $shipto['Name'] = $QUOTE_REQUEST['nameto'];
    $addressTo['AddressLine'] = array($QUOTE_REQUEST['ads1to'], $QUOTE_REQUEST['ads2to'], $QUOTE_REQUEST['ads3to']);
    $addressTo['City'] = $QUOTE_REQUEST['cityto'];
    $addressTo['StateProvinceCode'] = $QUOTE_REQUEST['stateto'];
    $addressTo['PostalCode'] = $QUOTE_REQUEST['zipcodeto'];
    $addressTo['CountryCode'] = 'US';
    $addressTo['ResidentialAddressIndicator'] = @$QUOTE_REQUEST['addressTo']['ResidentialAddressIndicator'];
    $shipto['Address'] = $addressTo;
    $shipment['ShipTo'] = $shipto;

    $shipfrom['Name'] = $QUOTE_REQUEST['namefrom'];
    $addressFrom['AddressLine'] = array($QUOTE_REQUEST['ads1from'], $QUOTE_REQUEST['ads2from'], $QUOTE_REQUEST['ads3from']);
    $addressFrom['City'] = $QUOTE_REQUEST['cityfrom'];
    $addressFrom['StateProvinceCode'] = $QUOTE_REQUEST['statefrom'];
    $addressFrom['PostalCode'] = $QUOTE_REQUEST['zipcodefrom'];
    $addressFrom['CountryCode'] = 'US';
    $shipfrom['Address'] = $addressFrom;
    $shipment['ShipFrom'] = $shipfrom;

    $service['Code'] = '03';
    $service['Description'] = 'Service Code';
    $shipment['Service'] = $service;

    $packaging1['Code'] = '00';
    $packaging1['Description'] = 'Rate';
    $package1['PackagingType'] = $packaging1;
    $dunit1['Code'] = 'IN';
    $dunit1['Description'] = 'inches';
    $dimensions1['Length'] = $QUOTE_REQUEST['length'];
    $dimensions1['Width'] = $QUOTE_REQUEST['width'];
    $dimensions1['Height'] = $QUOTE_REQUEST['height'];
    $dimensions1['UnitOfMeasurement'] = $dunit1;
    $package1['Dimensions'] = $dimensions1;
    $punit1['Code'] = 'LBS';
    $punit1['Description'] = 'Pounds';
    $packageweight1['Weight'] = $QUOTE_REQUEST['weight'];
    $packageweight1['UnitOfMeasurement'] = $punit1;
    $package1['PackageWeight'] = $packageweight1;

    /* $packaging2['Code'] = '02';
      $packaging2['Description'] = 'Rate';
      $package2['PackagingType'] = $packaging2;
      $dunit2['Code'] = 'IN';
      $dunit2['Description'] = 'inches';
      $dimensions2['Length'] = '3';
      $dimensions2['Width'] = '5';
      $dimensions2['Height'] = '8';
      $dimensions2['UnitOfMeasurement'] = $dunit2;
      $package2['Dimensions'] = $dimensions2;
      $punit2['Code'] = 'LBS';
      $punit2['Description'] = 'Pounds';
      $packageweight2['Weight'] = '2';
      $packageweight2['UnitOfMeasurement'] = $punit2;
      $package2['PackageWeight'] = $packageweight2; */

    $shipment['Package'] = array($package1);
    $shipment['ShipmentServiceOptions'] = '';
    $shipment['LargePackageIndicator'] = '';
    $ShipmentRatingOptions ['NegotiatedRatesIndicator'] = '';
    $shipment['ShipmentRatingOptions'] = $ShipmentRatingOptions;


    $request['Shipment'] = $shipment;

    //echo "Request.......\n";
    //print_r($request);
    //echo "\n\n";
    return $request;
}

try {
    $mode = array
        (
        'soap_version' => 'SOAP_1_1', // use soap 1.1 client
        'trace' => 1
    );

    // initialize soap client
    $client = new SoapClient($wsdl, $mode);

    //set endpoint url
    $client->__setLocation($endpointurl);


    //create soap header
    $usernameToken['Username'] = $userid;
    $usernameToken['Password'] = $passwd;
    $serviceAccessLicense['AccessLicenseNumber'] = $access;
    $upss['UsernameToken'] = $usernameToken;
    $upss['ServiceAccessToken'] = $serviceAccessLicense;

    $header = new SoapHeader('http://www.ups.com/XMLSchema/XOLTWS/UPSS/v1.0', 'UPSSecurity', $upss);
    $client->__setSoapHeaders($header);


    //get response
    //print_r($array );
    //echo "<br><br><br>";    
} catch (Exception $ex) {
    print_r($ex);
}
?>

<?php
//从quote.php界面提取参数
$QUOTE_REQUEST = $_SESSION['RATE']['SHIP_REQUEST'];

try {
    $resp = $client->__soapCall($operation, array(processRate($QUOTE_REQUEST)));
} catch (\SoapFault $fault) {
    //trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
    var_dump($fault->faultcode);
    var_dump($fault->faultstring);
    var_dump($fault->detail);
    die('Errore chiamata webservice UPS');
}

//get status
//echo "Response Status: " . $resp->Response->ResponseStatus . "\n";
//save soap request and response to file    
$fw = fopen("./label/" . $outputFileName, 'w');
fwrite($fw, $client->__getLastResponse() . "\n");
fclose($fw);
$fw = fopen("./label/" . $outputFileName_reqest, 'w');
fwrite($fw, $client->__getLastRequest() . "\n");
fclose($fw);
//$response=$client->__soapcall("getLastRequest",NULL);
// var_dump($resp);
//echo "<br><br><br>";
$array = json_decode(json_encode($resp), true);
?>

<?php
//根据service跳转页面
foreach ($array['RatedShipment'] as $x) {
    $option = "CHECK" . $x['Service']['Code'];
    if (isset($_REQUEST[$option])) {
        $_SESSION['CHECK']['SHIP_REQUEST'] = $QUOTE_REQUEST;
        $_SESSION['CHECK']['SHIP_REQUEST']['GuaranteedDelivery']['BusinessDaysInTransit']=$x['GuaranteedDelivery']['BusinessDaysInTransit'];
        $_SESSION['CHECK']['SHIP_REQUEST']['SERVICE']['Code'] = $x['Service']['Code'];
        $_SESSION['CHECK']['SHIP_REQUEST']['COST'] = adjust_price($x['TotalCharges']['MonetaryValue'], $x['NegotiatedRateCharges']['TotalCharge']['MonetaryValue']);
        $_SESSION['CHECK']['SHIP_REQUEST']['ORIGINALCOST'] = $x['NegotiatedRateCharges']['TotalCharge']['MonetaryValue'];
        header('Location:' . $createresponse_to . encode(session_id()));
        exit;
    }
}
?>
<?php

function adjust_price($orignal, $cost) {
    return round(min($cost + 2, max($cost + 0.5, 1.05 * $cost), $orignal), 2);
}
?>

<!-- 分割线1 -->
<html class="no-js" lang="en">

    <body> 
        <!-- 分割线2 -->
        <div>
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
                    <td>包裹信息:</td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php print $QUOTE_REQUEST['weight'] . " 磅"; ?></td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php print $QUOTE_REQUEST['length'] . " 英寸 x " . $QUOTE_REQUEST['width'] . " 英寸 x " . $QUOTE_REQUEST['height'] . " 英寸"; ?></td>
                </tr>
            </table>
        </div>        
        <div>
            <h1></h1>      
            
        </div>
        <div>
            <form action="#" method="get" name="form">
                <table>
                    <tr>
                        <th>可选服务种类</th>
                        <th>计费重量(磅) </th>
                        <th>预计送达时间(工作日)</th>
                        <th>UPS官方价格</th>
                        <th>实收（美元）</th>                                      
                        <th></th>
                    </tr>
                    
                    <?php
                    foreach ($array['RatedShipment'] as $x) {
                        print "<tr>";
                        switch ($x['Service']['Code']) {
                            case "01": {
                                    print("<td> UPS Next Day Air </td>");
                                    break;
                                }
                            case "02" : {
                                    print(" <td> UPS 2nd Day Air </td>");
                                    break;
                                }
                            case "03" : {
                                    print(" <td> UPS Ground </td>");
                                    break;
                                }
                            case "12" : {
                                    print(" <td> UPS 3 Day Select </td>");
                                    break;
                                }
                            case "13" : {
                                    print(" <td> UPS Next Day Air Saver </td>");
                                    break;
                                }
                            case "14" : {
                                    print(" <td> UPS Next Day Air Early </td>");
                                    break;
                                }
                            case "59" : {
                                    print(" <td> 2nd Day Air A.M.</td>");
                                    break;
                                }
                            case "07" : {
                                    print(" <td> Worldwide Express </td>");
                                    break;
                                }
                            case "08" : {
                                    print(" <td> Worldwide Expedited </td>");
                                    break;
                                }
                            case "11": {
                                    print(" <td> Standard </td>");
                                    break;
                                }
                            case "54" : {
                                    print(" <td> Worldwide Express Plus </td>");
                                    break;
                                }
                            case "65" : {
                                    print(" <td> Saver </td>");
                                    break;
                                }
                            case "96" : {
                                    print(" <td> UPS Worldwide Express Freight </td>");
                                    break;
                                }
                            case "71" : {
                                    print("<td> UPS Worldwide Express Freight Midday</td>");
                                    break;
                                }
                            case "92" : {
                                    print("<td> UPS SurePost Less than 1LB</td>");
                                    break;
                                }
                            case "93" : {
                                    print("<td> UPS SurePost 1LB or greater</td>");
                                    break;
                                }
                            case "94" : {
                                    print("<td> UPS SurePost BPM</td>");
                                    break;
                                }
                            case "95" : {
                                    print("<td> UPS SurePost Media Mail</td>");
                                    break;
                                }
                        }
                        print("<td>" . $x['BillingWeight']['Weight'] . " </td>");

                        if (@$x['GuaranteedDelivery']['BusinessDaysInTransit'] > 0) {
                            print("<td>"); 
                            @$x['GuaranteedDelivery']['BusinessDaysInTransit']===NULL?print 'N/A':print $x['GuaranteedDelivery']['BusinessDaysInTransit']; 
                            print ("</td>");
                        } else
                            print("<td></td>");
                        print("<td>$" . $x['TotalCharges']['MonetaryValue'] . "</td>");
                        print("<td>$" . adjust_price($x['TotalCharges']['MonetaryValue'], $x['NegotiatedRateCharges']['TotalCharge']['MonetaryValue']) . "</td>");
                        ?>
                        <td><button name='<?php print("CHECK" . $x['Service']['Code']); ?>'  type="submit" > 确认 </button> </td>
                        </tr>
                        <?php
                    }
                    ?>      
                </table>
            </form>
            <a href="createrequest.php">返回上一级</a>
        </div>
        
        <!-- 分割线3 -->

    </body>   
</html>