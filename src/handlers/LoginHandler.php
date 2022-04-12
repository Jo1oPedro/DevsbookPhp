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

    public static function verifyLogin($email, $password) {
        $user = User::select()->where('email', $email)->one();
        if(!$user) {
            return false;
        }
        if(!password_verify($password, $user['password'])) {
            return false;
        }
        $token = md5(time().rand(0,9999).time());
        User::update()
            ->set('token', $token)
            ->where('id', $user->id)
        ->execute();
        return $token;
    }

}