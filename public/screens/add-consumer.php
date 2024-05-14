<?php
session_start();
include('../../api_codes/api_req_functions.php');

$current_page = "../screens/".basename($_SERVER['PHP_SELF']);

if(empty($_SESSION['token_auth'])) {
  header("Location: ../index.php");
  echo "Veuillez vous connecter!";
}

// Définir les en-têtes personnalisés nécessaires pour les prochaines requêtes
$headers_all = [
  'Content-Type: application/json',
];

$exist_activ_user_data = [];
$exist_inactiv_user_phone = "";

$url_signup = "http://104.196.146.173:9000/api/v1/auth/signup";

if($_SERVER["REQUEST_METHOD"] == "POST"){

  $reg_user_lname = $_POST["nom"];
  $reg_user_fname = $_POST["prenom"];
  $reg_user_phone = $_POST["numero"];
  $reg_user_mdp1 = $_POST["mdp1"];
  $reg_user_mdp2 = $_POST["mdp1"];

  if(!empty($reg_user_lname) && !empty($reg_user_fname) && !empty($reg_user_phone) && !empty($reg_user_mdp1) && !empty($reg_user_mdp2)){
    if($reg_user_mdp1 = $_POST["mdp1"] == $reg_user_mdp1 = $_POST["mdp2"]){

      $roleName = "client";
      $donnees_reg = [
        "password" => $_POST["mdp1"],
        "phone" => $_POST["numero"],
        "nom" => $_POST["nom"],
        "prenom" => $_POST["prenom"],
        "role_name" => $roleName
      ];
      $reg_user_data = api_post_data_function($url_signup,$donnees_reg,$headers_all);

      $decode_register_user_data = json_decode($reg_user_data, true);
      
      if ($decode_register_user_data !== null) {
        if(strpos($decode_register_user_data['message'], "existe") == true){
          $exist_activ_user_data[] = $decode_register_user_data['utilisateur'];
        }
        else if(strpos($decode_register_user_data['message'], "suppri") == true){
          $exist_inactiv_user_phone = $decode_register_user_data['utilisateur']['phone'];
        }
        else{
          
        }
      }
    }
    else {
      echo "Les mots de passe ne correspondent pas";
    }
  }
  else {
    echo "Veuillez remplir tous les champs";
  }

}
else {

}

$nbre_lignes = 0;
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Ajouter un client | Mayo Admin</title>
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

  <div>
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h4 class="m-0 text-dark">Ajouter un client</h4>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
      <div class="row">
          <div class="col-12">
          <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title">Informations du client</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="POST" role="form">
                <div class="card-body">
                  <div class="form-group">
                    <label for="InputLastName">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrer le nom du client">
                  </div>
                  <div class="form-group">
                    <label for="InputFirstName">Prénom(s)</label>
                    <input type="text" class="form-control" id="nom" name="prenom" placeholder="Entrer le/les prénoms du client">
                  </div>
                  <div class="form-group">
                    <label for="InputTel">Numéro du client (Format +228XXXXXXXX)</label>
                    <input type="text" class="form-control" id="numero" name="numero" placeholder="Entrer le numéro du client">
                  </div>
                  <div class="form-group">
                    <label for="InputPassword1">Mot de passe</label>
                    <input type="password" class="form-control" id="mdp1" name="mdp1" placeholder="Choisir un mot de passe">
                  </div>
                  <div class="form-group">
                    <label for="InputPassword2">Confirmation du mot de passe</label>
                    <input type="password" class="form-control" id="mdp2" name="mdp2" placeholder="Entrer le même mot de passe">
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-default" style="background-color:#AA742A;color:white">Soumettre</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
    <!-- /.row (main row) -->
  </div><!-- /.container-fluid -->
  </div>
  </section>
  <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
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