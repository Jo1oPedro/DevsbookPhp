<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use \src\handlers\PostHandler;

class ProfileController extends Controller {

    private $loggedUser;

    public function __construct() {
        $this->loggedUser = UserHandler::checkLogin();
        if($this->loggedUser === false) {
            $this->redirect('/login');
        }
    }

    public function index($attributes = []) {
        $user = UserHandler::getUser($this->loggedUser->id);
        if(!empty($attributes['id'])) {
            $id = $attributes['id'];
            $user = UserHandler::getUser($id);
            if(!$user) {
                $this->redirect('/');
            }
        }
        $this->render('profile', [
            'user' => $user,
        ]);
    }

}