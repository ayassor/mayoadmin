<?php
session_start();
include('../../api_codes/api_req_functions.php');

$current_page = "../screens/".basename($_SERVER['PHP_SELF']);

if (empty($_SESSION['token_auth'])) {
  header("Location: ../index.php");
  echo "Veuillez vous connecter!";
  die;
}

$url_get_users = "http://104.196.146.173:9000/api/v1/utilisateur";
$nbre_lignes = 0;

$headers_all = [
  'Content-Type: application/json',
  'Authorization: Bearer ' . $_SESSION['token_auth']
];

$users_data = api_get_data_function($url_get_users, $headers_all);
$decode_users_data = json_decode($users_data, true);
$users_ids = [];
$users_data_vue = [];

if ($decode_users_data !== null && isset($decode_users_data['data'])) {
  // Parcourir les enregistrements et afficher les valeurs des propriétés spécifiques
  foreach ($decode_users_data['data'] as $enregistrement) {
    $users_ids[] = $enregistrement['id'];
    $users_data_vue[] = $enregistrement;
  }
} else {
    echo "Erreur de décodage ou de type des données JSON concernant les ID des utilisateurs.";
}

$json_users_ids = json_encode($users_ids);
$json_users_data = json_encode($users_data_vue);

?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Achat de forfaits | Mayo Admin</title>
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

  <script type="module">

    // Import the functions you need from the SDKs you need
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-app.js";
    import { getFirestore, collection, getDocs } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-firestore.js"

  
    const firebaseConfig = {
      apiKey: "AIzaSyAl7cmE0wnwB9mapNmleYIh-0yi6e2Z5JU",
      authDomain: "united-night-409713.firebaseapp.com",
      projectId: "united-night-409713",
      storageBucket: "united-night-409713.appspot.com",
      messagingSenderId: "19844670722",
      appId: "1:19844670722:web:02ddda73b79c7f69080e1b",
      measurementId: "G-9Y3TMCC495"
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);

    if (app) {
      console.log("Firebase initialisé avec succès !");
    } else {
      console.error("Erreur lors de l'initialisation de Firebase.");
    }

    const db = getFirestore()


    const colRef = collection(db, 'transactions')

    let usersIds = <?php echo $json_users_ids; ?>;

    let forfaitsData = []; // Initialiser un tableau vide pour stocker les données

    getDocs(colRef)
      .then((snapshot) => {
        snapshot.forEach((doc) => {
          var transactionsData = doc.data();
              if(transactionsData.type == "Factures"){
                 forfaitsData.push(doc.data()); // Ajouter les données de chaque document au tableau
              }
        });

        // Afficher le tableau dans la console
        // console.log(transactionsData);
        // pousser les données en php

        window.myData = forfaitsData;

        //console.log(window.myData);

      })
      .catch((error) => {
        console.error('Une erreur s\'est produite lors de la récupération des données :', error);
      })
      .finally(() => {
        console.log('Fin de la récupération des données');
      });

    // console.log("après récupération")

  </script>


  <div id="app3">
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h4 class="m-0 text-dark">Paiements de factures</h4>
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
                  <span style="margin-right:10px;margin-top:7px">Filtrer par dates</span>
                  <input type="date" id="dateDebut" class="form-control float-right mr-2" style="height: 40px">
                  <input type="date" id="dateFin" class="form-control float-right" style="height: 40px">
                </div>
                <div class="input-group input-group-sm" style="width: 275px;margin-right:15px">
                  <span style="margin-right:10px;margin-top:7px">Classer par</span>
                  <select class="form-control" style="height: 40px">
                    <option>---Aucun---</option>
                    <option>Date d'achat</option>
                    <option>Opérateur</option>
                    <option>Type de forfait</option>
                    <option>Intitulé du forfait</option>
                    <option>Numéro de l'expéditeur</option>
                    <option>Numéro du destinataire</option>
                    <option>Montant</option>
                  </select>
                </div>
                <div class="input-group input-group-sm" style="width: 125px;">
                  <button type="button" class="btn btn-default btn btn-block btn-outline-secondary" data-toggle="modal"
                    data-target="#modal-default">Exporter</button>
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
                <table id="tableau-billPaidList" class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Date d'achat</th>
                      <!--<th>Opérateur</th>
                      <th>Type</th>
                      <th>Intitulé</th>
                      <th>Montant</th>-->
                      <th>SMS</th>
                      <th>Id utilisateur</th>
                      <th>Nom de l'acheteur</th>
                      <th>Numéro de l'acheteur</th>
                      <!--<th>Numéro du receveur</th>
                      <th>Type(Forfait/Facture)</th>-->
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(data, index) in billPaidList">
                      <td> {{ index + 1 }}</td>
                      <td id="colonne-date"> {{ formatDate(data.date) }}</td>
                      <!--<td> {{ operatorCheck(data.phone) }}</td>
                      <td> {{ afficherTypeForfait(data.title) }} </td>
                      <td> {{ data.title }} </td>
                      <td> {{ afficherMontant(data.title) }}</td>-->
                      <td> {{ data.body }} </td>
                      <td> {{ data.UserId }} </td>
                      <td> {{ data.nom }} {{ data.prenom }}</td>
                      <td> {{ data.phone }} </td>
                      <!--<td v-if="extraireNumReceiv(data.body)"> +228{{ extraireNumReceiv(data.body) }}</td>
                      <td v-else> {{ data.phone }} </td>-->
                      <!--<td> {{ data.type }}</td>-->
                      <td>
                        <button type="button" :class="btns.viewClassValue" data-toggle="modal"
                          data-target="#modal-primary"><i :class="btns.viewIconValue"></i></button>
                      </td>
                    </tr>
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
      el: '#app3',
      data: {
        loggedIn: false,
        username: '',
        password: '',
        billPaidList: [],
        btns: { viewClassValue: 'btn btn-primary btn-xs', viewIconValue: 'far fa-eye' }
      },

      mounted() {
        // Surveiller les changements de window.myData
        const updateData = () => {
          if (window.myData && window.myData.length > 0) {
            let usersData = <?php echo $json_users_data; ?>;

            for(let i=0;i<usersData.length;i++){
                for(let j=0;j<window.myData.length;j++){
                  if (usersData[i]['id'] == window.myData[j].UserId) {
                      // Ajoutez le nom et le prénom de l'utilisateur aux données de paiement
                       window.myData[j].nom = usersData[i]['nom'];
                       window.myData[j].prenom = usersData[i]['prenom'];
                       window.myData[j].phone = usersData[i]['phone'];
                  } 
                }
            }
              console.log("hey");
              this.billPaidList = window.myData;
          } else {
            setTimeout(updateData, 100); // Vérifier à nouveau dans 100ms
          }

        };

        updateData(); // Démarrer la surveillance
      },

      methods: {
        formatDate(timestamp) {
      // Convertir le timestamp en millisecondes
        let date = new Date(timestamp.seconds * 1000); // Multipliez par 1000 pour convertir en millisecondes

      // Obtenir les composants de la date
        let jour = date.getDate();
        let mois = date.getMonth() + 1; // Les mois sont indexés à partir de 0
        let annee = date.getFullYear();

      // Formater la date dans le format "JJ-MM-AAAA"
        let dateFormatee = mois.toString().padStart(2, '0') + '-' + jour.toString().padStart(2, '0') + '-' + annee.toString();

      // Retourner la date formatée
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
        extraireNumReceiv(message) {
    // Recherche du mot-clé "pour le"
    var position = message.indexOf("pour le ");
    
    // Si le mot-clé est trouvé
    if (position !== -1) {
        // Extrait les 8 caractères après le mot-clé
        var num = message.substr(position + "pour le ".length, 8);
        
        // Retourne le numero extrait
        return num;
    }
    },
    afficherMontant(message) {
    // Modèle de pattern pour extraire les chiffres suivant une parenthèse fermante et un espace
    const pattern = /\) (\d+F)/;
    // Recherche du montant dans le message
    const correspondance = message.match(pattern);
    // Si un montant est trouvé, retournez-le
    if (correspondance && correspondance.length > 0) {
      const montant = correspondance[1]; // Le montant correspond au premier groupe capturé
        return montant.replace('F', ' F CFA'); // Retirer le "F"
    } else {
        // Sinon, retournez une chaîne vide
        return "";
    }
},
    afficherTypeForfait(message) {
    // Recherche des mots précis dans le message
    if (message.includes("NET")) {
        return "Forfait Internet";
    } else if (message.includes("Léma")) {
        return "Forfait Appel";
    } else if (message.includes("OVO")) {
        return "Forfait Mixte";
    } else {
        return "null";
    }
}

    }
    });

    document.getElementById('table-searchbar').addEventListener('input', function (event) {
      searchEl(event.target.value);
    });

    function searchEl(motsCles) {
      var lignes = document.querySelectorAll('#tableau-bundleTrans tbody tr');

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

          var lignes = document.querySelectorAll('#tableau-billPaidList tbody tr');

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
            else if (isNaN(dateDebut.getTime()) && isNaN(dateFin.getTime())) {
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