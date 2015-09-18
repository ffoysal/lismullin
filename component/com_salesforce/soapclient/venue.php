<?php
class Venue{
    protected $name;
    protected $address;
    protected $phone;
    protected $website;
    
    public function __construct($na="", $add="", $ph="", $web=""){
        $this->address = $add;
        $this->name = $na;
        $this->phone = $ph;
        $this->website = $web;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function getAddress(){
        return $this->address;
    }
    public function getPhone(){
        return $this->phone;
    }
    
    public function getWebsite(){
        return $this->website;
    }
}