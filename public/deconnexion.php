<?php
session_start();

  unset($_SESSION['token_auth']);
  
session_destroy();
header("Location: index.php");
die;
?>