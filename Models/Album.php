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

    public function isExisted()
    {
        $result = $this->where('name', "name = '$this->name'");
        return (count($result) != 0);
    }
}