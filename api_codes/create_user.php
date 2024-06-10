<?php
session_start();

if (empty($_SESSION['token_auth'])) {
  header("Location: ../index.php");
  die;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $role_name = $_POST['role_name'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $phone = $_POST['phone'];
  $cashbackPoints = 0; // Valeur par défaut

  $data = [
    'role_name' => $role_name,
    'email' => $email,
    'password' => $password,
    'nom' => $nom,
    'prenom' => $prenom,
    'phone' => $phone,
    'cashbackPoints' => $cashbackPoints
  ];

  $token = $_SESSION['token_auth'];
  $url = 'http://104.196.146.173:9000/api/v1/utilisateur';

  $options = [
    'http' => [
      'header'  => "Content-type: application/json\r\n" .
                   "Authorization: Bearer $token\r\n",
      'method'  => 'POST',
      'content' => json_encode($data)
    ]
  ];

  $context  = stream_context_create($options);
  $result = file_get_contents($url, false, $context);

  if ($result === FALSE) {
    // Gérer l'erreur
    echo "Erreur lors de la création de l'utilisateur.";
  } else {
    // Redirection vers la page de départ
    $message = urlencode("L'utilisateur a bien été créé");
    $redirectUrl = $_SERVER['HTTP_REFERER'];
    $separator = (parse_url($redirectUrl, PHP_URL_QUERY) == NULL) ? '?' : '&';
    header("Location: {$redirectUrl}{$separator}success={$message}");
    exit;
  }
} else {
  echo "Requête invalide.";
}
?>
