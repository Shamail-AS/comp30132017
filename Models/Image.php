<?php
/**
 * Created by PhpStorm.
 * User: tuanp
 * Date: 06/02/2017
 * Time: 13:20
 */

namespace Database\Models;
require_once('../Core/Model.php');


class Image extends Model
{
    protected $table = 'images';
    public $id;
    public $user_id;
    public $album_id;
    public $url;
}