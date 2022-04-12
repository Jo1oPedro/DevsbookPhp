<?php
namespace src\handlers;

use \src\models\User;

class LoginHandler {
    
    public static function checkLogin() {
        if(empty($_SESSION['token'])) {
            return false;
        } 
        $token = $_SESSION['token'];
        $data = User::select()->where('token', $token)->one();
        if(empty($data)) {
            return false;
        }
        //return $data;
        $loggedUser = new User();
        $loggedUser->id = $data['id'];
        $loggedUser->email = $data['email'];
        $loggedUser->name = $data['name'];
        return $loggedUser;
    }

}