<?php

include("../objects/page.class.php");

$page = new Page();
$page->loadMeta("Install",true);

$MySql = new MySql();
$MySql->initializeDatabase();

if($MySql->getAllUsers() != null){
    header("Location: ../index.php");
}

?>
<p> Voer de details voor het beheerders account in. </p>
<form method="POST" action="">
    <label for="username"> Username:
    <input type="text" id="username" name="username" length="64" class='form-control'> <br>
    
    <label for="email"> Email:
    <input type="email" id="email" name="email" class='form-control'> <br>

    <label for="password"> Password:
    <input type="password" id="password" name="password" class='form-control'> <br>

    <label for="authpassword"> Retype Password:
    <input type="password" id="authpassword" name="authpassword" class='form-control'> <br>

    <input type="submit" name="submit" id="submit" value="Submit" class='btn'>
</form>

<?php
    if(isset($_POST["submit"])){
        if(isset($_POST["username"]) && !empty($_POST["username"])){
            if(isset($_POST["email"]) && !empty($_POST["email"])){
                if(isset($_POST["password"]) && !empty($_POST["password"])){
                    if($_POST["password"] == $_POST["authpassword"]){
                        $user = new User();
                        $user->setUsername($_POST["username"]);
                        $user->setPassword($MySql->encrypt($_POST['password']));
                        $user->setEmail($_POST["email"]);
                        $user->setPermission(2);
                        $MySql->createAccount($user);
                    }
                    else{
                        echo "passwords zijn niet het zelfde.";
                    }
                }
            }
        }
    }
?>