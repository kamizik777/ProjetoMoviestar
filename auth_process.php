<?php
use App\Models\DAO\UsersDAO;

require_once ("App/Models/Entidades/Users.php");
require_once ("globals.php");
require_once ("App/Models/DAO/UsersDAO.php");
require_once ("App/Models/Entidades/Message.php");

$message = new Message($BASE_URL);
$userDao = new UsersDAO();

// Resgata o tipo do formulário
$type = filter_input(INPUT_POST, "type");

// Verificação do de tipo de formulário
if ($type === "register") {
    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, "password");
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

    // Verificação de dados mínimos
    $valido = false;
    if ($name && $lastname && $email && $password) {
        // Verificar se as senhas batem
        if ($password === $confirmpassword) {

            // Verificar se o e-mail já está cadastrado no sistema

            if ($userDao->getBy(null, $email)) {
                // Enviar uma msg de erro, o email já existe
                $erro = $message->setMessage("E-mail já cadastrado.", "error", "auth.php");
                return $erro;
            }
        } else {
            // Enviar uma msg de erro, de senhas não batem
            $erro = $message->setMessage("As senhas não são iguais.", "error", "auth.php");
            return $erro;
        }
    } else {

        // Enviar uma mensagem de erro de dados faltantes
        $erro = $message->setMessage("Por favor, preencha todos os campos.", "error", "auth.php");
        return $erro;
    }

    $valido = true;

    if ($valido == true) {
        $user = new Users();
        $userToken = $user->generateToken();
        $finalPassword = password_hash($password, PASSWORD_DEFAULT);
        $user->setName($name);
        $user->setLastName($lastname);
        $user->setEmail($email);
        $user->setPassword($finalPassword);
        $user->setToken($userToken);
        $auth = true;
        $userDao->gravar($user, $auth);
    }
} // Fazer o login do usuário
 else if($type === "login") {
    
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');
    
    // Se conseguir autenticar, mensagem de sucesso
    if($userDao->authenticateUser($email, $password)) {
        
        $message->setMessage("Seja bem-vindo!", "sucess", "editprofile.php");
        
        // Caso não autenticar, redireciona para a página de auth com erro
    } else {
        
        $message->setMessage("Usuário e/ou senha incorretos!", "error", "auth.php");
        
    }
    
} else {
    
    $message->setMessage("Informações inválidas, tente novamente.", "error", "index.php");
    
}