<?php
session_start();
include('../../api_codes/api_req_functions.php');

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
  <link rel="stylesheet" href="../dist/css/mayo.css">
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
              <h4 class="m-0 text-dark">Tous les clients</h4>
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
                <div class="input-group input-group-sm mr-3" style="width: 250px">
                    <input type="text" id="table-searchbar" name="table_search" class="form-control float-right" style="height: 40px" placeholder="Rechercher">
                </div>
                <div class="col-md-4 input-group input-group-sm mr-3">
                    <span style="margin-right:10px;margin-top:7px">Filtre de dates</span>
                    <input type="date" id="dateDebut" class="form-control float-right mr-2" style="height: 40px">
                    <input type="date" id="dateFin" class="form-control float-right" style="height: 40px">
                </div>
                <div class="input-group input-group-sm" style="width: 250px;margin-right:15px">
                    <span style="margin-right:10px;margin-top:7px">Classer par</span>
                       <select class="form-control" style="height: 40px">
                          <option>---Aucun---</option>
                          <option>Date d'inscription</option>
                          <option>Derniere mise a jour</option>
                          <option>Opérateur</option>
                          <option>Statut</option>
                          <option>Role</option>
                        </select>
                </div>
                <div class="btn-group" style="width: 250px;">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Ajouter un compte client</button>
                    <button type="button" class="btn btn-default btn-outline-secondary" data-toggle="modal" data-target="#modal-default">Exporter</button>
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
                <table id="tableau-consumers" class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>
                          <input type="checkbox">
                      </th>
                      <th>ID</th>
                      <th>Date d'inscription</th>
                      <th>Derniere mise a jour</th>
                      <th>Nom & Prénom(s)</th>
                      <th>Opérateur</th>
                      <th>Numéro de téléphone</th>
                      <th>Statut</th>
                      <th>Points Cashback</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(data, index) in userList" :key="data.id">
                      <td>
                          <input :id="data.id" type="checkbox">
                      </td>
                      <td> {{ index + 1 }} </td>
                      <td id="colonne-date"> {{ formatDate(data.createdAt) }} </td>
                      <td> {{ formatDate(data.updatedAt) }} </td>
                      <td style="text-transform:uppercase"> {{ data.nom }} {{ data.prenom }}</td>
                      <td> {{ operatorCheck(data.phone) }} </td>
                      <td> {{ data.phone }} </td>
                      <td style="text-transform:uppercase"><span> {{ data.status }} </span></td>
                      <td v-if="data.cashbackPoints"> {{ data.cashbackPoints }} </td>
                      <td v-else> 0 </td>
                      <td>
                      <button type="button" :class="btns.editClassValue" data-toggle="modal" data-target="#modal-primary"><i :class="btns.editIconValue"></i></button>
                      <button type="button" :class="btns.disableClassValue" data-toggle="modal" data-target="#modal-danger"><i :class="btns.disableIconValue"></i></button>
                      <button type="button" :class="btns.deleteClassValue" data-toggle="modal" data-target="#modal-danger"><i :class="btns.deleteIconValue"></i></button>
                      </td>
                    </tr>
                  </tbody>
                </table>
                
      <div class="modal fade" id="modal-primary">
        <div class="modal-dialog">
          <div class="modal-content bg-primary">
            <div class="modal-header">
              <h4 class="modal-title">Modification des informations</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <p>Êtes-vous sûr de vouloir modifier les informations de ce compte utilisateur ?</p>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-outline-light" data-dismiss="modal">Annuler</button>
              <button type="button" class="btn btn-outline-light">Modifier les informations</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
                      
      <div class="modal fade" id="modal-warning">
        <div class="modal-dialog">
          <div class="modal-content bg-warning">
            <div class="modal-header">
              <h4 class="modal-title">Désactivation de compte</h4>
            </div>
            <div class="modal-body">
              <p>Êtes-vous sûr de vouloir désactiver ce compte utilisateur ?</p>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-outline-light" data-dismiss="modal">Annuler</button>
              <button type="button" class="btn btn-outline-light">Désactiver le compte</button>
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
              <h4 class="modal-title">Suppression de compte</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <p>Êtes-vous sûr de vouloir supprimer ce compte utilisateur ?</p>
              <h5>(Cette action est irréversible)</h5>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-outline-light" data-dismiss="modal">Annuler</button>
              <button type="button" class="btn btn-outline-light">Supprimer le compte</button>
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
      el: '#app',
      data: {
        loggedIn: false,
        username: '',
        password: '',
        userList: <?php echo $data_vue_json; ?>,
        btns: { editClassValue: 'btn btn-primary btn-xs', editIconValue: 'fas fa-user-edit', disableClassValue: 'btn btn-warning btn-xs', disableIconValue: 'fas fa-power-off', deleteClassValue: 'btn btn-danger btn-xs', deleteIconValue: 'fas fa-trash'}
      },

      methods: {
        formatDate(dateRecue) {
            const dateObj = new Date(dateRecue);

            const jour = dateObj.getDate();
            const mois = dateObj.getMonth() + 1;
            const annee = dateObj.getFullYear();

            const dateFormatee = `${mois < 10 ? '0' + mois : mois}-${jour < 10 ? '0' + jour : jour}-${annee}`;

            return dateFormatee;
        },
        
        operatorCheck(numero) {
           let ope_name = "";
           for (let i = 0; i < numero.length; i++) {
               let ope_digit = numero[5]; 
               if (ope_digit == 0 || ope_digit == 1 || ope_digit == 2 || ope_digit == 3) {
                  ope_name = "TOGOCOM"; 
               } else if (ope_digit == 9 || ope_digit == 8 || ope_digit == 7 || ope_digit == 6) {
                  ope_name = "MOOV AFRICA"; 
               } else {
                  ope_name = "INCONNU"; 
               }
           }

           return ope_name;
        }

      }
    });

    document.getElementById('table-searchbar').addEventListener('input', function(event) {
         searchEl(event.target.value);
    });

    function searchEl(motsCles) {
       var lignes = document.querySelectorAll('#tableau-consumers tbody tr');

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

       document.getElementById('dateDebut').addEventListener('change', function() {
              filtrerParPeriode(new Date(this.value), new Date(document.getElementById('dateFin').value));
        });

       document.getElementById('dateFin').addEventListener('change', function() {
              filtrerParPeriode(new Date(document.getElementById('dateDebut').value), new Date(this.value));
        });

       function filtrerParPeriode(dateDebut, dateFin) {

          if (dateDebut > dateFin) {
             alert("La date de début ne peut pas être postérieure à la date de fin.");
             return; 
            }

          var lignes = document.querySelectorAll('#tableau-consumers tbody tr');

          lignes.forEach(function(ligne) {
            var colonneDate = ligne.querySelector('#colonne-date'); 
            var dateLigne = new Date(colonneDate.textContent);

            if (dateLigne >= dateDebut && dateLigne <= dateFin) {
                ligne.style.display = '';
            }
            else if (dateLigne <= dateDebut && dateLigne >= dateFin) {
                ligne.style.display = 'none';
            }
            else if (dateLigne >= dateDebut && isNaN(dateFin.getTime())) {
                ligne.style.display = '';
            }
            else if (isNaN(dateDebut.getTime()) && dateLigne <= dateFin) {
                ligne.style.display = '';
            }
            else {
                ligne.style.display = 'none';
            }
        });
    }

  </script>
</body>

</html>