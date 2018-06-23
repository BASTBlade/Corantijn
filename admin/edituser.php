<?php
@include("objects/page.class.php");
@include("../objects/page.class.php");


$page = new Page();
$page->loadMeta("Protocollen website");

$user = new User();
$MySql = new MySql();

$page->loadHeader();

if(isset($_SESSION["loggedin"])){
    $user = $_SESSION["loggedin"];
}
else{
    $user = new User();
}
if($user->getPermission() != null && $user->getPermission() == 2){
    if(!empty($_GET)){
        if(isset($_GET["user"])){
            $editUser = $MySql->getUserFromId($_GET["user"]);
            $groups = $MySql->getAllPermissions();
            ?>  
            <div class="panel">
                <form method="post" action="">
                    <label for="username"> Username: </label>
                    <input type="text" name="username" id="username" class="form-control" value="<?php echo $editUser->getUsername();?>"> <br>
                    <label for="email"> Email: </label>
                    <input type="email" name="email" id="email" class="form-control" value ="<?php echo $editUser->getEmail();?>"><br>
                    <label for="userpassword"> Password <sup> Hou leeg indied geen verandering </sup> </label>
                    <input type="password" name="userpassword" id="userpassword" class="form-control"><br>
                    <label for="permission"> Permissie level </label>
                    <select name="permission" class="form-control">
                        <?php
                            foreach($groups as $row){
                                if($row["id"] == $editUser->getPermission()){
                                    echo "<option value='".$row["id"]."' selected> ".$row["name"]."</option>"; 
                                }else{
                                    echo "<option value='".$row["id"]."'> ".$row["name"]."</option>";
                                }
                            }
                        ?>
                    </select><br>

                    <input type="submit" name="submit" class="btn btn-danger" value="Bewerk Account">
                </form>
            </div>
            <?php
            $page->showFooter();

            if(isset($_POST["submit"])){
                if(!empty($_POST["username"]) && !empty($_POST["email"]) && !empty($_POST["permission"])){
                    $user = new User();
                    $pwchanged = false;
                    if(!empty($_POST["userpassword"]) && isset($_POST["userpassword"])){
                        $user->setPassword($MySql->encrypt($_POST["userpassword"]));
                        $pwchanged = true;
                    }
                    $user->setId($_GET["user"]);
                    $user->setUsername($_POST["username"]);
                    $user->setEmail($_POST["email"]);
                    $user->setPermission($_POST["permission"]);
                    $MySql->editUser($user,$pwchanged);
                    ?> <script> window.location.href = "http://<?php echo getenv('HTTP_HOST'); ?>/admin/" </script><?php
                }
            }
        }
    }
    else{
        header("Location: http://".getenv('HTTP_HOST')." ");
    }
}
else{
    header("Location: http://".getenv('HTTP_HOST')." ");
}

?>