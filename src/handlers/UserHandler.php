<?php
namespace src\handlers;

use \src\models\User;
use \src\models\User_relation;
use \src\handlers\PostHandler;

class UserHandler {
    
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
        $loggedUser->name = $data['name'];
        $loggedUser->avatar = $data['avatar'];
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
            ->where('id', $user['id'])
        ->execute();
        return $token;
    }

    public function idExists($id) {
        $user = User::select()->where('id', $id)->one();
        return $user ? true : false;
    }

    public function emailExists($email) {
        $user = User::select()->where('email', $email)->one();
        return $user ? true : false;
    }

    public static function getUser($id, $full = false) {
        $data = User::select()->where('id', $id)->one();
        if(!$data) {
            return false;
        }
        $user = new User();
        $user->id = $data['id'];
        $user->name = $data['name'];
        $user->birthdate = $data['birthdate'];
        $user->city = $data['city'];
        $user->work = $data['work'];
        $user->avatar = $data['avatar'];
        $user->cover = $data['cover'];

        //if($full) {
            $user->followers = [];
            $user->following = [];
            $user->photos = [];

            $followers = User_relation::select()->where('user_to', $user->id)->get();
            foreach($followers as $follower) {
                $userData = User::select()->where('id', $follower['user_from'])->one();
                $newUser = new User();
                $newUser->id = $userData['id'];
                $newUser->nome = $userData['nome'];
                $newUser->avatar = $userData['avatar'];
                $user->followers[] =  $newUser;
            } 

            $followings = User_relation::select()->where('user_from', $user->id)->get();
            foreach($followings as $following) {
                $userData = User::select()->where('id', $follower['user_to'])->one();
                $newUser = new User();
                $newUser->id = $userData['id'];
                $newUser->nome = $userData['nome'];
                $newUser->avatar = $userData['avatar'];
                $user->following[] =  $newUser;
            }

            $user->photos = PostHandler::getPhotosFrom($id);
        //}

        return $user;
    }

    public function addUser($name, $email, $password, $birthdate) {
        $token = md5(time().rand(0,9999).time());
        User::insert([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'birthdate' => $birthdate,
            'token' => $token,
        ])->execute();
        return $token;
    }
}