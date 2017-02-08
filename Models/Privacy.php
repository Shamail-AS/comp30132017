<?php
/**
 * Created by PhpStorm.
 * User: tuanp
 * Date: 08/02/2017
 * Time: 17:48
 */

namespace Database\Models;
require_once('../Core/Model.php');

class Privacy extends Model
{
    protected $table = 'privacy_level';

    public function listAll()
    {
        $result = $this->all();
        return $result;
    }
}