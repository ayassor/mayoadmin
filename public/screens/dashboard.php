<?php
session_start();
include('../../api_codes/api_req_functions.php');

if(empty($_SESSION['token_auth'])) {
  header("Location: ../index.php");
  echo "Veuillez vous connecter!";
  die;
}

// URL de l'API pour récupérer la liste des users
$url_get_users = "http://35.237.39.146:9000/api/v1/utilisateur";

// Définir les en-têtes personnalisés nécessaires pour les prochaines requêtes
$headers_all = [
  'Content-Type: application/json',
  'Authorization: Bearer ' . $_SESSION['token_auth']
];

$compare_token = $_SESSION['token_auth'];

$users_data = api_data_function($url_get_users, $headers_all);

$decode_users_data = json_decode($users_data, true);

$data_vue = [];

$nbre_lignes = 0;

if ($decode_users_data !== null && isset($decode_users_data['data'])) {
  // Parcourir les enregistrements et afficher les valeurs des propriétés spécifiques
  foreach ($decode_users_data['data'] as $enregistrement) {
      $data_vue[] = $enregistrement;
      $nbre_lignes++;
    }
  } else {
      echo "Erreur de décodage ou de type des données JSON.";
  }
  //var_dump($decode_users_data);
  $data_vue_json = json_encode($data_vue);
?>

  <style>

    #dashboard i{
        color:#AA742A !important;
    }
    #dashboard p{
        color:#AA742A !important;
    }
  
  #dashboard{
   background-color:#fff !important;
   color:rgb(70,70,70);
}

</style>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Tableau de bord | Mayo Admin</title>
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
              <h1 class="m-0 text-dark">Tableau de bord</h1>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>
            <div class="col-12 col-sm-6 col-md-3" v-for="data in resumeCardData" :key="data.id">
              <a :href="data.url" style="color:#000">
              <div class="info-box mb-3">
                <span :class="data.subBoxStyle" style="margin:5px;width:75px;height:50px" > <i :class="data.icon"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">{{data.title}}</span>
                  <span class="info-box-number">{{data.number}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
              </a>
            </div>
          </div>
          <!-- /.row -->

          <!-- Main row -->
          <div class="row">
            <!-- Left col -->
            <section class="col-lg-7 connectedSortable">
              <!-- Custom tabs (Charts with tabs)-->
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title" style='font-weight:600'>
                    CE MOIS, VOUS AVEZ OBTENU :
                  </h3>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6" v-for="data in statCardData" :key="data.id">
                      <div class="container info-box">
                        <div class="row" style="width:100%">
                          <div class="col-md-6">
                            <div class="info-box-content">
                              <span class="info-box-number theme-text-primary theme-stat-size" style="font-size:25px">
                                {{data.number}}
                              </span>
                              <span class="info-box-text" style="font-weight:bold">{{data.title}}</span>
                            </div>
                          </div>
                          <div class="col-md-6 align-right">
                            <div>
                              <span :class="data.subBoxStyle" style="margin-left:75px;padding-left:10px;padding-right:10px"> {{data.number}} </span>
                              </br>
                              <p style="color: black;font-size:13px;margin-left:15px;margin-top:10px;margin-bottom:5px"> {{data.number}} {{data.title}} actif(s) </p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div><!-- /.card-body -->
                </div>
                <!-- /.card -->


                <!-- /.card -->
            </section>
            <!-- /.Left col -->
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <section class="col-lg-5 connectedSortable">

              <!-- Map card -->
              <div class="card ">

                <div class="card-header">
                  <h3 class="card-title">
                    Dernières opérations
                  </h3>
                </div>

                <div class="card-body" style="overflow-x:hidden">
                  <!--<div class="container info-box" v-for="data in dataList" :key="data.id" style="height:100px">
                    <div class="row mt-1" style="display:flex;flex-direction:column;width:100%">
                      <div class="col-md-6 ml-2">
                        <div class="row" style="font-weight:bold">{{ data.title }}</div>
                        <div class="row">DE : {{data.from}} </div>
                        <div class="row">A : {{data.to}} </div>
                      </div>
                      <div class="col-md-6 align-right">
                         <div class="row" style="font-size:20px;font-weight:bold;color:green;width:100%">{{ data.amount }} FCFA</div>
                         <br>
                         <div class="row">
                          <div class="col-md-2"><img src="../dist/img/tmoney.png" style="width:60px;height:43px"></div>
                          <div class="col-md-1"><i class="fa fa-arrow-right" style="margin-top:12px"></i></div>
                          <div class="col-md-2"><img src="../dist/img/moov-money.png" style="width:35px;height:35px;margin-top:5px"></div>
                         </div>
                      </div>
                    </div>
                    <br>
                  </div>-->
                    <span>Aucune transaction</span>
                </div>

              </div>

              <div class="card-footer bg-transparent" style="height: 300px">

                <!-- /.row -->
              </div>
          </div>
          <!-- /.card -->

          <!-- /.card -->
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
  <footer class="main-footer">
    Vous êtes connecté(e) en tant que <strong>SUPER ADMIN</strong>
    <div class="float-right d-none d-sm-inline-block">
    </div>
  </footer>

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
  <!-- AdminLTE App -->
  <script src="../dist/js/adminlte.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="../dist/js/pages/dashboard.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="../dist/js/demo.js"></script>


  <script>
    // Initialiser une nouvelle instance de Vue
    new Vue({
      el: '#app',
      data: {
        loggedIn: false,
        username: '',
        password: '',
        dataList: [
          { id: 1, title: 'Transfert Mobile Money', from :'+228 98489705', to:'+228 90050505', amount: '5000' },
          { id: 2, title: 'Donnée 2' , from :'+228 98489705', to:'+228 90050505' },
          { id: 3, title: 'Donnée 3', from :'+228 98489705', to:'+228 90050505' },
        ],
        resumeCardData: [
          { id: 1, title: 'Total Clients', number: <?= $nbre_lignes; ?>, icon: 'fas fa-users', subBoxStyle: 'info-box-icon elevation-1 bg-success', url: 'consumers-all.php' },
          { id: 2, title: 'Total Marchands', number: '0', icon: 'fas fa-store', subBoxStyle: 'info-box-icon elevation-1 bg-info', url: '#' },
          { id: 3, title: 'Total Chauffeurs', number: '0', icon: 'fas fa-car', subBoxStyle: 'info-box-icon elevation-1 bg-primary ', url: '#' },
          { id: 4, title: 'Total Opérations', number: '0', icon: 'fas fa-exchange-alt', subBoxStyle: 'info-box-icon elevation-1 bg-danger ', url: '#' },
        ],
        statCardData: [
          { id: 1, title: 'Client(s)', number: <?= $nbre_lignes; ?>, subBoxStyle: 'bg-success elevation-1 stat-info', activeUser: '50' },
          { id: 2, title: 'Marchand(s)', number: '0', subBoxStyle: 'bg-danger elevation-1 stat-info', activeUser: '0' },
          { id: 3, title: 'Chauffeur(s)', number: '0', subBoxStyle: 'elevation-1 stat-info', activeUser: '0' },
        ],
      }
    });
  </script>
</body>

</html>