<?php
require "header.php";
?>

<?php
//从quote.php界面提取参数
$QUOTE_REQUEST = $_SESSION['AV']['SHIP_REQUEST'];

//Configuration
$wsdl = "../SCHEMA-WSDLs/XAV.wsdl";
$operation = "ProcessXAV";
$endpointurl = 'https://onlinetools.ups.com/webservices/XAV';
$outputFileName = "../label/AV_Request.xml";

function processXAV($QUOTE_REQUEST) {
    //create soap request
    $option['RequestOption'] = '3';
    $request['Request'] = $option;
    $request['MaximumCandidateListSize'] = '1';
    //$request['RegionalRequestIndicator'] = '';
    $addrkeyfrmt['ConsigneeName'] = $QUOTE_REQUEST['nameto'];
    $addrkeyfrmt['AddressLine'] = array
        ($QUOTE_REQUEST['ads1to'], $QUOTE_REQUEST['ads2to'], $QUOTE_REQUEST['ads3to']
    );
    $addrkeyfrmt['Region'] = $QUOTE_REQUEST['cityto'] . "," . $QUOTE_REQUEST['stateto'] . "," . $QUOTE_REQUEST['zipcodeto'];
    $addrkeyfrmt['PoliticalDivision2'] = $QUOTE_REQUEST['cityto'];
    $addrkeyfrmt['PoliticalDivision1'] = $QUOTE_REQUEST['stateto'];
    $addrkeyfrmt['PostcodePrimaryLow'] = $QUOTE_REQUEST['zipcodeto'];
    $addrkeyfrmt['PostcodeExtendedLow'] = '';
    //$addrkeyfrmt['Urbanization'] = 'porto arundal';
    $addrkeyfrmt['CountryCode'] = 'US';
    $request['AddressKeyFormat'] = $addrkeyfrmt;

    // echo "Request.......\n";
    // print_r($request);
    // echo "\n\n";
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
    $resp = $client->__soapCall($operation, array(processXAV($QUOTE_REQUEST)));

    //get status
    echo "Response Status: " . $resp->Response->ResponseStatus->Description . "\n";

    //save soap request and response to file
    $fw = fopen($outputFileName, 'w');
    fwrite($fw, "Request: \n" . $client->__getLastRequest() . "\n");
    fwrite($fw, "Response: \n" . $client->__getLastResponse() . "\n");
    fclose($fw);
    $array = json_decode(json_encode($resp), true);
} catch (Exception $ex) {
    print_r($ex);
}
?>
<?php
if (isset($_GET['keep'])) {
    $_SESSION['RATE']['SHIP_REQUEST'] = $QUOTE_REQUEST;
    header('Location: createresponse.php?xl=' . encode(session_id()));
} elseif (isset($_GET['update'])) {
    $QUOTE_REQUEST['cityto'] = strtoupper($array['Candidate']['AddressKeyFormat']['PoliticalDivision2']);
    $QUOTE_REQUEST['stateto'] = strtoupper($array['Candidate']['AddressKeyFormat']['PoliticalDivision1']);
    $QUOTE_REQUEST['zipcodeto'] = strtoupper($array['Candidate']['AddressKeyFormat']['PostcodePrimaryLow'] . "-" . $array['Candidate']['AddressKeyFormat']['PostcodeExtendedLow']);
    $QUOTE_REQUEST['addressTo']['ResidentialAddressIndicator'] = $array['Candidate']['AddressClassification']['Code'];
    $i = 0;
    $add = "ads" . strval($i) . 'to';
    if (is_array($array['Candidate']['AddressKeyFormat']['AddressLine'])) {
        foreach ($array['Candidate']['AddressKeyFormat']['AddressLine'] as $x) {
            $QUOTE_REQUEST[$add] = $x;
            $i++;
        }
    } else {
        $QUOTE_REQUEST['ads1to'] = $array['Candidate']['AddressKeyFormat']['AddressLine'];
        $QUOTE_REQUEST['ads2to'] = '';
        $QUOTE_REQUEST['ads3to'] = '';
    }

    $_SESSION['RATE']['SHIP_REQUEST'] = $QUOTE_REQUEST;
    header('Location: createresponse.php?xl=' . encode(session_id()));
}
?>


<html>
    <body>
        <form>
            <table>
                <tr>
                    <td>
                        <div>
                            <a>You Original Delivery Address:<br><br></a>
                            <a><?php print @$QUOTE_REQUEST['nameto']; ?><br></a>      
                            <a><?php print @$QUOTE_REQUEST['ads1to'] . ", " . @$QUOTE_REQUEST['ads2to'] . " " . @$QUOTE_REQUEST['ads3to'] ?><br></a>
                            <a><?php print @$QUOTE_REQUEST['cityto'] . ", " . @$QUOTE_REQUEST['stateto'] . " " . @$QUOTE_REQUEST['zipcodeto']; ?></a>

                        </div>
                    </td>
                    <td>     

                        <div>
                            <a>UPS Recommed Delivery Address:<br><br></a>
                            <a><?php
                                $flag = @$array['Candidate']['AddressKeyFormat']['AddressLine'] == NULL;
                                if (!$flag)
                                    print strtoupper(@$QUOTE_REQUEST['nameto']);
                                else
                                    print "Your input address is invalid!!!"
                                    ?><br></a>            
                            <a>

                                <?php
                                if (!$flag) {
                                    if (is_array(@$array['Candidate']['AddressKeyFormat']['AddressLine'])) {
                                        foreach (@$array['Candidate']['AddressKeyFormat']['AddressLine'] as $x) {
                                            print $x . "  ";
                                        }
                                    } else {
                                        print @$array['Candidate']['AddressKeyFormat']['AddressLine'];
                                    }
                                }
                                ?><br>


                            </a>
                            <a><?php
                                if (!$flag) {
                                    print @$array['Candidate']['AddressKeyFormat']['Region'];
                                } else {
                                    echo '<form><input type="button" value="Return to previous page" onClick="javascript:history.go(-1)"></form>';
                                }
                                ?><br></a>


                        </div>


                    </td>


                </tr>

                <tr>
                    <?php
                    if (!$flag)
                        print " <td><button  type='submit' name='keep'  onclick='return confirmation()'>KEEP</button>
                    </td>
                    <td><button  type='submit'    name='update'         />UPDATE</td>";
                    ?>
                </tr>

            </table>
        </form>
    </body>


    <script>
        function confirmation(url) {

            return confirm('The address non-validation may case error in the future!!');
        }
    </script>

</html>
