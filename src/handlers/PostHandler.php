<?php
namespace src\handlers;

use \src\models\Post;
use \src\models\User;
use \src\models\User_relation;

class PostHandler {
    
    public static function addPost($idUser, $type, $body) {
        $body = trim($body);

        if(!empty($idUser) && !empty($body)) {
            Post::insert([
                'type' => $type,
                'created_at' => date('Y-m-d H:i:s'),
                'body' => $body,
                'id_user' => $idUser,
            ])->execute();
        }
    }

    public static function getHomeFeed($idUser, $page) {
        $perPage = 2;
        $usersList = User_relation::select()->where('user_from', $idUser)->get();
        $users = [];
        foreach($usersList as $userItem) {
            $users[] = $userItem['user_to']; 
        }
        $users[] = $idUser;

        $postList = Post::select()
            ->where('id_user', 'in', $users)
            ->orderBy('created_at', 'desc')
            ->page($page, 2)
        ->get(); // retorna um array de arrays

        $total = Post::select()
            ->where('id_user', 'in', $users)
        ->count();
        $pageCount = ceil($total / $perPage);

        $posts = [];
        foreach($postList as $postItem) { // transforma cada elemento do array em um objeto
            $newPost = new Post();
            $newPost->id = $postItem['id'];
            $newPost->type = $postItem['type'];
            $newPost->id_user = $postItem['id_user'];
            $newPost->body = $postItem['body'];     
            $newPost->created_at = $postItem['created_at'];
            $newPost->mine = false;
            if($postItem['id_user'] == $idUser) {
                $newPost->mine = true;
            }

            $newUser = User::select()->where('id', $postItem['id_user'])->one();
            $newPost->user = new User();
            $newPost->user->id = $newUser['id'];
            $newPost->user->name = $newUser['name'];
            $newPost->user->avatar = $newUser['avatar'];

            $newPost->likeCount = 0;
            $newPost->liked = false;
            $newPost->comments = [];

            $posts[] = $newPost;
        }
        return [
            'posts' => $posts,
            'pageCount' => $pageCount,
            'currentPage' => $page,
        ];
    }   

}