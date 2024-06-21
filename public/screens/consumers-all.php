<?php
session_start();
include ('../../api_codes/api_req_functions.php');

if (empty($_SESSION['token_auth'])) {
  header("Location: ../index.php");
  die;
}

// URL de l'API pour récupérer la liste des utilisateurs
$url_get_users = "http://104.196.146.173:9000/api/v1/utilisateur";

// Définir les en-têtes personnalisés nécessaires pour les prochaines requêtes
$headers_all = [
  'Content-Type: application/json',
  'Authorization: Bearer ' . $_SESSION['token_auth']
];

$headers_base = [
  'Content-Type: application/json'
];

if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST["deleteForm"])){
    $delUserId = $_POST["delID"];

    $url_delete_user = "$baseUrl/api/v1/utilisateur/$delUserId";
  
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
    $url_update_user = "$baseUrl/api/v1/utilisateur/$updUserId";
  
    api_put_data_function($url_update_user,$donnees_update,$headers_all);
    header("Location: consumers-all.php");
    exit();
  }
  else if(isset($_POST["updatePwdForm"])){
    
    $url_get_reset_token = "$baseUrl/api/v1/reinitialser";
    $url_pwd_update_user = "$baseUrl/api/v1/reinitialser/reset";

    $phone = $_POST["openUpdatePwdPhone"];

    $donnees_get_reset_token = [
      "phone" => $phone
    ];

    $data_user_update_pwd = api_post_data_function($url_get_reset_token,$donnees_get_reset_token,$headers_base);

    $decode_data_user_update_pwd = json_decode($data_user_update_pwd, true);

    $resetToken = $decode_data_user_update_pwd['resetToken'];

    $newPwd = $_POST["openUpdatePwdNew"];

    $donnees_pwd_update = [
      "resetToken" => $resetToken,
      "newPassword" => $newPwd
    ];
  
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
    // Ajouter l'enregistrement aux données à afficher
    $data_vue[] = $enregistrement;
    $nbre_lignes++;
  }

  // Tri des transactions par date
  usort($data_vue, function ($a, $b) {
    $dateA = isset($a['createdAt']) ? strtotime($a['createdAt']) : 0;
    $dateB = isset($b['createdAt']) ? strtotime($b['createdAt']) : 0;
    return $dateB - $dateA;
  });

} else {
  echo "Erreur de décodage ou de type des données JSON.";
}

$data_vue_json = json_encode($data_vue);


?>



<style>
  #clients i {
    color: #AA742A !important;
  }

  #clients p {
    color: #AA742A !important;
  }

  #clients {
    background-color: #fff !important;
    color: rgb(70, 70, 70);
  }
</style>


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

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
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
  <?php include '../partials/toasts.php'; ?>

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
                  <input type="text" id="table-searchbar" name="table_search" class="form-control float-right"
                    style="height: 40px" placeholder="Rechercher">
                </div>
                <div class="col-md-4 input-group input-group-sm mr-3">
                  <span style="margin-right:10px;margin-top:7px">Filtre de dates</span>
                  <input type="date" id="dateDebut" class="form-control float-right mr-2" style="height: 40px">
                  <input type="date" id="dateFin" class="form-control float-right" style="height: 40px">
                </div>
                <div class="col-md-4 input-group input-group-sm mr-3">
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-create-user">
                    Créer un utilisateur
                  </button>
                  </clas>
                </div>
              </div>
              <div class="input-group input-group-sm" style="width: 250px;margin-right:15px">
                <!-- <span style="margin-right:10px;margin-top:7px">Classer par</span>
                  <select class="form-control" style="height: 40px" v-model="sortCriteria" @change="sortTable">
                    <option value="none">---Aucun---</option>
                    <option value="date">Date d'inscription</option>
                    <option value="operator">Opérateur</option>
                    <option value="status">Statut</option>
                    <option value="cashback">Cashback</option>
                  </select>
                </div> -->


                <!-- modal -->
                <div class="modal fade" id="modal-create-user">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Créer un utilisateur</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form action="../../api_codes/create_user.php" method="post">
                          <div class="form-group">
                            <label for="role">Rôle</label>
                            <select id="role" name="role_name" class="form-control" required>
                              <option value="client">Client</option>
                              <option value="admin">Admin</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                          </div>
                          <div class="form-group">
                            <label for="password">Mot de passe</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                          </div>
                          <div class="form-group">
                            <label for="nom">Nom</label>
                            <input type="text" id="nom" name="nom" class="form-control" required>
                          </div>
                          <div class="form-group">
                            <label for="prenom">Prénom</label>
                            <input type="text" id="prenom" name="prenom" class="form-control" required>
                          </div>
                          <div class="form-group">
                            <label for="phone">Téléphone</label>
                            <input type="tel" id="phone" name="phone" class="form-control" required>
                          </div>
                          <button type="submit" class="btn btn-primary">Créer</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- fin de modal  -->

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

                      <th>ID</th>
                      <th>Date d'inscription</th>
                      <th>Nom & Prénom(s)</th>
                      <th>Opérateur</th>
                      <th>Téléphone</th>
                      <th>OTP</th>
                      <th>Statut</th>
                      <th>Cashback</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(data, index) in userList" :key="data.id">

                      <td> {{ index + 1 }} </td>
                      <td id="colonne-date"> {{ formatDate(data.createdAt) }} </td>
                      <td style="text-transform:uppercase"> {{ data.nom }} {{ data.prenom }}</td>
                      <td> {{ operatorCheck(data.phone) }} </td>
                      <td> {{ data.phone }} </td>
                      <td> {{ data.otp }} </td>
                      <td style="text-transform:uppercase"><span> {{ data.status }} </span></td>
                      <td v-if="data.cashbackPoints"> {{ data.cashbackPoints }} </td>
                      <td v-else> 0 </td>
                      <td>
                        <button type="button" :class="btns.editClassValue" data-toggle="modal"
                          data-target="#modal-primary"><i :class="btns.editIconValue"></i></button>
                        <button type="button" :class="btns.disableClassValue" data-toggle="modal"
                          :data-phone="data.phone" :data-otp="data.otp" data-target="#modal-desactivation"><i
                            :class="btns.disableIconValue"></i></button>

                        <button type="button" :class="btns.deleteClassValue" data-toggle="modal"
                          data-target="#modal-delete" :data-id="data.id" :data-phone="data.phone"
                          :data-name="data.nom + ' ' + data.prenom" data-target="#modal-delete"><i
                            :class="btns.deleteIconValue"></i></button>

                      </td>
                    </tr>
                  </tbody>
                </table>

                <div class="modal fade" id="modal-primary">
                  <div class="modal-dialog">
                    <div class="modal-content">
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

                <!-- Modal pour désactiver le compte -->
                <div class="modal fade" id="modal-desactivation">
                  <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Activer le compte</h4>
                      </div>
                      <div class="modal-body">
                        <form action="../../api_codes/verify_user.php" method="post">
                          <div class="form-group">
                            <label for="phone">Téléphone</label>
                            <input type="text" id="phone" name="phone" class="form-control" readonly>
                          </div>
                          <div class="form-group">
                            <label for="otp">Code OTP</label>
                            <input type="text" id="otp" name="otp" class="form-control" readonly>
                          </div>
                          <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-outline-secondary"
                              data-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-outline-success">Activer</button>
                          </div>
                        </form>
                      </div>

                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
              </div>


              <!-- Modal pour supprimer le compte -->
              <div class="modal fade" id="modal-delete">
                <div class="modal-dialog modal-sm">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Suppression de compte</h4>
                    </div>
                    <div class="modal-body">
                      <div class="alert alert-warning" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> Êtes-vous sûr de vouloir supprimer ce compte
                        utilisateur ?
                      </div>
                      <form id="delete-form" action="../../api_codes/delete_user.php" method="post">
                        <input type="hidden" id="delete-id" name="id">
                        <div class="form-group">
                          <label for="delete-phone">Téléphone</label>
                          <input type="text" id="delete-phone" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                          <label for="delete-name">Nom</label>
                          <input type="text" id="delete-name" class="form-control" readonly>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Annuler</button>
                          <button type="submit" class="btn btn-outline-danger">Supprimer</button>
                        </div>
                      </form>
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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <script>
    // Initialiser une nouvelle instance de Vue
    new Vue({
      el: '#app',
      data: {
        loggedIn: false,
        username: '',
        password: '',
        user: {
          role_name: 'client',
          email: '',
          password: '',
          nom: '',
          prenom: '',
          phone: '',
          cashbackPoints: 0
        },
        userList: <?php echo $data_vue_json; ?>,
        btns: {
          editClassValue: 'btn btn-primary btn-xs',
          editIconValue: 'fas fa-user-edit',
          disableClassValue: 'btn btn-warning btn-xs',
          disableIconValue: 'fas fa-power-off',
          deleteClassValue: 'btn btn-danger btn-xs',
          deleteIconValue: 'fas fa-trash'
        },
        sortCriteria: 'none'
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
        },
        sortTable() {
          if (this.sortCriteria === 'none') {
            return;
          }
          this.userList.sort((a, b) => {
            if (this.sortCriteria === 'date') {
              return new Date(a.date) - new Date(b.date);
            } else if (this.sortCriteria === 'operator') {
              return this.operatorCheck(a.numero).localeCompare(this.operatorCheck(b.numero));
            } else if (this.sortCriteria === 'status') {
              return a.status.localeCompare(b.status); // Assuming 'status' is a field in user data
            } else if (this.sortCriteria === 'cashback') {
              return a.cashback - b.cashback; // Assuming 'cashback' is a numerical field in user data
            }
          });
        },
        createUser() {
          const token = '<?php echo $_SESSION['token_auth']; ?>';
          axios.post('http://104.196.146.173:9000/api/v1/utilisateur', this.user, {
            headers: {
              'Content-Type': 'application/json',
              'Authorization': `Bearer ${token}`
            }
          })
            .then(response => {
              console.log(response.data);
              // Fermez la modal après création
              $('#modal-create-user').modal('hide');
              // Réinitialiser le formulaire
              this.user = {
                role_name: 'client',
                email: '',
                password: '',
                nom: '',
                prenom: '',
                phone: '',
                cashbackPoints: 0
              };
            })
            .catch(error => {
              console.error(error);
            });
        }
      }
    });

    document.getElementById('table-searchbar').addEventListener('input', function (event) {
      searchEl(event.target.value);
    });

    function searchEl(motsCles) {
      var lignes = document.querySelectorAll('#tableau-consumers tbody tr');
      var motsClesArray = motsCles.toLowerCase().split(' ');

      lignes.forEach(function (ligne) {
        var colonnes = ligne.querySelectorAll('td');
        var afficherLigne = false;

        colonnes.forEach(function (colonne) {
          var colonneContientMotsCles = motsClesArray.every(function (motCle) {
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

      var lignes = document.querySelectorAll('#tableau-consumers tbody tr');

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

    function sortEl(criteria) {
      var lignes = Array.from(document.querySelectorAll('#tableau-consumers tbody tr'));

      lignes.sort(function (a, b) {
        var valueA, valueB;

        if (criteria === 'date') {
          valueA = new Date(a.querySelector('.date-column').textContent);
          valueB = new Date(b.querySelector('.date-column').textContent);
        } else if (criteria === 'operator') {
          valueA = a.querySelector('.operator-column').textContent.toLowerCase();
          valueB = b.querySelector('.operator-column').textContent.toLowerCase();
        } else if (criteria === 'status') {
          valueA = a.querySelector('.status-column').textContent.toLowerCase();
          valueB = b.querySelector('.status-column').textContent.toLowerCase();
        } else if (criteria === 'cashback') {
          valueA = parseFloat(a.querySelector('.cashback-column').textContent);
          valueB = parseFloat(b.querySelector('.cashback-column').textContent);
        }

        if (valueA < valueB) {
          return -1;
        }
        if (valueA > valueB) {
          return 1;
        }
        return 0;
      });

      var tbody = document.querySelector('#tableau-consumers tbody');
      tbody.innerHTML = '';
      lignes.forEach(function (ligne) {
        tbody.appendChild(ligne);
      });
    }

  </script>
  <script>
    $(document).ready(function () {
      $('#modal-desactivation').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var phone = button.data('phone'); // Extract info from data-* attributes
        var otp = button.data('otp'); // Extract info from data-* attributes

        var modal = $(this);
        modal.find('#phone').val(phone);
        modal.find('#otp').val(otp);
      });
    });


    $(document).ready(function () {
      $('#modal-desactivation').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var phone = button.data('phone'); // Extract info from data-* attributes
        var otp = button.data('otp'); // Extract info from data-* attributes

        var modal = $(this);
        modal.find('#phone').val(phone);
        modal.find('#otp').val(otp);
      });

      $('#modal-delete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id'); // Extract info from data-* attributes
        var phone = button.data('phone');
        var name = button.data('name');

        var modal = $(this);
        modal.find('#delete-id').val(id);
        modal.find('#delete-phone').val(phone);
        modal.find('#delete-name').val(name);
      });
    });


  
    $(document).ready(function() {
      toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "3000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      };

      const urlParams = new URLSearchParams(window.location.search);
      const successMessages = urlParams.getAll('success');

      if (successMessages.length > 0) {
        const lastSuccessMessage = successMessages[successMessages.length - 1];
        toastr.success(lastSuccessMessage, 'Succès');
      }
    });
  </script>

</body>

</html>