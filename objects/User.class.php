<?php
class User {
  private $id;
  public function setId($input){
    $this->id = $input;
  }
  public function getId(){
    return $this->id;
  }

  private $username;
  public function setUsername($input) {
    $this->username = $input;
  }
  public function getUsername() {
    return $this->username;
  }

  private $email;
  public function setEmail($input) {
    $this->email = $input;
  }
  public function getEmail() {
    return $this->email;
  }

  private $password;
  public function setPassword($input){
    $this->password = $input;
  }
  public function getPassword(){
      return $this->password;
  }

  private $permission;
  public function setPermission($input){
    $this->permission = $input;
  }
  public function getPermission(){
    return $this->permission;
  }

  public function login(){
    if(!isset($_SESSION["loggedin"]) || empty($_SESSION["loggedin"])){
      ?>
        <div id='loginBlock'>
          <form method='post' action=''>
            <label for='username' > Username:
            <input type='text' name='username' id='username' class='form-control'> <br>
            <label for='password'> Password:
            <input type='password' name='password' id='password' class='form-control'> <br> <br>
            <input type='submit' name='submit' id='submit' value='Log in' class='btn'>
          </form>
        
      <?php
      if(isset($_POST["submit"])){
        if(isset($_POST["username"]) && !empty($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["password"])){
          $user = new User();
          $user->setUsername($_POST["username"]);
          $user->setPassword($_POST["password"]);
          $mysql = new MySql();
          if($loggedinUser = $mysql->checkLogin($user)){
            $_SESSION["loggedin"] = $loggedinUser;
            header("Location: index.php");
          }else{
            ?><p class='red'> Verkeerde informatie gegeven. </p> 
            <?php
          }
        } ?> </div> <?php
      }
    }
    else{
      $user = $_SESSION["loggedin"];
      ?>U bent al ingelogd als: <?php echo $user->getUsername(); 
    }
  }
  public function showLogin(){
    if(!isset($_SESSION["loggedin"]) || empty($_SESSION["loggedin"])){
      ?>
        <form method='post' action=''>
          <input type='submit' name='login' id='login' value='Log in' class='btn'>
        </form>
      <?php
    }
    if(isset($_POST["login"])){
      header("Location: http://".getenv('HTTP_HOST')."/login.php");
    }
  }
  public function showLogout(){
    if(isset($_SESSION["loggedin"]) || !empty($_SESSION["loggedin"])){
      $user = $_SESSION["loggedin"];
      ?>
          <form method='post' action=''>
            Ingelogd als: <?php echo $user->getUsername() ?>
            <input type='submit' name='logout' id='logout' value='Log uit' class='btn btn-danger navbar-btn'>
          </form>
      <?php
    }
    if(isset($_POST["logout"])){
      $_SESSION["loggedin"] = null;
      header("Location: http://".getenv('HTTP_HOST')."/index.php");
    }
  }
  public function showUser(){
    $this->showLogin();
    $this->showLogout();
  }




  
}
?>