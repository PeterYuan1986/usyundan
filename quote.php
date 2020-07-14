<?php
require_once 'ydheader.php';
session_start();

function adjust_price($orignal, $cost) {
    return round(min($cost + 2, max($cost + 0.5, 1.05 * $cost), $orignal), 2);
}
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

    $shipto['Name'] = '';
    $addressTo['AddressLine'] = '';
    $addressTo['City'] = $QUOTE_REQUEST['cityto'];
    $addressTo['StateProvinceCode'] = $QUOTE_REQUEST['stateto'];
    $addressTo['PostalCode'] = $QUOTE_REQUEST['zipcodeto'];
    $addressTo['CountryCode'] = 'US';
    $addressTo['ResidentialAddressIndicator'] = @$QUOTE_REQUEST['addressTo']['ResidentialAddressIndicator'];
    $shipto['Address'] = $addressTo;
    $shipment['ShipTo'] = $shipto;

    $shipfrom['Name'] = '';
    $addressFrom['AddressLine'] = '';
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
    $dimensions1['Length'] = '1';
    $dimensions1['Width'] = '1';
    $dimensions1['Height'] = '1';
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

<html class="no-js" lang="en">

    <body>
        <form  action="quote.php"   name="form" method="get"   id="loginForm">
            <div>起始地:
                <div>
                    <label>城市</label>
                    <input name="cityfrom" type="text"   placeholder="请输入起始城市名称" required="" >
                    <span> &nbsp; &nbsp; </span>
                    <label>州</label>
                    <select name="statefrom">
                        <option value="AK">AK - Alaska</option>
                        <option value="AL">AL - Alabama</option>
                        <option value="AR">AR - Arkansas</option>
                        <option value="AZ">AZ - Arizona</option>
                        <option value="CA">CA - California</option>
                        <option value="CO">CO - Colorado</option>
                        <option value="CT">CT - Connecticut</option>
                        <option value="DC">DC - District of Columbia</option>
                        <option value="DE">DE - Delaware</option>
                        <option value="FL">FL - Florida</option>
                        <option value="GA">GA - Georgia</option>
                        <option value="HI">HI - Hawaii</option>
                        <option value="IA">IA - Iowa</option>
                        <option value="ID">ID - Idaho</option>
                        <option value="IL">IL - Illinois</option>
                        <option value="IN">IN - Indiana</option>
                        <option value="KS">KS - Kansas</option>
                        <option value="KY">KY - Kentucky</option>
                        <option value="LA">LA - Louisiana</option>
                        <option value="MA">MA - Massachusetts</option>
                        <option value="MD">MD - Maryland</option>
                        <option value="ME">ME - Maine</option>
                        <option value="MI">MI - Michigan</option>
                        <option value="MN">MN - Minnesota</option>
                        <option value="MO">MO - Missouri</option>
                        <option value="MS">MS - Mississippi</option>
                        <option value="MT">MT - Montana</option>
                        <option value="NC">NC - North Carolina</option>
                        <option value="ND">ND - North Dakota</option>
                        <option value="NE">NE - Nebraska</option>
                        <option value="NH">NH - New Hampshire</option>
                        <option value="NJ">NJ - New Jersey</option>
                        <option value="NM">NM - New Mexico</option>
                        <option value="NV">NV - Nevada</option>
                        <option value="NY">NY - New York</option>
                        <option value="OH">OH - Ohio</option>
                        <option value="OK">OK - Oklahoma</option>
                        <option value="OR">OR - Oregon</option>
                        <option value="PA">PA - Pennsylvania</option>
                        <option value="PR">PR - Puerto Rico</option>
                        <option value="RI">RI - Rhode Island</option>
                        <option value="SC">SC - South Carolina</option>
                        <option value="SD">SD - South Dakota</option>
                        <option value="TN">TN - Tennessee</option>
                        <option value="TX">TX - Texas</option>
                        <option value="UT">UT - Utah</option>
                        <option value="VA">VA - Virginia</option>
                        <option value="VI">VI - US Virgin Islands</option>
                        <option value="VT">VT - Vermont</option>
                        <option value="WA">WA - Washington</option>
                        <option value="WI">WI - Wisconsin</option>
                        <option value="WV">WV - West Virginia</option>
                        <option value="WY">WY - Wyoming</option>
                    </select>
                    <span> &nbsp; &nbsp; </span>
                    <label>邮编</label>
                    <input name="zipcodefrom" type="text"  placeholder="请输入起始地邮编" required="" >
                </div>

            </div>
            <div>
                <br>
            </div>

            <div>目的地:
                <div>
                    <label>城市</label>
                    <input name="cityto" type="text"  placeholder="请输入目的地城市名称"  required="" >
                    <span> &nbsp; &nbsp; </span>
                    <label>州</label>
                    <select name="stateto">

                        <option value="AK">AK - Alaska</option>
                        <option value="AL">AL - Alabama</option>
                        <option value="AR">AR - Arkansas</option>
                        <option value="AZ">AZ - Arizona</option>
                        <option value="CA">CA - California</option>
                        <option value="CO">CO - Colorado</option>
                        <option value="CT">CT - Connecticut</option>
                        <option value="DC">DC - District of Columbia</option>
                        <option value="DE">DE - Delaware</option>
                        <option value="FL">FL - Florida</option>
                        <option value="GA">GA - Georgia</option>
                        <option value="HI">HI - Hawaii</option>
                        <option value="IA">IA - Iowa</option>
                        <option value="ID">ID - Idaho</option>
                        <option value="IL">IL - Illinois</option>
                        <option value="IN">IN - Indiana</option>
                        <option value="KS">KS - Kansas</option>
                        <option value="KY">KY - Kentucky</option>
                        <option value="LA">LA - Louisiana</option>
                        <option value="MA">MA - Massachusetts</option>
                        <option value="MD">MD - Maryland</option>
                        <option value="ME">ME - Maine</option>
                        <option value="MI">MI - Michigan</option>
                        <option value="MN">MN - Minnesota</option>
                        <option value="MO">MO - Missouri</option>
                        <option value="MS">MS - Mississippi</option>
                        <option value="MT">MT - Montana</option>
                        <option value="NC">NC - North Carolina</option>
                        <option value="ND">ND - North Dakota</option>
                        <option value="NE">NE - Nebraska</option>
                        <option value="NH">NH - New Hampshire</option>
                        <option value="NJ">NJ - New Jersey</option>
                        <option value="NM">NM - New Mexico</option>
                        <option value="NV">NV - Nevada</option>
                        <option value="NY">NY - New York</option>
                        <option value="OH">OH - Ohio</option>
                        <option value="OK">OK - Oklahoma</option>
                        <option value="OR">OR - Oregon</option>
                        <option value="PA">PA - Pennsylvania</option>
                        <option value="PR">PR - Puerto Rico</option>
                        <option value="RI">RI - Rhode Island</option>
                        <option value="SC">SC - South Carolina</option>
                        <option value="SD">SD - South Dakota</option>
                        <option value="TN">TN - Tennessee</option>
                        <option value="TX">TX - Texas</option>
                        <option value="UT">UT - Utah</option>
                        <option value="VA">VA - Virginia</option>
                        <option value="VI">VI - US Virgin Islands</option>
                        <option value="VT">VT - Vermont</option>
                        <option value="WA">WA - Washington</option>
                        <option value="WI">WI - Wisconsin</option>
                        <option value="WV">WV - West Virginia</option>
                        <option value="WY">WY - Wyoming</option>
                    </select>
                    <span> &nbsp; &nbsp; </span>
                    <label>邮编</label>
                    <input name="zipcodeto" type="text"  placeholder="请输入目的地邮编" required="" >
                    <span> &nbsp; &nbsp; </span>
                    <label>是否住宅地址</label>
                    <input name="resid" type="checkbox" value="0">
                </div>
            </div>
            <div>
                <br>
            </div>
            <div>包裹信息:
                <div>
                    <label >重量:</label>
                    <input name="weight" type="text"  title="LBS" placeholder="请输入包裹重量" required="" >
                </div>
            </div>
            <div>
                <br>
            </div>
            <div >
                <input type="submit" name="quote" value="查询价格">

            </div>
            <div>
                <br><br>
            </div>

        </form>

        <div>
            <?php
            if (isset($_GET["cityfrom"])) {
                $QUOTE_REQUEST['cityfrom'] = @$_GET["cityfrom"];
                $QUOTE_REQUEST['cityto'] = @$_GET["cityto"];
                $QUOTE_REQUEST['statefrom'] = @$_GET["statefrom"];
                $QUOTE_REQUEST['stateto'] = @$_GET["stateto"];
                $QUOTE_REQUEST['weight'] = @$_GET["weight"];
                $QUOTE_REQUEST['zipcodefrom'] = @$_GET["zipcodefrom"];
                $QUOTE_REQUEST['zipcodeto'] = @$_GET["zipcodeto"];
                if (@$_GET["resid"] != NULL) {
                    $QUOTE_REQUEST['addressTo']['ResidentialAddressIndicator'] = 1;
                } ELSE {
                    $QUOTE_REQUEST['addressTo']['ResidentialAddressIndicator'] = 0;
                }
                try {
                    $resp = $client->__soapCall($operation, array(processRate($QUOTE_REQUEST)));
                } catch (\SoapFault $fault) {
                    print"<h2>您输入的地址与邮编不符，请重新输入！<br><br><br></h2>";
                }
                //get status
                //echo "Response Status: " . $resp->Response->ResponseStatus . "\n";
                //save soap request and response to file
                /* $fw = fopen($outputFileName, 'w');
                  fwrite($fw, $client->__getLastResponse() . "\n");
                  fclose($fw);
                  $fw = fopen($outputFileName_reqest, 'w');
                  fwrite($fw, $client->__getLastRequest() . "\n");
                  fclose($fw); */
                //$response=$client->__soapcall("getLastRequest",NULL);
                // var_dump($resp);
                //echo "<br><br><br>";
                $array = json_decode(json_encode(@$resp), true);
                if ($array != NUll) {
                    ?>

                    <div>
                        <table>
                            <tr> 
                                <td></td>
                                <td>&nbsp;&nbsp;&nbsp;</td>
                                <td>发件城市</td>
                                <td>&nbsp;&nbsp;&nbsp;</td>
                                <td>收件城市</td>
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
                                <td>邮编:</td>
                                <td>&nbsp;&nbsp;&nbsp;</td>
                                <td><?php print strtoupper($QUOTE_REQUEST['zipcodefrom']); ?></td>
                                <td>&nbsp;&nbsp;&nbsp;</td>
                                <td><?php print strtoupper($QUOTE_REQUEST['zipcodeto']); ?></td>
                            </tr>
                            <tr> 
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><?php
                                    if ($QUOTE_REQUEST['addressTo']['ResidentialAddressIndicator'])
                                        print " 住宅地址";
                                    else
                                        print "非住宅地址";
                                    ?></td>
                            </tr>
                            <tr> 
                                <td>包裹信息:</td>
                                <td>&nbsp;&nbsp;&nbsp;</td>
                                <td><?php print $QUOTE_REQUEST['weight'] . " 磅"; ?></td>
                                <td>&nbsp;&nbsp;&nbsp;</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>   


                    <div>
                        <br><br>
                    </div>
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
                            foreach (@$array['RatedShipment'] as $x) {
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
                                    @$x['GuaranteedDelivery']['BusinessDaysInTransit'] === NULL ? print 'N/A' : print $x['GuaranteedDelivery']['BusinessDaysInTransit'];
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
                        }
                    }
                    ?>      
                </table>
            </form>
        </div>

    </body>

</html>