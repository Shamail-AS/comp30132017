<?php
/**
 * Created by PhpStorm.
 * User: Tang Gao Jun
 * Date: 9/2/2017
 * Time: 11:32 AM
 */

require_once('../Core/SessionManager.php');
require_once ('../Models/BlogPost.php');
require_once('../Models/User.php');

use Database\Models\Blog_post;
use Http\Session\SessionManager;

$session = new SessionManager();
$session->start();
$session->blockGuest();
$user = $session ->user;

$blog = new Blog_post();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Add Post</title>
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
<div class = "container">

    <h2>Add Post</h2>
    <hr />

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
        if(!isset($error)){
            try{
                $blog->users = $user->id;
                $blog->post_title = $_POST['postTitle'];
                $blog->post_content = $_POST['postCont'];
                $blog->timestamp =  date('Y-m-d H:i:s');
                $blog->save();

                //redirect to blog page
                header('Location: blog.php?action=added');
                exit;
            }catch(PDOException $e){
                echo $e-> getMessage();
            }
        }

        if(isset($error)){
            foreach($error as $error){
                echo '<p class="error">'.$error.'</p>';
            }
        }

    }
    ?>
    <form action = '' method = 'post'>

        <p><label>Title</label><br />
            <input type='text' name ='postTitle' value='<?php if(isset($error)){echo $_POST['postTitle'];}?>'></p>
        <p><label>Content</label><br />
            <textarea name ='postCont' cols ='60' rows='10'><?php if(isset($error)){echo $_POST['postCont'];}?></textarea></p>
        <p><input type='submit' name='submit' value='Submit'</p>
    </form>
</div>

