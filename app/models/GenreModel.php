<?php
class GenreModel{
    private $db;

    function __construct(){
        $this->db = new PDO('mysql:host=localhost;'.'dbname=db_juegos;charset=utf8','root', '');
    }
    
    //listar los generos
    function get(){
        $query = $this->db->prepare('SELECT * FROM genero');
        $query->execute();
        $genres = $query->fetchAll(PDO::FETCH_OBJ);
        return $genres;
    }
    
}