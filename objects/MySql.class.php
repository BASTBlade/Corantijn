<?php

class MySql{
    private $dbHost = "localhost";
    private $dbUser = "root";
    private $dbPw = "";
    private $dbName = "corantijn";
    
    // Table creation queries.
    private $createDBQueries = 
            "CREATE DATABASE corantijn";

    private $userQueries = 
            "CREATE TABLE user(
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(64) NOT NULL,
                password VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                permission INT(6) NOT NULL,
                reg_date TIMESTAMP
            )";
    private $protocolQueries = 
            "CREATE TABLE protocol(
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                text TEXT(65535) NOT NULL,
                category INT(6) NOT NULL
            )";
    private $categoryQueries =
            "CREATE TABLE category(
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL
            )";
    private $permissionQueries =
            "CREATE TABLE permission(
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                level INT(6) NOT NULL
            )";
    private $protocol_userQueries =
            "CREATE TABLE protocol_user(
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                userid INT(6) NOT NULL,
                protocolid INT(6) NOT NULL
            )";
    // Keys generation queries.
    private $userkeysQueries = 
            "ALTER TABLE user
                ADD CONSTRAINT FK_UserPermission
                FOREIGN KEY (permission) REFERENCES permission(id);
            ";
    private $protocolkeysQueries =
            "ALTER TABLE protocol
                ADD CONSTRAINT FK_ProtocolCategory
                FOREIGN KEY (category) REFERENCES category(id);
            ";
    private $protocoluserkeysQueries =
            "ALTER TABLE protocol_user
                ADD CONSTRAINT FK_ProtocolUser
                FOREIGN KEY (userid) REFERENCES user(id);
                ADD CONSTRAINT FK_ProtocolProtocol
                FOREIGN KEY (protocolid) REFERENCES protocol(id);
            ";
    private $createPermissionGroupQuery = "INSERT INTO permission(name,level) VALUES('Deelnemer',1), ('Beheerder',2);
            ";
    // Assignment Objects and keys
    private $createOpdrachtQuery = 
            "CREATE TABLE opdracht(
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                beschrijving VARCHAR(255) NOT NULL,
                category INT(6) NOT NULL,
                status INT(6) NOT NULL,
                creator INT(6) NOT NULL,
                AssignedTo INT(6) NOT NULL,
                date TIMESTAMP
            )";
    private $createStatusQuery =
            "CREATE TABLE status(
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                color VARCHAR(7) NOT NULL
            )";

    private $opdrachtKeysQuery =
            "ALTER TABLE opdracht
                ADD CONSTRAINT FK_OpdrachtCategory
                FOREIGN KEY (category) REFERENCES category(id);
                ADD CONSTRAINT FK_OpdrachtStatus
                FOREIGN KEY (status) REFERENCES status(id);
                ADD CONSTRAINT FK_OpdrachtCreator
                FOREIGN KEY (creator) REFERENCES user(id);
                ADD CONSTRAINT FK_OpdrachtAssignedTo
                FOREIGN KEY (assignedto) REFERENCES user(id);
            ";
    
    // Creates a save connection to the database.
    public function createConnection(){
        $con = mysqli_connect($this->dbHost,$this->dbUser,$this->dbPw,$this->dbName);
        if (mysqli_connect_errno()){
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            return null;
            }
        else{
            return $con;
        }
    }

    // Initializes the database for first time use.
    public function initializeDatabase(){
        $this->performQuery($this->createDBQueries);
        $this->performQuery($this->userQueries);
        $this->performQuery($this->protocolQueries);
        $this->performQuery($this->categoryQueries);
        $this->performQuery($this->permissionQueries);
        $this->performQuery($this->protocol_userQueries);

        $this->performQuery($this->userkeysQueries);
        $this->performQuery($this->protocolkeysQueries);
        $this->performQuery($this->protocoluserkeysQueries);
        $this->performQuery($this->createPermissionGroupQuery);
        $this->performQuery($this->createOpdrachtQuery);
        $this->performQuery($this->createStatusQuery);
        $this->performQuery($this->opdrachtKeysQuery);
    }

    // Creates the account with given $user object.
    public function createAccount($user){
        $query = "INSERT INTO user (username,password,email,permission) VALUES ('"
                    . $user->getUsername() . "','"
                    . $user->getPassword() . "','"
                    . $user->getEmail() . "','"
                    . $user->getPermission() . "'
                    )";
        $this->performQuery($query);
    }

    public function createCategory($text){
        $query = "INSERT INTO category(name) VALUES ('".$text."')";
        $this->performQuery($query);
    }


    public function encrypt($input){
        
        return password_hash($input,PASSWORD_DEFAULT);
    }

    // Determines wether the input is correct.
    public function checkLogin($user){
        $con = $this->createConnection();
        $username = mysqli_real_escape_string($con, $user->getUsername());
        $con->close();
        $query = "SELECT * FROM user WHERE username = '" . $username . "'";
        $data = $this->selectQuery($query);
        if($data != null){
            foreach($data as $row){
                if(password_verify($user->getPassword(),$row["password"])){
                    $user->setUsername($row["username"]);
                    $user->setEmail($row["email"]);
                    $user->setId($row["id"]);
                    $user->setPermission($row["permission"]);
                    $user->setPassword("");
                    return $user;
                }
                else{
                    return null;
                }
            }
        }
    }

    public function getAllPermissions(){
        $query = "SELECT * FROM permission";
        return $this->selectQuery($query);
    }
    public function getPermission($id){
        $query = "SELECT * FROM permission where id = ".$id;
        return $this->selectQuery($query);
    }

    public function getAllCategories(){
        $query = "SELECT * FROM category";
        return $this->selectQuery($query);
    }
    public function getCategory($id){
        $query = "SELECT * FROM category where id =".$id;
        $data = $this->selectQuery($query);
        $category = new category();
        $category->setId($data[0]["id"]);
        $category->setName($data[0]["name"]);
        return $category;
    }
    public function editCategory($category){
        $query = "UPDATE category
         SET name ='".$category->getName()."'
         WHERE id = '".$category->getId()."'
        ;";
        return $this->performQuery($query);
    }

    public function getAllProtocols(){
        $query = "SELECT * FROM protocol";
        $protocols = array();
        $i = 0;
        foreach($this->selectQuery($query) as $protocol){
            $protocols[$i] = $protocol;
            $i++;
        }
        return $protocols;
    }
    public function getProtocolFromId($id){
        $query = "SELECT * FROM protocol where id = ".$id;
        $protocol = new Protocol();
        $data = $this->selectQuery($query);
        foreach($data as $value){
            $protocol->setId($value["id"]);
            $protocol->setName($value["name"]);
            $protocol->setText($value["text"]);
            $protocol->setCategory($value["category"]);
        }
        return $protocol;
    }
    public function getProtocolFromName($name){
        $query = "SELECT * FROM protocol where name = ".$name;
        return $this->selectQuery($query);
    }

    public function editProtocol($protocol){
        $query = "UPDATE protocol 
            SET name = '".$protocol->getName()."',
                text = '".$protocol->getText()."',
                category = '".$protocol->getCategory()."'
                WHERE id=".$protocol->getId().";";
        $this->performQuery($query);
    }

    public function getAllProtocolsFromCategory($id){
        $query = "SELECT protocol.id,protocol.name,protocol.text FROM protocol INNER JOIN category ON protocol.category = category.id WHERE protocol.category = ".$id;
        return $this->selectQuery($query);
    }

    public function getAllUsers(){
        $query = "SELECT * FROM user";
        $users = array();
        $data = $this->selectQuery($query);
        foreach($data as $row){
            $user = new User();
            $user->setId($row["id"]);
            $user->setUsername($row["username"]);
            $user->setEmail($row["email"]);
            $user->setPermission($row["permission"]);
            array_push($users,$user);
        }
        return $users;
    }
    public function getUserFromId($id){
        $query = "SELECT * FROM user WHERE id=".$id.";";
        $data = $this->selectQuery($query);
        $user = new User();
        foreach($data as $row){
            $user->setId($row["id"]);
            $user->setUsername($row["username"]);
            $user->setEmail($row["email"]);
            $user->setPermission($row["permission"]);
        }
        return $user;
    }

    public function editUser($user,$pw = false){
        $query = "";
        if($pw){
            $query = "UPDATE user
            SET username='".$user->getUsername()."',
            email = '".$user->getEmail()."',
            password = '".$user->getPassword()."',
            permission = '".$user->getPermission()."'
            WHERE id= ".$user->getId().";";
        }
        else{
            $query = "UPDATE user
            SET username='".$user->getUsername()."',
            email = '".$user->getEmail()."',
            permission = '".$user->getPermission()."'
            WHERE id= ".$user->getId().";";
        }
        $conn = $this->createConnection();
        $conn->query($query);
        $conn->close();
    }

    // Queries has to be in string type.
    private function performQuery($query){
        $conn = $this->createConnection();
        $result = $conn->query($query);
        $conn->close();
        return $result;
    }
    private function selectQuery($query){
        $conn = $this->createConnection();
        $result = $conn->query($query);
        $temparray = array();

        if ($result->num_rows > 0) {
            $i = 0;
            while($row = $result->fetch_assoc()){
                $temparray[$i] = $row;
                $i++;
            }
            return $temparray;
        }
        else{
            return null;
        }
        $conn->close();
    }

    
    public function createNewProtocol($protocol){
        $conn = $this->createConnection();
        $query = "INSERT INTO protocol (name,text,category) VALUES ('"
            .$protocol->getName()."','"
            .$protocol->getText()."','"
            .$protocol->getCategory()
        ."')";
        $conn->query($query);
        $lastid = $conn->insert_id;
        $conn->close();
        $user = $_SESSION["loggedin"];
        $query2 = "INSERT INTO protocol_user(userid,protocolid) VALUES ('".$user->getId()."','".$lastid."')";
        $this->performQuery($query2);
        return $lastid;
    }

    public function removeProtocol($id){
        $query = "DELETE FROM protocol WHERE id='".$id."';";
        $bool = $this->performQuery($query);
        if($bool){
            $query2 = "DELETE FROM protocol_user WHERE protocolid='".$id."';";
            $this->performQuery($query2);
        }
        return $bool;
    }


    public function createOpdracht($opdracht){
        $query = "INSERT INTO opdracht (name,beschrijving,category,status,creator,assignedto) VALUES ('"
                .$opdracht->getName()."','"
                .$opdracht->getBeschrijving()."','"
                .$opdracht->getCategory()."','"
                .$opdracht->getStatus()."','"
                .$opdracht->getCreator()."','"
                .$opdracht->getAssignedTo()."
            ');";
        return $this->performQuery($query);
    }
    public function editOpdracht($opdracht){
        $query = " UPDATE opdracht
        SET name='".$opdracht->getName()."',
        beschrijving='".$opdracht->getBeschrijving()."',
        category='".$opdracht->getCategory()."',
        status='".$opdracht->getStatus()."'
        WHERE id=".$opdracht->getId().";";
        return $this->performQuery($query);
    }
    public function removeOpdracht($opdracht){
        $query = "DELETE FROM opdracht WHERE id=".$opdracht->getId().";";
        return $this->performQuery($query);
    }
    public function getAllOpdrachten(){
        $query = "SELECT * FROM opdracht";
        $data = $this->selectQuery($query);
        $opdrachten = array();
        foreach($data as $value){
            $opdracht = new Opdracht();
            $opdracht->setId($value["id"]);
            $opdracht->setName($value["name"]);
            $opdracht->setBeschrijving($value["beschrijving"]);
            $opdracht->setCategory($value["category"]);
            $opdracht->setStatus($value["status"]);
            $opdracht->setCreator($value["creator"]);
            $opdracht->setAssignedTo($value["AssignedTo"]);
            $opdracht->setDate($value["date"]);
            array_push($opdrachten,$opdracht);
        }
        return $opdrachten;
    }
    public function getOpdracht($id){
        
    }

    public function createStatus($status){
        $query = "INSERT INTO status (name,color) VALUES ('"
                . $status->getName()."','"
                . $status->getColor()."
        ');";
        return $this->performQuery($query);
    }
    public function editStatus($status){

    }
    public function removeStatus($status){

    }
    public function getAllStatuses(){
        $query = "SELECT * FROM status";
        $data = $this->selectQuery($query);
        $statuses = array();
        foreach($data as $value){
            $status = new Status();
            $status->setId($value["id"]);
            $status->setName($value["name"]);
            $status->setColor($value["color"]);
            array_push($statuses,$status);
        }
        return $statuses;
    }
    public function getStatus($id){

    }
}
?>