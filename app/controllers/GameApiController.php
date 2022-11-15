<?php
require_once './app/models/GameModel.php';
require_once './app/models/GenreModel.php';
require_once './app/views/ApiView.php';
require_once './app/helpers/AuthApiHelper.php';



class GameApiController{
    private $model;
    private $modelGenre;
    private $view;
    private $data;
    private $AuthApiHelper;


    public function __construct() {
        $this->model = new GameModel();
        $this->modelGenre = new GenreModel();
        $this->view = new ApiView();
        $this->AuthApiHelper = new AuthApiHelper();
        $this->data = file_get_contents("php://input");        
    } 

    private function getData(){
        return json_decode($this->data);
    }

    public function getGames(){
        $sort = null;
        $order = null;
        $limitPage = 3;
        $offset = 0;
        
        if(!empty($_GET['limitPage'])){
            $limitPage=$_GET['limitPage'];
        }

        if(!empty($_GET['offset'])){
            $offset=$_GET['offset'];
        }
        
        if(!empty($_GET['sort']) && (($_GET['sort'] == "precio") || ($_GET['sort'] == "id_juego") || ($_GET['sort'] == "id_genero") || ($_GET['sort'] == "nombre"))){
            $sort = $_GET['sort'];
        }

        if(!empty($_GET['order']) && (($_GET['order'] == "asc") || ($_GET['order'] == "desc") || ($_GET['order'] == "ASC") || ($_GET['order'] == "DESC"))){
            $order =$_GET['order'];
        }

        if (($sort != null && $order == null) || $order !=null && $sort == null){
            if ($sort == null){
                return $this->view->response("Sort es requerido o el campo es invalido", 400);
            }else{
                return $this->view->response("Order es requerido o el campo es invalido", 400);
            }
        }

        $price = null;

        if(!empty($_GET['precio'])){
            $price = $_GET['precio'];
        }

        $operatorPrice = null;

        if(!empty($_GET['operatorPrice'])){
            $operatorPrice = $_GET['operatorPrice'];
        }

        if (($price != null && $operatorPrice == null) || ($price ==null && $operatorPrice != null)){
            if ($price == null){
                return $this->view->response("Price es requerido o el campo es invalido", 400);
            }else{
                return $this->view->response("OperatorPrice es requerido o el campo es invalido", 400);
            }
        }


        if ($sort == null && $order == null && $price == null && $operatorPrice == null){
            $games = $this->model->getAll($limitPage, $offset);
        }elseif($price != null && $operatorPrice != null){
            $games = $this->model->filterByPrice($limitPage, $offset, $price, $operatorPrice);
        }elseif($sort != null && $order != null){
            $games = $this->model->getAllOrder($sort, $order, $limitPage, $offset);
        }else{
            $games = $this->model->filterAndPaginated($sort, $order, $limitPage, $offset, $price, $operatorPrice);
        }

        if($games){
            $this->view->response($games, 200);
        }else{
            $this->view->response("No se encontraron resultados", 404);
        }
    }

    public function getGame($params = null){
        $idGame = $params[':ID'];
        $game = $this->model->get($idGame);
        if($game){
            $this->view->response($game, 200);
        }else{
            $this->view->response("No existe el juego con el id={$idGame}", 404);
        }
    }

    public function deleteGame($params = null) {
        $idGame = $params[':ID'];

        $game = $this->model->get($idGame);
        if ($game) {
            $this->model->delete($idGame);
            $this->view->response("Juego eliminado con exito", 200);
        } else 
            $this->view->response("El juego con el id={$idGame} no existe", 404);
    }

    public function addGame() {
        if(!$this->AuthApiHelper->isLoggedIn()){
            $this->view->response("No estas logueado", 401);
            return;
        }

        $game = $this->getData();
        $idGenre = $game->id_genero;

        if(!empty($game->nombre) && !empty($game->descripcion) && !empty($game->precio) && !empty($idGenre)){
            $genres = $this->modelGenre->get();
            $genreIds = [];
            foreach($genres as $genre){
                array_push($genreIds, $genre->id_genero);
            }
            $existId =  in_array($idGenre,$genreIds);
            if ($existId == true) {
                $idGame = $this->model->insert($game->nombre, $game->descripcion, $game->precio, $game->id_genero);
                $newGame = $this->model->get($idGame);
                $this->view->response($newGame, 201);
            } else {
                $this->view->response("No existe el genero con el id={$idGenre}", 404);
            }
        }else{
            $this->view->response("Complete todos los campos", 400);
        }
    }

    public function modifyGame($params = null){
        if(!$this->AuthApiHelper->isLoggedIn()){
            $this->view->response("No estas logueado", 401);
            return;
        }

        $idGame = $params[':ID'];
        $game = $this->model->get($idGame);

        if ($game){
            $gameModify = $this->getData();
            $name = $gameModify->nombre;
            $description = $gameModify->descripcion;
            $price = $gameModify->precio;
            $idGenre = $gameModify->id_genero;
            if(!empty($name) && !empty($description) && !empty($price) && !empty($idGenre)){
                $genres = $this->modelGenre->get();
                $genreIds = [];
                foreach($genres as $genre){
                    array_push($genreIds, $genre->id_genero);
                }
                $existId = in_array($idGenre,$genreIds);
                if($existId == true){
                    $this->model->modify($idGame, $name, $description, $price, $idGenre);
                    $this->view->response("El juego ha sido actualizado", 200);
                }else {
                    $this->view->response("No existe el genero con el id={$idGenre}", 404);
                }
            }else{
                $this->view->response("Completa todos los campos", 400);
            }
        }else{
            $this->view->response("El juego no se ha encontrado", 404);
        }
    }
   
}