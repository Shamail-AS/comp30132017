<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 01/03/2017
 * Time: 15:04
 */
namespace Database\Models;
require_once('../Core/Model.php');
require_once('../Core/Db.php');


class Messages extends Model
{
    protected $table = 'messages';
}