<?php
/**
 * Created by PhpStorm.
 * User: Tang Gao Jun
 * Date: 25/2/2017
 * Time: 5:10 PM
 */
require_once('../Models/BlogPost.php');

use Http\Session\SessionManager;
use Database\Models\Blog_post;

$session = new SessionManager();
$session-> start();
$session-> blockGuest();
$user = $session->user;

$blog_post_id = $_GET['id'];
$blog = new Blog_post();
$b = $blog->find($blog_post_id);

?>

<!DOCTYPE html>
<html lang = "en">
<head>
    <title>Edit Post</title>
    <script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: "textarea",
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
        });
    </script>
    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include('common/nav.php') ?>
    <div id ="wrapper">
        <h2>Edit Post</h2>

        <?php
        if(isset($_POST['submit'])){
        $_POST = array_map('stripslashes', $_POST);
        extract($_POST);

        if($_POST['postTitle'] == ''){
            $error[] = 'Please enter the title.';
        }
        if($_POST['postCont'] == ''){
            $error[] = 'Please enter the content.';
        }
        if(!isset($error)) {
            try{
                $blog->users = $user->id;
                $blog->post_title = $_POST['postTitle'];
                $blog->post_content = $_POST['postCont'];
                $blog->timestamp =  date('Y-m-d H:i:s');
                $blog->save();

                //redirect to blog page
                header('Location: blog.php?action=updated');
                exit;

            }catch(PDOException $e){
                echo $e-> getMessage();
            }
        }
        }

        ?>

    </div>

</body>
</html>
