<?php 

class Users{
    private $id;
    private $name;
    private $lastname;
    private $email;
    private $password;
    private $image;
    private $bio;
    private $token;

    public function getFullName(Users $users) {
     return $users->name . " " . $users->lastname;
      }
    
    public function generateToken(){
       return bin2hex(random_bytes(50));
    }

    public function getId(){
        return $this->id;
    }
    
    public function setId(int $id){
        $this->id = $id;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function setName($name){
        $this->name = $name;
    }
    
    public function getLastName(){
        return $this->lastname;
    }
    public function setLastName(string $ln){
        $this->lastname = $ln;
    }
    
    public function getEmail(){
        return $this->email;
    }
    
    public function setEmail(string $email){
        $this->email = $email;
    }
    
    public function getPassword(){
        return $this->password;
    }
    
    public function setPassword(string $pw){
        $this->password = $pw;
    }
    
    public function getImage(){
        return $this->image;
    }
    
    public function setImage(string $img = null){
        $this->image = $img;
    }
    
    public function getBio(){
        return $this->bio;
    }
    
    public function setBio(string $bio = null){
        $this->bio = $bio;
    }
    
    public function getToken(){
        return $this->token;
    }
    
    public function setToken(string $token = null){
        $this->token = $token;
    }
}



?>