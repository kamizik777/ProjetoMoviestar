<?php
namespace App\Models\DAO;

require_once ("BaseDAO.php");
require_once ("./globals.php");
require_once ("App/Models/Entidades/Users.php");
require_once ("App/Models/Entidades/Message.php");
use Message;
use PDO;
use Users;

class UsersDAO extends BaseDAO implements UsersDAOInterface
{

    const USERS = "users";

    public function gravar(Users $users, $authUser = false)
    {
        if ($users->getId() == false) {
            $this->inserir($users, $authUser);
        } else {
            $this->atualizar($users, $authUser);
        }
    }

    public function verifyToken($protected = true)
    {
        if (! empty($_SESSION["token"])) {
            // Pega o token da session
            $token = $_SESSION["token"];
            
            $user = $this->getBy(null, null, $token);

            if ($user) {
                return $user;
            } else if ($protected) {
                // Redireciona usuário não autenticado
                $userr = new Message($BASE_URL);
                $userr->setMessage("Faça a autenticação para entrar na página", "error", "index.php");
            }
        } else {
            return false;
        }
    }

    public function updateToken(Users $users, $id)
    {
        $sql = "update " . self::USERS . " set token = ? where id = ?";
        $stmt = $this->getConexao()->prepare($sql);
        $token = $_SESSION["token"];
        $stmt->bindParam(1, $token);
        $stmt->bindParam(2, $id);
        
        $stmt->execute();
    }

    public function authenticateUser($email, $password, $authUser = false)
    {
        $user = new Users();
        $user = $this->getBy(null, $email);

        if ($user) {
            // Checar se as senhas batem
            if (password_verify($password, $user->getPassword())) {

                // Gerar um token e inserir na session
                $token = $user->generateToken();
                $this->setTokenToSession($token, $user->getId(), false);

                // Atualizar token no usuario
                $this->updateToken($user, $user->getId());
                $authUser = true;

                return true;
            } else {
                return false;
            }
        } 
    }

    public function setTokenToSession($token, $id, $redirect = true)
    {
        $_SESSION["token"] = $token;
        $sql = "update " . self::USERS . " set token = ? where id = ?";
        $stmt = $this->getConexao()->prepare($sql);
        $stmt->bindParam(1, $token);
        $stmt->bindParam(2, $id);
        
        if ($redirect) {
            // Redireciona para o perfil do usuário
            $user = new Message($BASE_URL);

            $user->setMessage("Seja bem-vindo", "sucess", "editprofile.php");
        }
    }

    public function changePassword(Users $users)
    {
        $sql = "update " . self::USERS . " set password = ? where id = ?";
        $stmt = $this->getConexao()->prepare($sql);
        $stmt->bindParam(1, $users->getPassword());
        $stmt->bindParam(2, $users->getId());
        $stmt->execute();
    }
    
    public function imageGenerateName(){
        return bin2hex(random_bytes(60)) . ".jpg";
    }

    public function destroyToken()
    {
        // Remove o token da session
        unset($_SESSION["token"]);

        // Redirecionar e apresentar a mensagem de sucesso
        $msg = new Message($BASE_URL);
        $msg->setMessage("Você fez o logout com sucesso", "sucess", "index.php");
    }

    private function inserir(Users $users, $authUser = false)
    {
        $sql = "insert into " . self::USERS . " (id, name, lastname, email, password, image, bio, token) values (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->getConexao()->prepare($sql);
        $users->setId($this->getMaxId());
        $stmt->bindParam(1, $users->getId());
        $stmt->bindParam(2, $users->getName());
        $stmt->bindParam(3, $users->getLastName());
        $stmt->bindParam(4, $users->getEmail());
        $stmt->bindParam(5, $users->getPassword());
        $stmt->bindParam(6, $users->getImage());
        $stmt->bindParam(7, $users->getBio());
        $stmt->bindParam(8, $users->getToken());
        $stmt->execute();

        // Autenticar usuário, caso auth seja true;
        if ($authUser) {
            $this->setTokenToSession($users->getToken(), $users->getId(), $redirect = true);
        }
    }

    private function atualizar(Users $users, $redirect = true)
    {
        $sql = "update " . self::USERS . " set name = ?, lastname = ?, email = ?, password = ?, image = ?,  bio = ? where token = ?";
        $stmt = $this->getConexao()->prepare($sql);
        $stmt->bindParam(1, $users->getName());
        $stmt->bindParam(2, $users->getLastName());
        $stmt->bindParam(3, $users->getEmail());
        $stmt->bindParam(4, $users->getPassword());
        $stmt->bindParam(5, $users->getImage());
        $stmt->bindParam(6, $users->getBio());
        $stmt->bindParam(7, $users->getToken());
        $stmt->execute();

        if ($redirect) {
            // Redireciona para o perfil do usuário
            $user = new Message($BASE_URL);

            $user->setMessage("Dados atualizados com sucesso.", "sucess", "editprofile.php");
        }
    }

    public function deletar(Users $users)
    {
        $sql = "delete from " . self::USERS . " where id = ?";
        $stmt = $this->getConexao()->prepare($sql);
        $stmt->bindParam(1, $users->getId());
        $stmt->execute();
    }

    public function listar(Users $users)
    {
        $sql = "select * from " . self::USERS;
        $stmt = $this->getConexao()->prepare($sql);
        $stmt->execute();
        $sql = $stmt->fetchAll(PDO::FETCH_OBJ);
        $lista = [];

        foreach ($sql as $registro) {
            $user = new Users();
            $user->setId($registro->id);
            $user->setName($registro->name);
            $user->setLastName($registro->lastname);
            $user->setEmail($registro->email);
            $user->setPassword($registro->password);
            $user->setImage($registro->image);
            $user->setBio($registro->bio);
            $user->setToken($registro->token);

            $lista[] = $user;
        }
        return $lista;
    }

    public function getBy(int $id = null, string $email = null, string $token = null)
    {
        $sql = "select * from " . self::USERS . " where ";
        if ($id) {
            $sql .= "id = ?";
            $stmt = $this->getConexao()->prepare($sql);
            $stmt->bindParam(1, $id);
        }

        if ($email) {
            $sql .= "email = ?";
            $stmt = $this->getConexao()->prepare($sql);
            $stmt->bindParam(1, $email);
        }
        if ($token) {
            $sql .= "token = ?";
            $stmt = $this->getConexao()->prepare($sql);
            $stmt->bindParam(1, $token);
        }

        $stmt->execute();
        $sql = $stmt->fetch(PDO::FETCH_OBJ);
        if ($sql) {
            $user1 = new Users();
            $user1->setId($sql->id);
            $user1->setName($sql->name);
            $user1->setLastName($sql->lastname);
            $user1->setEmail($sql->email);
            $user1->setPassword($sql->password);
            $user1->setImage($sql->image);
            $user1->setBio($sql->bio);
            return $user1;
        } else
            return false;
    }

    private function getMaxId()
    {
        $rs = $this->getConexao()->prepare("select max(id) as id from " . self::USERS);
        $rs->execute();

        if ($rs->execute()) {
            if ($registro = $rs->fetch(PDO::FETCH_OBJ)) {
                return ++ $registro->id;
            }
        }
    }
}

interface UsersDAOInterface
{

    public function gravar(Users $users, $authUser = false);

    public function authenticateUser($email, $password);

    public function setTokenToSession($token, $id, $redirect = true);

    public function listar(Users $users);

    public function verifyToken($protected = false);

    public function changePassword(Users $users);

    public function getBy(int $id = null, string $email = null, string $token = null);

    public function destroyToken();
}

?>