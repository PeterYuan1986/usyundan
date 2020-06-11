
<?php

require 'header.php';
$shipping_num = $_GET['voidlabel'];
?>
<?php

//Configuration
$wsdl = "../SCHEMA-WSDLs/Void.wsdl";
$operation = "ProcessVoid";
if ($developmodel == "test") {
    $endpointurl = 'https://wwwcie.ups.com/webservices/Void';
} else {
    $endpointurl = 'https://onlinetools.ups.com/webservices/Void';
}
$outputFileName = "../label/VoidRequset_".$shipping_num.".xml";

function processVoid($shipping_num) {
    //create soap request
    $tref['CustomerContext'] = 'Add description here';
    $req['TransactionReference'] = $tref;
    $request['Request'] = $req;
    $voidshipment['ShipmentIdentificationNumber'] = $shipping_num;
    $request['VoidShipment'] = $voidshipment; 
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
    $resp = $client->__soapCall($operation, array(processVoid($shipping_num)));

    //get status
    echo $resp->Response->ResponseStatus->Description . "\n";

    //save soap request and response to file
    $fw = fopen($outputFileName, 'w');
    fwrite($fw, "Request: \n" . $client->__getLastRequest() . "\n");
    fwrite($fw, "Response: \n" . $client->__getLastResponse() . "\n");
    fclose($fw);
} catch (Exception $ex) {
    print "We are sorry for the inconvience. <br><br>Your request is refused because of ' '";
    print_r($ex->detail->Errors->ErrorDetail->PrimaryErrorCode->Description);
    print "'.<br>";
    print "<br>For detailed information, please contact our customer service.";
}
?>
