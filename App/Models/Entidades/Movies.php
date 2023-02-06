<?php 

class Movies{
    private $id;
    private $title;
    private $description;
    private $image;
    private $trailer;
    private $category;
    private $users_id;
    
    public function getId(){
        return $this->id;
    }
    
    public function setId(int $id){
        $this->id = $id;
    }
    
    public function getDescription(){
        return $this->description;
    }
    
    public function setDescription(string $desc){
        $this->description = $desc;
    }
    
    public function getImage(){
        return $this->image;
    }
    
    public function setImage(string $img){
        $this->image = $img;
    }
    
    public function getTrailer(){
        return $this->trailer;
    }
    
    public function setTrailer(string $trailer){
        $this->trailer = $trailer;
    }
    
    public function getCategory(){
        return $this->category;
    }
    
    public function setCategory(string $category){
        $this->category = $category;
    }
}



?>