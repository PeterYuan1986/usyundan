<?php
require 'header.php';
if (isset($_GET['xl'])) {
    session_id(decode($_GET['xl']));
}
session_start();
session_unset();
session_destroy();
?>
<html>  

    <body>
        <h1>            
            你的操作已超时！！
        </h1>

        <a href="createrequest.php" ><h2>Click here go to main page!</h2></a>
    </body>



</html>
