<?php
require_once './app/models/JuegoModel.php';
require_once './app/views/ApiView.php';


class JuegoApiController{
    private $model;
    private $view;
    private $data;


    public function __construct() {
        $this->model = new JuegoModel();
        $this->view = new ApiView();

        //lee el body del request
        $this->data = file_get_contents("php://input");        
    } 
    //buscar que hacia xD
    private function getData(){
        return json_decode($this->data);
    }

    public function getJuegos(){
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
        if(!empty($_GET['sort'])){
            $sort = $_GET['sort'];
        }
        if(!empty($_GET['order'])){
            $order =$_GET['order'];
        }
        if (($sort != null && $order == null) || $order !=null && $sort == null){
            if ($sort ==null){
                return $this->view->response("Sort are required", 400);
            }else{
                return $this->view->response("Order are required", 400);
            }
        }
        if ($sort == null && $order == null){
            $juegos = $this->model->getAll($limitPage, $offset);
        }else
            $juegos = $this->model->getAllOrder($sort, $order, $limitPage, $offset);
        if($juegos){
            $this->view->response($juegos, 200);
        }else{
            $this->view->response("Page not found", 404);
        }
    }

    public function getJuego($params = null){
        $idJuego = $params[':ID'];
        $juego = $this->model->get($idJuego);
        if($juego){
            $this->view->response($juego, 200);
        }else{
            $this->view->response("No existe el juego con el id={$idJuego}", 404);
        }
    }

    public function deleteJuego($params = null) {
        $idJuego = $params[':ID'];

        $juego = $this->model->get($idJuego);
        if ($juego) {
            $this->model->delete($idJuego);
            $this->view->response("Juego eliminado con exito", 200);
        } else 
            $this->view->response("El juego con el id=$idJuego no existe", 404);
    }

    public function insertJuego($params = null) {
        $juego = $this->getData();
        if (empty($juego->nombre) || empty($juego->descripcion) || empty($juego->precio) || empty($juego->id_genero)) {
            $this->view->response("Complete los datos", 400);
        } else {
            $idJuego = $this->model->insert($juego->nombre, $juego->descripcion, $juego->precio, $juego->id_genero);
            $nuevoJuego = $this->model->get($idJuego);
            $this->view->response($nuevoJuego, 201);
        }
    }

    public function modifyJuego($params = null, $id){
        $idJuego =$params[':ID'];
        $juego = $this->model->get($idJuego);
        if ($juego){
            $juegoModify = $this->getData();
            $nombre = $juegoModify->nombre;
            $descripcion = $juegoModify->descripcion;
            $precio = $juegoModify->precio;
            $id_genero = $juegoModify->id_genero;
            $this->model->modify($id, $nombre, $descripcion, $precio, $id_genero);
            $this->view->response("El juego ha sido actualizado", 200);
        } else{
            $this->view->response("El juego no se ha encontrado", 404);
        }
    }
   
}