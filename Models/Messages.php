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

    function getCircleMSG($circle_id) {
        $rows = parent::findByColumn("circle", $circle_id);
        return $rows;
    }

    public function sender()
    {
        $u = new User();
        return $u->find($this->from_user);
    }







}

