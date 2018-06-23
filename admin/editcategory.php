<?php
@include("objects/page.class.php");
@include("../objects/page.class.php");


$page = new Page();
$page->loadMeta("Protocollen website");
$page->loadHeader();

$user = new User();
$MySql = new MySql();
if(isset($_SESSION["loggedin"])){
    $user = $_SESSION["loggedin"];
}
else{
    $user = new User();
}
if($user->getPermission() != null && $user->getPermission() == 2){
    if(!empty($_GET)){
        if(isset($_GET["category"])){
            $category = $MySql->getCategory($_GET["category"]);
            ?>
            <div id="editCategory" class="panel">
                <form method="post" action="">
                    <h1> Bewerk Categorie </h1>
                    <p> <b>Aan het bewerken:</b> <?php echo $category->getName(); ?> </p>
                    <label for="editCategoryInput"> Naam: </label>
                    <input type="text" id="editCategoryInput" name="editCategoryInput" class="form-control" value="<?php echo $category->getName(); ?>">
                    <input type="hidden" name="categoryid" value="<?php echo $_GET["category"]; ?>">
                    <br><input type="submit" name="editCategorySubmit" id="editCategorySubmit" class="btn btn-danger" Value="Opslaan">
                </form>
            </div>
            <?php
        }
    }
    if(isset($_POST["editCategorySubmit"]) || !empty($_POST["editCategoryInput"])){
        $category = new Category();
        $category->setId($_POST["categoryid"]);
        $category->setName($_POST["editCategoryInput"]);
        if($MySql->editCategory($category)){
            ?> <script> window.location.href = "http://<?php echo getenv('HTTP_HOST'); ?>/protocols/"</script><?php
        }
    };
}
else{
    header("Location: http://".getenv('HTTP_HOST')." ");
}

$page->showFooter();
?>