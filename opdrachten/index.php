<?php

@include("objects/page.class.php");
@include("../objects/page.class.php");


$page = new Page();
$page->loadMeta("Protocollen website");

$user = new User();
$MySql = new MySql();

if(isset($_SESSION["loggedin"])){
    $user = $_SESSION["loggedin"];
}
else{
    $user = new User();
}
if($user->getPermission() != null && $user->getPermission() == 2){
    $page->loadHeaderOpdrachten();


    $page->showFooter();
}
else{
    header("Location: http://".getenv('HTTP_HOST')." ");
}

?>