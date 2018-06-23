<?php

class Category{
    private $id;
    private $name;

    public function setId($input){
        $this->id = $input;
    }
    public function getId(){
        return $this->id;
    }

    public function setName($input){
        $this->name = $input;
    }
    public function getName(){
        return $this->name;
    }

    public function getAllCategories(){
        $MySql = new MySql();
        return $MySql->getAllCategories();
    }

}

?>