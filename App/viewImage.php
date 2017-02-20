<?php
/**
 * Created by PhpStorm.
 * User: tuanp
 * Date: 08/02/2017
 * Time: 17:35
 */
require_once('../Core/SessionManager.php');
require_once('../Models/Image.php');
require_once('../Models/Comment.php');
require_once('../Models/User.php');

use Database\Models\Image;
use Database\Models\User;
use Database\Models\Comment;
use Http\Session\SessionManager;

$session = new SessionManager();
$session->start();
$session->blockGuest();
$user = $session->user;

if (!isset($_GET['id'])) {
    // Fallback behaviour goes here
    $session->redirect('home');
}

$image_id = $_GET['id'];
//echo $_GET['link'];
$image = new Image();
$i = $image->find($image_id);
//pr($i->id);
$tags = $i->getTags();
//pr($tags);
//pr($i);

$u = new User();

$comment = new Comment();
$allComment = $comment->findByColumn("image_id", $image_id);

function pr($data)
{
    echo "<pre>";
    print_r($data); // or var_dump($data);
    echo "</pre>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>View Image</title>

    <!-- Bootstrap core CSS -->
    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" type="text/javascript"></script>

    <!-- Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
</head>

<body>

<?php include('common/nav.php') ?>
<div class="container">

    <div class="starter-template">
        <h1>View Image</h1>
        <?php
        $url = "viewAlbum.php?id=" . $i->album_id;
        echo "<h6><a href= ". $url . ">Back To Album</a></h6>"
        ?>
    </div>
    <div>
        <div class="form-group">
            <?php
            echo "<img src=\"$i->URL\" class=\"img-thumbnail\" width=\"50%\" height=\"50%\">";
            ?>
        </div>
        <div class="form-group">
            <?php
            echo "<h5>$i->name</h5>";
            ?>
            <?php
            echo "$i->description";
            ?>
        </div>
        <div class ="form-group">
            <div id="tag_section">

                <select class="select-tag" multiple="multiple" style="width:100%">
                    <?php
                    if (!empty($tags)) {
                        foreach ($tags as $t) {
                            echo "<option value=" . $t->id . " selected=\"selected\">" . $t->text . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>

        <h2>Comment</h2>
        <div id="comment_section">
            <?php
            if (!empty($allComment)) {
                foreach ($allComment as $c) {
                    $name = $u->getNameById($c->user_id);
                    echo "<div class=\"form-group\">
                        <h5>$name</h5>
                        <p><small>on $c->timestamp</small></p>
                        <p>$c->comment</p>
                    </div>";
                }
            }
            ?>
        </div>
        <form>
            <div class="form-group">
                <div>
                    <textarea class="form-control" rows="10" placeholder="Your comment" name="content" id="content"
                              required=""></textarea>
                </div>
            </div>
            <div class="form-group">
                <button id="postCmtBtn" class="btn btn-default">Comment</button>
            </div>
        </form>
    </div>

</div>


<script>
    $(document).ready(function(){
        var tags = <?php echo json_encode($tags); ?>;
        //console.log(tags);
        $(".select-tag").select2({
            tags: true
        })
        $(".select-tag").on("select2:select", function(e) {
            console.log(e.params);
            $.ajax({
                type: "POST",
                url: 'TagController.php',
                data: {text: e.params.data.text, image_id: <?php echo json_encode($image_id); ?>, action: 'add'},    //Or you can e.removed.text
                error: function () {
                    alert("error");
                },
                success : function(data) {
                    e.params.data.id = data;
                    console.log(e.params.data);
                    //alert(data);
                }
            });
        });
        $(".select-tag").on("select2:unselect", function(e) {
            console.log(e.params.data);
            $.ajax({
                type: "POST",
                url: 'TagController.php',
                data: {tag_id: e.params.data.text, action: 'remove'},    //Or you can e.removed.text
                error: function () {
                    alert("error");
                },
                success : function(data) {
                    //alert(data);
                }
            });
        });

        $("#postCmtBtn").click(function(event) { // button event handler
            event.preventDefault(); // prevent page from redirecting
            postComment();
        });
        function postComment() {
            var content = $('#content').val();
            var image_id = <?php echo json_encode($image_id); ?>;
            var comment_author = <?php echo json_encode($user->name); ?>;
            $.post('postComment.php', {content: content, image_id: image_id}, function () {
            })
                .done(function(){
                    var newcomment ='<div class="form-group">' +
                        '<h5>' + comment_author + '</h5>' +
                        '<p><small>' + 'Now' + '</small>' + '</p>' +
                        '<p>' + content +'</p>' +
                        '</div>';

                    //add comment
                    var currentMarkUp = $('#comment_section').html();
                    $('#comment_section').html(currentMarkUp + newcomment);

                    //Clear Text Area
                    $('#content').val("")
                });

        }
    })
</script>

</body>
</html>