<?php
session_start();

if (empty($_SESSION['token_auth'])) {
  header("Location: ../index.php");
  die;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $phone = $_POST['phone'];
  $otp = $_POST['otp'];

  $data = [
    'phone' => $phone,
    'otp' => $otp
  ];

  $token = $_SESSION['token_auth'];
  $url = 'http://104.196.146.173:9000/api/v1/auth/verify-otp';

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
    echo "Erreur lors de la vérification de l'utilisateur.";
  } else {
    // Décoder la réponse et afficher un message de succès
    $response = json_decode($result, true);
    if ($response['message'] == 'utilisateur Profile picture uploaded') {
      echo "Utilisateur vérifié avec succès.";
      $message = urlencode("Utilisateur vérifié avec succès");
      $redirectUrl = $_SERVER['HTTP_REFERER'];
      $separator = (parse_url($redirectUrl, PHP_URL_QUERY) == NULL) ? '?' : '&';
      header("Location: {$redirectUrl}{$separator}success={$message}");
      exit;
    } else {
      echo "Échec de la vérification de l'utilisateur : " . $response['message'];
      header("Location: {$_SERVER['HTTP_REFERER']}");
      exit;
    }
  }
} else {
  echo "Requête invalide.";
}
?>
