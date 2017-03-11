<?php
/**
 * Created by PhpStorm.
 * User: Shamail
 * Date: 01/02/2017
 * Time: 11:12
 */

namespace Database\Models;

require_once('../Core/Model.php');
require_once('../Models/Friendship.php');
require_once('../Models/Invite.php');
require_once('../Models/Image.php');
require_once('../Models/Album.php');

class User extends Model
{
    protected $table = 'users';

    public function isRegistered($name = null)
    {
        $name = $name ?? $this->username;
        $result = $this->where('username', "username = '$name'");
        return (count($result) != 0);
    }

    public function getUserById($id){

        $sql = "SELECT * FROM users WHERE id = $id";
        $users = parent::raw($sql);
        return $users[0];

    }

    public function getFriends()
    {
        $sql = "SELECT * FROM `users` WHERE users.id IN (SELECT `user2` FROM friends WHERE `user1` = $this->id)";
        $users = parent::raw($sql);
        return $users;
    }

    public function getFriendsOfFriends()
    {
        $sql = "SELECT * from users where id in(
                SELECT user2 from friends where user1 in (
                SELECT user2 from friends where user1 = $this->id))
                and id <> $this->id
                and id NOT IN (SELECT user2 from friends where user1 = $this->id)"; //except friends id
        $users = parent::raw($sql);
        return $users;
    }
    public function getNameById($id){
        $user = new User();
        $u = $user->find($id);
        return $u->name;
    }

    public function getIdByName($username){
        $user = new User();
        $u = $user->findByColumn('username',$username);
        foreach($u as $u2){
            $id = $u2->id;
        }
        return $id;


    }

    public function isFriendsWith($user)
    {
        $friendship = new Friendship();
        return $friendship->areFriends($this, $user);
    }

    public function hasContacted($user)
    {
        $inv = new Invite();
        return $inv->existsBetween($this, $user, 'pending');
    }

    public function age()
    {
        $now = new \DateTime();
        $dob = new \DateTime($this->dob);
        $interval = $now->diff($dob, true);
        return intval($interval->format("%y"));
    }

    public function sex()
    {
        if ($this->sex == 'M') return 'Male';
        else if ($this->sex == 'F') return 'Female';
        else return 'Undisclosed';
    }

    public function interested_in()
    {
        if ($this->interested_in == 'M') return 'Male';
        else if ($this->interested_in == 'F') return 'Female';
        else return 'Undisclosed';
    }

    public function isSameAgeGroupAs($user)
    {
        return (abs($user->age() - $this->age()) < 5);
    }

    public function invites()
    {
        $invite = new Invite();
        $received = $invite->allFor($this);
        $sent = $invite->allBy($this);
        return [
            'received' => $received,
            'sent' => $sent,
            'all' => array_merge($received, $sent)
        ];
    }

    public function profilePic(){
        $image = new Image();
        if($this->pic == null) return $image;

        $i = $image->find(($this->pic));
        return $i;
    }

    public function proifilePicAlbum(){
        $album = new Album();
        $results = $album->where(null,"name = 'Profile pictures'");
        if(empty($results)){
            $album = new Album();
            $album->name = 'Profile pictures';
            $album->user_id = $this->id;
            $album->privacy_level = 1; //Public
            $album->save();
            return $album;
        }
        else{
            return $results[0];
        }

    }
}