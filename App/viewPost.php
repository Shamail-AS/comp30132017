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

use Http\Session\SessionManager;
use Database\Models\Blog_post;
use Database\Models\User;

$session = new SessionManager();
$session->start();
$session->blockGuest();
$user = $session ->user;


$blog_post_id = $_GET['id'];
$blog_post = new Blog_post();
$b = $blog_post->find($blog_post_id);




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
    <div id = "wrapper">
        <h1>Blog</h1>
        <hr />

        <?php
            echo '<div>';
                echo'<h1>'. $b->post_title . '</h1>';
                echo '<p> Posted on '.date('jS M Y', strtotime($b->timestamp)).'</p>';
                echo '<p>' .$b->post_content. '</p>';
            echo '</div>';

        ?>

        <a href="javascript:delpost('<?php echo $b->id;?>','<?php echo $b->post_title;?>')">Delete</a>
    </div>
</body>
</html>


