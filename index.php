<?php
@include("objects/page.class.php");
@include("../objects/page.class.php");


$page = new Page();
$page->loadMeta("Protocollen website",false);

$MySql = new MySql();


$page->loadHeader();


echo "Hier moet nog iets komen maar ik weet niet wat.";
    
$page->showFooter();

?>