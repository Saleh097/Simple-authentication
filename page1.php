<?php
session_start();

if(isset($_POST['logout'])){
    unset($_SESSION['user']);
    header('Location: index.php');
}


?>

<!DOCTYPE html>
<head>
    <title>First Page</title>
</head>

<body>
<form action="page1.php" method="post">
    <input type="submit" name="logout" value="logout">
</form>

<h1> <?php echo "logged in as ".$_SESSION['user']; ?></h1>
</body>
