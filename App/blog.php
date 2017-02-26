<?php
/**
 * Created by PhpStorm.
 * User: Tang Gao Jun
 * Date: 6/2/2017
 * Time: 5:32 PM
 */

require_once('../Models/BlogPost.php');
require_once('../Core/SessionManager.php');
require_once('../Models/User.php');

use Http\Session\SessionManager;
use Database\Models\Blog_post;
use Database\Models\User;

$session = new SessionManager();
$session->start();
$session->blockGuest();
$user = $session ->user;

$user_id = $user->id;
$blog = new Blog_post();
$blogs = $blog->getByUser($user_id);

if(isset($_GET['delpost'])){

    $blog->deletePost($_GET['delpost']);

    header('Location: blog.php?action=deleted');
    exit;
}
?>

<!DOCTYPE html>
<html lang = "en">
<head>

    <title>Blog</title>

    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <?php include('common/nav.php') ?>

        <h1>Blog</h1>
        <hr />

        <?php

            if (!empty($blogs)) {
                foreach ($blogs as $b) {

                    echo '<div>';
                        echo '<h1>'.$b->post_title.'</h1>';
                        echo '<p>Posted on '.date('jS M Y H:i:s', strtotime($b->timestamp)).'</p>';
                        echo '<p><a href = "viewPost.php?id= '.$b->id.'">Read More</a></p>';
                    echo '</div>';

                }
            }else {
                echo "No Blog Post";
            }


        ?>
        <h4><a href="addPost.php">Add Post</a></h4>


</body>
</html>

