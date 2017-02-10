<?php
/**
 * Created by PhpStorm.
 * User: Shamail
 * Date: 03/02/2017
 * Time: 18:52
 */
require_once('../Models/User.php');

use Database\Models\User;


$user = new User();
$u = $user->find(1);
//var_dump($user->all());
var_dump($user->find(1));
var_dump($u->exists());

$u2 = $user->find(11);
var_dump($u->isFriendsWith($u2));
var_dump($u2->isFriendsWith($u));

