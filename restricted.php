<?php
session_start();
if (!array_key_exists("email", $_SESSION)) {
   header("Location: login.php?message=error-login");
}
?>
<html>

<head>

</head>

<body>
   <h1>Page avec accès restreint !!!!</h1>
   <h2>Bonjour <?php echo ($_SESSION["email"]); ?></h2>
   <a href="logout.php">Me déco !</a>
</body>

</html>