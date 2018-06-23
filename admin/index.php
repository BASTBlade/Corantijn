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
    $page->loadHeader();

    $page->showCreateAccountForm();
    $page->showEditUserForm();

    $page->showCreateCategoryForm();
    $page->showEditCategoryForm();

    $page->showCreateProtocolForm();
    $page->showEditProtocolForm();

    $page->showCreateOpdrachtForm();

    $MySql->getAllOpdrachten();
    $page->showFooter();
}
else{
    header("Location: http://".getenv('HTTP_HOST')." ");
}
?>