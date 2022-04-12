<?php
namespace src\controllers;

use \core\Controller;

class LoginController extends Controller {

    public function signIn() {
        $this->render('login');
    }

    public function signUp() {
        echo 'Cadastro';
    }

    public function signInAction() {
        echo 'Login recebido';
    }

}