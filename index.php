<?php
    session_start();
    if(isset($_SESSION['user'])) {
        header("Location: page1.php");
        die();
    }
?>


<?php
    function connectToDB(){
        return mysqli_connect('localhost','root','','simple_authentication');
    }
    function closeConnection($connection){
        mysqli_close($connection);
    }

    function insertUser($username, $password){
        $connection = connectToDB();
        $insertStatement = "INSERT INTO users VALUES ('$username','$password')";
        mysqli_query($connection, $insertStatement);
        closeConnection($connection);
    }

    function userExists($username){
        $connection = connectToDB();
        $usersResult = mysqli_query($connection, "SELECT * FROM users WHERE username='$username'");
        if(mysqli_num_rows($usersResult)==0)
            $result = false;
        else
            $result = true;
        closeConnection($connection);
        return $result;
    }

    function getUser($username){
        $connection = connectToDB();
        $res = mysqli_query($connection, "SELECT * FROM users WHERE username='$username'");
        closeConnection($connection);
        if(mysqli_num_rows($res) != 0)
            return mysqli_fetch_assoc($res);
        return null;
    }

?>






<?php

    $pageType = false; //0 for register and 1 for login
    if(isset($_GET['pageType']) && $_GET['pageType']=='login')
        $pageType = true;
    elseif(isset($_GET['pageType']) && $_GET['pageType']=='register')
        $pageType = false;

    if(!$pageType && isset($_POST['register'])){
        $username = $_POST['username'];
        $password = $_POST['pass'];
        if(userExists($username)){
            echo "User Exists";
        }
        else{
            $password = hash('MD5' , $password);
            insertUser($username, $password);
            header("Location: index.php?pageType=login");
        }
    }
    elseif($pageType && isset($_POST['login'])){
        $username = $_POST['username'];
        $user = getUser($username);
        if(sizeof($user)!=0) {
            if ($user['password']==hash('MD5', $_POST['pass'])) {
                $_SESSION['user'] = $username;
                header('Location: page1.php');
            }
        }
    }

?>

<!DOCTYPE html>
<head>
    <title> main page </title>
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
</head>

<body>

<form action="index.php" method="get">
    <input type="submit" name="pageType" value="<?php echo $pageType? 'register' : 'login'; ?>">
</form>

<div class="logreg">
    <form method="post" action="index.php?pageType=<?php echo $pageType? 'login' : 'register'; ?>">
        User name: <input type="text" name="username"> </br>
        Password: <input type="password" name="pass"> </br>
    <?php
    if($pageType)
        echo "<input type=\"submit\" name=\"login\" value=\"Login\"> </br>";
    else
        echo "<input type=\"submit\" name=\"register\" value=\"Register\"> </br>";
    ?>
    </form>
</div>

</body>


