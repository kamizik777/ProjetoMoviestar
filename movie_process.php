<?php 
use App\Models\DAO\UsersDAO;

require_once ("App/Models/DAO/UsersDAO.php");
require_once ("App/Models/Entidades/Users.php");
require_once ("App/Models/Entidades/Message.php");
require_once ("globals.php");

//Instanciando as classes

$msg = new Message($BASE_URL);
$userDao = new UsersDAO();

//Resgata o tipo de formulário
$type = filter_input(INPUT_POST, "type");

//Inserir filme

if($type === "create"){
    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $image = filter_input(INPUT_POST, "image");
    $trailer = filter_input(INPUT_POST, "trailer");
    $category = filter_input(INPUT_POST, "category");
    
    //Criando um novo objeto Movie
    
    
}



?>