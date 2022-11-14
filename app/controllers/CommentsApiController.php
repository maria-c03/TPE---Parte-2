<?php
require_once './app/models/CommentsModel.php';
require_once './app/models/GameModel.php';
require_once './app/views/ApiView.php';



class CommentsApiController{
    private $model;
    private $modelGame;
    private $view;
    private $data;


    public function __construct() {
        $this->model = new CommentsModel();
        $this->modelGame = new GameModel();
        $this->view = new ApiView();
        $this->data = file_get_contents("php://input");        
    } 

    private function getData(){
        return json_decode($this->data);
    }

    public function getCommentsGame($params = null) {
        $idGame = $params[':ID'];
        $game = $this->modelGame->get($idGame);
        if($game){
            $comments = $this->model->getAll($idGame);
            if ($comments){
                return $this->view->response($comments, 200);   
            }else{
                return $this->view->response("El juego no tiene comentarios", 404);
            }
        }else{
            return $this->view->response("No existe el juego", 404);
        }
    }

    public function getComment($params = null) {
        $idComment = $params[':ID'];
        $comment = $this->model->get($idComment);
        if($comment){
            $this->view->response($comment, 200);   
        }else{
            $this->view->response("No existe el comentario con el id={$idComment}", 404);
        }
    }


    public function addComment() {
        $comment = $this->getData();
        $idGame = $comment->id_juego;
        if (!empty($comment->usuario) && !empty($comment->comentario) && !empty($comment->puntuacion) && !empty($idGame)) {
            $games = $this->modelGame->getGames();
            $gamesIds = [];
            foreach($games as $game){
                array_push($gamesIds, $game->id_juego);
            }
            $existId = in_array($idGame,$gamesIds);

            if($existId == true){
                $idComment = $this->model->insert($comment->usuario, $comment->comentario, $comment->puntuacion, $comment->id_juego);
                $newComment = $this->model->get($idComment);
                $this->view->response($newComment, 201);
            }else{
                $this->view->response("No existe el juego con el id={$idGame}", 404);
            }
        }else {
                $this->view->response("Complete todos los campos", 400);
        }
    }
  

    public function deleteComment($params = null) {
        $idComment = $params[':ID'];
        $comment = $this->model->get($idComment);
        if($comment) {
            $this->model->delete($idComment);
            $this->view->response("Comentario eliminado con éxito", 200);
        }
        else 
            $this->view->response("El comentario con el id={$idComment} no existe", 404);
    }


}