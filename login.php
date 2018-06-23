<?php
    @include("objects/page.class.php");
    @include("../objects/page.class.php");

    $page = new Page();
    $page->loadMeta("Log in",false);
    
    $user = new User();
    $MySql = new MySql();
    
    
    $page->loadHeader();
    $user->login();
    $page->showFooter();
?>