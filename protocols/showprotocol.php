<?php
if(empty($_GET) || empty($_GET["protocol"])){
    header("Location: ../index.php");
}
else{
    @include("objects/page.class.php");
    @include("../objects/page.class.php");


    $page = new Page();
    $page->loadMeta("Protocollen");
    $page->loadHeader();

    $user = new User();
    $MySql = new MySql();

    $protocol = $MySql->getProtocolFromId($_GET["protocol"]);
    ?>
        <div id="protocolShow" style="border: 1px solid black;">
            <h1> <?php echo $protocol->getName(); ?> </h1>
    <?php
    $i = 0;
    foreach(json_decode($protocol->getText(),true) as $key => $text){
        ?> <br><div class="protocolText <?php echo $i;?>" style="display:none;"> <?php echo $text; ?> </div> <br><?php
        $i++;
    }

    ?>
        <input type="button" name="next" id="next" value="Volgende Stap" class="btn btn-primary">
        <input type="button" name="print" id="print" value="Print Protocol" class="btn btn-primary">
        <?php 
        if(isset($_SESSION["loggedin"])){
            $user = $_SESSION["loggedin"];
        }
        else{
            $user = new User();
        }
        if($user->getPermission() != null && $user->getPermission() == 2){
            ?>
            <input type="button" name="edit" id="edit" value="Bewerk Protocol" class="btn btn-danger">
        <?php }?>
        </div>

        <script>
            $(".0").show();
            var i = 1;
            $("#next").click(function(){
                $("."+i).show();
                i++;
                if($("."+i).length){

                }
                else{
                    $("#next").hide();

                }
            });

            $("#edit").click(function(){
                window.location.href = "http://<?php echo getenv('HTTP_HOST'); ?>/protocols/editprotocol.php?protocol=<?php echo $protocol->getId(); ?>";
            });
            $("#print").click(function(){
                window.location.href = "http://<?php echo getenv('HTTP_HOST'); ?>/protocols/printprotocol.php?protocol=<?php echo $protocol->getId(); ?>";
            });
        </script>
    <?php
}

$page->showFooter();
?>