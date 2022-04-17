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
        $loggedUser = UserHandler::getUser($this->loggedUser->id);
        $this->render('config', [
            'loggedUser' => $loggedUser,
        ]);
    }

    public function edit() {
        $name = filter_input(INPUT_POST, 'name');
        $name = filter_input(INPUT_POST, 'name');
        $name = filter_input(INPUT_POST, 'name');
        $name = filter_input(INPUT_POST, 'name');
        $name = filter_input(INPUT_POST, 'name');
        $name = filter_input(INPUT_POST, 'name');
        $name = filter_input(INPUT_POST, 'name');
    }

}