<?php
require_once 'header.php';
?>


<?php
//Configuration
$wsdl = "../SCHEMA-WSDLs/RateWS.wsdl";
$operation = "ProcessRate";
if ($developmodel == "test") {
    $endpointurl = 'https://wwwcie.ups.com/webservices/Ship';
} else {
    $endpointurl = 'https://onlinetools.ups.com/webservices/Ship';
}
$outputFileName = "lastresponse.xml";
$outputFileName_reqest = "lastrequest.xml";

function processRate($nameto, $namefrom, $ads1from, $ads2from, $ads3from, $ads1to, $ads2to, $ads3to, $cityfrom, $cityto, $statefrom, $stateto, $weight, $length, $width, $height, $zipcodefrom, $zipcodeto) {
    //create soap request
    $option['RequestOption'] = 'Shop';
    $request['Request'] = $option;

    $pickuptype['Code'] = '01';
    $pickuptype['Description'] = '';
    $request['PickupType'] = $pickuptype;

    $customerclassification['Code'] = '00';
    $customerclassification['Description'] = 'Classfication';
    $request['CustomerClassification'] = $customerclassification;

    $shipper['Name'] = 'Peter Yuan';
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

    $shipto['Name'] = $nameto;
    $addressTo['AddressLine'] = array($ads1to, $ads2to, $ads3to);
    $addressTo['City'] = $cityto;
    $addressTo['StateProvinceCode'] = $stateto;
    $addressTo['PostalCode'] = $zipcodeto;
    $addressTo['CountryCode'] = 'US';
    $addressTo['ResidentialAddressIndicator'] = '';
    $shipto['Address'] = $addressTo;
    $shipment['ShipTo'] = $shipto;

    $shipfrom['Name'] = $namefrom;
    $addressFrom['AddressLine'] = array($ads1from, $ads2from, $ads3from);
    $addressFrom['City'] = $cityfrom;
    $addressFrom['StateProvinceCode'] = $statefrom;
    $addressFrom['PostalCode'] = $zipcodefrom;
    $addressFrom['CountryCode'] = 'US';
    $shipfrom['Address'] = $addressFrom;
    $shipment['ShipFrom'] = $shipfrom;

    $service['Code'] = '93';
    $service['Description'] = 'Service Code';
    $shipment['Service'] = $service;

    $packaging1['Code'] = '02';
    $packaging1['Description'] = 'Rate';
    $package1['PackagingType'] = $packaging1;
    $dunit1['Code'] = 'IN';
    $dunit1['Description'] = 'inches';
    $dimensions1['Length'] = $length;
    $dimensions1['Width'] = $width;
    $dimensions1['Height'] = $height;
    $dimensions1['UnitOfMeasurement'] = $dunit1;
    $package1['Dimensions'] = $dimensions1;
    $punit1['Code'] = 'LBS';
    $punit1['Description'] = 'Pounds';
    $packageweight1['Weight'] = $weight;
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
        <form name="form" method="post"  action="#" id="loginForm">
            <div>Ship From:
                <div>
                    <label>Name:</label>
                    <input name="namefrom" type="text"  title="First name, Last Name" required="" >
                </div>
                <div>
                    <label>AddressLine 1:</label>
                    <input name ="ads1from" type="text"  title="AddressLine 1" required="" >
                </div>
                <div>
                    <label >AddressLine 2:</label>
                    <input name ="ads2from" type="text"  title="AddressLine 2" >
                </div>
                <div>
                    <label>AddressLine 3:</label>
                    <input name ="ads3from" type="text"  title="AddressLine 3">
                </div>
                <div>
                    <label>City</label>
                    <input name="cityfrom" type="text"  title="Please enter you City"  >
                </div>
                <div>
                    <label>State</label>
                    <select name="statefrom">
                        <option value="NC" selected>NC</option>
                        <option value="CA" selected>CA</option>
                        <option value="TX" selected>TX</option>
                    </select>
                </div>
                <div>
                    <label>ZipCode</label>
                    <input name="zipcodefrom" type="text"  title="Please enter you ZipCode" required="" >
                </div>

            </div>
            <div>
                <br><br><br>
            </div>

            <div>Ship To:
                <div>
                    <label>Name:</label>
                    <input name="nameto" type="text"  title="First name, Last Name" required="" >
                </div>
                <div>
                    <label>AddressLine 1:</label>
                    <input name="ads1to" type="text"  title="AddressLine 1" required="" >
                </div>
                <div>
                    <label >AddressLine 2:</label>
                    <input name="ads2to" type="text"  title="AddressLine 2" >
                </div>
                <div>
                    <label>AddressLine 3:</label>
                    <input name="ads3to" type="text"  title="AddressLine 3">
                </div>
                <div>
                    <label>City</label>
                    <input name="cityto" type="text"  title="Please enter you City"  >
                </div>
                <div>
                    <label>State</label>
                    <select name="stateto">
                        <option value="NC" selected>NC</option>
                        <option value="CA" selected>CA</option>
                        <option value="TX" selected>TX</option>
                    </select>
                </div>
                <div>
                    <label>ZipCode</label>
                    <input name="zipcodeto" type="text"  title="Please enter your ZipCode" required="" >
                </div>

            </div>
            <div>
                <br><br><br>
            </div>
            <div>Package:
                <div>
                    <label >Weight*:</label>
                    <input name="weight" type="text"  title="LBS" >
                </div>
                <div>
                    <label>Length</label>
                    <input name="length" type="text"  title="in" >
                </div>
                <div>
                    <label >Width</label>
                    <input name="width" type="text"  title="in" >
                </div>
                <div>
                    <label>Height</label>
                    <input name="height" type="text"  title="in">
                </div>
            </div>




            <div >
                <input type="submit" name="quote" value="Quote">  

            </div>


        </form>

        <div>
            <?php
            if (isset($_POST["quote"])) {
                $nameto = @$_POST["nameto"];
                $namefrom = @$_POST["namefrom"];
                $ads1from = @$_POST["ads1from"];
                $ads2from = @$_POST["ads2from"];
                $ads3from = @$_POST["ads3from"];
                $ads1to = @$_POST["ads1to"];
                $ads2to = @$_POST["ads2to"];
                $ads3to = @$_POST["ads3to"];
                $cityfrom = @$_POST["cityfrom"];
                $cityto = @$_POST["cityto"];
                $statefrom = @$_POST["statefrom"];
                $stateto = @$_POST["stateto"];
                $weight = @$_POST["weight"];

                if (@$_POST["length"] == '') {
                    $length = '1';
                } else {
                    $length = @$_POST["length"];
                }

                if (@$_POST["width"] == '') {
                    $width = '1';
                } else {
                    $width = @$_POST["width"];
                }

                if (@$_POST["height"] == '') {
                    $height = '1';
                } else {
                    $height = @$_POST["height"];
                }



                $zipcodefrom = @$_POST["zipcodefrom"];
                $zipcodeto = @$_POST["zipcodeto"];

                $resp = $client->__soapCall($operation, array(processRate($nameto, $namefrom, $ads1from, $ads2from, $ads3from, $ads1to, $ads2to, $ads3to, $cityfrom, $cityto, $statefrom, $stateto, $weight, $length, $width, $height, $zipcodefrom, $zipcodeto)));

                //get status
                //echo "Response Status: " . $resp->Response->ResponseStatus . "\n";
                //save soap request and response to file    
                $fw = fopen($outputFileName, 'w');
                fwrite($fw, $client->__getLastResponse() . "\n");
                fclose($fw);
                $fw = fopen($outputFileName_reqest, 'w');
                fwrite($fw, $client->__getLastRequest() . "\n");
                fclose($fw);
                //$response=$client->__soapcall("getLastRequest",NULL);
                // var_dump($resp);
                //echo "<br><br><br>";
                $array = json_decode(json_encode($resp), true);

                print('<br><br>From:' . $cityfrom . "," . $statefrom . "," . $zipcodefrom . "<br><br>");
                print('To:' . $cityto . "," . $stateto . "," . $zipcodeto . "<br><br>");
                print('Package weight:' . $weight . " LBS" . "<br><br>");
                print('Package dimision:' . $length . " inch x " . $width . " inch x " . $height . " inch<br><br><br>");

                foreach ($array['RatedShipment'] as $x) {
                    switch ($x['Service']['Code']) {
                        case "01": {
                                print(" Next Day Air ");
                                break;
                            }
                        case "02" : {
                                print(" 2nd Day Air ");
                                break;
                            }
                        case "03" : {
                                print(" Ground ");
                                break;
                            }
                        case "12" : {
                                print(" 3 Day Select ");
                                break;
                            }
                        case "13" : {
                                print(" Next Day Air Saver ");
                                break;
                            }
                        case "14" : {
                                print(" UPS Next Day Air Early ");
                                break;
                            }
                        case "59" : {
                                print(" 2nd Day Air A.M.");
                                break;
                            }
                        case "07" : {
                                print(" Worldwide Express ");
                                break;
                            }
                        case "08" : {
                                print(" Worldwide Expedited ");
                                break;
                            }
                        case "11": {
                                print(" Standard ");
                                break;
                            }
                        case "54" : {
                                print(" Worldwide Express Plus ");
                                break;
                            }
                        case "65" : {
                                print(" Saver ");
                                break;
                            }
                        case "96" : {
                                print(" UPS Worldwide Express Freight ");
                                break;
                            }
                        case "71" : {
                                print("UPS Worldwide Express Freight Midday");
                            }

                        case "92" : {
                                print("UPS SurePost Less than 1LB");
                            }
                        case "93" : {
                                print("UPS SurePost 1LB or greater");
                            }
                        case "94" : {
                                print("UPS SurePost BPM");
                            }
                        case "95" : {
                                print("UPS SurePost Media Mail");
                            }
                    }
                    echo "<br><br>";
                    print("BillingWeight:" . $x['BillingWeight']['Weight'] . " LBS");

                    if (@$x['GuaranteedDelivery']['BusinessDaysInTransit'] > 0) {
                        echo "<br><br>";
                        print("GuaranteedDelivery:" . @$x['GuaranteedDelivery']['BusinessDaysInTransit'] . " Day");
                    }
                    echo "<br><br>";
                    print("Total Charge:" . $x['TotalCharges']['MonetaryValue']);
                    echo "<br><br>";
                    print("Negotiate Charge:" . $x['NegotiatedRateCharges']['TotalCharge']['MonetaryValue']);

                    echo "<br><br><br><br>";
                }
            }
            ?>




        </div>



    </body>       

</html>