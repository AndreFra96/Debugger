<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/debugger/assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Document</title>
    <h1>Index di Debugger</h1>
</head>

<body>
    <?php

    use AndreFra96\Debugger\Debugger;

    require_once "../vendor/autoload.php";

    $debugger = new Debugger();
    $debugger->connect("localhost", "root", "", "db_ordini");
    print_r($debugger->getParameter());
    echo $debugger->connectionOk() ? "OK" : "NON OK";
    ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    Bootstrap import ok
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                Bootstrap import ok
                </div>
            </div>
        </div>
    </div>

</body>
<script type="text/javascript" src="debugger/assets/bootstrap/js/bootstrap.min.js"></script>

</html>