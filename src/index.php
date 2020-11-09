<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="/debugger/assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/debugger/assets/font-awesome/css/all.css" rel="stylesheet">
  <style>
    .modal-dialog {
      position: relative;
      display: table;
      /* This is important */
      overflow-y: auto;
      overflow-x: auto;
      width: auto;
      /* min-width: 350px; */
    }
  </style>

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

<body>

  <?php
  ////////////////////////////
  /////START PHP SECTION//////
  ////////////////////////////

  //SAVING IN SESSION DB CONNECTION DATA
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


  use AndreFra96\Debugger\Debugger; //use Debugger

  //require composer autoload an custom functions
  require_once "../vendor/autoload.php";
  require_once "php/phpfunc.php";


  $debugger = new Debugger(); //Init debugger
  $tableRows = "";
  //Attempt to connect
  if (!($debugger->connect($_SESSION['servername'], $_SESSION['username'], $_SESSION['password'], $_SESSION['dbname']))) {
    echo "Nessun database connesso"; //Connection failed
  } else {
    echo "Connesso a " . $_SESSION['dbname'] . " - user: " . $_SESSION['username']; //Connection success
    //try load tests from file
    try {
      $debugger->loadTestsFromFile("txt/data.txt");
    } catch (Exception $e) {
      echo " Impossibile caricare i test dal file"; //Errors in tests upload
    }
    //Build table rows
    if ($debugger->tests() != []) {
      foreach ($debugger->tests() as $id => $content) {
        $tableRows .= $debugger->asTableRow($id);
        // print_r($debugger->debugData($id));
        echo createModal($debugger->debugData($id),"test".$id);
      }
    }
  }

  //Set db connection modal fields with javascript
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


  <!--///////////////////
  /////START NAVBAR//////
  ////////////////////-->
  <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="#">
      <img src="img/debugger.png" width="100" height="30" alt="">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
      <ul class="navbar-nav mr-auto">

        <li class="nav-item">
          <a class="nav-link" id="toggleDebugging" href="#" data-toggle="tooltip" data-placement="bottom" title="Debug"></a>
        </li>
        <li class="nav-item">

          <a class="nav-link" href="#exampleModalCenter" data-toggle="tooltip" data-placement="bottom" title="Connessione DB"><i class="fas fa-database" data-toggle="modal" data-target="#exampleModalCenter"></i></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" id="toggleDraggableTable" onclick="toggleDraggable()" data-toggle="tooltip" data-placement="bottom" title="Movimento righe"><i class="fas fa-arrows-alt"></i></a>
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
      <table id="draggableTable" class="table table-hover" style="text-align:center">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Testing Case</th>
            <th scope="col">Status</th>
            <th scope="col">Bugs</th>
          </tr>
        </thead>
        <tbody>
          <?php
          //////PRINT TABLE ROWS//////
          echo $tableRows;
          ///////END TABLE ROWS///////
          ?>
        </tbody>
      </table>
      <!--/////////////////
      /////END TABLE///////
      //////////////////-->
    </div>
  </div>

</body>
<!-- jquery -->
<script type="text/javascript" src="assets/jquery/jquery.min.js"></script>
<!-- tablednd for draggable table -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/TableDnD/0.9.1/jquery.tablednd.js" integrity="sha256-d3rtug+Hg1GZPB7Y/yTcRixO/wlI78+2m08tosoRn7A=" crossorigin="anonymous"></script>
<!-- popper.js for tooltips  -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<!-- bootstrap -->
<script type="text/javascript" src="assets/bootstrap/dist/js/bootstrap.min.js"></script>
<script>
  //////////////////////////////////
  /////START JAVASCRIPT SECTION/////
  //////////////////////////////////

  window.onload = function() {
    $('[data-toggle="tooltip"]').tooltip(); //Enable tooltip
    if (sessionStorage.getItem("draggable") == 1) { //Enable draggable if 1 in localStorange
      $("#draggableTable").tableDnD();
    }
  };

  //toggle table drag
  function toggleDraggable() {
    sessionStorage.setItem("draggable", sessionStorage.getItem("draggable") == 1 ? 0 : 1);
    location.href = "http://" + location.hostname + "/debugger";
  }

  //continous debugging by page reload
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