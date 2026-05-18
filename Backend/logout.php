<?php

require_once 'auth.php';


logoutUser();


header("Location: ../Frontend/auth.php");
exit;
?>
