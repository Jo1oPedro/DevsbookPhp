<?php
use core\Router;

$router = new Router();

$router->get('/', 'HomeController@index');

$router->get('/login', 'LoginController@signIn');
$router->post('/login', 'LoginController@signInAction');

$router->get('/cadastro','LoginController@signUp');
$router->post('/cadastro','LoginController@signUpAction');

$router->post('/post/new', 'PostController@new');

$router->get('/perfil/{id}/fotos', 'ProfileController@photos');
$router->get('/perfil/{id}/amigos', 'ProfileController@friends');
$router->get('/perfil/{id}/follow', 'ProfileController@follow');
$router->get('/perfil/{id}', 'ProfileController@index');
$router->get('/perfil', 'ProfileController@index');
$router->get('/amigos', 'ProfileController@friends');
$router->get('/fotos', 'ProfileController@photos');

$router->get('/pesquisa', 'SearchController@index');

$router->get('/config', 'ConfigController@index');
$router->post('/config', 'ConfigController@edit');
$router->get('/sair', 'LoginController@logout');

$router->get('/ajax/like/{id}', 'AjaxController@like');
$router->post('/ajax/comment', 'AjaxController@comment');

//$router->get('/pesquisar');
//$router->get('/sair');
//$router->get('/amigos');
//$router->get('/fotos');
//$Router->get('/config');