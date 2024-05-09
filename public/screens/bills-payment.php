<?php
session_start();
include('../../api_codes/api_req_functions.php');

if(empty($_SESSION['token_auth'])) {
  header("Location: ../index.php");
  echo "Veuillez vous connecter!";
  die;
}

// URL de l'API pour récupérer la liste des users
$url_get_users = "http://104.196.146.173:9000/api/v1/utilisateur";

$bills_bundle_trans = [];

// Définir les en-têtes personnalisés nécessaires pour les prochaines requêtes
$headers_all = [
  'Content-Type: application/json',
  'Authorization: Bearer ' . $_SESSION['token_auth']
];

$users_data = api_data_function($url_get_users, $headers_all);

$bills_trans_data = [];

$decode_bills_trans_data = [];

$decode_users_data = json_decode($users_data, true);

$users_ids = [];

$nbre_lignes = 0;

if ($decode_users_data !== null && isset($decode_users_data['data'])) {
  // Parcourir les enregistrements et afficher les valeurs des propriétés spécifiques
  foreach ($decode_users_data['data'] as $enregistrement) {
      $users_ids[] = $enregistrement['id'];
    }
  } else {
      echo "Erreur de décodage ou de type des données JSON concernant les ID des utilisateurs.";
  }

  foreach ($users_ids as $user_id){
      $bills_bundle_trans[] = "http://104.196.146.173:9004/api/v1/transactionInterne/user/all/$user_id";
  }
  
  $bills_trans_data = api_data_array_function($bills_bundle_trans, $headers_all);

  foreach($bills_trans_data as $bills_trans_data_item){
    $decode_bills_trans_data[] = json_decode($bills_trans_data_item, true);
  }

  if ($decode_bills_trans_data !== null) {
    // Parcourir les enregistrements et afficher les valeurs des propriétés spécifiques
    foreach ($decode_bills_trans_data as $bills_trans_enregistrement) {
        $data_bills_trans_vue[] = $bills_trans_enregistrement;
        $nbre_lignes++;
      }
    } else {
        echo "Erreur de décodage ou de type des données JSON concernant les transactions d'achat de forfaits'.";
    }
    //var_dump($data_bills_trans_vue[1]['data']);
  $data_bills_trans_vue_json = json_encode($data_bills_trans_vue[1]['data']);
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Paiement de factures | Mayo Admin</title>
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

  <div id="app4">
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h4 class="m-0 text-dark">Paiement de factures</h4>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
      <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header row">
                <div class="input-group input-group-sm mr-3" style="width: 350px">
                    <input id="table-searchbar" type="text" name="table_search" class="form-control float-right" style="height: 40px" placeholder="Rechercher">
                </div>
                <div class="col-md-4 input-group input-group-sm mr-3">
                    <span style="margin-right:10px;margin-top:7px">Filtrer par dates</span>
                    <input type="date" class="form-control float-right mr-2" style="height: 40px">
                    <input type="date" class="form-control float-right" style="height: 40px">
                </div>
                <div class="input-group input-group-sm" style="width: 275px;margin-right:15px">
                    <span style="margin-right:10px;margin-top:7px">Trier par</span>
                       <select class="form-control" style="height: 40px">
                          <option>---Aucun---</option>
                          <option>Date de transaction</option>
                          <option>Opérateur</option>
                          <option>Type de facture</option>
                          <option>Intitulé de facture</option>
                          <option>Numéro du souscripteur</option>
                          <option>Montant</option>
                        </select>
                </div>
                <div class="input-group input-group-sm" style="width: 125px;">
                    <button type="button" class="btn btn-default btn btn-block btn-outline-secondary" data-toggle="modal" data-target="#modal-default">Exporter</button>
                </div>
                <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Default Modal</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Save changes</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table id="tableau-billsTrans" class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>
                          <input type="checkbox">
                      </th>
                      <th>ID</th>
                      <th>Date de transaction</th>
                      <th>Opérateur</th>
                      <th>Type de facture</th>
                      <th>Intitulé de facture</th>
                      <th>Numéro du souscripteur</th>
                      <th>Montant</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!--<tr v-for="(data, index) in billsTransList" :key="data.id">
                      <td>
                          <input :id="data.id" type="checkbox">
                      </td>
                      <td> {{ index + 1 }} </td>
                      <td> {{ formatDate(data.date) }} </td>
                      <td> {{ data.type }} </td>
                      <td> {{ data.title }} </td>
                      <td> {{ data.title }} </td>
                      <td>+228 {{ data.sender_number }} </td>
                      <td> {{ data.total_amount }} F CFA</td>
                      <td>
                      <button type="button" :class="btns.viewClassValue" data-toggle="modal" data-target="#modal-primary"><i :class="btns.viewIconValue"></i></button>
                      </td>
                    </tr>-->
                  </tbody>
                </table>
                
      <div class="modal fade" id="modal-primary">
        <div class="modal-dialog">
          <div class="modal-content bg-primary">
            <div class="modal-header">
              <h4 class="modal-title">Primary Modal</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-outline-light">Save changes</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
                
      <div class="modal fade" id="modal-danger">
        <div class="modal-dialog">
          <div class="modal-content bg-danger">
            <div class="modal-header">
              <h4 class="modal-title">Danger Modal</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-outline-light">Save changes</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
                <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                  <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                  <li class="page-item"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                </ul>
              </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
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
    <strong><?= $nbre_lignes ?> élément(s) | 0 sélectionné(s)</strong>
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
      el: '#app4',
      data: {
        loggedIn: false,
        username: '',
        password: '',
        billsTransList: <?php echo $data_bills_trans_vue_json; ?>,
        btns: { viewClassValue: 'btn btn-primary btn-xs', viewIconValue: 'far fa-eye' }
      },

      methods: {
         formatDate(dateRecue) {
         const dateObj = new Date(dateRecue);

         const jour = dateObj.getDate();
         const mois = dateObj.getMonth() + 1;
         const annee = dateObj.getFullYear();

         const dateFormatee = `${jour < 10 ? '0' + jour : jour}-${mois < 10 ? '0' + mois : mois}-${annee}`;

         return dateFormatee;
        }
  }
    });

    document.getElementById('table-searchbar').addEventListener('input', function(event) {
         searchEl(event.target.value);
    });

    function searchEl(motsCles) {
    var lignes = document.querySelectorAll('#tableau-billsTrans tbody tr');

    var motsClesArray = motsCles.toLowerCase().split(' ');

    lignes.forEach(function(ligne) {
        var colonnes = ligne.querySelectorAll('td');
        var afficherLigne = false;

        colonnes.forEach(function(colonne) {

          var colonneContientMotsCles = motsClesArray.every(function(motCle) {
                return colonne.textContent.toLowerCase().includes(motCle);
            });

            if (colonneContientMotsCles) {
                afficherLigne = true;
            }
        });

        if (afficherLigne) {
            ligne.style.display = '';
        } else {
            ligne.style.display = 'none';
        }
    });
    }
  </script>
</body>

</html>