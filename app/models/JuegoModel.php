<?php
class JuegoModel{
    private $db;

    function __construct(){
        $this->db = new PDO('mysql:host=localhost;'.'dbname=db_juegos;charset=utf8','root', '');
    }


    
    //listar los items
    function getAll($limitPage, $offset){
        $query = $this->db->prepare("SELECT * FROM juego limit $limitPage offset $offset");
        $query->execute();
        $juegos = $query->fetchAll(PDO::FETCH_OBJ);
        return $juegos;
    }


//consultar como mejorar el cogido para evitar la inyeccion!
    function getAllOrder($sort, $order, $limitPage, $offset){
        $query = $this->db->prepare("SELECT * FROM juego ORDER BY $sort $order limit $limitPage offset $offset");
        $query->execute();
        $juegos = $query->fetchAll(PDO::FETCH_OBJ);
        return $juegos;
    }




    
    //listar un item
    function get($idJuego){
        $query = $this->db->prepare("SELECT * FROM juego WHERE id_juego=?");
        $query->execute(array($idJuego));
        $juego = $query->fetch(PDO::FETCH_OBJ);
        return $juego;
    }

    //agregar un juego(ALTA)
    function insert($nombre, $descripcion, $precio, $id_genero){
        $query = $this->db->prepare("INSERT INTO juego(nombre, descripcion, precio, id_genero) VALUES(?, ?, ?, ?)");
        $query->execute(array($nombre, $descripcion, $precio, $id_genero));
    }
    
    //modificar un juego(MODIFICACION)
    function modify($id, $nombre, $descripcion, $precio, $id_genero){
        $query = $this->db->prepare("UPDATE juego SET nombre=?, descripcion=?, precio=?, id_genero=? WHERE id_juego=?");
        $query->execute(array($nombre, $descripcion, $precio, $id_genero, $id));
    }
    
    //borrar un juego (BAJA)
    function delete($id){
        $query = $this->db->prepare("DELETE FROM juego WHERE id_juego=?");
        $query->execute(array($id));
    }
}

