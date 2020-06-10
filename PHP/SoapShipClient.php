<?php
require 'header.php';
?>

<?php
//Configuration
$wsdl = "../SCHEMA-WSDLs/Ship.wsdl";
$operation = "ProcessShipment";
$endpointurl = 'https://wwwcie.ups.com/webservices/Ship';
$outputFileName = "XOLTResult.xml";
$SHIP_REQUEST = $_SESSION['SHIP_REQUEST'];

function processShipment($SHIP_REQUEST) {
    //create soap request
    $requestoption['RequestOption'] = 'nonvalidate';
    $request['Request'] = $requestoption;
    $shipment['Description'] = '';
    $shipper['Name'] = $SHIP_REQUEST['namefrom'];
    $shipper['AttentionName'] = $SHIP_REQUEST['namefrom'];
    $shipper['TaxIdentificationNumber'] = '123456';
    $shipper['ShipperNumber'] = '86F304 ';
    $address['AddressLine'] =  $SHIP_REQUEST['ads1from'].", ".$SHIP_REQUEST['ads2from'].", ".$SHIP_REQUEST['ads3from'];
    $address['City'] = $SHIP_REQUEST['cityfrom'];
    $address['StateProvinceCode'] =  $SHIP_REQUEST['statefrom'];
    $address['PostalCode'] = $SHIP_REQUEST['zipcodefrom'];
    $address['CountryCode'] = 'US';
    $shipper['Address'] = $address;
    $phone['Number'] = $SHIP_REQUEST['phonefrom'];
    $phone['Extension'] = '';
    $shipper['Phone'] = $phone;
    $shipment['Shipper'] = $shipper;

    
    
    $shipto['Name'] = $SHIP_REQUEST['nameto'];
    $shipto['AttentionName'] = $SHIP_REQUEST['nameto'];
    $addressTo['AddressLine'] = array(  $SHIP_REQUEST['ads1to']." ".$SHIP_REQUEST['ads2to']." ".$SHIP_REQUEST['ads3to']);
    $addressTo['City'] =  $SHIP_REQUEST['cityto'];
    $addressTo['PostalCode'] = $SHIP_REQUEST['zipcodeto'];
    $addressTo['StateProvinceCode'] = $SHIP_REQUEST['stateto'];
    $addressTo['CountryCode'] = 'US';
    $phone2['Number'] =$SHIP_REQUEST['phoneto'];
    $shipto['Address'] = $addressTo;
    $shipto['Phone'] = $phone2;
    $shipment['ShipTo'] = $shipto;
     
    

    $shipfrom['Name'] =$SHIP_REQUEST['namefrom'];
    $shipfrom['AttentionName'] = $SHIP_REQUEST['namefrom'];
    $addressFrom['AddressLine'] = $SHIP_REQUEST['ads1from'].$SHIP_REQUEST['ads2from'].$SHIP_REQUEST['ads3from'];
    $addressFrom['City'] = $SHIP_REQUEST['cityfrom'];
    $addressFrom['StateProvinceCode'] = $SHIP_REQUEST['statefrom'];
    $addressFrom['PostalCode'] = $SHIP_REQUEST['zipcodefrom'];
    $addressFrom['CountryCode'] = 'US';
    $phone3['Number'] = $SHIP_REQUEST['phonefrom'];
    $shipfrom['Address'] = $addressFrom;
    $shipfrom['Phone'] = $phone3;
    $shipment['ShipFrom'] = $shipfrom;   
    
    

    $shipmentcharge['Type'] = '01';
    $creditcard['Type'] = '06';
    $creditcard['Number'] = '4716995287640625';
    $creditcard['SecurityCode'] = '864';
    $creditcard['ExpirationDate'] = '12/2013';
    $creditCardAddress['AddressLine'] = '2010 warsaw road';
    $creditCardAddress['City'] = 'Roswell';
    $creditCardAddress['StateProvinceCode'] = 'GA';
    $creditCardAddress['PostalCode'] = '30076';
    $creditCardAddress['CountryCode'] = 'US';
    $creditcard['Address'] = $creditCardAddress;
    $billshipper['CreditCard'] = $creditcard;
    $shipmentcharge['BillShipper'] = $billshipper;
    $paymentinformation['ShipmentCharge'] = $shipmentcharge;
    $shipment['PaymentInformation'] = $paymentinformation;

    $service['Code'] = $SHIP_REQUEST['SERVICE'];
    $service['Description'] = 'Expedited';
    $shipment['Service'] = $service;

    $internationalForm['FormType'] = '01';
    $internationalForm['InvoiceNumber'] = 'asdf123';
    $internationalForm['InvoiceDate'] = '20151225';
    $internationalForm['PurchaseOrderNumber'] = '999jjj777';
    $internationalForm['TermsOfShipment'] = 'CFR';
    $internationalForm['ReasonForExport'] = 'Sale';
    $internationalForm['Comments'] = 'Your Comments';
    $internationalForm['DeclarationStatement'] = 'Your Declaration Statement';
    $soldTo['Option'] = '01';
    $soldTo['AttentionName'] = 'Sold To Attn Name';
    $soldTo['Name'] = 'Sold To Name';
    $soldToPhone['Number'] = '1234567890';
    $soldToPhone['Extension'] = '1234';
    $soldTo['Phone'] = $soldToPhone;
    $soldToAddress['AddressLine'] = '5640 W Market St, Apt C';
    $soldToAddress['City'] = 'Greensboro';
    $soldToAddress['PostalCode'] = '27409';
    $soldToAddress['StateProvinceCode'] = 'MD';
    $soldToAddress['CountryCode'] = 'US';
    $soldTo['Address'] = $soldToAddress;
    $contact['SoldTo'] = $soldTo;
    $internationalForm['Contacts'] = $contact;
    $product['Description'] = 'Product 1';
    $product['CommodityCode'] = '111222AA';
    $product['OriginCountryCode'] = 'US';
    $unitProduct['Number'] = '147';
    $unitProduct['Value'] = '478';
    $uom['Code'] = 'BOX';
    $uom['Description'] = 'BOX';
    $unitProduct['UnitOfMeasurement'] = $uom;
    $product['Unit'] = $unitProduct;
    $productWeight['Weight'] = '10';
    $uomForWeight['Code'] = 'LBS';
    $uomForWeight['Description'] = 'LBS';
    $productWeight['UnitOfMeasurement'] = $uomForWeight;
    $product['ProductWeight'] = $productWeight;
    $internationalForm['Product'] = $product;
    $discount['MonetaryValue'] = '100';
    $internationalForm['Discount'] = $discount;
    $freight['MonetaryValue'] = '50';
    $internationalForm['FreightCharges'] = $freight;
    $insurance['MonetaryValue'] = '200';
    $internationalForm['InsuranceCharges'] = $insurance;
    $otherCharges['MonetaryValue'] = '50';
    $otherCharges['Description'] = 'Misc';
    $internationalForm['OtherCharges'] = $otherCharges;
    $internationalForm['CurrencyCode'] = 'USD';
    $shpServiceOptions['InternationalForms'] = $internationalForm;
    $shipment['ShipmentServiceOptions'] = $shpServiceOptions;


    $package['Description'] = '';
    $packaging['Code'] = '02';
    $packaging['Description'] = '';
    $package['Packaging'] = $packaging;
    $unit['Code'] = 'IN';
    $unit['Description'] = 'Inches';
    $dimensions['UnitOfMeasurement'] = $unit;
    $dimensions['Length'] = $SHIP_REQUEST['length'];
    $dimensions['Width'] = $SHIP_REQUEST['width'];
    $dimensions['Height'] = $SHIP_REQUEST['height'];
    $package['Dimensions'] = $dimensions;
    $unit2['Code'] = 'LBS';
    $unit2['Description'] = 'Pounds';
    $packageweight['UnitOfMeasurement'] = $unit2;
    $packageweight['Weight'] = $SHIP_REQUEST['weight'];
    $package['PackageWeight'] = $packageweight;
    $shipment['Package'] = $package;
    
    
    

    $labelimageformat['Code'] = 'GIF';
    $labelimageformat['Description'] = 'GIF';
    $labelspecification['LabelImageFormat'] = $labelimageformat;
    $labelspecification['HTTPUserAgent'] = 'Mozilla/4.5';
    $shipment['LabelSpecification'] = $labelspecification;
    $request['Shipment'] = $shipment;

    /* echo "Request.......\n";
      print_r($request);
      echo "\n\n"; */
    return $request;
}

function processShipConfirm() {

    //create soap request
}

function processShipAccept() {
    //create soap request
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

    if (strcmp($operation, "ProcessShipment") == 0) {
        //get response
        $resp = $client->__soapCall('ProcessShipment', array(processShipment($SHIP_REQUEST)));

        //get status
        //echo "Response Status: " . $resp->Response->ResponseStatus->Description . "\n";
        //save soap request and response to file
        /*$fw = fopen($outputFileName, 'w');
        fwrite($fw, "Request: \n" . $client->__getLastRequest() . "\n");
        fwrite($fw, "Response: \n" . $client->__getLastResponse() . "\n");
        fclose($fw);
*/
        $array = json_decode(json_encode($resp), true);
        $shipping_num = $array['ShipmentResults']['ShipmentIdentificationNumber'];
        $label_image = "label" . $shipping_num . ".gif";
        $rotate_label_image = "rotate" . $shipping_num . ".gif";
        
        $source = imagecreatefromstring(base64_decode($array['ShipmentResults']['PackageResults']['ShippingLabel']['GraphicImage']));
        imagejpeg($source, "../label/".$label_image, 100);
        $rotate = imagerotate($source, 270, 0); // if want to rotate the image
        imagejpeg($rotate,"../label/".$rotate_label_image, 100);

        /*$file = fopen($label_image, "w");
        fwrite($file, base64_decode($array['ShipmentResults']['PackageResults']['ShippingLabel']['GraphicImage']));
        fclose($file);*/

        $HTMLImage = "label" . $shipping_num . ".html";
        $file = fopen("../label/".$HTMLImage, "w");
        fwrite($file, base64_decode($array['ShipmentResults']['PackageResults']['ShippingLabel']['HTMLImage']));
        fclose($file);

        //print"<img src=\"temp.gif\" width=\"100px\" height=\"100px\"\/>";
    } else if (strcmp($operation, "ProcessShipConfirm") == 0) {
        //get response
        $resp = $client->__soapCall('ProcessShipConfirm', array(processShipConfirm()));

        //get status
        echo "Response Status: " . $resp->Response->ResponseStatus->Description . "\n";

        //save soap request and response to file
        $fw = fopen($outputFileName, 'w');
        fwrite($fw, "Request: \n" . $client->__getLastRequest() . "\n");
        fwrite($fw, "Response: \n" . $client->__getLastResponse() . "\n");
        fclose($fw);
    } else {
        $resp = $client->__soapCall('ProcessShipeAccept', array(processShipAccept()));

        //get status
        // echo "Response Status: " . $resp->Response->ResponseStatus->Description . "\n";
        //save soap request and response to file
        /* $fw = fopen($outputFileName, 'w');
          fwrite($fw, "Request: \n" . $client->__getLastRequest() . "\n");
          fwrite($fw, "Response: \n" . $client->__getLastResponse() . "\n");
          fclose($fw); */


        $array = json_decode(json_encode($resp), true);
    }
} catch (Exception $ex) {
    print_r($ex);
}
?>

<html>  

    <body>
        <button type="button" onclick="window.open('<?php print "../label/".$HTMLImage; ?>')">
            PDF</button>
        <button type="button" onclick="window.open('<?php print "../label/".$rotate_label_image; ?>')">
            print label</button>



    </body>



</html>
