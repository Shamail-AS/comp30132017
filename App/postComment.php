<?php
/**
 * Created by PhpStorm.
 * User: tuanp
 * Date: 10/02/2017
 * Time: 06:16
 */
require_once('../Models/Comment.php');
use Database\Models\Comment;

var_dump($_POST);
if (isset($_POST) && !empty($_POST)) {
    $comment = new Comment();
    $comment->comment = $_POST['content'];
    $comment->user_id = 1; //SessionUser
    $comment->image_id = 1;
    $comment->save();
}
?>