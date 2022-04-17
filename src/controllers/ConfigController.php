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
        $notEmptyInputs = [];
        foreach($inputs as $key => $input) {
            if($input) {
                if($key == "email") {
                    if($inputs['email'] == $loggedUser->email) {
                        $_SESSION['flash']['email'] = 'O email digitado já existe';
                    }
                }
                if($key == 'password') {
                    if($inputs['password'] != $input['password_confirmation']) {
                        $_SESSION['flash']['password'] = 'As senhas não conferem';
                    } 
                }
                $notEmptyInputs[$key] = $input;
            }
        }
        if(count($notEmptyInputs) > 0) {
            if(count($_SESSION['flash']) == 0) {
                UserHandler::editUser($this->loggedUser->id, $notEmptyInputs);
            }
        }
        $this->redirect('/config');
    }

}