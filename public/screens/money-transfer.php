<?php
session_start();
include ('../../api_codes/api_req_functions.php');

if (empty($_SESSION['token_auth'])) {
  header("Location: ../index.php");
  echo "Veuillez vous connecter!";
  die;
}

// URL de l'API pour récupérer la liste des users
$url_get_users = "http://104.196.146.173:9000/api/v1/utilisateur";

$urls_money_trans = [];

// Définir les en-têtes personnalisés nécessaires pour les prochaines requêtes
$headers_all = [
  'Content-Type: application/json',
  'Authorization: Bearer ' . $_SESSION['token_auth']
];

$user_id_auth = $_SESSION['user_id_auth'];

$users_data = api_get_data_function($url_get_users, $headers_all);
$decode_users_data = json_decode($users_data, true);

$users_ids = [];
$users_data_list = []; // Tableau pour stocker les données complètes des utilisateurs

$enregistrements = [];

$nbre_lignes = 0;

  $url_money_trans = "http://104.196.146.173:9004/api/v1/transactionInterne/$user_id_auth";
  
  $money_trans_data = api_get_data_function($url_money_trans, $headers_all);
  $decode_money_trans_data = json_decode($money_trans_data, true);

if ($decode_users_data !== null && isset($decode_users_data['data'])) {
  // Parcourir les enregistrements et afficher les valeurs des propriétés spécifiques
  foreach ($decode_users_data['data'] as $enregistrement) {
    $users_ids[] = $enregistrement['id'];
    $users_data_list[] = $enregistrement; // Ajouter les données complètes de l'utilisateur au tableau
  }
} else {
  echo "Erreur de décodage ou de type des données JSON concernant les ID des utilisateurs.";
}

foreach ($users_ids as $user_id) {
  $urls_money_trans[] = "http://104.196.146.173:9004/api/v1/transactionInterne/user/all/$user_id";
}

$money_trans_data = api_data_array_function($urls_money_trans, $headers_all);

foreach ($money_trans_data as $money_trans_data_item) {
  $decode_money_trans_data[] = json_decode($money_trans_data_item, true);
}

$data_money_trans_vue = [];

if ($decode_money_trans_data !== null) {
  // Parcourir les enregistrements et afficher les valeurs des propriétés spécifiques
  foreach ($decode_money_trans_data as $money_trans_enregistrement) {
    if (isset($money_trans_enregistrement['data'])) {
      $data_money_trans_vue = array_merge($data_money_trans_vue, $money_trans_enregistrement['data']);
      $nbre_lignes += count($money_trans_enregistrement['data']);
    }
  }
} else {
  echo "Erreur de décodage ou de type des données JSON concernant les transactions de transfert d'argent.";
}

// Tri des transactions par date
usort($data_money_trans_vue, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

$data_money_trans_vue_json = json_encode($data_money_trans_vue);
$users_data_list_json = json_encode($users_data_list); // Convertir le tableau des utilisateurs en JSON

?>



<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Transfert d'argent | Mayo Admin</title>
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

  <div id="app2">
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h4 class="m-0 text-dark">Transferts d'argent</h4>
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
                  <input type="text" id="table-searchbar" name="table_search" class="form-control float-right"
                    style="height: 40px" placeholder="Rechercher">
                </div>
                <div class="col-md-4 input-group input-group-sm mr-3">
                  <span style="margin-right:10px;margin-top:7px">Filtre de dates</span>
                  <input type="date" id="dateDebut" class="form-control float-right mr-2" style="height: 40px">
                  <input type="date" id="dateFin" class="form-control float-right" style="height: 40px">
                </div>
                
                <!-- <div class="input-group input-group-sm" style="width: 275px;margin-right:15px">
                  <span style="margin-right:10px;margin-top:7px">Trier par</span>
                  <select class="form-control" style="height: 40px">
                    <option>---Aucun---</option>
                    <option>Date de transaction</option>
                    <option>Numéro de l'expéditeur</option>
                    <option>Numéro du destinataire</option>
                    <option>Montant</option>
                    <option>Frais</option>
                    <option>Statut</option>
                  </select>
                </div> -->
                <!-- <div class="input-group input-group-sm" style="width: 125px;">
                  <button type="button" class="btn btn-default btn btn-block btn-outline-secondary" data-toggle="modal"
                    data-target="#modal-default">Exporter</button>
                </div> -->
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
                <table id="tableau-moneyTrans" class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                     
                      <th>ID</th>
                      <th>Date</th>
                      <th>Expéditeur</th>
                      <th>Destinataire</th>
                      <th>Montant</th>
                      <th>Statut</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(data, index) in moneyTransList" :key="data.id">
                     
                      <td> {{ index + 1 }} </td>
                      <td id="colonne-date"> {{ formatDate(data.date) }} </td>
                      <!-- <th> {{ getPrenom(data.id)}}</th> -->
                      <td style="text-transform:uppercase"> {{ getNom(data.id_utilsateur)}}</td>
                      <td> +228 {{ extraireNumDestinataire(data.body) }} </td>
                      <td> {{ data.amount }} F CFA</td>
                      <td v-if="data.status"><span class="text-success" style="font-weight:bold"> {{ data.status }}
                        </span></td>
                      <td v-else><span class="text-success" style="font-weight:bold"> Réussi </span></td>
                      <td>
                        <button type="button" :class="btns.viewClassValue" data-toggle="modal" 
                          data-target="#modal-primary"><i :class="btns.viewIconValue"></i></button>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <div class="modal fade" id="modal-primary">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Détails de la transaction</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <p>{{ sms }}</p>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
    <strong>Nombre de lignes : <?= $nbre_lignes ?></strong>
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
      el: '#app2',
      data: {
        loggedIn: false,
        username: '',
        password: '',
        sms: 'message',
        moneyTransList: <?php echo $data_money_trans_vue_json; ?>,
        users: <?php echo $users_data_list_json; ?>,
        btns: { viewClassValue: 'btn btn-primary btn-xs', viewIconValue: 'far fa-eye' }
      },

      mounted() {
        // Surveiller les changements
        const updateData = () => {
          let myData = <?php echo $data_money_transfer_vue_json; ?>;
          if (myData && myData.length > 0) {
            let usersData = <?php echo $json_users_data; ?>;

            for(let i=0;i<usersData.length;i++){
                for(let j=0;j<myData.length;j++){
                  if (usersData[i]['id'] == myData[j]['id_utilsateur']) {
                      // Ajoutez le nom et le prénom de l'utilisateur aux données de paiement
                       myData[j]['nom'] = usersData[i]['nom'];
                       myData[j]['prenom'] = usersData[i]['prenom'];
                       myData[j]['phone'] = usersData[i]['phone'];
                  } 
                }
            } 
            let existingUsersMoneyTrans = [];
              for(let i=0;i<myData.length;i++){
                 if(myData[i]['phone'] !== undefined){
                  existingUsersMoneyTrans.push(myData[i]);
                 }
              }
              //console.log(existingUsersMoneyTrans);
              this.moneyTransList = existingUsersMoneyTrans;
          } else {
            setTimeout(updateData, 100); // Vérifier à nouveau dans 100ms
          }

        };

        updateData(); // Démarrer la surveillance
      },
      methods: {
        formatDate(dateRecue) {
          const dateObj = new Date(dateRecue);

          const jour = dateObj.getDate();
          const mois = dateObj.getMonth() + 1;
          const annee = dateObj.getFullYear();

          const dateFormatee = `${jour < 10 ? '0' + jour : jour}-${mois < 10 ? '0' + mois : mois}-${annee}`;

          return dateFormatee;
        },

        getNom(userId) {
          const user = this.users.find(user => user.id === userId);
          return user ? `${user.nom} ${user.prenom}` : 'Inconnu';
        },

        pushSms(content) {
          this.sms = 'content';
        },


        extraireNumDestinataire(message) {
          // Recherche du mot-clé "FCFA au"
          var position = message.indexOf("FCFA au ");

          // Si le mot-clé est trouvé
          if (position !== -1) {
            // Extrait les 8 caractères après le mot-clé
            var montant = message.substr(position + "FCFA au ".length, 8);

            // Retourne le montant extrait
            return montant;
          } else {
            // Si le mot-clé n'est pas trouvé, retourne une chaîne vide
            return "";
          }
        }
      }
    });

    document.getElementById('table-searchbar').addEventListener('input', function (event) {
      searchEl(event.target.value);
    });

    function searchEl(motsCles) {
      var lignes = document.querySelectorAll('#tableau-moneyTrans tbody tr');

      lignes.forEach(function (ligne) {
        var colonnes = ligne.querySelectorAll('td');
        var afficherLigne = false;

        colonnes.forEach(function (colonne) {
          if (colonne.textContent.toLowerCase().includes(motsCles.toLowerCase())) {
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
    document.getElementById('dateDebut').addEventListener('change', function () {
      filtrerParPeriode(new Date(this.value), new Date(document.getElementById('dateFin').value));
    });

    document.getElementById('dateFin').addEventListener('change', function () {
      filtrerParPeriode(new Date(document.getElementById('dateDebut').value), new Date(this.value));
    });

    function filtrerParPeriode(dateDebut, dateFin) {
      if (dateDebut > dateFin) {
        alert("La date de début ne peut pas être postérieure à la date de fin.");
        return;
      }

      var lignes = document.querySelectorAll('#tableau-moneyTrans tbody tr');

      lignes.forEach(function (ligne) {
        var colonneDate = ligne.querySelector('#colonne-date');
        var dateLigne = new Date(colonneDate.textContent);

        if (dateLigne >= dateDebut && dateLigne <= dateFin) {
          ligne.style.display = '';
        } else if (dateLigne <= dateDebut && dateLigne >= dateFin) {
          ligne.style.display = 'none';
        } else if (dateLigne >= dateDebut && isNaN(dateFin.getTime())) {
          ligne.style.display = '';
        } else if (isNaN(dateDebut.getTime()) && dateLigne <= dateFin) {
          ligne.style.display = '';
        } else {
          ligne.style.display = 'none';
        }
      });
    }
    document.getElementById('sort-select').addEventListener('change', function (event) {
      sortEl(event.target.value);
    });

  </script>
</body>

</html>

<style>
  #operation {
    display: block;
  }

  #moneytransfert i {
    color: #AA742A !important;
  }

  #moneytransfert p {
    color: #AA742A !important;
  }

  #moneytransfert {
    background-color: #fff !important;
    color: rgb(70, 70, 70);
  }
</style>