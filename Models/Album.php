<?php
/**
 * Created by PhpStorm.
 * User: tuanp
 * Date: 06/02/2017
 * Time: 13:20
 */

namespace Database\Models;
require_once('../Core/Model.php');


class Album extends Model
{
    protected $table = 'albums';
    public $id;
    public $timestamp;
    public $user_id;
    public $privacy_level;
}