<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;


class ConfigController extends Controller {

    private $loggedUser;

    public function __construct() {
        $this->loggedUser = UserHandler::checkLogin();
        if($this->loggedUser === false) {
            $this->redirect('/login');
        }
    }

    public function index() {
        $flash = [];
        if(!empty($_SESSION['flash'])) {
            if(count($_SESSION['flash']) > 0) {
                $flash = $_SESSION['flash'];
                $_SESSION['flash'] = '';
            }
        }
        $loggedUser = UserHandler::getUser($this->loggedUser->id);
        $this->render('config', [
            'loggedUser' => $loggedUser,
            'flash' => $flash,
        ]);
    }

    public function edit() {
        $_SESSION['flash'] = [];
        $loggedUser = UserHandler::getUser($this->loggedUser->id);
        $inputs = [
            'name' => filter_input(INPUT_POST, 'name'),
            'birthdate' => filter_input(INPUT_POST, 'birthdate'),
            'email' => filter_input(INPUT_POST, 'email'),
            'city' => filter_input(INPUT_POST, 'city'),
            'work' => filter_input(INPUT_POST, 'work'),
            'password' => filter_input(INPUT_POST, 'password'),
            'password_confirmation' => filter_input(INPUT_POST, 'password_confirmation'),
        ];
        foreach($inputs as $key => $input) {
            if($input) {
                if($key == "email") {
                    if($inputs['email'] == $loggedUser->email) {
                        $_SESSION['flash']['email'] = 'O email digitado já existe';
                    }
                }
                if($key == "password") {
                    if($inputs['password'] != $inputs['password_confirmation']) {
                        $_SESSION['flash']['password'] = 'As senhas não conferem';
                    } 
                }
            } else {
                if($key != 'password' && $key != 'password_confirmation') {
                    $inputs[$key] = $loggedUser->{$key};
                }
            }
        }
        $inputs['avatar'] = $loggedUser->avatar;
        $inputs['cover'] = $loggedUser->cover;
        if(isset($_FILES['avatar']) && !empty($_FILES['avatar']['tmp_name'])) {
            $inputs['avatar'] = $_FILES['avatar'];
            if(in_array($inputs['avatar']['type'], ['image/jpeg', 'image/jpg', 'image/png'])) {
                $inputs['avatar'] = $this->cutImage($inputs['avatar'], 200, 200, 'media/avatars');
            } else {
                $_SESSION['flash']['avatar'] = 'O tipo de imagem selecionado não é jpeg/jpg/png';
            }
        }
        if(isset($_FILES['cover']) && !empty($_FILES['cover']['tmp_name'])) {
            $inputs['cover'] = $_FILES['cover'];
            if(in_array($inputs['cover']['type'], ['image/jpeg', 'image/jpg', 'image/png'])) {
                $inputs['cover'] = $this->cutImage($inputs['cover'], 850, 310, 'media/covers');
            } else {
                $_SESSION['flash']['cover'] = 'O tipo de imagem selecionado não é jpeg/jpg/png';
            }
        }
        if(count($_SESSION['flash']) == 0) {
            UserHandler::editUser($this->loggedUser->id, $inputs);
        }
        $this->redirect('/config');
    }

    private function cutImage($file, $width, $height, $folder) {
        list($widthOrig, $heightOrig) = getimagesize($file['tmp_name']);
        $ratio = $widthOrig/ $heightOrig;

        $newWidth = $width;
        $newHeight = $newWidth / $ratio;
        if($newHeight < $height) {
            $newHeight = $height;
            $newWidth = $newHeight * $ratio;
        }
        $x = $width - $newWidth;
        $y = $height - $newHeight;
        $x = $x < 0? $x /2 : $x;
        $y = $y < 0? $y /2 : $y;
        
        $finalImage = imagecreatetruecolor($width,$height);
        switch($file['type']) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagecreatefromjpeg($file['tmp_name']);
            break;
            case 'image/png':
                $image = imagecreatefrompng($file['tmp_name']);
            break;
        }

        imagecopyresampled(
            $finalImage, $image,
            $x, $y, 0, 0,
            $newWidth, $newHeight, $widthOrig, $heightOrig
        );

        $fileName = md5(time().rand(0,9999)) . '.jpg';
        imagejpeg($finalImage, $folder.'/'.$fileName);

        return $fileName;
    }

}