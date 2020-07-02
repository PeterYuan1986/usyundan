<?php
require_once"ydheader.php";

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<!-- 分割线1 -->
<html>
    <body>
        <!-- 分割线2 -->
        <div>
            <table>
                <tr> 
                    <td></td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td>SENDER</td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td>RECEIVER</td>
                </tr>
                <tr> 
                    <td>NAME:</td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php print strtoupper($QUOTE_REQUEST['namefrom']) ?></td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php print strtoupper($QUOTE_REQUEST['nameto']) ?></td>
                </tr>
                <tr> 
                    <td>ADDRESS:</td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php print strtoupper($QUOTE_REQUEST['ads1from'] . " " . $QUOTE_REQUEST['ads2from'] . " " . $QUOTE_REQUEST['ads3from']); ?></td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php print strtoupper($QUOTE_REQUEST['ads1to'] . " " . $QUOTE_REQUEST['ads2to'] . " " . $QUOTE_REQUEST['ads3to']); ?></td>
                </tr>
                <tr> 
                    <td>CITY:</td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php print strtoupper($QUOTE_REQUEST['cityfrom']); ?></td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php print strtoupper($QUOTE_REQUEST['cityto']); ?></td>
                </tr>
                <tr> 
                    <td>STATE:</td>
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
                    <td>PACKAGE:</td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php print strtoupper($QUOTE_REQUEST['weight'] . " LBS"); ?></td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php print strtoupper($QUOTE_REQUEST['length'] . " inch x " . $QUOTE_REQUEST['width'] . " inch x " . $QUOTE_REQUEST['height'] . " inch"); ?></td>
                </tr>
            </table>
        </div> 








        <!-- 分割线3 -->
    </body>
</html>