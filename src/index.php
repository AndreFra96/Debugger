<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="/debugger/assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/debugger/assets/font-awesome/css/all.css" rel="stylesheet">


  <h1>Index di Debugger</h1>

  <!--////////////////////////////////
  /////START DB CONNECTION MODAL//////
  /////////////////////////////////-->

  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <form action="?" id="dbconnectform" method="POST">
            <div class="form-group">
              <label for="servername" class="col-form-label">Server:</label>
              <input type="text" class="form-control" name="servername" id="servername">
            </div>
            <div class="form-group">
              <label for="username" class="col-form-label">Username:</label>
              <input type="text" class="form-control" name="username" id="username">
            </div>
            <div class="form-group">
              <label for="password" class="col-form-label">Password:</label>
              <input type="text" class="form-control" name="password" id="password">
            </div>
            <div class="form-group">
              <label for="dbname" class="col-form-label">DB name:</label>
              <input type="text" class="form-control" name="dbname" id="dbname">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" form="dbconnectform" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
</head>

<!--//////////////////////////////
/////END DB CONNECTION MODAL//////
///////////////////////////////-->

<?php
////////////////////////////
/////START PHP SECTION//////
////////////////////////////

session_start();

if (!isset($_SESSION['servername'])) {
  $_SESSION['servername'] = "";
  $_SESSION['username'] = "";
  $_SESSION['password'] = "";
  $_SESSION['dbname'] = "";
}

if (isset($_POST['servername'])) {
  $_SESSION['servername'] = $_POST['servername'];
  $_SESSION['username'] = $_POST['username'];
  $_SESSION['password'] = $_POST['password'];
  $_SESSION['dbname'] = $_POST['dbname'];
}

use AndreFra96\Debugger\Debugger;

require_once "../vendor/autoload.php";
$debugger = new Debugger();
$status = $debugger->getStatus();

if ($debugger->connect($_SESSION['servername'], $_SESSION['username'], $_SESSION['password'], $_SESSION['dbname'])) {
  $debugger->debug();
  $status = $debugger->getStatus();
  echo "Connesso a " . $_SESSION['dbname'] . " User: " . $_SESSION['username'];
} else {
  echo "Nessun database connesso";
}





echo '<script type="text/javascript">',
  'document.getElementById("servername").value = "' . $_SESSION['servername'] . '";',
  'document.getElementById("username").value = "' . $_SESSION['username'] . '";',
  'document.getElementById("password").value = "' . $_SESSION['password'] . '";',
  'document.getElementById("dbname").value = "' . $_SESSION['dbname'] . '"',
  '</script>';

//////////////////////////
/////END PHP SECTION//////
//////////////////////////
?>

<body>

  <!--///////////////////
  /////START NAVBAR//////
  ////////////////////-->
  <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="#">Debugger</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
      <ul class="navbar-nav mr-auto">

        <li class="nav-item">
          <a class="nav-link" id="toggleDebugging" href="#"></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="modal" data-target="#exampleModalCenter" href="#">Database</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opzioni</a>
          <div class="dropdown-menu" aria-labelledby="dropdown01">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <a class="dropdown-item" href="#">Something else here</a>
          </div>
        </li>
      </ul>
      <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
      </form>
    </div>
  </nav>

  <!--/////////////////
  /////END NAVBAR//////
  //////////////////-->

  <div class="container-fluid" style="width:80%">
    <div class="row">
      <!--/////////////////
      /////START TABLE/////
      //////////////////-->
      <table class="table table-hover" style="text-align:center">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Test</th>
            <th scope="col">Stato</th>
            <th scope="col">Errori</th>
          </tr>
        </thead>
        <tbody>

          <tr>
            <th scope="row">1</th>
            <td>Ordini senza items</td>
            <td>
              <div class="progress">
                <?php if ($status['orderStatus']) { ?>
                  <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                <?php } else { ?>
                  <div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                <?php } ?>
              </div>
            </td>
            <td>@mdo</td>
          </tr>

          <tr>
            <th scope="row">2</th>
            <td>Items senza ordine</td>
            <td>
              <div class="progress">
                <?php if ($status['itemsStatus']) { ?>
                  <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                <?php } else { ?>
                  <div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                <?php } ?>
              </div>
            </td>
            <td>@fat</td>
          </tr>

          <tr>
            <th scope="row">3</th>
            <td>Rinnovi</td>
            <td>
              <div class="progress">
                <?php if ($status['renewStatus']) { ?>
                  <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                <?php } else { ?>
                  <div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                <?php } ?>
              </div>
            </td>
            <td>@twitter</td>
          </tr>

          <tr>
            <th scope="row">4</th>
            <td>Mensili</td>
            <td>
              <div class="progress">
                <?php if ($status['monthlyStatus']) { ?>
                  <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                <?php } else { ?>
                  <div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                <?php } ?>
              </div>
            </td>
            <td>@mdo</td>
          </tr>

          <tr>
            <th scope="row">5</th>
            <td>Seriali</td>
            <td>
              <div class="progress">
                <?php if ($status['serialStatus']) { ?>
                  <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                <?php } else { ?>
                  <div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                <?php } ?>
              </div>
            </td>
            <td>@mdo</td>
          </tr>

          <tr>
            <th scope="row">6</th>
            <td>Clienti</td>
            <td>
              <div class="progress">
                <?php if ($status['customerStatus']) { ?>
                  <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                <?php } else { ?>
                  <div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                <?php } ?>
              </div>
            </td>
            <td>@mdo</td>
          </tr>

          <tr>
            <th scope="row">7</th>
            <td>Locali</td>
            <td>
              <div class="progress">
                <?php if ($status['locationStatus']) { ?>
                  <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                <?php } else { ?>
                  <div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                <?php } ?>
              </div>
            </td>
            <td>@mdo</td>
          </tr>

          <tr>
            <th scope="row">8</th>
            <td>Gruppi</td>
            <td>
              <div class="progress">
                <?php if ($status['groupStatus']) { ?>
                  <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                <?php } else { ?>
                  <div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                <?php } ?>
              </div>
            </td>
            <td>@mdo</td>
          </tr>

        </tbody>
      </table>
      <!--/////////////////
      /////END TABLE///////
      //////////////////-->
    </div>
  </div>

</body>

<script type="text/javascript" src="assets/jquery/jquery.min.js"></script>
<script type="text/javascript" src="assets/bootstrap/dist/js/bootstrap.min.js"></script>
<script>
  //////////////////////////////////
  /////START JAVASCRIPT SECTION/////
  //////////////////////////////////
  var toggleDebugging = document.getElementById("toggleDebugging");
  var timeout;
  if (sessionStorage.getItem("debuggingLoop") == 1) {
    timeout = setTimeout('location.href="http://' + location.hostname + '/debugger"', 2000);
    toggleDebugging.innerHTML = "<i class='fas fa-pause'></i>";
    toggleDebugging.addEventListener("click", function() {
      sessionStorage.setItem("debuggingLoop", 0);
      location.href = "http://" + location.hostname + "/debugger";
    });
  } else {
    clearTimeout(timeout);
    toggleDebugging.innerHTML = "<i class='fas fa-play'></i>";
    toggleDebugging.addEventListener("click", function() {
      sessionStorage.setItem("debuggingLoop", 1);
      location.href = "http://" + location.hostname + "/debugger";
    });
  }
  ////////////////////////////////
  /////END JAVASCRIPT SECTION/////
  ////////////////////////////////
</script>

</html>