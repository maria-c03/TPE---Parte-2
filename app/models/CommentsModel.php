<?php
class CommentsModel{
    private $db;

    function __construct(){
        $this->db = new PDO('mysql:host=localhost;'.'dbname=db_juegos;charset=utf8','root', '');
    }

    function getAll($idGame){
        $query = $this->db->prepare("SELECT * FROM reviews WHERE id_juego=? ORDER BY puntuacion DESC");
        $query->execute(array($idGame));
        $coments = $query->fetchAll(PDO::FETCH_OBJ);
        return $coments;
    }

    function get($idComment){
        $query = $this->db->prepare('SELECT * FROM reviews WHERE id_comentario=?');
        $query->execute(array($idComment));
        $coment = $query->fetch(PDO::FETCH_OBJ);
        return $coment;
    }

    function insert($user, $comment, $rating, $id_game){
        $query = $this->db->prepare("INSERT INTO reviews(usuario, comentario, puntuacion, id_juego) VALUES(?, ?, ?, ?)");
        $query->execute(array($user, $comment, $rating, $id_game));
        return $this->db->lastInsertId();
    }

    function delete($idComment){
        $query = $this->db->prepare("DELETE FROM reviews WHERE id_comentario=?");
        $query->execute(array($idComment));
    }

}