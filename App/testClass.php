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


$user = new User();
$u = $user->find(11);


highlight_string(var_export($u, true));

$u->name = $u->name."q";



//$u->save();


$u = $user->find(11);

highlight_string(var_export($u, true));
