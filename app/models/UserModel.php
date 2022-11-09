<?php
class UserModel {
    private $db;
    
    function __construct() {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=db_juegos;charset=utf8','root', '');
    }

    function getUser($email){
        $query = $this->db->prepare('SELECT * FROM user WHERE email=?');
        $query->execute(array($email));
        $user = $query->fetch(PDO::FETCH_OBJ);
        return $user;
    }
}