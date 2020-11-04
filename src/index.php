<?php

use AndreFra96\Debugger\Debugger;

require_once "../vendor/autoload.php";
$debugger = new Debugger("localhost","root","","dbordini");
echo $debugger->__toString();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <h1>Index di Debugger</h1>
</head>
<body>
    
</body>
</html>