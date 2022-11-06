<?php
require_once './libs/Router.php';
require_once './app/controllers/JuegoApiController.php';


$router = new Router();

$router->addRoute('juegos','GET','JuegoApiController', 'getJuegos');
$router->addRoute('juegos/:ID','GET','JuegoApiController', 'getJuego');
$router->addRoute('juegos/:ID','DELETE','JuegoApiController', 'deleteJuego');
$router->addRoute('juegos','POST','JuegoApiController', 'addJuego');
$router->addRoute('juegos/:ID','PUT','JuegoApiController', 'modifyJuego');





$router->route($_GET['action'], $_SERVER['REQUEST_METHOD']);

