<?php
/**
 * Created by PhpStorm.
 * User: Tang Gao Jun
 * Date: 6/2/2017
 * Time: 9:19 PM
 */

require_once('../Models/BlogPost.php');
require_once('../Core/SessionManager.php');
require_once('../Models/User.php');
include_once('../Core/PrivacyManager.php');

use Http\Session\SessionManager;
use Database\Models\Blog_post;
use Database\Models\User;
use Database\Core\PrivacyManager;


$session = new SessionManager();
$session->start();
$session->blockGuest();
$user = $session ->user;

$u = new User();

$blog_post = new Blog_post();
$blog_post_id = $_GET['id'];
$b = $blog_post->find($blog_post_id);

$isViewingOwn = True;
if ($user->id != $b->user_id) {
    $isViewingOwn = false;
}


?>

<!DOCTYPE html>
<html lang = "en">
<head>

    <title><?php echo $b->post_title;?></title>
    <script language="JavaScript" type="text/javascript">
        function delpost(id, title)
        {
            if (confirm("Are you sure you want to delete '" + title + "'"))
            {
                window.location.href = 'blog.php?delpost=' + id;
            }
        }
    </script>

    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include('common/nav.php') ?>
    <div class = "container">
        <?php
            echo '<div>';
                echo'<h1>'. $b->post_title . '</h1>';
                echo '<hr>';
                echo '<p> Posted on '.date('jS M Y', strtotime($b->timestamp)).'</p>';
                echo '<br>';
                echo '<p>' .$b->post_content. '</p>';
            echo '</div>';
            echo '<br>'
        ?>
        <?php if ($isViewingOwn) { ?> <a href="editPost.php?id=<?php echo $b->id;?>" class="btn btn-primary" type = "button">Edit</a><?php } ?>
        <?php if ($isViewingOwn) { ?> <a href="javascript:delpost('<?php echo $b->id;?>','<?php echo $b->post_title;?>')" class="btn btn-danger" type = "button">Delete</a><?php } ?>
    </div>
</body>
</html>


