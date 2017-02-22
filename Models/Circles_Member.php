<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 16/02/2017
 * Time: 18:09
 */
namespace Database\Models;
require_once('../Core/Model.php');

class Circles_Member extends Model
{

    protected $table = 'circles_member';

    public function getByUser($user) {
        $rows = parent::findByColumn("user", $user);
        return $rows;
    }



}

