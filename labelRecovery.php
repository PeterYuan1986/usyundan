<?php
require_once 'header.php';
$shipping_num = $_GET['reprintlabel'];
$rotate_label_image = "rotate" . $shipping_num . ".gif";
?>


<?php
//Configuration
// remember to change 
// http://php.net/soap.wsdl-cache-enabled
// soap.wsdl_cache_enabled=0

if (!file_exists("./label/" . $rotate_label_image)) {

    $wsdl = "./SCHEMA-WSDLs/LabelRecoveryWS.wsdl";
    $operation = "ProcessLabelRecovery";
    if ($developmodel == "test") {
    $endpointurl = 'https://wwwcie.ups.com/webservices/LBRecovery';
} else {
    $endpointurl = 'https://onlinetools.ups.com/webservices/LBRecovery';
}     
    $endpointurl = 
    $outputFileName = "./label/RecoverRequset_".$shipping_num.".xml";

    function LabelRecoveryRequest($shipping_num) {
        //create soap request
        $tref['CustomerContext'] = 'Add description here';
        $req['TransactionReference'] = $tref;
        $request['Request'] = $req;
        $request['TrackingNumber'] = "$shipping_num";
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

        /* $functions = $client->__getFunctions();
          var_dump($functions);

          print '<br><br><br><br>'; */

        //get response
        $resp = $client->__soapCall($operation, array(LabelRecoveryRequest($shipping_num)));

        $array = json_decode(json_encode($resp), true);

        $label_image = "label" . $shipping_num . ".gif";
        $rotate_label_image = "rotate" . $shipping_num . ".gif";

        $source = imagecreatefromstring(base64_decode($array['LabelResults']['LabelImage']['GraphicImage']));

        imagejpeg($source, "./label/" . $label_image, 100);
        $rotate = imagerotate($source, 270, 0); // if want to rotate the image
        imagejpeg($rotate, "./label/" . $rotate_label_image, 100);

        //get status
        //echo "Response Status: " . $resp->Response->ResponseStatus->Description . "\n";
        //save soap request and response to file
        $fw = fopen($outputFileName, 'w');
        //fwrite($fw, "Request: \n" . $client->__getLastRequest() . "\n");
        fwrite($fw, "Response: \n" . $client->__getLastResponse() . "\n");
        fclose($fw);
    } catch (Exception $ex) {
        print_r($ex);
    }
}
$path = "./label/".$rotate_label_image;

?>

<script> window.open('<?php print $path;?>'); </script>

