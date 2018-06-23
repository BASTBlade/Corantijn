<?php
@include("objects/page.class.php");
@include("../objects/page.class.php");


$page = new Page();
$page->loadMeta("Protocollen website");
$page->loadHeader();

$user = new User();
$MySql = new MySql();

$page->showCategories();

$page->showFooter();
?>