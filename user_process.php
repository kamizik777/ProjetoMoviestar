<?php
use App\Models\DAO\UsersDAO;

require_once ("App/Models/DAO/UsersDAO.php");
require_once ("App/Models/Entidades/Users.php");
require_once ("App/Models/Entidades/Message.php");
require_once ("globals.php");

// Instanciando as classes
$msg = new Message($BASE_URL);
$userDao = new UsersDAO();

// Resgata o tipo de formulário
$type = filter_input(INPUT_POST, "type");

// Atualizar usuário
if ($type === "update") {

    // Resgata dados do usuário
    $userData = $userDao->verifyToken();

    // Receber dados do post
    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $email = filter_input(INPUT_POST, "email");
    $bio = filter_input(INPUT_POST, "bio");

    // Criar um novo objeto de usuário
    $user = new Users();

    // Preencher os dados do usuário
    $userData->setName($name);
    $userData->setLastName($lastname);
    $userData->setEmail($email);
    $userData->setBio($bio);
    $userData->setToken($_SESSION["token"]);

    // Upload de imagem
    if (isset($_FILES["image"]) && ! empty($_FILES["image"]["tmp_name"])) {

        $image = $_FILES["image"];

        // Checando tipo da imagem
        if (in_array($image["type"], [
            "image/jpeg",
            "image/jpg",
            "image/png"
        ])) {

            // Checa se é jpg
            if (in_array($image["type"], [
                "image/jpeg",
                "image/jpg"
            ])) {
                $imageFile = imagecreatefromjpeg($image["tmp_name"]);
            } else {
                $imageFile = imagecreatefrompng($image["tmp_name"]);
            }

            $imageName = $userDao->imageGenerateName();

            imagejpeg($imageFile, "./img/users/" . $imageName, 100);

            $userData->setImage($imageName);
        } else {
            $msg->setMessage("Tipo inválido de imagem, envie jpg ou png!", "error", "editprofile.php");
        }
    }

    $userDao->gravar($userData, $redirect = true);

    // Atualizar senha do usuário
} else if ($type === "changepassword") {} else {
    $msg->setMessage("Informações inválidas!", "error", "index.php");
}

?>