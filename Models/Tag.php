<?php
/**
 * Created by PhpStorm.
 * User: tuanp
 * Date: 10/02/2017
 * Time: 11:19
 */

namespace Database\Models;
require_once('../Core/Model.php');


class Tag extends Model
{
    protected $table = 'tags';

    public function addNewTag($image_id) {
        $this->save();
        $sql = "SELECT LAST_INSERT_ID() as id";
        $result = parent::raw($sql);
        $latest_tag = intval($result[0]["id"]);

        //return var_dump($result);
        $sql2 = "INSERT INTO image_tags (image, tag) VALUES ($image_id, $latest_tag)";
        $result = parent::raw($sql2);
        return var_dump($latest_tag);
    }
}