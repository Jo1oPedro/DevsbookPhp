<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use \src\handlers\PostHandler;

class PostController extends Controller {

    private $loggedUser;

    public function __construct() {
        $this->loggedUser = UserHandler::checkLogin();
        if($this->loggedUser === false) {
            $this->redirect('/login');
        }
    }

    public function new() {
        $body = filter_input(INPUT_POST, 'body');
        if($body) {
            PostHandler::addPost(
                $this->loggedUser->id,
                'text',
                $body,
            );
        }
        $this->redirect('/');
    }

    public function delete($attributes) {
        if($attributes['id']) {
            $id_post = $attributes['id'];
            PostHandler::deletePost($this->loggedUser->id, $id_post);
        }
        $this->redirect('/');
    }
    
}