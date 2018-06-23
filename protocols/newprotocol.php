<?php
@include("objects/page.class.php");
@include("../objects/page.class.php");


$page = new Page();
$page->loadMeta("Nieuw Protocol");
$page->loadHeader();

$user = new User();
$MySql = new MySql();
if(!empty($_GET)){
    if(isset($_GET["newprotocol"])){
        if($_GET["newprotocol"]){
            $data = json_decode($_SESSION["protocol"], true); 
            $protocol = new Protocol();
            $protocol->setName($data["title"]);
            $protocol->setCategory($data["categoryid"]);
            $protocol->setText($data["content"]);
            $protocol->setCreator($data["creator"]);
            $id = $MySql->createNewProtocol($protocol);
            ?> <script> window.location.href = "http://<?php echo getenv('HTTP_HOST'); ?>/protocols/showprotocol.php?protocol=<?php echo $id;?>" </script><?php
        }
    }
}

?>