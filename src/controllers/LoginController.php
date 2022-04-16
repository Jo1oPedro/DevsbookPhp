<?php
namespace src\controllers;

use \core\Controller;
use src\handlers\UserHandler;

class LoginController extends Controller {

    public function signIn() {
        $flash = '';
        if(!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }
        $this->render('signIn', [
            'flash' => $flash,
        ]);
    }

    public function signUp() {
        $flash = '';
        if(!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }
        $this->render('signUp', [
            'flash' => $flash,
        ]);
    }

    public function signInAction() {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');
        if(!$email || !$password) {
            $_SESSION['flash'] = 'Digite os campos de email e/ou senha.';
            $this->redirect('/login');
        }
        $token = UserHandler::verifyLogin($email, $password);
        if(!$token) {
            $_SESSION['flash'] = 'E-mail e/ou senha não conferem.';
            $this->redirect('/login');
        }
        $_SESSION['token'] = $token;
        $this->redirect('/');
    }

    public function signUpAction() {
        $name = filter_input(INPUT_POST, 'name');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');
        $birthdate = filter_input(INPUT_POST, 'birthdate');
        if(!$name || !$email || !$password || !$birthdate) {
            $this->redirect('/cadastro'); 
        }
        if(UserHandler::emailExists($email)) {
            $this->redirect('/cadastro');
        }
        $token = UserHandler::addUser($name, $email, $password, $birthdate);
        $_SESSION['token'] = $token;
        $this->redirect('/');
    }

}