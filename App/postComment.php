<?php
/**
 * Created by PhpStorm.
 * User: tuanp
 * Date: 10/02/2017
 * Time: 06:16
 */
require_once('../Models/Comment.php');
require_once('../Core/SessionManager.php');
require_once('../Models/User.php');

use Database\Models\Comment;
use Database\Models\User;
use Http\Session\SessionManager;

$session = new SessionManager();
$session->start();
$session->blockGuest();
$user = $session->user;

if (isset($_POST) && !empty($_POST)) {
    $comment = new Comment();
    $comment->comment = $_POST['content'];
    $comment->user_id = $user->id; //SessionUser
    $comment->image_id = $_POST['image_id'];
    $comment->save();
}
?>