<?php
require_once'ydheader.php';
session_start();
session_regenerate_id(FALSE);
$_SESSION['discard_after'] =time() + 10000;

if (isset($_REQUEST['quote'])) {
    $_SESSION['AV']['SHIP_REQUEST']['nameto'] = @$_POST["nameto"];
    $_SESSION['AV']['SHIP_REQUEST']['namefrom'] = @$_POST["namefrom"];
    $_SESSION['AV']['SHIP_REQUEST']['ads1from'] = @$_POST["ads1from"];
    $_SESSION['AV']['SHIP_REQUEST']['ads2from'] = @$_POST["ads2from"];
    $_SESSION['AV']['SHIP_REQUEST']['ads3from'] = @$_POST["ads3from"];
    $_SESSION['AV']['SHIP_REQUEST']['ads1to'] = @$_POST["ads1to"];
    $_SESSION['AV']['SHIP_REQUEST']['ads2to'] = @$_POST["ads2to"];
    $_SESSION['AV']['SHIP_REQUEST']['ads3to'] = @$_POST["ads3to"];
    $_SESSION['AV']['SHIP_REQUEST']['cityfrom'] = @$_POST["cityfrom"];
    $_SESSION['AV']['SHIP_REQUEST']['cityto'] = @$_POST["cityto"];
    $_SESSION['AV']['SHIP_REQUEST']['statefrom'] = @$_POST["statefrom"];
    $_SESSION['AV']['SHIP_REQUEST']['stateto'] = @$_POST["stateto"];
    $_SESSION['AV']['SHIP_REQUEST']['weight'] = @$_POST["weight"];
    $_SESSION['AV']['SHIP_REQUEST']['phonefrom'] = @$_POST["phonefrom"];
    $_SESSION['AV']['SHIP_REQUEST']['phoneto'] = @$_POST["phoneto"];
    $_SESSION['AV']['SHIP_REQUEST']['phoneto'] = @$_POST["message"];
    if (@$_POST["length"] == '') {
        $_SESSION['AV']['SHIP_REQUEST']['length'] = '1';
    } else {
        $_SESSION['AV']['SHIP_REQUEST']['length'] = @$_POST["length"];
    }
    if (@$_POST["width"] == '') {
        $_SESSION['AV']['SHIP_REQUEST']['width'] = '1';
    } else {
        $_SESSION['AV']['SHIP_REQUEST']['width'] = @$_POST["width"];
    }
    if (@$_POST["height"] == '') {
        $_SESSION['AV']['SHIP_REQUEST']['height'] = '1';
    } else {
        $_SESSION['AV']['SHIP_REQUEST']['height'] = @$_POST["height"];
    }
    $_SESSION['AV']['SHIP_REQUEST']['zipcodefrom'] = @$_POST["zipcodefrom"];
    $_SESSION['AV']['SHIP_REQUEST']['zipcodeto'] = @$_POST["zipcodeto"];

 
    header('Location:' . $createrequest_to . encode(session_id()));
}
?>
<!-- 分割线1 -->

<html class="no-js" lang="en">
    <body>
        <!-- 分割线2 -->
        <form name="form" method="post"  action='' id="loginForm">
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
                    <input name="cityfrom" type="text"  title="Please enter you City" required="" >
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
                <div>
                    <label>Phone Number</label>
                    <input name="phonefrom" type="text"  title="Phone Number"  >
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
                    <input name="cityto" type="text"  title="Please enter you City"   required="" >
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
                <div>
                    <label>Phone Number</label>
                    <input name="phoneto" type="text"  title="Phone Number"  >
                </div>

            </div>
            <div>
                <br><br><br>
            </div>
            <div>Package:
                <div>
                    <label >Weight*:</label>
                    <input name="weight" type="text"  title="LBS"  required="" >
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
            <div>
                <br><br><br>
            </div>
            <div>Message:
                <div>
                    <label >Customer Message(Shown on label):</label>
                    <input name="message" type="text"  title="Message"   >
                </div>
                

            </div>
            
            <div >
                <a href="#"><input type="submit" name="quote" value="Quote">  </a>
            </div>
        </form>
        <!-- 分割线3 -->
    </body>
</html>