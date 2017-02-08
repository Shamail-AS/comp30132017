<?php
/**
 * Created by PhpStorm.
 * User: tuanp
 * Date: 08/02/2017
 * Time: 17:35
 */
require_once('../Models/Album.php');
use Database\Models\Album;

function pr($data)
{
    echo "<pre>";
    print_r($data); // or var_dump($data);
    echo "</pre>";
}

if (isset($_POST) && !empty($_POST)) {
    $album = new Album();
    $album->name = $_POST['name'];
    $album->description = $_POST['description'];
    $album->user_id = 1; //maybe $SESSION->('userid');
    $user->privacy_level = $_POST['plevel'];
    if ($album->isExisted()) {
        $session->addError('albumExisted', 'Please choose a different name');
        $session->redirect('createAlbum');
    } else {
        $album->save();
        pr($album);
        if (!$user->exists()) {
            $session->addError('save', 'Could not create album');
            $session->redirect('createAlbum');
        } else {
            //redirect to home page because user has logged in
            $session->redirect('home');
        }
    }
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

    <title>Make a Post</title>

    <!-- Bootstrap core CSS -->
    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include('common/nav.php') ?>
<div class="container">

    <div class="starter-template">
        <h1>Create Album</h1>
    </div>
    <div>
        <form action="uploadImage.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <div>
                    <input type="text" class="form-control" id="name"
                           placeholder="Name" name="title" required="">
                </div>
            </div>
            <div class="form-group">
                <select id="plevel" class="form-control" title="Select">
                    <option>Privacy Level</option>
                    <?php
                    //$image = new Image();
                    //$options =  $image->findByColumn("user_id", $SESSION->("user_id"))
                    //TODO After login
                    $options = array(1, 2, 3, 4);
                    foreach ($options as $opt) {
                        echo "<option value = $opt>" . $opt . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <div>
                    <textarea class="form-control" rows="10" placeholder="Description" name="content"
                              required=""></textarea>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-default">Submit</button>
            </div>
        </form>
    </div>

</div>


</body>
</html>