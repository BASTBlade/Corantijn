<?php

class Page{

    public function loadMeta($title){ 
        
        @include("../objects/User.class.php");
        @include("../objects/MySql.class.php");
        @include("../objects/Protocol.class.php");
        @include("../objects/Category.class.php");
        @include("../objects/Status.class.php");
        @include("../objects/Opdracht.class.php");
        @include("objects/User.class.php");
        @include("objects/MySql.class.php");
        @include("objects/Protocol.class.php");
        @include("objects/Category.class.php");
        @include("objects/Status.class.php");
        @include("objects/Opdracht.class.php");
        session_start();
        ?>
            <!DOCTYPE html>
            <html>
                <head>
                    <title> <?php echo $title; ?> </title>
                    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
                    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
                    <script src='https://code.jquery.com/jquery-3.3.1.min.js'></script>
                    <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js' integrity='sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q' crossorigin='anonymous'></script>
                    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
                    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
                    <script>tinymce.init({ selector:'.protocolForm', height : "30vh"});</script>
                    <link rel='stylesheet' type='text/css' href='http://<?php echo getenv('HTTP_HOST'); ?>/style/style.css'>
                    <!--<link rel='stylesheet' type='text/css' href='style/style.css'>-->
            </head>
        <?php
    }

    public function loadHeader(){
        if(isset($_SESSION["loggedin"])){
            $user = $_SESSION["loggedin"];
        }
        else{
            $user = new User();
        }
        ?>
            <nav class="navbar navbar-inverse">
                <div class="container-fluid">
                    <div class="">
                        <a class="navbar-brand" href="http://<?php echo getenv('HTTP_HOST'); ?>"><img class="logo" src="http://<?php echo getenv('HTTP_HOST'); ?>/images/logo.png"></a>
                    </div>
                        <a class="nav-item" href="http://<?php echo getenv('HTTP_HOST'); ?>/protocols">Protocollen</a>
                        <a class="nav-item" href="http://<?php echo getenv('HTTP_HOST'); ?>/opdrachten">opdrachten</a>
                    <?php
                        if($user->getPermission() != null && $user->getPermission() == 2){
                            ?> <a class="nav-item" href="/admin/"> Beheerders Paneel </a><?php
                        }
                    ?>
                    
                    <div class='showUser'>
                        <?php $user->showUser(); ?>
                    </div>
                </div>
            </nav>
        <?php
    }
    public function loadHeaderOpdrachten(){
        if(isset($_SESSION["loggedin"])){
            $user = $_SESSION["loggedin"];
        }
        else{
            $user = new User();
        }
        ?>
            <nav class="navbar navbar-inverse navbar-opdrachten">
                <div class="container-fluid">
                    <div class="">
                        <a class="navbar-brand" href="http://<?php echo getenv('HTTP_HOST'); ?>"><img class="logo" src="http://<?php echo getenv('HTTP_HOST'); ?>/images/logo.png"></a>
                    </div>
                        <a class="nav-item" href="http://<?php echo getenv('HTTP_HOST'); ?>/protocols">Protocollen</a>
                        <a class="nav-item" href="http://<?php echo getenv('HTTP_HOST'); ?>/opdrachten">opdrachten</a>
                    <?php
                        if($user->getPermission() != null && $user->getPermission() == 2){
                            ?> <a class="nav-item" href="/admin/"> Beheerders Paneel </a><?php
                        }
                    ?>
                    
                    <div class='showUser'>
                        <?php $user->showUser(); ?>
                    </div>
                </div>
            </nav>
        <?php
    }

    public function showCreateAccountForm(){
        $MySql = new MySql();
        $data = $MySql->getAllPermissions();
        ?>
        <div id="createAccountForm" class="panel">
            <div class="toggleAccount"><h1> Maak een nieuwe gebruiker </h1></div>
            <div class="createAccount" style="display:none;">
                <form method='post' action=''>
                    <label for="createUsername"> Username: </label>
                    <input type="text" id="createUsername" name="createUsername" class="form-control"> <br>

                    <label for="createPassword"> Password:</label>
                    <input type="password" id="createPassword" name="createPassword" class="form-control"> <br>
                    
                    <label for="createEmail"> Email:</label>
                    <input type="email" id="createEmail" name="createEmail" class="form-control"> <br>

                    <label for="createPermission"> Rank:</label>
                    <select name="createPermission" class="form-control">
                        <?php
                            foreach($data as $row){
                                echo "<option value='".$row["id"]."'> ".$row["name"]."</option>"; 
                            }
                        ?>
                    </select>
                    <br>
                    <input type="submit" name="createAccountSubmit" id="createAccountSubmit" value="Opslaan" class="btn btn-danger">
                </form>
            </div>
        </div>
                        
        <script>
            $(".toggleAccount").click(function(){
                $(".createAccount").toggle();
            });
        </script>
        <?php

        if(isset($_POST["createAccountSubmit"])){
            if(isset($_POST["createUsername"])&& !empty($_POST["createUsername"])){
                if(isset($_POST["createPassword"]) && !empty($_POST["createPassword"])){
                    if(isset($_POST["createEmail"]) && !empty($_POST["createEmail"])){
                        $user = new User();
                        $user->setUsername($_POST["createUsername"]);
                        $user->setPassword($MySql->encrypt($_POST["createPassword"]));
                        $user->setEmail($_POST["createEmail"]);
                        $user->setPermission($_POST["createPermission"]);
                        $MySql->createAccount($user);
                    }
                }
            }
        }
        
    }
    public function showCreateCategoryForm(){
        $MySql = new MySql();
        ?>
            <div id="createCategoryForm" class="panel">
                <div class="toggleCategory"><h1> Maak een nieuwe Categorie </h1></div>
                <div class="createCategoryForm" style="display:none;">    
                    <form method="post" action="">
                        <label for="createCategory"> Category: </label>
                        <input type="text" id="createCategory" name="createCategory" class="form-control"> <br>
                        <input type="submit" name="createCategorySubmit" id="createCategorySubmit" value="Opslaan" class="btn btn-danger">
                    </form>
                </div>
            </div>

            <script>
                $(".toggleCategory").click(function(){
                    $(".createCategoryForm").toggle();
                });
            </script>
        <?php

        if(isset($_POST["createCategorySubmit"])){
            if(isset($_POST["createCategory"]) && !empty($_POST["createCategory"])){
                $MySql->createCategory($_POST["createCategory"]);
            }
        }
    }

    public function showEditCategoryForm(){
        $MySql = new MySql();
        $data = $MySql->getAllCategories();
        ?>
            <div id="editCategory" class="panel">
                <div class="toggleEditCategory" > <h1> Bewerk Categorie </h1> </div>
                    <div class="editCategoryForm" style="display:none">
                        <form method="post" action="">
                        <label for="editCategorySelect"> Categorie: </label>
                        <select name="editCategorySelect" id="editCategorySelect" class="form-control">
                            <?php
                                foreach($data as $row){
                                    echo "<option value='".$row["id"]."'> ".$row["name"]."</option>"; 
                                }
                            ?>
                        </select><br>
                        <input type="button" onclick="editCategory()" name="editCategorySave" id="editCategorySave" value="Bewerk" class="btn btn-danger">
                    </form>
                </div>
            </div>
            <script>
                $(".toggleEditCategory").click(function(){
                    $(".editCategoryForm").toggle();
                });
                function editCategory(){
                    window.location.href = "http://<?php echo getenv('HTTP_HOST'); ?>/admin/editcategory.php?category="+$("#editCategorySelect").val();
                }
                
            </script>
        <?php
    }
    public function showCreateProtocolForm(){
        $MySql = new MySql();
        $data = $MySql->getAllCategories();
        ?>
            <div id="createProtocol" class="panel">
                <div class="toggleProtocol"><h1> Maak een nieuwe protocol </h1> </div>
                    <div class="createProtocolForm" style="display:none">
                    <form method="post" action="">
                        <input type="hidden" name="protocolText" value="" id="protocolText">
                        <label for="createProtocolTitle"> Titel: </label>
                        <input type="text" class="form-control" name="createProtocolTitle" id="createProtocolTitle"> <br>
                        <label for="createProtocolCategory"> Category: </label>
                        <select name="createProtocolCategory" class="form-control">
                            <?php
                                foreach($data as $row){
                                    echo "<option value='".$row["id"]."'> ".$row["name"]."</option>"; 
                                }
                            ?>
                        </select><br>
                        <div id="inputs">
                            <div class="protocolForm" >
                            </div>
                            <input type="button" id="0" onclick="deleteInput(this)" value="Verwijder stap" class="btn btn-primary">
                        </div>
                        <br>
                        <input type="button" onclick="showNewForm()" name="createProtocolExtra" id="createProtocolExtra" value="Nieuwe stap" class="btn">
                        <input type="submit" name="createProtocolSave" id="createProtocolSave" value="Opslaan" class="btn btn-danger">
                    </form>
                </div>
            </div>

            <script>
                $(".toggleProtocol").click(function(){
                    $(".createProtocolForm").toggle();
                });

            $("#createProtocolSave").click(function(e){
                var contents = {};
                for (i=0; i < tinyMCE.editors.length; i++){
                    var text = tinyMCE.editors[i].getContent();
                    contents[i] = text.replace(/"/g,'');
                    text = contents[i];
                    contents[i] = text.replace(/(?:\r\n|\r|\n)/g, ' ');
                    text = contents[i];
                    contents[i] = text.replace(/'/g,'&#39;');
                }
                $("#protocolText").val(JSON.stringify(contents));
                //Cookies.set("protocolText", contents);
            });

            var current = 1;
            function showNewForm(){
                $("#inputs").append("<div class="+current+"></div><input type='button' id='"+current+"' onclick='deleteInput(this)' value='Verwijder stap' class='btn btn-primary'>");

                tinyMCE.init({
                    selector: "."+current,
                    height : "30vh"
                });
                current++;
            }


            function deleteInput(e){
                var id = $(e).attr("id");
                tinymce.remove('.'+id);
                $("."+id).remove();
                $(e).remove();
            }
        </script>
        <?php

        
        if(isset($_POST["createProtocolSave"])){
            $content = $_POST["protocolText"];
            $categoryId = $_POST["createProtocolCategory"];
            $title = $_POST["createProtocolTitle"];
            $creator = $_SESSION["loggedin"];
            $data = array('title' => $title,'categoryid' => $categoryId,'content' => $content,'creator'=>$creator->getId());
            $json = json_encode($data);
            $_SESSION["protocol"] = $json;
            ?><script>//Cookies.set("protocolText", <?php echo $json; ?>);
                 window.location.href = "http://<?php echo getenv('HTTP_HOST'); ?>/protocols/newprotocol.php?newprotocol=true" </script> <?php
        }
    }

    public function showEditProtocol(){
        $MySql = new MySql();
        $data = $MySql->getAllCategories();
        if(!empty($_GET) || !empty($_POST) ){
            if(!empty($_GET["protocol"])){
                $protocol = $MySql->getProtocolFromId($_GET["protocol"]);
                $_SESSION["protocolId"] = $_GET["protocol"];
                ?>
                <div class="panel">
                    <form method="post" action="">
                        <input type="hidden" name="protocolText" value="" id="protocolText">
                        <label for="createProtocolTitle"> Titel: </label>
                        <input type="text" class="form-control" name="createProtocolTitle" id="createProtocolTitle" value="<?php echo $protocol->getName();?>"> <br>
                        <label for="createProtocolCategory"> Category: </label>
                        <select name="createProtocolCategory" class="form-control">
                            <?php
                                foreach($data as $row){
                                    if($row["id"] == $protocol->getCategory()){
                                        echo "<option value='".$row["id"]."' selected> ".$row["name"]."</option>"; 
                                    }
                                    else{
                                        echo "<option value='".$row["id"]."'> ".$row["name"]."</option>"; 
                                    }
                                }
                            ?>
                        </select><br>
                            <div id="forms">
                                <?php  
                                $i = 0;
                                foreach(json_decode($protocol->getText(),true) as $key => $text){
                                    ?> 
                                        <div class="<?php echo $i;?>"><?php echo $text;?>
                                        </div>
                                        <input type="button" id="<?php echo $i;?>" onclick="deleteInput(this)" value="Verwijder stap" class="btn btn-primary">
                                    <?php
                                    $i++;
                                }
                                ?>

                                <script>
                                    for(var i = 0; i < <?php echo $i;?>; i++){
                                        tinyMCE.init({
                                            selector: "."+i
                                        });
                                    }
                                </script>
                            </div>
                        <br>
                        <input type="button" onclick="showNewForm()" name="createProtocolExtra" id="createProtocolExtra" value="Nieuwe stap" class="btn"><br><br>
                        <input type="submit" name="removeProtocol" id="removeProtocol" value="Verwijder Protocol" class="btn btn-warning">
                        <input type="submit" name="saveProtocol" id="saveProtocol" value="Opslaan" class="btn btn-danger">
                    </form>

                    <script>
                        var count = <?php echo $i ?>;
                        function showNewForm(){
                            $("#forms").append("<div class="+count+"></div><input type='button' id='"+count+"' onclick='deleteInput(this)' value='Verwijder stap' class='btn btn-primary'>");

                            tinyMCE.init({
                                selector: "."+count
                            });
                            count = count + 1;
                        }
                        $("#saveProtocol").click(function(e){
                            var contents = {};
                            for (i=0; i < tinyMCE.editors.length; i++){
                                var text = tinyMCE.editors[i].getContent();
                                contents[i] = text.replace(/"/g,'');
                                text = contents[i];
                                contents[i] = text.replace(/(?:\r\n|\r|\n)/g, ' ');
                                text = contents[i];
                                contents[i] = text.replace(/'/g,'&#39;');
                            }
                            $("#protocolText").val(JSON.stringify(contents));
                        });
                        
                        function deleteInput(e){
                            var id = $(e).attr("id");
                            tinymce.remove('.'+id);
                            $("."+id).remove();
                            $(e).remove();
                        }
                    </script>
                </div>

                <?php


            }
            if(!empty($_POST["saveProtocol"])){
                $protocol = new Protocol();
                $protocol->setId($_SESSION["protocolId"]);
                $protocol->setText($_POST["protocolText"]);
                $protocol->setCategory($_POST["createProtocolCategory"]);
                $protocol->setName($_POST["createProtocolTitle"]);
                $MySql->editProtocol($protocol);
                ?><script>window.location.href = "http://<?php echo getenv('HTTP_HOST'); ?>/protocols/showprotocol.php?protocol=<?php echo $protocol->getId();?>" </script> <?php
            }
            if(!empty($_POST["removeProtocol"])){
                if($MySql->removeProtocol($_GET["protocol"])){
                    ?><script>window.location.href = "http://<?php echo getenv('HTTP_HOST'); ?>/protocols/" </script><?php
                }
            }
        }

    }

    public function showEditUserForm(){
        $MySql = new MySql();
        $data = $MySql->getAllUsers();
        ?>
            <div id="editUser" class="panel">
                <div class="toggleEditUser"> <h1> Bewerk Gebruiker </h1> </div>
                <div class="editUserForm" style="display:none">
                    <select id="editUserSelect" class="form-control">
                        <?php
                            foreach($data as $row){
                                echo "<option value='".$row->getId()."'>".$row->getUsername()."</option>";
                            }
                        ?>
                    </select>
                    <input type="submit" onclick="editUser()" name="editProtocolButton" id="editProtocolButton" value="Bewerk" class="btn btn-danger">
                </div>
            </div>
            <script>
                $(".toggleEditUser").click(function(){
                    $(".editUserForm").toggle();
                });

                function editUser(){
                    var id = $("#editUserSelect").val();
                    window.location.href = "http://<?php echo getenv('HTTP_HOST'); ?>/admin/edituser.php?user="+id;
                }
            </script>
        <?php
    }


    public function showCategories(){
        $MySql = new MySql();
        $CategoryObject = new Category();
        $CategoryArray = $CategoryObject->getAllCategories();
        ?>  <div id="categories"><?php
        foreach($CategoryArray as $category){
            ?> <div class="categorybox <?php echo $category["id"];?> category"> <span class="categorytext"><?php echo $category["name"];?></span>

            <?php
                if($MySql->getAllProtocolsFromCategory($category["id"]) != null){
                    foreach($MySql->getAllProtocolsFromCategory($category["id"]) as $protocol){
                        ?> <a class="<?php echo $category["id"]?> protocol" href="showprotocol.php?protocol=<?php echo $protocol["id"];  ?>" style='display:none;'><?php echo $protocol["name"];?></a><?php
                    }
                }   
            ?>
            </div> <br> <?php
        }
        ?></div>
        
        <script>

            $(".categorybox").click(function(){
                var classes = $(this).attr("class").split(' ');
                $("."+classes[1]).each(function(i){
                    if($(this).hasClass("protocol")){
                        $(this).toggle(400);
                    }
                });
            });

        </script>
        
        <?php
    }

    public function showEditProtocolForm(){
        $MySql = new MySql();
        $data = $MySql->getAllProtocols();
        ?>
            <div id="editProtocol" class="panel">
                <div class="toggleEditProtocol"><h1>Bewerk Protocol</h1></div>
                <div class="editProtocolForm" style="display:none">
                    <select id="editProtocolSelect" class="form-control">
                        <?php
                            foreach($data as $row){
                                echo "<option value='".$row["id"]."'> ".$row["name"]."</option>"; 
                            }
                        ?>
                    </select>
                    <input type="submit" onclick="editProtocol()" name="editProtocolButton" id="editProtocolButton" value="Bewerk" class="btn btn-danger">
                </div>
            </div>
            <script>
                $(".toggleEditProtocol").click(function(){
                    $(".editProtocolForm").toggle();
                });

                function editProtocol(){
                    var id = $("#editProtocolSelect").val();
                    window.location.href = "http://<?php echo getenv('HTTP_HOST'); ?>/protocols/editprotocol.php?protocol="+id;
                }
            </script>
        <?php
    }


    public function showCreateOpdrachtForm(){
        $MySql = new MySql();
        $categories = $MySql->getAllCategories();
        $users = $MySql->getAllUsers();
        $statuses = $MySql->getAllStatuses();
        ?>
            <div id="createOpdracht" class="panel">
                <div class="toggleCreateOpdracht"><h1>Creeer Opdracht</h1></div>
                <div class="createOpdrachtForm" style="display:none">
                    <form method="post" action="">
                        <label for="createOpdrachtName"> Naam: </label>
                        <input type="text" class="form-control" name="createOpdrachtName" id="createOpdrachtName"> <br>
                        <label for="createOpdrachtBeschrijving"> Beschrijving:  </label>
                        <input type="text" class="form-control" name="createOpdrachtBeschrijving" id="createOpdrachtBeschrijving"> <br>
                        <label for="createOpdrachtCategory"> Category: </label>
                        <select name="createOpdrachtCategory" class="form-control">
                            <?php
                                foreach($categories as $row){
                                    echo "<option value='".$row["id"]."'> ".$row["name"]."</option>"; 
                                }
                            ?>
                        </select><br>
                        <label for="createOpdrachtStatus"> Status: </label>
                        <select name="createOpdrachtStatus" class="form-control">
                            <?php
                                foreach($statuses as $status){
                                    echo "<option value='".$status->getId()."'> ".$status->getName()."</option>"; 
                                }
                            ?>
                        </select><br>
                        <label for="createOpdrachtAssignedTo"> Deelnemer (Laat leeg indien geen deelnemer):</label>
                        <select name="createOpdrachtAssignedTo" class="form-control">
                        <option value=" "> </option>
                            <?php
                                foreach($users as $user){
                                    echo "<option value='".$user->getId()."'> ".$user->getUsername()."</option>"; 
                                }
                            ?>
                        </select><br>
                        <input type="submit" name="createOpdrachtSubmit" id="createOpdrachtSubmit" class="btn btn-danger">
                    </form>
                </div>
            </div>

            <script>
                $(".toggleCreateOpdracht").click(function(){
                    $(".createOpdrachtForm").toggle();
                });
            </script>

        <?php

        if(isset($_POST["createOpdrachtSubmit"])){
            if(isset($_POST["createOpdrachtName"]) && isset($_POST["createOpdrachtBeschrijving"])){
                $opdracht = new Opdracht();
                $opdracht->setName($_POST["createOpdrachtName"]);
                $opdracht->setBeschrijving($_POST["createOpdrachtBeschrijving"]);
                $opdracht->setCategory($_POST["createOpdrachtCategory"]);
                $opdracht->setStatus($_POST["createOpdrachtStatus"]);
                $opdracht->setAssignedTo($_POST["createOpdrachtAssignedTo"]);
                $opdracht->setCreator($user->getId());
                $MySql->createOpdracht($opdracht);
            }
        }
    }


    public function showFooter(){
        ?>
            <div class="footer-copyright py-3 text-center">
                © 2018 Copyright: Eijk Web Development
            </div>
        <?php
    }
}
?>