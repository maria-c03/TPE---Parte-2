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
        // $params = array("limitPage", "offset", "sort", "order");
        // array_key_exists("offset", $params);

        if(!empty($_GET['limitPage']) || ($_GET['limitPage'] == "limitPage")){
            $limitPage=$_GET['limitPage'];
        }

        if(!empty($_GET['offset']) || ($_GET['offset'] == "offset")){
            $offset=$_GET['offset'];
        }

        if(!empty($_GET['sort']) && ($_GET['sort'] == "precio") || ($_GET['sort'] == "id_juego") || ($_GET['sort'] == "id_genero") || ($_GET['sort'] == "nombre")){
            $sort = $_GET['sort'];
        }

        if(!empty($_GET['order']) && ($_GET['order'] == "asc") || ($_GET['order'] == "desc") || ($_GET['order'] == "ASC") || ($_GET['order'] == "DESC")){
            $order =$_GET['order'];
        }

        if (($sort != null && $order == null) || $order !=null && $sort == null){
            if ($sort ==null){
                return $this->view->response("Sort is required or the field is invalid", 400);
            }else{
                return $this->view->response("Order is required or the field is invalid", 400);
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

        if ($sort == null && $order == null){
            $games = $this->model->getAll($limitPage, $offset);
        }elseif($price != null && $operatorPrice != null){
            $games = $this->model->filterByPrice($sort, $order, $limitPage, $offset, $price, $operatorPrice);
        }elseif(($price != null && $operatorPrice == null) || ($price == null && $operatorPrice != null)){
            return $this->view->response("price and operatorPrice are required", 400);
        }
        else{
            $games = $this->model->getAllOrder($sort, $order, $limitPage, $offset);
        }

        if($games){
            $this->view->response($games, 200);
        }else{
            $this->view->response("Page not found", 404);
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
            $this->view->response("No estas logeado", 401);
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
            $this->view->response("No estas logeado", 401);
            return;
        }

        $idGame = $params[':ID'];
        $game = $this->model->get($idGame);

        if ($game){
            $gameModify = $this->getData();
            $name = $gameModify->nombre;
            $description = $gameModify->descripcion;
            $price = $gameModify->precio;
            $id_gender = $gameModify->id_genero;

            if(($id_gender == "2") || ( $id_gender == "3") || ($id_gender == "4") || ($id_gender == "15") || ($id_gender == "20")){
                $this->model->modify($idGame, $name, $description, $price, $id_gender);
                $this->view->response("El juego ha sido actualizado", 200);
            } else{
                $this->view->response("El id_genero no es valido", 400);
            }

        }else{
            $this->view->response("El juego no se ha encontrado", 404);
        }
    }
   
}