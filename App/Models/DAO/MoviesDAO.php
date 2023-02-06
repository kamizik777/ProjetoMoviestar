<?php 
namespace App\Models\DAO;

use Movies;

require_once ("BaseDAO.php");
require_once ("./globals.php");
require_once ("App/Models/Entidades/Users.php");
require_once ("App/Models/Entidades/Message.php");
require_once ("App/Models/Entidades/Movies.php")

class MoviesDao extends BaseDAO{
    const MOVIES = "movies";
    
    public function gravar(Movies $movies)
    {
        if ($users->getId() == false) {
            $this->inserir($movies);
        } else {
            $this->atualizar($movies);
        }
    }
}

?>