<?php
/**
 * Created by PhpStorm.
 * User: Tang Gao Jun
 * Date: 25/2/2017
 * Time: 5:10 PM
 */
require_once('../Models/BlogPost.php');
require_once('../Core/SessionManager.php');
require_once('../Models/User.php');

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
    <div class ="container">
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
                    $data = array('post_title' => $_POST['postTitle'], 'post_content' => $_POST['postCont'], 'timestamp' => date('Y-m-d H:i:s'));
                    $b->updatePost($blog_post_id, $data);

                    //redirect to blog page
                    header('Location: blog.php?action=updated');
                    exit;

                }catch(PDOException $e){
                    echo $e-> getMessage();
                }
            }
            if(isset($error)){
                foreach($error as $error){
                    echo '<div class="alert alert-danger">'.$error.'</div>';
                }
            }
        }

        ?>

        <form action='' method='post'>
            <input type='hidden' name='postID' value='<?php echo $b->id;?>'>

            <p><label>Title</label><br />
                <input type='text' name='postTitle' value='<?php echo $b->post_title;?>'></p>

            <p><label>Content</label><br />
                <textarea name='postCont' cols='60' rows='10'><?php echo $b->post_content;?></textarea></p>

            <p><input class="btn btn-primary" type = "submit" name='submit' value='Update'</p>

        </form>

    </div>

</body>
</html>
