<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 13/02/2017
 * Time: 17:05
 */
require_once('../Models/Circle.php');

use Database\Models\Circle;

if (isset($_POST) && !empty($_POST)) {

    $circle = new Circle();
    $circle->circle_name = $_POST['circle_name'];
    $circle->owner = $_POST['owner'];
    $circle->id = $_POST['id'];

    $circle->save();

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Fixed top navbar example for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include('common/nav.php') ?>
<div class="container">
    <h1>Create Circle</h1>
    <form action="createCircle.php" method="post">
        <div class="form-group">
            <label>Circle name</label>
            <input type="text" name="circle_name" class="form-control" placeholder="Enter circle name">
        </div>
        <div class="form-group">
            <label>ID</label>
            <input type="text" name="id" class="form-control" placeholder="Enter id">
        </div>
        <div class="form-group">
            <label>Owner</label>
            <input type="text" name="owner" class="form-control" placeholder="Owner">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>


</body>
</html>