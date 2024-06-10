<?php
session_start();

if (empty($_SESSION['token_auth'])) {
  header("Location: ../index.php");
  die;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'];
  $token = $_SESSION['token_auth'];
  $url = 'http://104.196.146.173:9000/api/v1/utilisateur/' . $id;

  $options = [
    'http' => [
      'header'  => "Authorization: Bearer $token\r\n",
      'method'  => 'DELETE'
    ]
  ];

  $context  = stream_context_create($options);
  $result = file_get_contents($url, false, $context);

  if ($result === FALSE) {
    // Gérer l'erreur
    echo "Erreur lors de la suppression de l'utilisateur.";
  } else {
    // Décoder la réponse et afficher un message de succès
    $response = json_decode($result, true);
    if ($response['message'] == 'Utilisateur supprimé avec succès') {
      echo "Utilisateur supprimé avec succès.";
      $message = urlencode("Utilisateur supprimé avec succès");
      $redirectUrl = $_SERVER['HTTP_REFERER'];
      $separator = (parse_url($redirectUrl, PHP_URL_QUERY) == NULL) ? '?' : '&';
      header("Location: {$redirectUrl}{$separator}success={$message}");
      exit;
      
    } else {
      echo "Échec de la suppression de l'utilisateur : " . $response['message'];
    }
  }
} else {
  echo "Requête invalide.";
}
?>
