<?php
class Opdracht{
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

    private $beschrijving;
    public function setBeschrijving($input){
        $this->beschrijving = $input;
    }
    public function getBeschrijving(){
        return $this->beschrijving;
    }

    private $category;
    public function setCategory($input){
        $this->category = $input;
    }
    public function getCategory(){
        return $this->category;
    }

    private $status;
    public function setStatus($input){
        $this->status = $input;
    }
    public function getStatus(){
        return $this->status;
    }

    private $creator;
    public function setCreator($input){
        $this->creator = $input;
    }
    public function getCreator(){
        return $this->creator;
    }

    private $assignedTo;
    public function setAssignedTo($input){
        $this->assignedTo = $input;
    }
    public function getAssignedTo(){
        return $this->assignedTo;
    }

    private $date;
    public function setDate($input){
        $this->date = $input;
    }
    public function getDate(){
        return $this->date;
    }
}
?>