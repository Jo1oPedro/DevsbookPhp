<?php
use core\Router;

$router = new Router();

$router->get('/', 'HomeController@index');
$router->get('/login', 'LoginController@signIn');
$router->get('/cadastro','LoginController@signUp');
$router->post('/login', 'LoginController@signInAction');