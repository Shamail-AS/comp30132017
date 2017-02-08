<?php
/**
 * Created by PhpStorm.
 * User: tuanp
 * Date: 06/02/2017
 * Time: 13:32
 */
require_once('../Models/Album.php');

use Database\Models\Album;

function pr($data)
{
    echo "<pre>";
    print_r($data); // or var_dump($data);
    echo "</pre>";
}
$album = new Album();
//pr($album->findByColumn("user_id", 1));
//$a = $album->find(1);
//var_dump($album->all());
//var_dump($album->find(1));

$album = new Album();
$album->user_id = 1;
$album->privacy_level =  1;
//$album->save();

$album2 = new Album();
$album2->id = 1;
$album2->user_id=1;
$album2->privacy_level = 2;
$album2->save();