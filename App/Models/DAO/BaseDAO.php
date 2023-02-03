<?php
namespace App\Models\DAO;

use PDO;
use PDOException;

class BaseDAO
{
    private static $conexao;

    public static function getConexao(): PDO
    {
        try {
            self::$conexao = new PDO("mysql:host=localhost;dbname=moviestar", "root", "silvas");
            self::$conexao->exec("set names utf8");
            
            return self::$conexao;
        } catch (PDOException $e) {
            echo "Falha: " . $e->getMessage();
            exit();
        }
    }
}

?>