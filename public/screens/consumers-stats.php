<?php
session_start();
include('../../api_codes/api_req_functions.php');

$current_page = "../screens/".basename($_SERVER['PHP_SELF']);

if(empty($_SESSION['token_auth'])) {
  header("Location: ../index.php");
  die;
  echo "Veuillez vous connecter!";
}

// URL de l'API pour récupérer la liste des users
//$url_get_users = "http://35.237.39.146:9000/api/v1/utilisateur";
$url_get_users = "http://104.196.146.173:9000/api/v1/utilisateur";

// Définir les en-têtes personnalisés nécessaires pour les prochaines requêtes
$headers_all = [
  'Content-Type: application/json',
  'Authorization: Bearer ' . $_SESSION['token_auth']
];

if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST["deleteForm"])){
    $delUserId = $_POST["delID"];

    $url_delete_user = "http://104.196.146.173:9000/api/v1/utilisateur/$delUserId";
  
    api_delete_data_function($url_delete_user,$headers_all);
    header("Location: consumers-all.php");
    exit();
  }
  else if(isset($_POST["updateForm"])){
    $updUserId = $_POST["updID"];
    
    $updateNom = $_POST["openUpdateNom"];
    $updatePrenom = $_POST["openUpdatePrenom"];
    $updatePhone = $_POST["openUpdatePhone"];
    $updateStatut = $_POST["openUpdateStatut"];

    $donnees_update = [
      "nom" => $updateNom,
      "prenom" => $updatePrenom,
      "phone" => $updatePhone,
      "status" => $updateStatut

    ];
    $url_update_user = "http://104.196.146.173:9000/api/v1/utilisateur/$updUserId";
  
    api_put_data_function($url_update_user,$donnees_update,$headers_all);
    header("Location: consumers-all.php");
    exit();
  }
  else if(isset($_POST["updatePwdForm"])){
    $updPwdUserId = $_POST["updPwdID"];
    
    $oldPwd = $_POST["openUpdatePwdOld"];
    $phone = $_POST["openUpdatePwdPhone"];
    $newPwd = $_POST["openUpdatePwdNew"];

    var_dump($oldPwd);
    $donnees_pwd_update = [
      "password" => $oldPwd,
      "phone" => $phone,
      "newPassword" => $newPwd

    ];
    $url_pwd_update_user = "http://104.196.146.173:9000/api/v1/utilisateur/$updPwdUserId";
  
    api_post_data_function($url_pwd_update_user,$donnees_pwd_update,$headers_all);
    header("Location: consumers-all.php");
    exit();
  }
}

$users_data = api_get_data_function($url_get_users, $headers_all);

$decode_users_data = json_decode($users_data, true);

$data_vue = [];

$nbre_lignes = 0;

if ($decode_users_data !== null && isset($decode_users_data['data'])) {
  // Parcourir les enregistrements et afficher les valeurs des propriétés spécifiques
  foreach ($decode_users_data['data'] as $enregistrement) {
    if($enregistrement['nom'] !== NULL && $enregistrement['prenom'] !== NULL) {
      $data_vue[] = $enregistrement;
      $nbre_lignes++;
    }
    else {
      $enregistrement['nom'] = "N/A";
      $data_vue[] = $enregistrement;
      $nbre_lignes++;
    }
    }
  } else {
      echo "Erreur de décodage ou de type des données JSON.";
  }
  //var_dump($decode_users_data);
  $data_vue_json = json_encode($data_vue);
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Tous les clients | Mayo Admin</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
    crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <?php include '../partials/navbar.php'; ?>
  <?php include '../partials/sidebar.php'; ?>

  <div id="app">
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h4 class="m-0 text-dark">Statistiques des clients</h4>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6">
            <!-- BAR CHART -->
            <div class="card card-default" style="height:550px">
              <div class="card-header" style="background-color:#AA742A;color:white">
                <h3 class="card-title">Marges périodiques d'activité des clients </h3>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="barChart" style="min-height: 250px; height: 450px; max-height: 450px; max-width: 100%;"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

          </div>
          <!-- /.col (LEFT) -->
          <div class="col-md-6">
            <!-- DONUT CHART -->
            <div class="card card-danger" style="height:550px">
              <div class="card-header" style="background-color:#AA742A;color:white">
                <h3 class="card-title">Marges totales de clients par opérateur</h3>
              </div>
              <div class="card-body">
                <canvas id="donutChart" style="min-height: 250px; height: 450px; max-height: 450px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

          </div>
          <!-- /.col (RIGHT) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
      </section>
      <!-- right col -->
    </div>
    <!-- /.row (main row) -->
  </div><!-- /.container-fluid -->
  </div>
  </section>
  <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="../plugins/jquery/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- Bootstrap 4 -->
  <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- ChartJS -->
  <script src="../plugins/chart.js/Chart.min.js"></script>
  <!-- Sparkline -->
  <script src="../plugins/sparklines/sparkline.js"></script>
  <!-- JQVMap -->
  <script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
  <script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
  <!-- jQuery Knob Chart -->
  <script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
  <!-- daterangepicker -->
  <script src="../plugins/moment/moment.min.js"></script>
  <script src="../plugins/daterangepicker/daterangepicker.js"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <!-- Summernote -->
  <script src="../plugins/summernote/summernote-bs4.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- ChartJS -->
<script src="../plugins/chart.js/Chart.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../dist/js/adminlte.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="../dist/js/pages/dashboard.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="../dist/js/demo.js"></script>
<!-- page script -->
<script>
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- AREA CHART -
    //--------------

    var areaChartData = {
      labels  : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'],
      datasets: [
        {
          label               : 'Actifs',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [28, 48, 40, 19, 86, 27, 90, 28, 48, 40, 19, 86]
        },
        {
          label               : 'Inactifs',
          backgroundColor     : 'rgba(210, 214, 222, 1)',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [65, 59, 80, 81, 56, 55, 40, 28, 48, 40, 19, 19]
        },
      ]
    }

    var areaChartOptions = {
      maintainAspectRatio : false,
      responsive : true,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          gridLines : {
            display : false,
          }
        }],
        yAxes: [{
          gridLines : {
            display : false,
          }
        }]
      }
    }

    //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
    var donutData = {
      labels: [
          'TOGOCOM', 
          'MOOV AFRICA', 
      ],
      datasets: [
        {
          data: [400,600],
          backgroundColor : ['#f39c12', '#00c0ef'],
        }
      ]
    }
    var donutOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var donutChart = new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: donutData,
      options: donutOptions      
    })

    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = jQuery.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets[0]
    var temp1 = areaChartData.datasets[1]
    barChartData.datasets[0] = temp1
    barChartData.datasets[1] = temp0

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    var barChart = new Chart(barChartCanvas, {
      type: 'bar', 
      data: barChartData,
      options: barChartOptions
    })

    //---------------------
    //- STACKED BAR CHART -
    //---------------------
    var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
    var stackedBarChartData = jQuery.extend(true, {}, barChartData)

    var stackedBarChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      scales: {
        xAxes: [{
          stacked: true,
        }],
        yAxes: [{
          stacked: true
        }]
      }
    }

    var stackedBarChart = new Chart(stackedBarChartCanvas, {
      type: 'bar', 
      data: stackedBarChartData,
      options: stackedBarChartOptions
    })
  })
</script>

</body>

</html>