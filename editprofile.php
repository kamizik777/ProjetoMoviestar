<?php

use App\Models\DAO\UsersDAO;

require_once("templates/header.php");
require_once("App/Models/DAO/UsersDAO.php");
require_once("App/Models/Entidades/Users.php");
require_once("globals.php");
$msg = new Message($BASE_URL);
$user = new Users();
$userDao = new UsersDao();
$userData = $userDao->verifyToken();
$img = $userData->getImage();


if ($img == "") {
    $img = "user.png";
}

if (!$userData) {
    $msg->setMessage("Você deve autenticar para editar um perfil.", "error", "index.php");
    header("Location:" . $BASE_URL);
}
?>
<div id="main-container" class="container-fluid edit-profile-page">
    <div class="cold-md-12">
        <form action="<?=$BASE_URL?>user_process.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="type" value="update">
            <div class="row">
                <div class="col-md-4">
                    <h1><?= $user->getFullName($userData); ?></h1>
                    <p class="page-description">Altere seus dados no formulário abaixo:</p>
                    <div class="form-group">
                        <label for="name">Nome:</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Digite seu nome" value=<?= $userData->getName() ?>>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Sobrenome:</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Digite seu sobrenome" value=<?= $userData->getLastName() ?>>
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail:</label>
                        <input type="text" readonly class="form-control disabled" id="email" name="email" placeholder="Digite seu email" value=<?= $userData->getEmail() ?>>
                    </div>
                    <br><input type="submit" class="btn card-btn" value="Alterar">



                </div>
                <div class="col-md-4">
                    <div id="profile-image-container" style="background-image: url('<?= $BASE_URL ?>img/users/<?= $img ?>')"></div>
                    <div class="form-group">
                        <label for="image">Foto</label>
                        <input type="file" name="image" class="form-control-file">
                    </div>
                    <div class="form-group">
                        <label for="bio">Sobre você:</label>
                        <textarea class="form-control" id="bio" name="bio" rows="5" placeholder="Conte quem você é, o que faz, onde trabalha..."><?= $userData->getBio() ?></textarea>
                    </div>
                </div>
        </form>
        <div class="row" id="change-password-container">
            <div class="col-md-4">
                <h2>Alterar a senha:</h2>
                <p class="page-description">Digite a nova senha e confirme, para alterar a senha.</p>
                <form action="<?=$BASE_URL?>user_process.php" method="POST">
                  <input type="hidden" name="type" value="changepassword">
                  <div class="form-group">
                        <label for="password">Senha:</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha">
                    </div>
                    <div class="form-group">
                        <label for="confirmpassword">Confirme sua Senha:</label>
                        <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Confirme sua nova senha senha">
                    </div>
                    <br><input type="submit" class="btn card-btn" value="Alterar">
                </form>
            </div>
        </div>
    </div>
</div>

<?php
require_once("templates/footer.php");
?>