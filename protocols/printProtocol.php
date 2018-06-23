<?php
if(empty($_GET) || empty($_GET["protocol"])){
    header("Location: ../index.php");
}
else{
    @include("objects/page.class.php");
    @include("../objects/page.class.php");


    $page = new Page();

    $page->loadMeta("Protocollen");

    $user = new User();
    $MySql = new MySql();
    $protocol = $MySql->getProtocolFromId($_GET["protocol"]);
    ?>
        <body onload="window.print()">
        <div id="protocolShow" style="border: 1px solid black;">
            <img class="logo" src="http://<?php echo getenv('HTTP_HOST'); ?>/images/logo.png" style="float:left;"><br><br><br><br>
            <h1> <?php echo $protocol->getName(); ?> </h1>
    <?php
    $i = 0;
    foreach(json_decode($protocol->getText(),true) as $key => $text){
        ?> <div class="protocolText <?php echo $i;?>"> <?php echo $text; ?> </div> </body><?php
        $i++;
    }
}
?>