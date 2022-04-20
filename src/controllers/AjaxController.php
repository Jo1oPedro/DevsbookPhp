<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use \src\handlers\PostHandler;

class AjaxController extends Controller {

    private $loggedUser;

    public function __construct() {
        $this->loggedUser = UserHandler::checkLogin();
        if($this->loggedUser === false) {
            header("Content-type: application/json");
            echo json_encode(['error' => 'usuario não logado',]);
            exit;
        }
    }

    public function like($attributes) {
        $idPost = $attributes['id'];
        if(PostHandler::isLiked($idPost,$this->loggedUser->id)) {
            PostHandler::deleteLike($idPost, $this->loggedUser->id);
        } else {
            PostHandler::addLike($idPost, $this->loggedUser->id);
        }
    }

    public function comment() {
        $array = ['error' => ''];
        $id_post = filter_input(INPUT_POST, 'id_post');
        $body = filter_input(INPUT_POST, 'body');
        if($id_post  &&  $body) {
            PostHandler::addComment($id_post, $body, $this->loggedUser->id);
        
            $array['link'] = '/perfil/'.$this->loggedUser->id;
            $array['avatar'] = '/media/avatars/'.$this->loggedUser->avatar;
            $array['name'] = $this->loggedUser->name;
            $array['body'] = $body;
        }
        header("Content-type: application/json");
        echo json_encode($array);
        exit;
    }

}