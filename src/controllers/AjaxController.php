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

    public function upload() {
        $array = ['error' => ''];
        if(!isset($_FILES['photo']) || empty($_FILES['photo']['tmp_name'])) {
            $array['error'] = 'Nenhuma imagem enviada';
        } else {
            $photo = $_FILES['photo'];
            $maxWidth = 400;
            $maxHeight = 400;

            if(in_array($photo['type'], ['image/png', 'image/jpg', 'image/jpeg'])) {
                
                list($widthOrig, $heightOrig) = getimagesize($photo['tmp_name']);
                $ratio = $widthOrig / $heightOrig;

                $newWidth = $maxWidth;
                $newHeight = $maxHeight;
                $ratioMax = $maxWidth / $maxHeight;

                if($ratioMax > $ratio) {
                    $newWidth = $newHeight * $ratio;
                } else {
                    $newHeight = $newWidth / $ratio;
                }

                $finalImage = imagecreatetruecolor($newWidth, $newHeight);
                switch($photo['type']) {
                    case 'image/png':
                        $image = imagecreatefrompng($photo['tmp_name']);
                    break;
                    case 'image/jpg':
                    case 'image/jpeg':
                        $image = imagecreatefromjpeg($photo['tmp_name']);
                    break;
                }

                imagecopyresampled(
                    $finalImage, $image,
                    0, 0, 0, 0,
                    $newWidth, $newHeight, $widthOrig, $heightOrig,
                );

                $photoName = md5(time().rand(0,9999)).'.jpg';
                imagejpeg($finalImage, 'media/uploads/'.$photoName);
                PostHandler::addPost(
                    $this->loggedUser->id,
                    'photo',
                    $photoName
                );
            } else {
                $array['error'] = 'A imagem não está de acordo com o formato aceito';
            }
        }


        header("Content-type: application/json");
        echo json_encode($array);
        exit;
    }

}