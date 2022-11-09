<?php
require_once './app/models/JuegoModel.php';
require_once './app/views/ApiView.php';
require_once './app/helpers/AuthApiHelper.php';



class JuegoApiController{
    private $model;
    private $view;
    private $data;
    private $AuthApiHelper;


    public function __construct() {
        $this->model = new JuegoModel();
        $this->view = new ApiView();
        $this->AuthApiHelper = new AuthApiHelper();

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
        if(!empty($_GET['limitPage']) || ($_GET['limitPage'] == "limitPage")){
            $limitPage=$_GET['limitPage'];
            var_dump($limitPage);
        }
        if(!empty($_GET['offset']) || ($_GET['offset'] == "offset")){
            $offset=$_GET['offset'];
        }
        if(!empty($_GET['sort']) && ($_GET['sort'] == "precio") || ($_GET['sort'] == "id_juego") || ($_GET['sort'] == "id_genero")|| ($_GET['sort'] == "nombre")){
            $sort = $_GET['sort'];
        }
        if(!empty($_GET['order']) && ($_GET['order'] == "asc") || ($_GET['order'] == "desc")){
            $order =$_GET['order'];
        }
        if (($sort != null && $order == null) || $order !=null && $sort == null){
            if ($sort ==null){
                return $this->view->response("Sort is required or the field is invalid", 400);
            }else{
                return $this->view->response("Order is required or the field is invalid", 400);
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

    public function addJuego() {
        // if(!$this->AuthApiHelper->isLoggedIn()){
        //     $this->view->response("No estas logeado", 401);
        //     return;
        // }

        $juego = $this->getData();
        if($juego->id_genero == "2" || $juego->id_genero == "3" || $juego->id_genero == "4" || $juego->id_genero == "15" || $juego->id_genero == "20"){
            if (empty($juego->nombre) || empty($juego->descripcion) || empty($juego->precio) || empty($juego->id_genero)) {
                $this->view->response("Complete los datos", 400);
            } else {
                $idJuego = $this->model->insert($juego->nombre, $juego->descripcion, $juego->precio, $juego->id_genero);
                $nuevoJuego = $this->model->get($idJuego);
                $this->view->response($nuevoJuego, 201);
            }
        }else{
            $this->view->response("El id_genero no es valido", 400);
        }
    }

    public function modifyJuego($params = null){
        // if(!$this->AuthApiHelper->isLoggedIn()){
        //     $this->view->response("No estas logeado", 401);
        //     return;
        // }
        $idJuego = $params[':ID'];
        $juego = $this->model->get($idJuego);
        if ($juego){
            $juegoModify = $this->getData();
            $nombre = $juegoModify->nombre;
            $descripcion = $juegoModify->descripcion;
            $precio = $juegoModify->precio;
            $id_genero = $juegoModify->id_genero;
            if($juego->id_genero == "2" || $juego->id_genero == "3" || $juego->id_genero == "4" || $juego->id_genero == "15" || $juego->id_genero == "20"){
                $this->model->modify($idJuego, $nombre, $descripcion, $precio, $id_genero);
                $this->view->response("El juego ha sido actualizado", 200);
            } else{
                $this->view->response("El id_genero no es valido", 400);
            }
        }else{
            $this->view->response("El juego no se ha encontrado", 404);
        }
    }
   
}