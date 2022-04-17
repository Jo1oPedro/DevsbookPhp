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
        $page = intval(filter_input(INPUT_GET, 'page'));
        $user = UserHandler::getUser($this->loggedUser->id, true);
        $id = $this->loggedUser->id;
        if(!empty($attributes['id'])) {
            $id = $attributes['id'];
            $user = UserHandler::getUser($id);
            if(!$user) {
                $this->redirect('/');
            }
        }
        $dateFrom = new \DateTime($user->birthdate);
        $dateTo = new \DateTime('today');
        $user->ageYears = $dateFrom->diff($dateTo)->y;
        $feed = PostHandler::getUserFeed($id, $page, $this->loggedUser->id);
        $isFollowing = false;
        if($user->id != $this->loggedUser->id) {
            $isFollowing = UserHandler::isFollowing($this->loggedUser->id, $user->id);
        }
        $this->render('profile', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'feed' => $feed,
            'isFollowing' => $isFollowing,
        ]);
    }

    public function follow($attributes) {
        if(!UserHandler::idExists($attributes['id'])) {
            $this->redirect('/');
        }
        if(UserHandler::isFollowing($this->loggedUser->id, $attributes['id'])) {
            UserHandler::unfollow($this->loggedUser->id, $attributes['id']);
            $this->redirect('/perfil/'.$attributes['id']);
        }
        UserHandler::follow($this->loggedUser->id, $attributes['id']);
        $this->redirect('/perfil/'.$attributes['id']);
        
    }

}