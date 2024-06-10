<?php
// require '../vendor/autoload.php';
session_start();
include('../api_codes/api_req_functions.php');
include('../api_codes/api_signin.php');

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $username = $_POST["username"];
    $pwd = $_POST["password"];

    $donnees_auth = [
        "password" => $pwd,
        "phone" => $username
      ];

      $api_connect = api_auth_function($url_auth, $donnees_auth, $headers_auth);
  
      $decode_signin_data = json_decode($api_connect, true);
      
      $token_auth = $decode_signin_data['token'];
      $user_id_auth = $decode_signin_data['data']['utilisateur']['id'];
      
      // Vérifier si le token d'authentification a été obtenu avec succès
      if ($token_auth) {
        // Stocker le token d'authentification dans la variable de session
        $_SESSION['token_auth'] = $token_auth;
        $_SESSION['user_id_auth'] = $user_id_auth;
        echo "Token d'authentification et ID de l'utilisateur obtenus avec succès.";
      } else {
        echo "Échec de l'obtention du token d'authentification.";
      }

    if(!empty($username) && !empty($pwd)){

        if(!empty($_SESSION['token_auth'])){
                header("Location: screens/dashboard.php");
                die;
        }
        else {
            header("Location: index.php");
            echo "Nom d'utilisateur ou mot de passe incorrect";
            die;
        }
    }    
    else {
        echo "Informations de connexion manquantes";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Admin Panel</title>
    <!-- Inclure Vue.js -->
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
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>

<body>
    <div id="app">
        <!-- <h1>Admin Panel</h1> -->
        <!-- Formulaire de connexion -->
        <div v-if="!loggedIn">

            <div class="red-background ">
                <div class="pattern-container">
                    <div class="container custom-container center">
                        <center>
                            <h2 class="title-style">Bienvenue !</h2>
                            <p class="text-style">Connectez-vous pour avoir accès a l'interface d'administration</p>
                        </center>
                        <form method="POST">
                            <label for="username" class="form-label label">Nom d'utilisateur</label>
                            <br>
                            <input type="text" id="username" name="username" class="form-control" v-model="username">
                            <div class="row">
                                <div class="col">
                                    <label for="password" class="form-label label">Mot de passe</label>
                                </div>
                                <div class="col flex-right">
                                    <span class="align-right text-style2">Mot de passe oublié ?</span>
                                </div>
                            </div>

                            <input type="password" id="password" name="password" class="form-control input" v-model="password">
                            <br>
                            <center>

                                <button type="submit" class="btn btn-primary custom-button">Connexion</button>

                            </center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Contenu de l'admin panel -->
        <div v-else>
            <h2>Bienvenue, {{ username }}</h2>
            <!-- Ajoutez ici le contenu de l'admin panel -->
            <button @click="logout">Déconnexion</button>
        </div>
    </div>
   
</body>
<style>
    .red-background {
        background-color: #AA742A;
        height: 100vh;
        /* Utilise toute la hauteur de l'écran */
        display: flex;
        justify-content: center;
        /* Centre le contenu horizontalement */
        align-items: center;
        /* Centre le contenu verticalement */
    }

    .custom-container {
        background-color: white;
        border-radius: 2px;
        /* Bordures légèrement arrondies */
        padding-top: 50px;
        padding-bottom: 50px;
        padding-left: 5vw;
        padding-right: 5vw;
        width: 35vw;


    }

    .flex-right {
        display: flex;
        justify-content: end;
        align-items: center;

    }

    .text-style {
        font-size: 10px;
        color: #AA742A;
    }

    .text-style2 {
        font-size: 10px;
        color: black;

    }

    .title-style {
        font-size: 20px;
        font-weight: 800;
        color: #AA742A;
    }

    .pattern-container {
        background-image: url('./assets/images/bg.png');
        background-size: cover;
        height: 100vh;
        width: 100vw;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 10px;
        /* Bordures légèrement arrondies */
    }

    .label {
        color: #AA742A;
        font-weight: bold;
        font-size: 12px;
        size: 12px;
    }

    .custom-button {
        width: 100%;
        background-color: #AA742A;
        border: none;
        border-radius: 14;
    }

    .custom-button:hover {
        background-color: #bc7c23;
    }

    input {
        border-color: transparent;
        /* Pas de couleur de bordure par défaut */
        border-width: 0;
        /* Supprime la largeur de la bordure */
        outline: none;
        /* Supprime l'effet de mise en évidence par défaut */
        background-color: transparent;
        /* Pas de couleur de fond par défaut */
    }

    /* Définit la couleur de la bordure lorsqu'elle est en focus */
    input:focus {
        border-color: red;
        /* Couleur de bordure rouge lorsque le focus est actif */
    }
</style>

</html>