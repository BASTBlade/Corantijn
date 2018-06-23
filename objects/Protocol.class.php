<?php

class Protocol{
    private $id;
    private $name;
    private $text;
    private $category;
    private $creator;

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

    public function setText($input){
        $this->text = $input;
    }
    public function getText(){
        return $this->text;
    }

    public function setCategory($input){
        $this->category = $input;
    }
    public function getCategory(){
        return $this->category;
    }

    public function setCreator($input){
        $this->creator = $input;
    }
    public function getCreator(){
        return $this->creator;
    }

    public function getAllProtocols(){
        $MySql = new MySql();
        return $MySql->getAllProtocols();
    }
}

?>