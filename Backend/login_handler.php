<?php


require_once 'auth.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
   
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
   
    if (loginUser($email, $password)) {
        
       
        if (isAdmin()) {
            header("Location: ../admin/index.php");
        } else {
            
            header("Location: ../index.php");
        }
        exit;
        
    } else {
        
        $_SESSION['login_error'] = "Invalid email address or password. Please try again.";
        header("Location: ../auth.php");
        exit;
    }
}


header("Location: ../auth.php");
exit;
?>
