<?php
/**
 * Created by PhpStorm.
 * User: Shamail
 * Date: 03/02/2017
 * Time: 18:52
 */
require_once('../Models/User.php');
require_once('../Models/Friendship.php');

use Database\Models\Friendship;
use Database\Models\User;


$friendship = new Friendship();
$user = new User();
$user1 = $user->find(41);
$user2 = $user->find(71);

var_dump($friendship->areFriendsOfFriend($user1, $user2));