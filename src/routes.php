<?php
use core\Router;

$router = new Router();

$router->get('/', 'HomeController@index');

$router->get('/login', 'LoginController@signIn');
$router->post('/login', 'LoginController@signInAction');

$router->get('/cadastro','LoginController@signUp');
$router->post('/cadastro','LoginController@signUpAction');

//$router->get('/pesquisa');
//$router->get('/perfil');
//$router->get('/sair');