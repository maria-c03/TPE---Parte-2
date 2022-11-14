<?php
require_once './libs/Router.php';
require_once './app/controllers/GameApiController.php';
require_once './app/controllers/CommentsApiController.php';
require_once './app/controllers/AuthApiController.php';



$router = new Router();

$router->addRoute('juegos','GET','GameApiController', 'getGames');
$router->addRoute('juegos/:ID','GET','GameApiController', 'getGame');
$router->addRoute('juegos/:ID','DELETE','GameApiController', 'deleteGame');
$router->addRoute('juegos','POST','GameApiController', 'addGame');
$router->addRoute('juegos/:ID','PUT','GameApiController', 'modifyGame');

$router->addRoute('auth/token','GET','AuthApiController', 'getToken');

$router->addRoute('juegos/:ID/comentarios','GET','CommentsApiController', 'getCommentsGame');
$router->addRoute('comentarios/:ID','GET','CommentsApiController', 'getComment');
$router->addRoute('comentarios/:ID','DELETE','CommentsApiController', 'deleteComment');
$router->addRoute('comentarios','POST','CommentsApiController', 'addComment');


$router->route($_GET['action'], $_SERVER['REQUEST_METHOD']);

