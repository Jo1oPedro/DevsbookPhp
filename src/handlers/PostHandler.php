<?php
namespace src\handlers;

use \src\models\Post;
use \src\models\User;
use \src\models\User_relation;
use \src\models\Posts_like;

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

        $posts = self::_postListToObject($postList, $idUser);
        return [
            'posts' => $posts,
            'pageCount' => $pageCount,
            'currentPage' => $page,
        ];
    }

    public static function _postListToObject($postList, $loggedUserId) {
        $posts = [];
        foreach($postList as $postItem) { // transforma cada elemento do array em um objeto
            $newPost = new Post();
            $newPost->id = $postItem['id'];
            $newPost->type = $postItem['type'];
            $newPost->id_user = $postItem['id_user'];
            $newPost->body = $postItem['body'];     
            $newPost->created_at = $postItem['created_at'];
            $newPost->mine = false;
            if($postItem['id_user'] == $loggedUserId) {
                $newPost->mine = true;
            }

            $newUser = User::select()->where('id', $postItem['id_user'])->one();
            $newPost->user = new User();
            $newPost->user->id = $newUser['id'];
            $newPost->user->name = $newUser['name'];
            $newPost->user->avatar = $newUser['avatar'];

            $likes = Posts_Like::select()->where('id_post', $postItem['id'])->get();
            $my_like = Posts_like::select()
                ->where('id_post', $postItem['id'])
                ->where('id_user', $loggedUserId)
            ->one();
            $newPost->likeCount = count($likes);
            $newPost->liked = ($my_like > 0) ? true : false;
            $newPost->comments = [];

            $posts[] = $newPost;
        }
        return $posts;
    }

    public static function getUserFeed($idUser, $page, $loggedUserId) {
        $perPage = 2;
        $postList = Post::select()
            ->where('id_user', $idUser)
            ->orderBy('created_at', 'desc')
            ->page($page, 2)
        ->get(); // retorna um array de arrays

        $total = Post::select()
            ->where('id_user', $idUser)
        ->count();
        $pageCount = ceil($total / $perPage);

        $posts = self::_postListToObject($postList, $loggedUserId);
        return [
            'posts' => $posts,
            'pageCount' => $pageCount,
            'currentPage' => $page,
        ];
    }

    public static function getPhotosFrom($id) {
        $photosData = Post::select()
            ->where('id_user', $id)
            ->where('type', 'photo')
            ->get();
        $photos = [];

        foreach($photosData as $photo) {
            $newPost = new Post();
            $newPost->id = $photo['id'];
            $newPost->type = $photo['type'];
            $newPost->created_at = $photo['created_at'];
            $newPost->body = $photo['body'];
            $photos[] = $newPost;
        }

        return $photos;
    }

}