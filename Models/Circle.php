<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 13/02/2017
 * Time: 16:36
 */

namespace Database\Models;
require_once('../Core/Model.php');

class Circle extends Model
{
    protected $table = 'circles';


    public function isExisted()
    {
        $result = $this->where('circle_name', "circle_name = '$this->circle_name'");
        return (count($result) != 0);
    }

    public function idToName($id){

        $circle = new Circle();
        $c = $circle->find($id);
        return $c->circle_name;

    }


}
