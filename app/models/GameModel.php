<?php
class GameModel{
    private $db;

    function __construct(){
        $this->db = new PDO('mysql:host=localhost;'.'dbname=db_juegos;charset=utf8','root', '');
    }

    function getAll($limitPage, $offset){
        $query = $this->db->prepare("SELECT * FROM juego limit $limitPage offset $offset");
        $query->execute();
        $games = $query->fetchAll(PDO::FETCH_OBJ);
        return $games;
    }

    function getAllOrder($sort, $order, $limitPage, $offset){
        $query = $this->db->prepare("SELECT * FROM juego ORDER BY $sort $order limit $limitPage offset $offset");
        $query->execute();
        $games = $query->fetchAll(PDO::FETCH_OBJ);
        return $games;
    }

    function filterByPrice($sort, $order, $limitPage, $offset, $price, $operatorPrice){
        $query = $this->db->prepare("SELECT * FROM juego WHERE precio $operatorPrice ? ORDER BY $sort $order limit $limitPage offset $offset");
        $query->execute(array($price));
        $games = $query->fetchAll(PDO::FETCH_OBJ);
        return $games;
    }
    
    function get($idGame){
        $query = $this->db->prepare("SELECT * FROM juego WHERE id_juego=?");
        $query->execute(array($idGame));
        $game = $query->fetch(PDO::FETCH_OBJ);
        return $game;
    }

    function insert($name, $description, $price, $id_gender){
        $query = $this->db->prepare("INSERT INTO juego(nombre, descripcion, precio, id_genero) VALUES(?, ?, ?, ?)");
        $query->execute(array($name, $description, $price, $id_gender));
        return $this->db->lastInsertId();
    }
    
    function modify($idGame, $name, $description, $price, $id_gender){
        $query = $this->db->prepare("UPDATE juego SET nombre=?, descripcion=?, precio=?, id_genero=? WHERE id_juego=?");
        $query->execute(array($name, $description, $price, $id_gender, $idGame));
    }
    
    function delete($id){
        $query = $this->db->prepare("DELETE FROM juego WHERE id_juego=?");
        $query->execute(array($id));
    }

    function getGames(){
        $query = $this->db->prepare("SELECT * FROM juego");
        $query->execute();
        $games = $query->fetchAll(PDO::FETCH_OBJ);
        return $games;
    }
    
}

