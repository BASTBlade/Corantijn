<?php
class Status{
    private $id;
    public function setId($input){
        $this->id = $input;
    }
    public function getId(){
        return $this->id;
    }

    private $name;
    public function setName($input){
        $this->name = $input;
    }
    public function getName(){
        return $this->name;
    }

    private $color;
    public function setColor($input){
        $this->color = $input;
    }
    public function getColor(){
        return $this->color;
    }
}
?>